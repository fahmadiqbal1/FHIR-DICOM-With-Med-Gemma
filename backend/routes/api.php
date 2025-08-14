<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\ImagingStudy;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Dashboard API
Route::get('/dashboard-stats', [\App\Http\Controllers\Api\DashboardController::class, 'stats']);
Route::get('/dashboard-health', [\App\Http\Controllers\Api\DashboardController::class, 'health']);

// User Management API
Route::prefix('users')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\UserController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\Api\UserController::class, 'store']);
    Route::get('/{user}', [\App\Http\Controllers\Api\UserController::class, 'show']);
    Route::put('/{user}', [\App\Http\Controllers\Api\UserController::class, 'update']);
    Route::delete('/{user}', [\App\Http\Controllers\Api\UserController::class, 'destroy']);
    Route::post('/{user}/assign-role', [\App\Http\Controllers\Api\UserController::class, 'assignRole']);
    Route::get('/{user}/earnings', [\App\Http\Controllers\Api\UserController::class, 'earnings']);
});

// Patient Management API
// Index route must be OUTSIDE the prefix group to override any resource routes
Route::get('/patients', [\App\Http\Controllers\Api\PatientController::class, 'index'])->name('api.patients.index.override');

// Patient API routes
Route::get('/patients', [\App\Http\Controllers\Api\PatientController::class, 'index']);

Route::prefix('patients')->group(function () {
    Route::post('/', [\App\Http\Controllers\Api\PatientController::class, 'store']);
    Route::get('/{patient}', [\App\Http\Controllers\Api\PatientController::class, 'show']);
    Route::get('/{patient}/details', [\App\Http\Controllers\Api\PatientController::class, 'show']);
    Route::put('/{patient}', [\App\Http\Controllers\Api\PatientController::class, 'update']);
    Route::delete('/{patient}', [\App\Http\Controllers\Api\PatientController::class, 'destroy']);
    Route::get('/{patient}/studies', [\App\Http\Controllers\Api\PatientController::class, 'studies']);
});

// Reports API
Route::prefix('reports')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\ReportsController::class, 'index']);
    Route::get('/{id}', [\App\Http\Controllers\Api\ReportsController::class, 'show']);
    Route::get('/export/{format}', [\App\Http\Controllers\Api\ReportsController::class, 'export']);
});

// Audit Logs API
Route::prefix('admin/audit-logs')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\AuditLogController::class, 'index']);
    Route::get('/stats', [\App\Http\Controllers\Api\AuditLogController::class, 'stats']);
    Route::get('/{id}', [\App\Http\Controllers\Api\AuditLogController::class, 'show']);
    Route::post('/', [\App\Http\Controllers\Api\AuditLogController::class, 'store']);
    Route::get('/export/{format}', [\App\Http\Controllers\Api\AuditLogController::class, 'export']);
    Route::delete('/cleanup', [\App\Http\Controllers\Api\AuditLogController::class, 'cleanup']);
});

// Get all imaging studies
Route::get('/imaging-studies', function () {
    return ImagingStudy::with('patient:id,first_name,last_name,mrn')
        ->select('id', 'patient_id', 'description', 'modality', 'started_at', 'status')
        ->get()
        ->map(function ($study) {
            return [
                'id' => $study->id,
                'patient' => $study->patient->first_name . ' ' . $study->patient->last_name,
                'description' => $study->description,
                'modality' => $study->modality,
                'started_at' => $study->started_at ? $study->started_at->toDateTimeString() : null,
                'status' => $study->status,
            ];
        });
});

// Test simple route
Route::get('/test', function () {
    return ['message' => 'Test route works'];
});

// Test patients debug route
Route::get('/patients-debug', function () {
    return ['message' => 'API patients debug route works', 'count' => \App\Models\Patient::count()];
});

// Test patients controller directly
Route::get('/patients-test', [\App\Http\Controllers\Api\PatientController::class, 'index']);

// Get doctors for dropdown
Route::get('/doctors', function () {
    return User::select('id', 'name', 'email')->get();
});

// Invoice management
Route::post('/invoices', [\App\Http\Controllers\InvoiceController::class, 'store']);
Route::get('/invoices', [\App\Http\Controllers\InvoiceController::class, 'index']);
Route::get('/invoices/{invoice}', [\App\Http\Controllers\InvoiceController::class, 'show']);
Route::post('/invoices/{invoice}/email', [\App\Http\Controllers\InvoiceController::class, 'sendEmail']);
Route::get('/invoices/{invoice}/pdf', [\App\Http\Controllers\InvoiceController::class, 'downloadPdf']);

// MedGemma API - Fix the namespace
Route::prefix('medgemma')->group(function () {
    Route::get('status', [\App\Http\Controllers\MedGemmaController::class, 'status']);
    Route::post('analyze-text', [\App\Http\Controllers\MedGemmaController::class, 'analyzeText']);
    Route::get('imaging-study/{study}/analyze', [\App\Http\Controllers\MedGemmaController::class, 'analyzeImagingStudy']);
    Route::get('patient/{patient}/labs/analyze', [\App\Http\Controllers\MedGemmaController::class, 'analyzeLabs']);
    Route::get('patient/{patient}/second-opinion', [\App\Http\Controllers\MedGemmaController::class, 'combinedSecondOpinion']);
    Route::get('patient/{patient}/insights', [\App\Http\Controllers\MedGemmaController::class, 'quickInsights']);
});

// Analysis result mock response
Route::get('/analysis-result', function () {
    return response()->json([
        'success' => true,
        'analysis' => 'Mock analysis response...',
        'recommendations' => [],
        'confidence' => null,
        'note' => null
    ]);
});