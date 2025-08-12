<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DicomController;
use App\Http\Controllers\FhirController;
use App\Http\Controllers\MedGemmaController;
use App\Http\Controllers\ReportsController;

Route::get('/', function () {
    return view('welcome');
});

// Simple front-end dashboard
Route::get('/app', function () {
    return view('app');
})->name('app');

// DICOM upload page
Route::get('/dicom-upload', function () {
    return view('dicom-upload');
})->name('dicom.upload.page');

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

$securedMiddleware = app()->environment('production') ? ['auth:sanctum', 'role:clinician|admin'] : [];

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

// DICOM endpoints (secured; open in testing)
Route::middleware($securedMiddleware)->prefix('dicom')->name('dicom.')->group(function () {
    Route::post('/upload', [DicomController::class, 'upload'])->name('upload');
    Route::get('/download/{image}', [DicomController::class, 'download'])->name('download');
    Route::get('/export-fhir/{study}', [DicomController::class, 'exportToFhir'])->name('export.fhir');
});

// FHIR endpoints (secured; open in testing)
Route::middleware($securedMiddleware)->prefix('fhir')->name('fhir.')->group(function () {
    Route::get('/Patient/{patient}', [FhirController::class, 'getPatient'])->name('patient.get');
    Route::post('/Patient', [FhirController::class, 'importPatient'])->name('patient.import');
    Route::get('/ImagingStudy/{study}', [FhirController::class, 'getImagingStudy'])->name('imagingstudy.get');
    Route::post('/ImagingStudy', [FhirController::class, 'importImagingStudy'])->name('imagingstudy.import');
});
