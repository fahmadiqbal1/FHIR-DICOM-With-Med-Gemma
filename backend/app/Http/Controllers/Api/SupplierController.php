<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\SupplierAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    /**
     * Get all suppliers
     */
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->boolean('active_only')) {
            $query->active();
        }

        $suppliers = $query->orderBy('name')->get();

        return response()->json([
            'suppliers' => $suppliers->map(function ($supplier) {
                return [
                    'id' => $supplier->id,
                    'name' => $supplier->name,
                    'type' => $supplier->type,
                    'contact_person' => $supplier->contact_person,
                    'email' => $supplier->email,
                    'phone' => $supplier->phone,
                    'city' => $supplier->city,
                    'country' => $supplier->country,
                    'is_active' => $supplier->is_active,
                    'total_orders' => $supplier->getTotalOrders(),
                    'pending_orders' => $supplier->getPendingOrders(),
                    'avg_completion_time' => $supplier->getAverageCompletionTime()
                ];
            })
        ]);
    }

    /**
     * Create new supplier
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email',
            'phone' => 'required|string|max:50',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'tax_id' => 'nullable|string|max:100',
            'payment_terms' => 'nullable|string|max:100',
            'credit_limit' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $supplier = Supplier::create($request->all());

        return response()->json([
            'message' => 'Supplier created successfully',
            'supplier' => $supplier
        ], 201);
    }

    /**
     * Show specific supplier
     */
    public function show(Supplier $supplier)
    {
        return response()->json([
            'id' => $supplier->id,
            'name' => $supplier->name,
            'type' => $supplier->type,
            'contact_person' => $supplier->contact_person,
            'email' => $supplier->email,
            'phone' => $supplier->phone,
            'address' => $supplier->address,
            'city' => $supplier->city,
            'country' => $supplier->country,
            'tax_id' => $supplier->tax_id,
            'payment_terms' => $supplier->payment_terms,
            'credit_limit' => $supplier->credit_limit,
            'is_active' => $supplier->is_active,
            'notes' => $supplier->notes,
            'created_at' => $supplier->created_at,
            'updated_at' => $supplier->updated_at,
            'total_orders' => $supplier->getTotalOrders(),
            'pending_orders' => $supplier->getPendingOrders(),
            'avg_completion_time' => $supplier->getAverageCompletionTime(),
            'work_orders' => $supplier->workOrders()->latest()->take(5)->get()
        ]);
    }

    /**
     * Update supplier
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email,' . $supplier->id,
            'phone' => 'required|string|max:50',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'tax_id' => 'nullable|string|max:100',
            'payment_terms' => 'nullable|string|max:100',
            'credit_limit' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $supplier->update($request->all());

        return response()->json([
            'message' => 'Supplier updated successfully',
            'supplier' => $supplier
        ]);
    }

    /**
     * Delete supplier
     */
    public function destroy(Supplier $supplier)
    {
        // Check if supplier has pending work orders
        if ($supplier->getPendingOrders() > 0) {
            return response()->json([
                'error' => 'Cannot delete supplier with pending work orders'
            ], 422);
        }

        $supplier->delete();

        return response()->json([
            'message' => 'Supplier deleted successfully'
        ]);
    }

    /**
     * Get supplier assignments for current user
     */
    public function userAssignments()
    {
        $user = Auth::user();
        
        $assignments = SupplierAssignment::active()
            ->forUser($user->id)
            ->with('supplier')
            ->get();

        return response()->json([
            'suppliers' => $assignments->map(function ($assignment) {
                return [
                    'id' => $assignment->supplier->id,
                    'name' => $assignment->supplier->name,
                    'type' => $assignment->supplier->type,
                    'contact_person' => $assignment->supplier->contact_person,
                    'email' => $assignment->supplier->email,
                    'phone' => $assignment->supplier->phone,
                    'assignment_id' => $assignment->id
                ];
            })
        ]);
    }

    /**
     * Assign supplier to user
     */
    public function assignToUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'supplier_id' => 'required|exists:suppliers,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $assignment = SupplierAssignment::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'supplier_id' => $request->supplier_id
            ],
            [
                'assigned_by' => Auth::id(),
                'is_active' => true
            ]
        );

        return response()->json([
            'message' => 'Supplier assigned successfully',
            'assignment' => $assignment->load(['user', 'supplier'])
        ]);
    }

    /**
     * Remove supplier assignment
     */
    public function removeAssignment($assignmentId)
    {
        $assignment = SupplierAssignment::findOrFail($assignmentId);
        $assignment->update(['is_active' => false]);

        return response()->json([
            'message' => 'Supplier assignment removed'
        ]);
    }

    /**
     * Get work orders for a specific supplier
     */
    public function workOrders(Supplier $supplier)
    {
        $workOrders = $supplier->workOrders()
            ->with(['user:id,name', 'assignedUser:id,name'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'work_orders' => $workOrders->map(function ($workOrder) {
                return [
                    'id' => $workOrder->id,
                    'title' => $workOrder->title,
                    'description' => $workOrder->description,
                    'status' => $workOrder->status,
                    'priority' => $workOrder->priority,
                    'estimated_cost' => $workOrder->estimated_cost,
                    'actual_cost' => $workOrder->actual_cost,
                    'due_date' => $workOrder->due_date,
                    'completed_at' => $workOrder->completed_at,
                    'location' => $workOrder->location,
                    'category' => $workOrder->category,
                    'created_by' => $workOrder->user ? $workOrder->user->name : null,
                    'assigned_to' => $workOrder->assignedUser ? $workOrder->assignedUser->name : null,
                    'is_overdue' => $workOrder->isOverdue()
                ];
            })
        ]);
    }
}
