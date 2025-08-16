<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DicomController;
use App\Http\Controllers\FhirController;
use App\Http\Controllers\MedGemmaController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Helpers\RoleHelper;
use App\Http\Controllers\Api\UserController as ApiUserController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\FinancialDashboardController;
use App\Http\Controllers\QuickLoginController;

// CSRF Token refresh endpoint
Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');

// Heartbeat endpoint for session management
Route::post('/heartbeat', function () {
    return response()->json(['status' => 'alive']);
})->middleware('auth');

// CSRF token endpoint
Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
});

// Test login route without CSRF for debugging
Route::post('/test-login', function (Illuminate\Http\Request $request) {
    $credentials = $request->only('email', 'password');
    
    if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
        return response()->json(['success' => true, 'message' => 'Login successful']);
    } else {
        return response()->json(['success' => false, 'message' => 'Invalid credentials']);
    }
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// Debug login page
Route::get('/login-debug', function () {
    return view('login-debug');
});
Route::get('/', function () {
    // Redirect to login if not authenticated, otherwise to dashboard
    if (!Auth::check()) {
        return redirect('/login');
    }
    return redirect('/dashboard');
});

// Protected routes that require authentication
Route::middleware(['auth', 'session.timeout'])->group(function () {

// Simple front-end dashboard
Route::get('/app', function () {
    return view('app');
})->name('app');

// Dashboard page (role-based view)
Route::get('/dashboard', function () {
    $user = Auth::user();
    
    // Redirect based on user role using helper
    if (RoleHelper::isRadiologist($user)) {
        return view('radiologist-dashboard');
    } elseif (RoleHelper::isLabTechnician($user)) {
        return view('lab-tech-dashboard');
    } elseif (RoleHelper::isDoctor($user)) {
        return redirect()->route('patients'); // Doctors go to patient management
    } elseif (RoleHelper::isAdmin($user)) {
        return view('dashboard'); // Admins get the main dashboard
    } else {
        return view('dashboard'); // Default dashboard for other roles
    }
})->name('dashboard');

// Patients page (role-based view)
Route::get('/patients', function () {
    $user = Auth::user();
    
    // Check if user is admin or has admin role
    if ($user && ($user->role === 'admin' || $user->is_admin)) {
        return view('patients'); // Full admin view with add/invoice capabilities
    } else {
        return view('patients-doctor'); // Doctor view with restricted permissions
    }
})->name('patients');

// MedGemma page (dedicated view)
Route::get('/medgemma', function () {
    return view('medgemma');
})->name('medgemma');

// Reports now integrated into dashboard - redirect to dashboard
Route::get('/reports', function () {
    return redirect('/dashboard');
})->name('reports');

// Help page
Route::get('/help', function () {
    return view('help');
})->name('help');

// Radiologist dashboard
Route::get('/radiologist', function () {
    return view('radiologist-dashboard');
})->middleware(['radiologist'])->name('radiologist.dashboard');

// Lab Technician dashboard
Route::get('/lab-tech', function () {
    return view('lab-tech-dashboard');
})->name('lab-tech.dashboard');

// DICOM upload page
Route::get('/dicom-upload', function () {
    return view('dicom-upload');
})->name('dicom.upload.page');

// Admin management page
Route::get('/admin-management', function () {
    return view('admin-management');
})->name('admin.management');

// User management page - Admin only
Route::get('/user-management', function () {
    // Check if user has Admin role or is the specific admin account
    if (!Auth::check() || 
        ((!(property_exists(Auth::user(), 'role') && Auth::user()->role === 'Admin')) && Auth::user()->email !== 'admin@medgemma.com')) {
        abort(403, 'Access denied. Admin privileges required.');
    }
    return view('user-management');
})->name('user.management')->middleware('auth');

// Financial Dashboard Routes
Route::get('/financial/doctor-dashboard', [FinancialDashboardController::class, 'doctorDashboard'])->name('financial.doctor-dashboard');
Route::get('/financial/admin-dashboard', [FinancialDashboardController::class, 'adminDashboard'])->name('financial.admin-dashboard');

// Test financial dashboard without auth
Route::get('/test-financial', function () {
    $user = App\Models\User::where('email', 'admin@medgemma.com')->first();
    \Illuminate\Support\Facades\Auth::login($user);
    return redirect()->route('financial.admin-dashboard');
});

// Quick login routes for demo
Route::get('/quick-login', [QuickLoginController::class, 'showQuickLogin']);
Route::get('/quick-login/admin', [QuickLoginController::class, 'loginAsAdmin']);
Route::get('/quick-login/doctor', [QuickLoginController::class, 'loginAsDoctor']);
Route::get('/quick-login/radiologist', [QuickLoginController::class, 'loginAsRadiologist']);
Route::get('/quick-login/lab-tech', [QuickLoginController::class, 'loginAsLabTech']);
});

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

// Admin: user management (secured with basic auth)
Route::middleware('admin.basic')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
});

// Admin account management routes (open for initial setup)
Route::prefix('admin-setup')->name('admin.setup.')->group(function () {
    Route::post('/create-admin', [AdminController::class, 'createAdmin'])->name('create');
    Route::post('/create-fahmad', [AdminController::class, 'createSpecificAdmin'])->name('create.fahmad');
    Route::get('/list-admins', [AdminController::class, 'listAdmins'])->name('list');
    Route::get('/test-email', [AdminController::class, 'testEmail'])->name('test.email');
    
    // Email preview route
    Route::get('/email-preview', function () {
        $user = new \App\Models\User([
            'name' => 'Fahmad Iqbal',
            'email' => 'fahmad_iqbal@hotmail.com'
        ]);
        return new \App\Mail\AdminWelcomeMail($user, '123456');
    })->name('email.preview');
});

$securedMiddleware = app()->environment('production') ? ['auth:sanctum', 'role:clinician|admin'] : [];

// Reports (secured for clinicians and admins; open in testing)
Route::middleware($securedMiddleware)->prefix('reports')->name('reports.')->group(function () {
    Route::get('/patients', [ReportsController::class, 'patients'])->name('patients');
    Route::get('/patients/{patient}', [ReportsController::class, 'patientShow'])->name('patients.show');
});

// MedGemma analysis endpoints (secured; open in testing)
Route::middleware($securedMiddleware)->prefix('medgemma')->name('medgemma.')->group(function () {
    Route::get('/status', [MedGemmaController::class, 'status'])->name('status');
    Route::post('/analyze/imaging/{study}', [MedGemmaController::class, 'analyzeImagingStudy'])->name('analyze.imaging');
    Route::post('/analyze/labs/{patient}', [MedGemmaController::class, 'analyzeLabs'])->name('analyze.labs');
    Route::post('/analyze/text', [MedGemmaController::class, 'analyzeText'])->name('analyze.text');
    Route::post('/second-opinion/{patient}', [MedGemmaController::class, 'combinedSecondOpinion'])->name('second.opinion');
    Route::get('/insights/{patient}', [MedGemmaController::class, 'quickInsights'])->name('insights');
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

// API routes for user management (temporarily unsecured for testing)
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/users', [ApiUserController::class, 'index'])->name('users.index');
    Route::post('/users', [ApiUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [ApiUserController::class, 'show'])->name('users.show');
    Route::put('/users/{user}', [ApiUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [ApiUserController::class, 'destroy'])->name('users.destroy');
    Route::get('/roles', [ApiUserController::class, 'roles'])->name('roles.index');
    
    // Revenue share management for doctors
    Route::put('/users/{user}/revenue-share', function (Illuminate\Http\Request $request, \App\Models\User $user) {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'revenue_share' => 'required|numeric|min:0|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update user's revenue share percentage
        $user->update(['revenue_share' => $request->revenue_share]);
        
        return response()->json([
            'message' => 'Revenue share updated successfully',
            'user' => $user
        ]);
    })->name('users.revenue-share.update');

    Route::get('/users/{user}/earnings', function (\App\Models\User $user) {
        // Get doctor's earnings summary
        $earnings = \App\Models\DoctorEarning::where('doctor_id', $user->id)
            ->selectRaw('SUM(amount) as total_earnings, COUNT(*) as total_procedures')
            ->first();
            
        return response()->json([
            'doctor' => $user->name,
            'total_earnings' => $earnings->total_earnings ?? 0,
            'total_procedures' => $earnings->total_procedures ?? 0,
            'revenue_share' => $user->revenue_share,
            'revenue_share_set' => !is_null($user->revenue_share)
        ]);
    })->name('users.earnings');
    
    // Patients API routes
    Route::get('/patients', function () {
        try {
            $patients = \App\Models\Patient::select('id', 'mrn', 'first_name', 'last_name', 'name', 'dob', 'sex')
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Format patient names consistently
            $formattedPatients = $patients->map(function($patient) {
                $name = $patient->name;
                if (empty($name)) {
                    $name = trim(($patient->first_name ?? '') . ' ' . ($patient->last_name ?? ''));
                }
                
                return [
                    'id' => $patient->id,
                    'mrn' => $patient->mrn,
                    'name' => $name ?: 'Unknown Patient',
                    'first_name' => $patient->first_name,
                    'last_name' => $patient->last_name,
                    'dob' => $patient->dob,
                    'sex' => $patient->sex,
                ];
            });
            
            return response()->json($formattedPatients);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load patients',
                'message' => $e->getMessage()
            ], 500);
        }
    })->name('patients.index');
    
    // Patient routes moved to api.php for proper handling with PatientController
    // Use /api/patients endpoint which includes proper name formatting and pagination
    
    Route::post('/patients', function (Illuminate\Http\Request $request) {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'mrn' => 'required|string|max:255|unique:patients',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'nullable|date',
            'sex' => 'nullable|in:male,female,other,unknown',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $patientData = $request->all();
        $patientData['uuid'] = \Illuminate\Support\Str::uuid();
        
        $patient = \App\Models\Patient::create($patientData);
        
        return response()->json([
            'message' => 'Patient created successfully',
            'patient' => $patient
        ], 201);
    })->name('patients.store');
    
    Route::get('/patients/{patient}', function (\App\Models\Patient $patient) {
        return $patient;
    })->name('patients.show');
    
    Route::put('/patients/{patient}', function (Illuminate\Http\Request $request, \App\Models\Patient $patient) {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'mrn' => 'required|string|max:255|unique:patients,mrn,' . $patient->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'nullable|date',
            'sex' => 'nullable|in:male,female,other,unknown',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $patient->update($request->all());
        
        return response()->json([
            'message' => 'Patient updated successfully',
            'patient' => $patient
        ]);
    })->name('patients.update');
    
    Route::delete('/patients/{patient}', function (\App\Models\Patient $patient) {
        $patient->delete();
        
        return response()->json([
            'message' => 'Patient deleted successfully'
        ]);
    })->name('patients.destroy');
    
    // Get doctors for dropdown
    Route::get('/doctors', function () {
        try {
            $doctors = \App\Models\User::whereHas('roles', function($query) {
                $query->where('name', 'Doctor');
            })->get(['id', 'name', 'email']);
            
            // Format the response to handle encrypted names
            $formattedDoctors = $doctors->map(function($doctor) {
                $name = $doctor->name;
                
                // Fallback for encrypted or corrupted names
                if (empty($name) || strlen($name) > 100 || str_contains($name, 'eyJ')) {
                    // Use email prefix as fallback
                    $emailPrefix = explode('@', $doctor->email)[0];
                    $name = 'Dr. ' . ucfirst(str_replace(['.', '_', '-'], ' ', $emailPrefix));
                }
                
                return [
                    'id' => $doctor->id,
                    'name' => $name,
                    'email' => $doctor->email,
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
    
    // Invoice CRUD API routes
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/view', [InvoiceController::class, 'view'])->name('invoices.view');
    
    // Dashboard stats
    Route::get('/dashboard-stats', function () {
        return response()->json([
            'patients' => \App\Models\Patient::count(),
            'studies' => \App\Models\ImagingStudy::count(),
            'labs' => \App\Models\LabOrder::count(),
            'ai' => \App\Models\AiResult::count()
        ]);
    })->name('dashboard.stats');
    
    // Clinical API routes moved to api.php for proper routing
});
