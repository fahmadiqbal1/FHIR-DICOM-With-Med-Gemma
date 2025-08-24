<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\ImagingStudy;
use App\Models\User;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\DicomController;

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

// Include notification and supplier management routes
require __DIR__.'/api_notifications.php';

// Dashboard API
Route::get('/dashboard-stats', [\App\Http\Controllers\Api\DashboardController::class, 'stats']);
Route::get('/dashboard-health', [\App\Http\Controllers\Api\DashboardController::class, 'health']);

// Role-based Dashboard APIs (temporarily without auth for testing)
Route::get('/dashboard/admin', [\App\Http\Controllers\Api\DashboardController::class, 'adminStats']);
Route::get('/dashboard/doctor', [\App\Http\Controllers\Api\DashboardController::class, 'doctorStats']);
Route::get('/dashboard/lab', [\App\Http\Controllers\Api\DashboardController::class, 'labStats']);
Route::get('/dashboard/radiology', [\App\Http\Controllers\Api\DashboardController::class, 'radiologistStats']);
Route::get('/dashboard/pharmacist', [\App\Http\Controllers\Api\DashboardController::class, 'pharmacistStats']);
Route::get('/dashboard/owner', [\App\Http\Controllers\Api\DashboardController::class, 'ownerStats']);

// Original protected routes (uncomment when authentication is implemented)
// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::get('/dashboard/admin', [\App\Http\Controllers\Api\DashboardController::class, 'adminStats']);
//     Route::get('/dashboard/doctor', [\App\Http\Controllers\Api\DashboardController::class, 'doctorStats']);
//     Route::get('/dashboard/lab', [\App\Http\Controllers\Api\DashboardController::class, 'labStats']);
//     Route::get('/dashboard/radiology', [\App\Http\Controllers\Api\DashboardController::class, 'radiologistStats']);
//     Route::get('/dashboard/owner', [\App\Http\Controllers\Api\DashboardController::class, 'ownerStats']);
// });

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
    try {
        $doctors = User::select('id', 'name', 'email', 'role', 'is_active_doctor')
            ->where('is_active_doctor', 1) // Only active doctors
            ->where(function($query) {
                $query->where('role', 'doctor')
                      ->orWhere('role', 'Doctor')
                      ->orWhereHas('roles', function($roleQuery) {
                          $roleQuery->where('name', 'Doctor')
                                    ->orWhere('name', 'doctor');
                      });
            })
            ->get();
            
        // Format the response to handle encrypted names and ensure proper display
        $formattedDoctors = $doctors->map(function($doctor) {
            $name = $doctor->name;
            
            // Handle encrypted or corrupted names
            if (empty($name) || strlen($name) > 100 || str_contains($name, 'eyJ')) {
                // Use email prefix as fallback or try to get from raw data
                $emailPrefix = explode('@', $doctor->email)[0];
                $name = 'Dr. ' . ucwords(str_replace(['.', '_', '-'], ' ', $emailPrefix));
            }
            
            return [
                'id' => $doctor->id,
                'name' => $name,
                'email' => $doctor->email,
                'role' => $doctor->role ?? 'Doctor'
            ];
        });
        
        return response()->json($formattedDoctors);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to load doctors',
            'message' => $e->getMessage()
        ], 500);
    }
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

// Clinical APIs with web session authentication for browser access
Route::middleware('web', 'auth')->group(function () {
    // Clinical Notes API
    Route::post('patients/{id}/notes', function ($id, Illuminate\Http\Request $request) {
        Log::info('Clinical note API called', ['patient_id' => $id, 'user_id' => Auth::id()]);
        
        $request->validate([
            'content' => 'required|string|max:2000'
        ]);
        
        $note = \App\Models\ClinicalNote::create([
            'patient_id' => $id,
            'provider_id' => Auth::id(),
            'soap_subjective' => $request->content,
            'soap_objective' => '',
            'soap_assessment' => '',
            'soap_plan' => ''
        ]);
        
        Log::info('Clinical note created successfully', ['note_id' => $note->id]);
        return response()->json(['success' => true, 'note' => $note]);
    });

    Route::get('patients/{id}/notes', function ($id) {
        $notes = \App\Models\ClinicalNote::where('patient_id', $id)
            ->with('provider:id,name,email')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($note) {
                // Get a clean, readable provider name
                $providerName = 'Unknown Provider';
                if ($note->provider) {
                    $name = $note->provider->name;
                    $email = $note->provider->email;
                    
                    // Check if name is encrypted or problematic (contains JSON-like structure)
                    if (empty($name) || strlen($name) > 100 || 
                        str_contains($name, 'eyJ') || str_contains($name, 'iv') || 
                        str_contains($name, 'value') || str_contains($name, 'mac')) {
                        
                        // Use email to determine clean display name
                        if ($email) {
                            $emailPrefix = explode('@', $email)[0];
                            // Map known emails to proper names
                            $nameMap = [
                                'admin' => 'Admin User',
                                'doctor1' => 'Dr. Sarah Johnson',  
                                'doctor2' => 'Dr. Michael Chen',
                                'labtech' => 'Lab Technician',
                                'radiologist' => 'Radiologist',
                                'pharmacist' => 'Pharmacist'
                            ];
                            $providerName = $nameMap[$emailPrefix] ?? ucwords(str_replace(['.', '_', '-', '+'], ' ', $emailPrefix));
                        } else {
                            $providerName = 'System User';
                        }
                    } else {
                        $providerName = $name;
                    }
                }
                
                return [
                    'id' => $note->id,
                    'content' => $note->soap_subjective,
                    'provider' => [
                        'id' => $note->provider?->id,
                        'name' => $providerName,
                        'email' => $note->provider?->email
                    ],
                    'created_at' => $note->created_at,
                    'formatted_date' => $note->created_at?->format('M d, Y \a\t g:i A')
                ];
            });
        
        return response()->json($notes);
    });

    // Medical Orders API
    Route::post('patients/{id}/orders', function ($id, Illuminate\Http\Request $request) {
        Log::info('Order API called', ['patient_id' => $id, 'user_id' => Auth::id(), 'order_data' => $request->all()]);
        
        $request->validate([
            'type' => 'required|in:lab,imaging,prescription',
            'description' => 'required|string|max:500',
            'priority' => 'required|in:routine,urgent,stat',
            'notes' => 'nullable|string|max:1000'
        ]);
        
        try {
            // Create order based on type
            $orderData = [
                'patient_id' => $id,
                'ordered_by' => Auth::id(),
                'priority' => $request->priority,
                'status' => 'pending',
                'ordered_at' => now(),
            ];
            
            $order = null;
            
            switch ($request->type) {
                case 'lab':
                    // For lab orders, we'll create a basic lab order
                    $labTest = \App\Models\LabTest::first(); // Get first available test
                    if (!$labTest) {
                        // Create a default test if none exists
                        $labTest = \App\Models\LabTest::create([
                            'code' => 'CBC',
                            'name' => 'Complete Blood Count',
                            'units' => 'count',
                            'specimen_type' => 'blood'
                        ]);
                    }
                    
                    $orderData['lab_test_id'] = $labTest->id;
                    $orderData['result_notes'] = $request->notes;
                    $order = \App\Models\LabOrder::create($orderData);
                    Log::info('Lab order created', ['order_id' => $order->id]);
                    break;
                    
                case 'imaging':
                    // For imaging, create an imaging study
                    $orderData['uuid'] = \Illuminate\Support\Str::uuid();
                    $orderData['accession_number'] = 'ACC' . time();
                    $orderData['study_instance_uid'] = 'STUDY' . time();
                    $orderData['description'] = $request->description;
                    $orderData['modality'] = 'X-RAY'; // Default modality
                    $orderData['started_at'] = now();
                    unset($orderData['ordered_by']); // ImagingStudy doesn't have this field
                    unset($orderData['priority']);
                    unset($orderData['ordered_at']);
                    $order = \App\Models\ImagingStudy::create($orderData);
                    
                    // Create notification for radiologists
                    $radiologists = \App\Models\User::role('Radiologist')->get();
                    foreach ($radiologists as $radiologist) {
                        // You can implement a proper notification system here
                        // For now, we'll log it
                        Log::info('New imaging request notification', [
                            'radiologist_id' => $radiologist->id,
                            'study_id' => $order->id,
                            'patient_id' => $id,
                            'description' => $request->description
                        ]);
                    }
                    
                    Log::info('Imaging study created', ['study_id' => $order->id]);
                    break;
                    
                case 'prescription':
                    // For prescriptions, create a prescription
                    $orderData['medication_id'] = 1; // Default medication ID
                    $orderData['prescribed_by'] = $orderData['ordered_by'];
                    $orderData['dosage'] = 'As prescribed';
                    $orderData['frequency'] = 'As directed';
                    $orderData['route'] = 'Oral';
                    $orderData['quantity'] = '30';
                    $orderData['refills_allowed'] = 0;
                    $orderData['refills_used'] = 0;
                    $orderData['notes'] = $request->description . ' - ' . ($request->notes ?: '');
                    unset($orderData['ordered_by']);
                    unset($orderData['priority']);
                    unset($orderData['ordered_at']);
                    $order = \App\Models\Prescription::create($orderData);
                    Log::info('Prescription created', ['prescription_id' => $order->id]);
                    break;
            }
            
            return response()->json(['success' => true, 'message' => 'Order placed successfully', 'order_id' => $order->id]);
            
        } catch (\Exception $e) {
            Log::error('Error creating order', ['error' => $e->getMessage(), 'patient_id' => $id, 'type' => $request->type]);
            return response()->json(['success' => false, 'message' => 'Error placing order: ' . $e->getMessage()], 500);
        }
    });

    Route::get('patients/{id}/orders', function ($id) {
        Log::info('Loading orders for patient', ['patient_id' => $id]);
        
        try {
            $orders = [];
            
            // Get lab orders
            $labOrders = \App\Models\LabOrder::where('patient_id', $id)
                ->with('orderingProvider:id,name')
                ->orderBy('ordered_at', 'desc')
                ->get();
                
            foreach ($labOrders as $lab) {
                $orders[] = [
                    'id' => 'lab_' . $lab->id,
                    'type' => 'lab',
                    'title' => 'Laboratory Order #' . $lab->id,
                    'description' => $lab->result_notes ?: 'Laboratory testing ordered',
                    'status' => $lab->status,
                    'date' => $lab->ordered_at->format('Y-m-d'),
                    'results' => $lab->result_value ?: null,
                    'provider' => $lab->orderingProvider->name ?? 'Unknown'
                ];
            }
            
            // Get imaging studies
            $imagingStudies = \App\Models\ImagingStudy::where('patient_id', $id)
                ->orderBy('started_at', 'desc')
                ->get();
                
            foreach ($imagingStudies as $study) {
                $orders[] = [
                    'id' => 'imaging_' . $study->id,
                    'type' => 'imaging',
                    'title' => $study->description ?: ($study->modality . ' Study'),
                    'description' => 'Imaging study: ' . $study->description,
                    'status' => $study->status,
                    'date' => $study->started_at->format('Y-m-d'),
                    'results' => null,
                    'provider' => 'Imaging Department'
                ];
            }
            
            // Get prescriptions
            $prescriptions = \App\Models\Prescription::where('patient_id', $id)
                ->with('prescriber:id,name')
                ->orderBy('created_at', 'desc')
                ->get();
                
            foreach ($prescriptions as $prescription) {
                $orders[] = [
                    'id' => 'prescription_' . $prescription->id,
                    'type' => 'prescription',
                    'title' => 'Prescription #' . $prescription->id,
                    'description' => $prescription->notes ?: 'Medication prescribed',
                    'status' => $prescription->status,
                    'date' => $prescription->created_at->format('Y-m-d'),
                    'results' => $prescription->dosage ? "Dosage: {$prescription->dosage}, Frequency: {$prescription->frequency}" : null,
                    'provider' => $prescription->prescriber->name ?? 'Unknown'
                ];
            }
            
            // Sort by date (newest first)
            usort($orders, function($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });
            
            Log::info('Orders loaded successfully', ['patient_id' => $id, 'order_count' => count($orders)]);
            return response()->json($orders);
            
        } catch (\Exception $e) {
            Log::error('Error loading orders', ['error' => $e->getMessage(), 'patient_id' => $id]);
            return response()->json([]);
        }
    });

    Route::get('patients/{id}/imaging', function ($id) {
        // Demo imaging data - in real implementation, fetch DICOM studies
        $imaging = [
            [
                'id' => 1,
                'title' => 'Chest X-Ray - PA View',
                'date' => '2025-08-14',
                'type' => 'X-Ray',
                'thumbnail' => '🫁',
                'status' => 'completed'
            ],
            [
                'id' => 2,
                'title' => 'Chest X-Ray - Lateral View',
                'date' => '2025-08-13',
                'type' => 'X-Ray',
                'thumbnail' => '🫁',
                'status' => 'completed'
            ]
        ];
        
        return response()->json($imaging);
    });
});

// Radiologist-specific API endpoints
Route::middleware('web', 'auth', 'radiologist')->prefix('radiologist')->group(function () {
    
    // Get pending imaging requests for radiologist
    Route::get('imaging-requests', function () {
        try {
            // Get pending and in-progress imaging studies (not completed ones)
            $imagingRequests = \App\Models\ImagingStudy::whereIn('status', ['pending', 'in_progress'])
                ->with('patient:id,first_name,last_name,medical_record_number,date_of_birth,gender')
                ->orderBy('started_at', 'desc')
                ->get()
                ->map(function ($study) {
                    return [
                        'id' => $study->id,
                        'patient_id' => $study->patient_id,
                        'patient_name' => $study->patient->first_name . ' ' . $study->patient->last_name,
                        'patient_mrn' => $study->patient->medical_record_number,
                        'description' => $study->description,
                        'modality' => $study->modality,
                        'status' => $study->status,
                        'priority' => 'routine', // Default priority - you can add this field to imaging_studies table
                        'ordered_date' => $study->started_at->format('Y-m-d H:i'),
                        'doctor_name' => 'Ordering Physician', // You may need to add ordering physician to the model
                        'notes' => null // You can add this field if needed
                    ];
                });
            
            Log::info('Radiologist imaging requests loaded', ['count' => $imagingRequests->count()]);
            return response()->json($imagingRequests);
            
        } catch (\Exception $e) {
            Log::error('Error loading imaging requests for radiologist', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error loading imaging requests'], 500);
        }
    });

    // Serve uploaded images
    Route::get('images/{filename}', function ($filename) {
        $path = storage_path('app/public/radiology-images/' . $filename);
        
        if (!file_exists($path)) {
            abort(404, 'Image not found');
        }
        
        $file = file_get_contents($path);
        $type = mime_content_type($path);
        
        return response($file)->header('Content-Type', $type);
    })->where('filename', '.*');
    
    // Upload radiology report and images
    Route::post('upload-report', function (Illuminate\Http\Request $request) {
        $request->validate([
            'study_id' => 'required|exists:imaging_studies,id',
            'findings' => 'required|string|max:2000',
            'impression' => 'required|string|max:1000',
            'status' => 'required|in:completed,preliminary,addendum',
            'images.*' => 'nullable|file|mimes:jpg,jpeg,png,dcm|max:10240' // 10MB max per file, made optional
        ]);
        
        try {
            // Update the imaging study
            $study = \App\Models\ImagingStudy::findOrFail($request->study_id);
            $study->status = $request->status;
            $study->save();
            
            // Store the radiology report data (we'll use a simple approach for now)
            // You might want to create a separate RadiologyReport model in the future
            $reportData = [
                'study_id' => $study->id,
                'radiologist_id' => Auth::id(),
                'findings' => $request->findings,
                'impression' => $request->impression,
                'status' => $request->status,
                'reported_at' => now()
            ];
            
            // For now, we'll store the report in the clinical_notes table as a temporary solution
            // or you can create a dedicated radiology_reports table
            \App\Models\ClinicalNote::create([
                'patient_id' => $study->patient_id,
                'provider_id' => Auth::id(),
                'soap_subjective' => "RADIOLOGY REPORT - " . $study->description,
                'soap_objective' => "Findings: " . $request->findings,
                'soap_assessment' => "Impression: " . $request->impression,
                'soap_plan' => "Status: " . $request->status,
            ]);
            
            // Handle file uploads
            $uploadedFiles = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('radiology-images', $filename, 'public');
                    $uploadedFiles[] = $path;
                }
            }
            
            // Create a DicomImage record for each uploaded file
            foreach ($uploadedFiles as $path) {
                try {
                    $fullPath = storage_path('app/public/' . $path);
                    $contentType = file_exists($fullPath) ? mime_content_type($fullPath) : 'application/octet-stream';
                    
                    \App\Models\DicomImage::create([
                        'imaging_study_id' => $study->id,
                        'file_path' => $path,
                        'series_instance_uid' => 'SERIES_' . time() . '_' . uniqid(),
                        'sop_instance_uid' => 'SOP_' . time() . '_' . uniqid(),
                        'content_type' => $contentType,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error creating DicomImage record', [
                        'path' => $path,
                        'error' => $e->getMessage()
                    ]);
                    // Continue with other files even if one fails
                }
            }
            
            // Log the activity
            Log::info('Radiology report uploaded', [
                'study_id' => $study->id,
                'radiologist_id' => Auth::id(),
                'status' => $request->status,
                'files_count' => count($uploadedFiles)
            ]);
            
            return response()->json([
                'success' => true, 
                'message' => 'Report and images uploaded successfully',
                'files_uploaded' => count($uploadedFiles)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error uploading radiology report', [
                'error' => $e->getMessage(),
                'study_id' => $request->study_id,
                'radiologist_id' => Auth::id()
            ]);
            return response()->json(['error' => 'Error uploading report: ' . $e->getMessage()], 500);
        }
    });
    
    // Get completed studies for radiologist
    Route::get('completed-studies', function () {
        try {
            $completedStudies = \App\Models\ImagingStudy::whereIn('status', ['completed', 'preliminary', 'addendum'])
                ->with([
                    'patient:id,first_name,last_name,medical_record_number',
                    'images:id,imaging_study_id,file_path,content_type'
                ])
                ->orderBy('updated_at', 'desc')
                ->limit(50) // Limit to recent 50 studies
                ->get()
                ->map(function ($study) {
                    // Get the radiology report from clinical notes - using PHP filtering for reliability
                    $patientNotes = \App\Models\ClinicalNote::where('patient_id', $study->patient_id)
                        ->orderBy('created_at', 'desc')
                        ->get();
                    
                    $radiologyReport = $patientNotes->first(function ($note) {
                        return stripos($note->soap_subjective, 'RADIOLOGY') !== false && 
                               stripos($note->soap_subjective, 'REPORT') !== false;
                    });
                    
                        // Prepare image URLs
                        $imageUrls = [];
                        foreach ($study->images as $image) {
                            if (str_contains($image->file_path, 'radiology-images/')) {
                                $filename = basename($image->file_path);
                                $imageUrls[] = [
                                    'id' => $image->id,
                                    'url' => url('/storage/radiology-images/' . $filename),
                                    'filename' => $filename,
                                    'content_type' => $image->content_type
                                ];
                            }
                        }                    return [
                        'id' => $study->id,
                        'patient_id' => $study->patient_id, // Add patient_id for history button
                        'patient_name' => $study->patient->first_name . ' ' . $study->patient->last_name,
                        'patient_mrn' => $study->patient->medical_record_number,
                        'description' => $study->description,
                        'modality' => $study->modality,
                        'status' => $study->status,
                        'completed_date' => $study->updated_at->format('Y-m-d H:i'),
                        'images_count' => $study->images->count(),
                        'images' => $imageUrls,
                        'has_report' => $radiologyReport ? true : false,
                        'findings' => $radiologyReport ? str_replace('Findings: ', '', $radiologyReport->soap_objective) : null,
                        'impression' => $radiologyReport ? str_replace('Impression: ', '', $radiologyReport->soap_assessment) : null
                    ];
                });
            
            return response()->json($completedStudies);
            
        } catch (\Exception $e) {
            Log::error('Error loading completed studies', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error loading completed studies'], 500);
        }
    });    // Debug endpoint for testing upload
    Route::post('test-upload', function (Illuminate\Http\Request $request) {
        Log::info('Test upload called', [
            'request_data' => $request->all(),
            'has_files' => $request->hasFile('images'),
            'user_id' => Auth::id()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Test upload endpoint working',
            'received_data' => $request->except(['images']),
            'files_count' => $request->hasFile('images') ? count($request->file('images')) : 0
        ]);
    });
});

// Test Orders API
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/test-orders', function(Request $request) {
        try {
            $request->validate([
                'patient_query' => 'required|string',
                'priority' => 'required|in:routine,urgent,stat',
                'lab_tests' => 'nullable|array',
                'lab_tests.*' => 'integer|exists:lab_tests,id',
                'imaging_tests' => 'nullable|array',
                'imaging_tests.*' => 'integer|exists:imaging_test_types,id',
                'clinical_notes' => 'nullable|string'
            ]);

            // For now, assume we have a patient (in real implementation, search for patient)
            $patientId = 1; // Placeholder - would search based on patient_query

            $orders = [];
            
            // Create lab orders
            if ($request->lab_tests) {
                foreach ($request->lab_tests as $testId) {
                    $orderId = DB::table('lab_orders')->insertGetId([
                        'patient_id' => $patientId,
                        'doctor_id' => Auth::id() ?? 1,
                        'test_id' => $testId,
                        'priority' => $request->priority,
                        'status' => 'pending',
                        'notes' => $request->clinical_notes,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    $orders[] = ['type' => 'lab', 'id' => $orderId];
                }
            }
            
            // Create imaging orders
            if ($request->imaging_tests) {
                foreach ($request->imaging_tests as $testId) {
                    $studyId = DB::table('imaging_studies')->insertGetId([
                        'patient_id' => $patientId,
                        'referring_physician_id' => Auth::id() ?? 1,
                        'test_type_id' => $testId,
                        'study_status' => 'scheduled',
                        'priority' => $request->priority,
                        'clinical_history' => $request->clinical_notes,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    $orders[] = ['type' => 'imaging', 'id' => $studyId];
                }
            }

            return response()->json([
                'message' => 'Test orders created successfully',
                'orders' => $orders
            ]);

        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create orders: ' . $e->getMessage()], 500);
        }
    });
    
    Route::get('/patients', function() {
        // Return sample patients for now
        return response()->json([
            ['id' => 1, 'name' => 'John Doe', 'identifier' => 'P001'],
            ['id' => 2, 'name' => 'Jane Smith', 'identifier' => 'P002'],
            ['id' => 3, 'name' => 'Bob Johnson', 'identifier' => 'P003']
        ]);
    });
});

// Configuration routes

// Configuration Management API routes
Route::prefix('configuration')->name('configuration.')->group(function () {
    // Lab Tests Configuration
    Route::get('/lab-tests', [ConfigurationController::class, 'getLabTests']);
    Route::post('/lab-tests', [ConfigurationController::class, 'createLabTest']);
    Route::put('/lab-tests/{id}', [ConfigurationController::class, 'updateLabTest']);
    Route::delete('/lab-tests/{id}', [ConfigurationController::class, 'deleteLabTest']);
    
    // Imaging Tests Configuration
    Route::get('/imaging-tests', [ConfigurationController::class, 'getImagingTests']);
    Route::post('/imaging-tests', [ConfigurationController::class, 'createImagingTest']);
    Route::put('/imaging-tests/{id}', [ConfigurationController::class, 'updateImagingTest']);
    Route::delete('/imaging-tests/{id}', [ConfigurationController::class, 'deleteImagingTest']);
});

// Lab Equipment Integration API routes
Route::prefix('lab-equipment')->name('lab-equipment.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\LabEquipmentController::class, 'index']);
    Route::get('/{equipment}', [\App\Http\Controllers\Api\LabEquipmentController::class, 'show']);
    Route::post('/fetch-results', [\App\Http\Controllers\Api\LabEquipmentController::class, 'fetchResults']);
    Route::post('/upload-result-image', [\App\Http\Controllers\Api\LabEquipmentController::class, 'uploadResultImage']);
    Route::put('/results/{result}/verify', [\App\Http\Controllers\Api\LabEquipmentController::class, 'verifyResult']);
    Route::get('/results', [\App\Http\Controllers\Api\LabEquipmentController::class, 'getResults']);
    Route::get('/statistics', [\App\Http\Controllers\Api\LabEquipmentController::class, 'getStatistics']);
    Route::post('/{equipment}/test-connection', [\App\Http\Controllers\Api\LabEquipmentController::class, 'testConnection']);
});

// Admin API Routes
Route::prefix('admin/api')->middleware(['auth'])->group(function () {
    // Get doctors for dropdown - admin specific
    Route::get('/doctors', function () {
        try {
            $doctors = App\Models\User::select('id', 'name', 'email', 'role', 'is_active_doctor')
                ->where('is_active_doctor', 1) // Only active doctors
                ->where(function($query) {
                    $query->where('role', 'doctor')
                          ->orWhere('role', 'Doctor')
                          ->orWhereHas('roles', function($roleQuery) {
                              $roleQuery->where('name', 'Doctor')
                                        ->orWhere('name', 'doctor');
                          });
                })
                ->get();
                
            // Format the response to handle encrypted names and ensure proper display
            $formattedDoctors = $doctors->map(function($doctor) {
                $name = $doctor->name;
                
                // Handle encrypted or corrupted names
                if (empty($name) || strlen($name) > 100 || str_contains($name, 'eyJ')) {
                    // Use email prefix as fallback or try to get from raw data
                    $emailPrefix = explode('@', $doctor->email)[0];
                    $name = 'Dr. ' . ucwords(str_replace(['.', '_', '-'], ' ', $emailPrefix));
                }
                
                return [
                    'id' => $doctor->id,
                    'name' => $name,
                    'email' => $doctor->email,
                    'role' => $doctor->role ?? 'Doctor'
                ];
            });
            
            return response()->json($formattedDoctors);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load doctors',
                'message' => $e->getMessage()
            ], 500);
        }
    });

    // Invoice creation endpoint
    Route::post('/invoices', function (Illuminate\Http\Request $request) {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'service_type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $invoice = App\Models\Invoice::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'service_type' => $request->service_type,
            'amount' => $request->amount,
            'description' => $request->description,
            'status' => 'pending',
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Invoice created successfully',
            'invoice' => $invoice
        ]);
    });
    
    // DICOM and Imaging Study Routes
    Route::prefix('imaging')->group(function () {
        // Get all imaging studies
        Route::get('/studies', function () {
            try {
                $studies = ImagingStudy::with(['patient', 'created_by_user'])
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->map(function ($study) {
                        return [
                            'id' => $study->id,
                            'patient_name' => $study->patient->name ?? 'Unknown Patient',
                            'patient_mrn' => $study->patient->mrn ?? 'N/A',
                            'modality' => $study->modality,
                            'description' => $study->description,
                            'study_date' => $study->study_date,
                            'status' => $study->status ?? 'pending',
                            'urgency' => $study->urgency ?? 'normal',
                            'created_at' => $study->created_at->format('Y-m-d H:i:s')
                        ];
                    });

                return response()->json($studies);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Failed to load imaging studies',
                    'message' => $e->getMessage()
                ], 500);
            }
        });
        
        // Upload DICOM study
        Route::post('/upload', function (Request $request) {
            $request->validate([
                'patient_id' => 'required|exists:patients,id',
                'modality' => 'required|string',
                'description' => 'required|string',
                'dicom_files.*' => 'required|file|mimes:dcm,dicom'
            ]);

            try {
                $study = ImagingStudy::create([
                    'patient_id' => $request->patient_id,
                    'modality' => $request->modality,
                    'description' => $request->description,
                    'study_date' => now(),
                    'status' => 'pending',
                    'urgency' => $request->urgency ?? 'normal',
                    'created_by' => auth()->id() ?? 1,
                ]);

                // Handle file uploads
                if ($request->hasFile('dicom_files')) {
                    $uploadedFiles = [];
                    foreach ($request->file('dicom_files') as $file) {
                        $filename = time() . '_' . $file->getClientOriginalName();
                        $path = $file->storeAs('dicom/' . $study->id, $filename, 'public');
                        $uploadedFiles[] = $path;
                    }
                    
                    $study->update([
                        'file_paths' => json_encode($uploadedFiles)
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'DICOM study uploaded successfully',
                    'study' => $study
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Failed to upload DICOM study',
                    'message' => $e->getMessage()
                ], 500);
            }
        });
        
        // Get study details
        Route::get('/studies/{id}', function ($id) {
            try {
                $study = ImagingStudy::with(['patient', 'created_by_user'])->findOrFail($id);
                
                return response()->json([
                    'id' => $study->id,
                    'patient_name' => $study->patient->name,
                    'patient_mrn' => $study->patient->mrn,
                    'modality' => $study->modality,
                    'description' => $study->description,
                    'study_date' => $study->study_date,
                    'status' => $study->status,
                    'urgency' => $study->urgency,
                    'file_paths' => json_decode($study->file_paths ?? '[]'),
                    'created_at' => $study->created_at->format('Y-m-d H:i:s')
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Study not found',
                    'message' => $e->getMessage()
                ], 404);
            }
        });
        
        // AI Analysis endpoint
        Route::post('/analyze/{id}', function ($id, Request $request) {
            $request->validate([
                'analysis_type' => 'required|string',
                'confidence_threshold' => 'numeric|between:0.5,0.95'
            ]);
            
            try {
                $study = ImagingStudy::findOrFail($id);
                
                // Simulate AI analysis (in production, this would call actual AI service)
                $analysisResults = [
                    'study_id' => $study->id,
                    'analysis_type' => $request->analysis_type,
                    'confidence' => $request->confidence_threshold ?? 0.8,
                    'findings' => [
                        'abnormality' => [
                            'detected' => false,
                            'confidence' => 85,
                            'description' => 'No significant abnormalities detected'
                        ],
                        'quality' => [
                            'score' => 92,
                            'description' => 'Good image quality'
                        ]
                    ],
                    'recommendations' => [
                        'Follow-up recommended in 6 months',
                        'Patient education on preventive care'
                    ],
                    'analyzed_at' => now()->toISOString()
                ];
                
                return response()->json([
                    'success' => true,
                    'results' => $analysisResults
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Analysis failed',
                    'message' => $e->getMessage()
                ], 500);
            }
        });
        
        // Update study status
        Route::patch('/studies/{id}/status', function ($id, Request $request) {
            $request->validate([
                'status' => 'required|in:pending,in-progress,completed,archived'
            ]);
            
            try {
                $study = ImagingStudy::findOrFail($id);
                $study->update(['status' => $request->status]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Study status updated successfully'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Failed to update study status',
                    'message' => $e->getMessage()
                ], 500);
            }
        });
    });
});

// Platform Management APIs (protected by Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    
    // Notifications API
    Route::prefix('notifications')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\NotificationController::class, 'index']);
        Route::patch('/{id}/read', [\App\Http\Controllers\Api\NotificationController::class, 'markAsRead']);
        Route::patch('/read-all', [\App\Http\Controllers\Api\NotificationController::class, 'markAllAsRead']);
        Route::get('/counts', [\App\Http\Controllers\Api\NotificationController::class, 'getCounts']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\NotificationController::class, 'destroy']);
    });

    // Suppliers API
    Route::prefix('suppliers')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\SupplierController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\SupplierController::class, 'store']);
        Route::get('/{supplier}', [\App\Http\Controllers\Api\SupplierController::class, 'show']);
        Route::put('/{supplier}', [\App\Http\Controllers\Api\SupplierController::class, 'update']);
        Route::delete('/{supplier}', [\App\Http\Controllers\Api\SupplierController::class, 'destroy']);
        Route::get('/{supplier}/work-orders', [\App\Http\Controllers\Api\SupplierController::class, 'workOrders']);
        
        // User assignments
        Route::get('/user/assignments', [\App\Http\Controllers\Api\SupplierController::class, 'userAssignments']);
        Route::post('/assign', [\App\Http\Controllers\Api\SupplierController::class, 'assignToUser']);
        Route::delete('/assignments/{id}', [\App\Http\Controllers\Api\SupplierController::class, 'removeAssignment']);
    });

    // Work Orders API
    Route::prefix('work-orders')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\WorkOrderController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\WorkOrderController::class, 'store']);
        Route::get('/statistics/overview', [\App\Http\Controllers\Api\WorkOrderController::class, 'getStatistics']);
        Route::get('/{workOrder}', [\App\Http\Controllers\Api\WorkOrderController::class, 'show']);
        Route::put('/{workOrder}', [\App\Http\Controllers\Api\WorkOrderController::class, 'update']);
        Route::delete('/{workOrder}', [\App\Http\Controllers\Api\WorkOrderController::class, 'destroy']);
    });

    // Business Intelligence API (Owner role only)
    Route::middleware('role:owner')->prefix('business-intelligence')->group(function () {
        Route::get('/data', [\App\Http\Controllers\Api\BusinessIntelligenceController::class, 'getBusinessData']);
        Route::get('/expense-tracking', [\App\Http\Controllers\Api\BusinessIntelligenceController::class, 'getExpenseTracking']);
        Route::get('/income-expenses-analysis', [\App\Http\Controllers\Api\BusinessIntelligenceController::class, 'getIncomeVsExpenses']);
        Route::post('/ai-insights', [\App\Http\Controllers\Api\BusinessIntelligenceController::class, 'generateAIInsights']);
        Route::get('/export', [\App\Http\Controllers\Api\BusinessIntelligenceController::class, 'exportBusinessReport']);
        Route::get('/department-performance', [\App\Http\Controllers\Api\BusinessIntelligenceController::class, 'getDepartmentPerformance']);
    });
});