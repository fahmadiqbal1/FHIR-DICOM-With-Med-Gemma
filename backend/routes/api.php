<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Patient;
use App\Models\ImagingStudy;

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

// Get all patients (simplified for demo)
Route::get('/patients', function () {
    return Patient::select('id', 'first_name', 'last_name', 'mrn')->get();
});

// Get patient's imaging studies
Route::get('/patients/{patient}/studies', function (Patient $patient) {
    return $patient->imagingStudies()->select('id', 'description', 'modality', 'started_at', 'status')->get();
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