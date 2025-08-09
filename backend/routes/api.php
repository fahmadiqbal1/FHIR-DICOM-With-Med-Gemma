<?php

use App\Http\Controllers\MedGemmaController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Apply rate limiting to all API routes
Route::middleware('api.rate.limit')->group(function () {
    // MedGemma integration status (public)
    Route::get('/integrations/medgemma', function () {
        $cfg = config('services.medgemma');
        if (! is_array($cfg)) {
            $cfg = [];
        }
        $enabled = isset($cfg['enabled']) ? (bool) filter_var($cfg['enabled'], FILTER_VALIDATE_BOOL) : false;
        $configured = $enabled && ! empty($cfg['endpoint']) && ! empty($cfg['api_key']);
        $model = isset($cfg['model']) ? $cfg['model'] : 'medgemma';

        return response()->json([
            'name' => 'MedGemma',
            'integrated' => true,
            'enabled' => $enabled,
            'configured' => $configured,
            'model' => $model,
        ]);
    })->name('api.integrations.medgemma');

    // Reports endpoints (public for demo)
    Route::prefix('reports')->name('api.reports.')->group(function () {
        Route::get('/patients', [ReportsController::class, 'patients'])->name('patients');
        Route::get('/patients/{patient}', [ReportsController::class, 'patientShow'])->name('patients.show');
    });

    // MedGemma analysis endpoints (should be authenticated in production)
    Route::prefix('medgemma')->name('api.medgemma.')->group(function () {
        Route::post('/analyze/imaging/{study}', [MedGemmaController::class, 'analyzeImagingStudy'])->name('analyze.imaging');
        Route::post('/analyze/labs/{patient}', [MedGemmaController::class, 'analyzeLabs'])->name('analyze.labs');
        Route::post('/second-opinion/{patient}', [MedGemmaController::class, 'combinedSecondOpinion'])->name('second.opinion');
    });
});
