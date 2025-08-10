<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AuditLogController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:admin']);
    }

    /**
     * Display a listing of the audit logs.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->wantsJson() || $request->is('api/*')) {
            $logs = AuditLog::with('user')
                ->orderByDesc('created_at')
                ->limit(200)
                ->get()
                ->map(function ($log) {
                    return [
                        'created_at' => $log->created_at->toDateTimeString(),
                        'user_name' => $log->user ? ($log->user->name ?? $log->user->email) : null,
                        'action' => $log->action,
                        'subject_type' => class_basename($log->subject_type),
                        'subject_id' => $log->subject_id,
                        'ip' => $log->ip,
                        'details' => $log->details,
                    ];
                });
            if ($request->query('export') === 'csv') {
                $csv = "Date,User,Action,Subject,IP,Details\n";
                foreach ($logs as $log) {
                    $csv .= '"'.implode('","', [
                        $log['created_at'],
                        $log['user_name'],
                        $log['action'],
                        $log['subject_type'].' #'.$log['subject_id'],
                        $log['ip'],
                        json_encode($log['details'])
                    ]).'"\n';
                }
                return Response::make($csv, 200, [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="audit-logs.csv"',
                ]);
            }
            return response()->json($logs);
        }
        return view('admin-audit-logs');
    }
}
