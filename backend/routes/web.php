<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\MedGemmaController;
use App\Http\Controllers\ReportsController;

Route::get('/', function () {
    return view('welcome');
});

// Simple front-end dashboard
Route::get('/app', function () {
    return view('app');
})->name('app');

// MedGemma integration status
Route::get('/integrations/medgemma', function () {
    $cfg = config('services.medgemma');
    if (!is_array($cfg)) {
        $cfg = [];
    }
    $enabled = isset($cfg['enabled']) ? (bool) filter_var($cfg['enabled'], FILTER_VALIDATE_BOOL) : false;
    $configured = $enabled && !empty($cfg['endpoint']) && !empty($cfg['api_key']);
    $model = isset($cfg['model']) ? $cfg['model'] : 'medgemma';

    return response()->json([
        'name' => 'MedGemma',
        'integrated' => true,
        'enabled' => $enabled,
        'configured' => $configured,
        'model' => $model,
    ]);
})->name('integrations.medgemma');

// Admin: user management (secured)
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
});

$securedMiddleware = app()->environment('testing') ? [] : ['auth:sanctum', 'role:clinician|admin'];

// Reports (secured for clinicians and admins; open in testing)
Route::middleware($securedMiddleware)->prefix('reports')->name('reports.')->group(function () {
    Route::get('/patients', [ReportsController::class, 'patients'])->name('patients');
    Route::get('/patients/{patient}', [ReportsController::class, 'patientShow'])->name('patients.show');
});

// MedGemma analysis endpoints (secured; open in testing)
Route::middleware($securedMiddleware)->prefix('medgemma')->name('medgemma.')->group(function () {
    Route::post('/analyze/imaging/{study}', [MedGemmaController::class, 'analyzeImagingStudy'])->name('analyze.imaging');
    Route::post('/analyze/labs/{patient}', [MedGemmaController::class, 'analyzeLabs'])->name('analyze.labs');
    Route::post('/second-opinion/{patient}', [MedGemmaController::class, 'combinedSecondOpinion'])->name('second.opinion');
});
