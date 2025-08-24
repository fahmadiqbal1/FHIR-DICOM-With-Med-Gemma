<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\Supplier;
use App\Models\SupplierAssignment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class WorkOrderController extends Controller
{
    /**
     * Get work orders for current user
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->get('status');
        $priority = $request->get('priority');
        $supplierId = $request->get('supplier_id');
        $limit = $request->get('limit', 20);

        $query = WorkOrder::where('user_id', $user->id)
            ->with('supplier')
            ->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        if ($priority) {
            $query->where('priority', $priority);
        }

        if ($supplierId) {
            $query->where('supplier_id', $supplierId);
        }

        $workOrders = $query->limit($limit)->get();

        return response()->json($workOrders->map(function ($order) {
            return [
                'id' => $order->id,
                'title' => $order->title,
                'description' => $order->description,
                'priority' => $order->priority,
                'status' => $order->status,
                'due_date' => $order->due_date->toISOString(),
                'estimated_cost' => $order->estimated_cost,
                'actual_cost' => $order->actual_cost,
                'location' => $order->location,
                'category' => $order->category,
                'supplier' => $order->supplier ? [
                    'id' => $order->supplier->id,
                    'name' => $order->supplier->name,
                    'contact_person' => $order->supplier->contact_person
                ] : null,
                'assignedUser' => $order->assignedUser ? [
                    'id' => $order->assignedUser->id,
                    'name' => $order->assignedUser->name
                ] : null,
                'is_overdue' => $order->isOverdue(),
                'duration_hours' => $order->getDurationInHours(),
                'created_at' => $order->created_at->toISOString(),
                'completed_at' => $order->completed_at?->toISOString()
            ];
        }));
    }

    /**
     * Create new work order
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent',
            'due_date' => 'required|date|after:now',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'assigned_to' => 'nullable|exists:users,id',
            'estimated_cost' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if user can assign supplier
        if ($request->supplier_id) {
            $canAssignSupplier = $this->canUserAssignSupplier($user, $request->supplier_id);
            
            if (!$canAssignSupplier) {
                return response()->json([
                    'error' => 'You are not authorized to assign this supplier'
                ], 403);
            }
        }

        $workOrder = WorkOrder::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'supplier_id' => $request->supplier_id,
            'assigned_to' => $request->assigned_to,
            'estimated_cost' => $request->estimated_cost,
            'location' => $request->location,
            'category' => $request->category,
            'notes' => $request->notes
        ]);

        // Create notification for work order assignment
        if ($request->supplier_id) {
            Notification::createWorkOrder($user->id, $workOrder);
        }

        return response()->json([
            'message' => 'Work order created successfully',
            ...$workOrder->fresh()->load(['supplier', 'assignedUser'])->toArray()
        ], 201);
    }

    /**
     * Show specific work order
     */
    public function show(WorkOrder $workOrder)
    {
        $user = Auth::user();
        
        // Check authorization
        if (!$user->hasRole('Admin') && $workOrder->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'id' => $workOrder->id,
            'title' => $workOrder->title,
            'description' => $workOrder->description,
            'priority' => $workOrder->priority,
            'status' => $workOrder->status,
            'due_date' => $workOrder->due_date,
            'estimated_cost' => $workOrder->estimated_cost,
            'actual_cost' => $workOrder->actual_cost,
            'location' => $workOrder->location,
            'category' => $workOrder->category,
            'notes' => $workOrder->notes,
            'created_at' => $workOrder->created_at,
            'updated_at' => $workOrder->updated_at,
            'supplier' => $workOrder->supplier,
            'assigned_user' => $workOrder->assignedUser,
            'is_overdue' => $workOrder->isOverdue(),
            'duration_hours' => $workOrder->getDurationInHours()
        ]);
    }

    /**
     * Update work order
     */
    public function update(Request $request, WorkOrder $workOrder)
    {
        $user = Auth::user();
        
        // Check authorization
        if (!$user->hasRole('Admin') && $workOrder->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'priority' => 'sometimes|in:low,normal,high,urgent',
            'status' => 'sometimes|in:pending,in_progress,completed,cancelled',
            'due_date' => 'sometimes|date|after:now',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'assigned_to' => 'nullable|exists:users,id',
            'estimated_cost' => 'nullable|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle status change logic
        if ($request->has('status')) {
            if ($request->status === 'in_progress' && !$workOrder->started_at) {
                $workOrder->started_at = now();
            }
            
            if ($request->status === 'completed' && !$workOrder->completed_at) {
                $workOrder->completed_at = now();
            }
        }

        $workOrder->update($request->all());

        return response()->json([
            'message' => 'Work order updated successfully',
            'work_order' => $workOrder->fresh()->load(['supplier', 'assignedUser'])
        ]);
    }

    /**
     * Delete work order
     */
    public function destroy(WorkOrder $workOrder)
    {
        $user = Auth::user();
        
        // Check authorization
        if (!$user->hasRole('Admin') && $workOrder->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Prevent deletion of completed work orders
        if ($workOrder->status === 'completed') {
            return response()->json([
                'error' => 'Cannot delete completed work orders'
            ], 422);
        }

        $workOrder->delete();

        return response()->json([
            'message' => 'Work order deleted successfully'
        ]);
    }

    /**
     * Update work order status
     */
    public function updateStatus(Request $request, WorkOrder $workOrder)
    {
        $user = Auth::user();

        if ($workOrder->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $workOrder->status = $request->status;
        
        if ($request->notes) {
            $workOrder->notes = $request->notes;
        }

        if ($request->status === 'in_progress' && !$workOrder->started_at) {
            $workOrder->started_at = now();
        }

        if ($request->status === 'completed' && !$workOrder->completed_at) {
            $workOrder->completed_at = now();
        }

        $workOrder->save();

        return response()->json([
            'message' => 'Work order status updated',
            'work_order' => $workOrder->load('supplier')
        ]);
    }

    /**
     * Complete work order with invoice
     */
    public function complete(Request $request, WorkOrder $workOrder)
    {
        $user = Auth::user();

        if ($workOrder->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'total_amount' => 'required|numeric|min:0',
            'invoice' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle file upload
        if ($request->hasFile('invoice')) {
            $file = $request->file('invoice');
            $filename = 'work_order_' . $workOrder->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('work_orders/invoices', $filename, 'public');
            
            $workOrder->invoice_path = $path;
        }

        $workOrder->markAsCompleted($workOrder->invoice_path ?? null, $request->total_amount);
        
        if ($request->notes) {
            $workOrder->notes = $request->notes;
            $workOrder->save();
        }

        return response()->json([
            'message' => 'Work order completed successfully',
            'work_order' => $workOrder->load('supplier')
        ]);
    }

    /**
     * Get available suppliers for current user
     */
    public function availableSuppliers()
    {
        $user = Auth::user();
        
        // Admin can access all suppliers
        if ($user->hasRole('Admin')) {
            $suppliers = Supplier::active()->get();
        } else {
            // Other users can only access assigned suppliers
            $assignmentIds = SupplierAssignment::active()
                ->forUser($user->id)
                ->pluck('supplier_id');
            
            $suppliers = Supplier::active()
                ->whereIn('id', $assignmentIds)
                ->get();
        }

        return response()->json([
            'suppliers' => $suppliers->map(function ($supplier) {
                return [
                    'id' => $supplier->id,
                    'name' => $supplier->name,
                    'type' => $supplier->type,
                    'contact_person' => $supplier->contact_person,
                    'email' => $supplier->email,
                    'phone' => $supplier->phone
                ];
            })
        ]);
    }

    /**
     * Download work order invoice
     */
    public function downloadInvoice(WorkOrder $workOrder)
    {
        $user = Auth::user();

        if ($workOrder->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!$workOrder->invoice_path) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        if (!Storage::disk('public')->exists($workOrder->invoice_path)) {
            return response()->json(['error' => 'Invoice file not found'], 404);
        }

        return Storage::disk('public')->download($workOrder->invoice_path);
    }

    /**
     * Get work order statistics
     */
    public function getStatistics()
    {
        $user = Auth::user();
        
        $totalQuery = WorkOrder::query();
        if (!$user->hasRole('Admin')) {
            $totalQuery->where('user_id', $user->id);
        }

        $total = $totalQuery->count();
        
        $byStatus = [
            'pending' => $totalQuery->clone()->where('status', 'pending')->count(),
            'in_progress' => $totalQuery->clone()->where('status', 'in_progress')->count(),
            'completed' => $totalQuery->clone()->where('status', 'completed')->count(),
            'cancelled' => $totalQuery->clone()->where('status', 'cancelled')->count(),
        ];

        $byPriority = [
            'low' => $totalQuery->clone()->where('priority', 'low')->count(),
            'normal' => $totalQuery->clone()->where('priority', 'normal')->count(),
            'high' => $totalQuery->clone()->where('priority', 'high')->count(),
            'urgent' => $totalQuery->clone()->where('priority', 'urgent')->count(),
        ];

        $overdue = $totalQuery->clone()->overdue()->count();
        
        $avgCompletionTime = $totalQuery->clone()
            ->where('status', 'completed')
            ->whereNotNull('completed_at')
            ->get()
            ->avg(function($workOrder) {
                return $workOrder->created_at->diffInHours($workOrder->completed_at);
            });

        $totalEstimatedCost = $totalQuery->clone()->sum('estimated_cost') ?? 0;
        $totalActualCost = $totalQuery->clone()->whereNotNull('actual_cost')->sum('actual_cost') ?? 0;

        return response()->json([
            'total' => $total,
            'by_status' => $byStatus,
            'by_priority' => $byPriority,
            'overdue_count' => $overdue,
            'avg_completion_hours' => $avgCompletionTime ? round($avgCompletionTime, 2) : null,
            'total_estimated_cost' => $totalEstimatedCost,
            'total_actual_cost' => $totalActualCost,
        ]);
    }

    /**
     * Check if user can assign supplier
     */
    private function canUserAssignSupplier($user, $supplierId)
    {
        // Admin can assign any supplier
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Check if user has this supplier assigned
        return SupplierAssignment::active()
            ->forUser($user->id)
            ->where('supplier_id', $supplierId)
            ->exists();
    }
}
