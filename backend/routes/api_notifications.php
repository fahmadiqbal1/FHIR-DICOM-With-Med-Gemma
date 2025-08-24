<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\WorkOrderController;
use App\Http\Controllers\Api\BusinessIntelligenceController;

// Notification API routes
Route::middleware(['auth:sanctum'])->prefix('notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::put('/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::put('/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/{id}', [NotificationController::class, 'destroy']);
    Route::get('/counts', [NotificationController::class, 'getCounts']);
});

// Supplier Management API routes
Route::middleware(['auth:sanctum'])->prefix('suppliers')->group(function () {
    Route::get('/', [SupplierController::class, 'index']);
    Route::post('/', [SupplierController::class, 'store'])->middleware('role:Admin|Owner');
    Route::get('/{id}', [SupplierController::class, 'show']);
    Route::put('/{id}', [SupplierController::class, 'update'])->middleware('role:Admin|Owner');
    Route::delete('/{id}', [SupplierController::class, 'destroy'])->middleware('role:Admin|Owner');
    
    // Supplier assignment routes
    Route::post('/{id}/assign', [SupplierController::class, 'assign'])->middleware('role:Admin|Owner');
    Route::delete('/{id}/unassign', [SupplierController::class, 'unassign'])->middleware('role:Admin|Owner');
    Route::get('/{id}/assignments', [SupplierController::class, 'assignments'])->middleware('role:Admin|Owner');
});

// Work Order API routes
Route::middleware(['auth:sanctum'])->prefix('work-orders')->group(function () {
    Route::get('/', [WorkOrderController::class, 'index']);
    Route::post('/', [WorkOrderController::class, 'store']);
    Route::put('/{workOrder}/status', [WorkOrderController::class, 'updateStatus']);
    Route::post('/{workOrder}/complete', [WorkOrderController::class, 'complete']);
    Route::get('/suppliers', [WorkOrderController::class, 'availableSuppliers']);
    Route::get('/{workOrder}/invoice', [WorkOrderController::class, 'downloadInvoice']);
});

// Business Intelligence API routes
Route::middleware(['auth:sanctum'])->prefix('business-intelligence')->group(function () {
    Route::get('/dashboard-data', [BusinessIntelligenceController::class, 'getBusinessData']);
    Route::get('/work-order-expenses', [BusinessIntelligenceController::class, 'getWorkOrderExpenses']);
    Route::post('/ai-insights', [BusinessIntelligenceController::class, 'generateAIInsights']);
    Route::post('/export-report', [BusinessIntelligenceController::class, 'exportBusinessReport']);
});
