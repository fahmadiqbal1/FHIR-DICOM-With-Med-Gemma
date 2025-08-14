<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Get all audit logs with filtering and pagination
     */
    public function index(Request $request)
    {
        try {
            $query = AuditLog::query();

            // Apply filters
            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->has('action')) {
                $query->where('action', 'like', '%' . $request->action . '%');
            }

            if ($request->has('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Load relationships
            $query->with(['user:id,name,email']);

            // Order by latest first
            $query->orderBy('created_at', 'desc');

            // Paginate
            $perPage = $request->get('per_page', 50);
            $logs = $query->paginate($perPage);

            return response()->json([
                'data' => $logs->items(),
                'total' => $logs->total(),
                'page' => $logs->currentPage(),
                'per_page' => $logs->perPage(),
                'total_pages' => $logs->lastPage()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to load audit logs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific audit log
     */
    public function show($id)
    {
        try {
            $log = AuditLog::with(['user:id,name,email'])->findOrFail($id);

            return response()->json($log);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to load audit log',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new audit log entry
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'action' => 'required|string|max:255',
                'description' => 'nullable|string',
                'ip_address' => 'nullable|ip',
                'user_agent' => 'nullable|string',
                'metadata' => 'nullable|array'
            ]);

            $log = AuditLog::create($validated);

            return response()->json([
                'message' => 'Audit log created successfully',
                'data' => $log
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create audit log',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get audit log statistics
     */
    public function stats(Request $request)
    {
        try {
            $period = $request->get('period', '7days');
            
            $stats = [
                'total_logs' => AuditLog::count(),
                'today_logs' => AuditLog::whereDate('created_at', today())->count(),
                'this_week_logs' => AuditLog::where('created_at', '>=', now()->subWeek())->count(),
                'this_month_logs' => AuditLog::where('created_at', '>=', now()->subMonth())->count(),
            ];

            // Get top actions
            $topActions = AuditLog::selectRaw('action, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('action')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get();

            // Get top users
            $topUsers = AuditLog::selectRaw('user_id, users.name, COUNT(*) as count')
                ->join('users', 'audit_logs.user_id', '=', 'users.id')
                ->where('audit_logs.created_at', '>=', now()->subDays(30))
                ->groupBy('user_id', 'users.name')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get();

            // Get daily activity for chart
            $dailyActivity = AuditLog::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            return response()->json([
                'stats' => $stats,
                'top_actions' => $topActions,
                'top_users' => $topUsers,
                'daily_activity' => $dailyActivity
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to load audit log statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export audit logs
     */
    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'csv');
            
            $query = AuditLog::with(['user:id,name,email']);
            
            // Apply same filters as index
            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            if ($request->has('action')) {
                $query->where('action', 'like', '%' . $request->action . '%');
            }

            if ($request->has('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $logs = $query->orderBy('created_at', 'desc')->get();

            switch ($format) {
                case 'csv':
                    return $this->exportCsv($logs);
                default:
                    return response()->json($logs);
            }

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to export audit logs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function exportCsv($logs)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="audit_logs.csv"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'User', 'Action', 'Description', 'IP Address', 'Created At']);
            
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user->name ?? 'Unknown',
                    $log->action,
                    $log->description,
                    $log->ip_address,
                    $log->created_at->format('Y-m-d H:i:s')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Delete old audit logs (cleanup)
     */
    public function cleanup(Request $request)
    {
        try {
            $days = $request->get('days', 90); // Default: keep 90 days
            
            $deleted = AuditLog::where('created_at', '<', now()->subDays($days))->delete();

            return response()->json([
                'message' => "Deleted {$deleted} old audit logs (older than {$days} days)",
                'deleted_count' => $deleted
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to cleanup audit logs',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
