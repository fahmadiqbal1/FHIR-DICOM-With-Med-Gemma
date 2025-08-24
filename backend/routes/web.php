<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

// Quick login routes for demo (no auth middleware required)
Route::get('/quick-login', [QuickLoginController::class, 'showQuickLogin']);
Route::get('/quick-login/admin', [QuickLoginController::class, 'loginAsAdmin']);
Route::get('/quick-login/doctor', [QuickLoginController::class, 'loginAsDoctor']);
Route::get('/quick-login/radiologist', [QuickLoginController::class, 'loginAsRadiologist']);
Route::get('/quick-login/lab-tech', [QuickLoginController::class, 'loginAsLabTech']);
Route::get('/quick-login/owner', [QuickLoginController::class, 'loginAsOwner']);

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
        return redirect()->route('login');
    }
    return redirect()->route('dashboard');
});

// Protected routes that require authentication
Route::middleware(['auth'])->group(function () {

// Simple front-end dashboard
Route::get('/app', function () {
    return view('app');
})->name('app');

// Dashboard page (role-based view)
Route::get('/dashboard', function () {
    $user = Auth::user();
    
    // User is guaranteed to exist due to auth middleware
    if (!$user) {
        Log::error('Dashboard: No user found despite auth middleware');
        return redirect()->route('login');
    }
    
    // Debug: Log user role detection
    Log::info('Dashboard access attempt', [
        'user_id' => $user->id,
        'email' => $user->email,
        'role_column' => $user->role ?? 'null',
        'spatie_roles' => $user->roles->pluck('name')->toArray()
    ]);
    
    // Check for owner role first (both old and new systems)
    if ($user->role === 'owner' || $user->hasRole('owner') || $user->hasRole('Owner')) {
        Log::info('Redirecting to owner dashboard');
        
        // Get dashboard data
        $totalRevenue = \App\Models\Invoice::where('status', 'paid')->sum('amount');
        $totalProfit = \App\Models\DoctorEarning::sum('admin_share');
        $profitMargin = $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0;
        
        // Get department-wise revenue (last 30 days)
        $departmentRevenue = \App\Models\Invoice::join('users', 'invoices.doctor_id', '=', 'users.id')
            ->where('invoices.created_at', '>=', now()->subDays(30))
            ->where('invoices.status', 'paid')
            ->selectRaw('
                CASE 
                    WHEN invoices.description LIKE "%CT%" OR invoices.description LIKE "%MRI%" OR invoices.description LIKE "%X-Ray%" OR invoices.description LIKE "%Ultrasound%" THEN "radiology"
                    WHEN invoices.description LIKE "%Laboratory%" OR invoices.description LIKE "%Test%" OR invoices.description LIKE "%CBC%" THEN "laboratory" 
                    WHEN invoices.description LIKE "%Prescription%" OR invoices.description LIKE "%Medication%" THEN "pharmacy"
                    ELSE "consultation"
                END as department,
                SUM(invoices.amount) as revenue,
                COUNT(*) as procedures
            ')
            ->groupBy('department')
            ->get()
            ->keyBy('department');
        
        // Staff count
        $staffCount = [
            'total' => \App\Models\User::count() - 1, // Exclude owner
            'doctors' => \App\Models\User::where(function($q) { 
                $q->where('role', 'doctor')->orWhereHas('roles', function($r) { 
                    $r->where('name', 'Doctor'); 
                }); 
            })->count(),
            'lab_techs' => \App\Models\User::where(function($q) { 
                $q->where('role', 'lab_tech')->orWhereHas('roles', function($r) { 
                    $r->where('name', 'Lab Technician'); 
                }); 
            })->count(),
            'radiologists' => \App\Models\User::where(function($q) { 
                $q->where('role', 'radiologist')->orWhereHas('roles', function($r) { 
                    $r->where('name', 'Radiologist'); 
                }); 
            })->count(),
            'pharmacists' => \App\Models\User::where(function($q) { 
                $q->where('role', 'pharmacist')->orWhereHas('roles', function($r) { 
                    $r->where('name', 'Pharmacist'); 
                }); 
            })->count(),
            'admins' => \App\Models\User::where(function($q) { 
                $q->where('role', 'admin')->orWhereHas('roles', function($r) { 
                    $r->where('name', 'Admin'); 
                }); 
            })->count(),
        ];
        
        $dashboardData = [
            'total_revenue' => round($totalRevenue, 2),
            'total_profit' => round($totalProfit, 2),
            'profit_margin' => round($profitMargin, 2),
            'departments' => [
                'consultation' => [
                    'revenue' => round($departmentRevenue->get('consultation')->revenue ?? 0, 2),
                    'owner_profit' => round(($departmentRevenue->get('consultation')->revenue ?? 0) * 0.3, 2),
                    'margin' => 30,
                    'procedures' => $departmentRevenue->get('consultation')->procedures ?? 0
                ],
                'laboratory' => [
                    'revenue' => round($departmentRevenue->get('laboratory')->revenue ?? 0, 2),
                    'owner_profit' => round(($departmentRevenue->get('laboratory')->revenue ?? 0) * 0.3, 2),
                    'margin' => 30,
                    'procedures' => $departmentRevenue->get('laboratory')->procedures ?? 0
                ],
                'radiology' => [
                    'revenue' => round($departmentRevenue->get('radiology')->revenue ?? 0, 2),
                    'owner_profit' => round(($departmentRevenue->get('radiology')->revenue ?? 0) * 0.3, 2),
                    'margin' => 30,
                    'procedures' => $departmentRevenue->get('radiology')->procedures ?? 0
                ],
                'pharmacy' => [
                    'revenue' => round($departmentRevenue->get('pharmacy')->revenue ?? 0, 2),
                    'owner_profit' => round(($departmentRevenue->get('pharmacy')->revenue ?? 0) * 0.3, 2),
                    'margin' => 30,
                    'procedures' => $departmentRevenue->get('pharmacy')->procedures ?? 0
                ]
            ],
            'staff_count' => $staffCount,
            'performance_metrics' => [
                'patient_satisfaction' => 94,
                'staff_utilization' => 87,
                'equipment_uptime' => 96,
                'roi' => round($totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0, 1)
            ],
            'recent_activity' => [
                'new_patients_today' => \App\Models\Patient::whereDate('created_at', today())->count(),
                'invoices_today' => \App\Models\Invoice::whereDate('created_at', today())->count(),
                'revenue_today' => \App\Models\Invoice::whereDate('created_at', today())->where('status', 'paid')->sum('amount')
            ]
        ];
        
        return view('owner-dashboard', compact('user', 'dashboardData')); // Pass data to view
    }
    
    // Check for admin role (both systems)
    if ($user->role === 'admin' || $user->hasRole('admin') || $user->hasRole('Admin')) {
        Log::info('Redirecting to admin dashboard');
        return view('admin-dashboard'); // Dedicated admin dashboard
    }
    
    // Check for doctor role (both systems)  
    if ($user->role === 'doctor' || $user->hasRole('doctor') || $user->hasRole('Doctor') || RoleHelper::isDoctor($user)) {
        Log::info('Redirecting to doctor dashboard');
        return view('doctor-dashboard'); // Dedicated doctor dashboard with financials
    }
    
    // Check for radiologist role (both systems)
    if ($user->role === 'radiologist' || $user->hasRole('radiologist') || $user->hasRole('Radiologist') || RoleHelper::isRadiologist($user)) {
        Log::info('Redirecting to radiologist dashboard');
        return view('radiologist-dashboard'); // Dedicated radiologist dashboard
    }
    
    // Check for lab tech role (both systems)
    if ($user->role === 'lab_tech' || $user->hasRole('lab_tech') || $user->hasRole('Lab Technician') || RoleHelper::isLabTechnician($user)) {
        Log::info('Redirecting to lab tech dashboard');
        return view('lab-tech-dashboard'); // Dedicated lab tech dashboard  
    }
    
    // Check for pharmacist role (both systems)
    if ($user->role === 'pharmacist' || $user->hasRole('pharmacist') || $user->hasRole('Pharmacist') || RoleHelper::isPharmacist($user)) {
        Log::info('Redirecting to pharmacist dashboard');
        return view('pharmacist-dashboard'); // Dedicated pharmacist dashboard
    }
    
    // Default to admin dashboard for other roles
    Log::info('Redirecting to default admin dashboard');
    return view('admin-dashboard');
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

// Radiologist dashboard - direct view
Route::get('/radiologist', function () {
    return view('radiologist-dashboard');
})->name('radiologist.dashboard');

// Lab Technician dashboard - direct view
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

// Test Access Page - Direct access to all dashboards
Route::get('/test-access', function () {
    return view('test-access');
})->name('test.access');

// Direct Dashboard Access Routes (for testing and direct access)
Route::get('/admin-dashboard-direct', function () {
    return view('dashboard');
})->name('admin.dashboard.direct');

Route::get('/doctor-dashboard-direct', function () {
    return view('patients');  
})->name('doctor.dashboard.direct');

Route::get('/radiologist-dashboard-direct', function () {
    return view('radiologist-dashboard');
})->name('radiologist.dashboard.direct');

Route::get('/lab-tech-dashboard', function () {
    return view('lab-tech-dashboard');
})->name('lab-tech.dashboard');

// Pharmacist dashboard - dedicated view
Route::get('/pharmacist-dashboard', function () {
    return view('pharmacist-dashboard');
})->name('pharmacist.dashboard');

// Doctor AI Analysis page (exclusive to doctors)
Route::get('/doctor/ai-analysis', function () {
    $user = Auth::user();
    
    // Check if user is a doctor
    if (!RoleHelper::isDoctor($user)) {
        abort(403, 'Access denied. This feature is exclusive to doctors.');
    }
    
    return view('doctor-ai-analysis');
})->name('doctor.ai.analysis');

// Financial Dashboard Routes - now redirect to main dashboards
Route::get('/financial/doctor-dashboard', [FinancialDashboardController::class, 'doctorDashboard'])->name('financial.doctor-dashboard')->middleware('auth');
Route::get('/financial/admin-dashboard', function() {
    return redirect('/dashboard')->with('info', 'Financial data is integrated into the main admin dashboard');
})->name('financial.admin-dashboard');

// Test financial dashboard without auth - now redirects to main dashboard
Route::get('/test-financial', function () {
    $user = App\Models\User::where('email', 'admin@medgemma.com')->first();
    \Illuminate\Support\Facades\Auth::login($user);
    return redirect()->route('dashboard');
});


// Configuration pages
Route::get('/lab-tech-configuration', function () {
    return view('lab-tech-configuration');
})->name('lab-tech-configuration');

Route::get('/radiologist-configuration', function () {
    return view('radiologist-configuration');
})->name('radiologist-configuration');

// Radiologist module routes
Route::prefix('radiologist')->name('radiologist.')->group(function () {
    Route::get('/viewer', function () {
        return view('radiologist.viewer', [
            'title' => 'DICOM Viewer',
            'message' => 'DICOM viewer interface for radiological image analysis'
        ]);
    })->name('viewer');
    
    Route::get('/studies', function () {
        return view('radiologist.studies', [
            'title' => 'Study Queue',
            'message' => 'Pending radiological studies for review'
        ]);
    })->name('studies');
    
    Route::get('/reports', function () {
        return view('radiologist.reports', [
            'title' => 'Radiology Reports',
            'message' => 'Generated and pending radiology reports'
        ]);
    })->name('reports');
    
    Route::get('/analytics', function () {
        return view('radiologist.analytics', [
            'title' => 'Radiology Analytics',
            'message' => 'Performance analytics and metrics'
        ]);
    })->name('analytics');
    
    Route::get('/critical-findings', function () {
        return view('radiologist.critical-findings', [
            'title' => 'Critical Findings',
            'message' => 'Urgent and critical radiological findings'
        ]);
    })->name('critical-findings');
    
    Route::get('/templates', function () {
        return view('radiologist.templates', [
            'title' => 'Report Templates',
            'message' => 'Standard radiology report templates'
        ]);
    })->name('templates');
});

// Enhanced doctor dashboard
Route::get('/doctor-enhanced-dashboard', function () {
    return view('doctor-enhanced-dashboard');
})->name('doctor-enhanced-dashboard');

// ==================== NEW COMPREHENSIVE FUNCTIONALITY ROUTES ====================

// Owner Module Routes (Owner-only access to user management)
Route::middleware(['auth'])->prefix('owner')->name('owner.')->group(function () {
    // Check owner role in the routes for added security
    Route::middleware(['role:owner'])->group(function () {
        Route::get('/users', function () {
            $users = App\Models\User::with('roles')->get();
            $roles = Spatie\Permission\Models\Role::all();
            $rolesAvailable = true;
            return view('owner.users', compact('users', 'roles', 'rolesAvailable'));
        })->name('users.index');
        
        // API endpoint for owner dashboard staff data
        Route::get('/api/staff-data', function () {
            $users = App\Models\User::with('roles')
                ->where('email', '!=', 'owner@medgemma.com') // Exclude owner
                ->get();
            
            return response()->json($users);
        })->name('api.staff.data');
        
        Route::post('/users', function (Illuminate\Http\Request $request) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'role' => 'required|string|exists:roles,name',
                'revenue_share' => 'nullable|numeric|min:0|max:100',
                'lab_revenue_percentage' => 'nullable|numeric|min:0|max:100',
                'radiology_revenue_percentage' => 'nullable|numeric|min:0|max:100',
                'pharmacy_revenue_percentage' => 'nullable|numeric|min:0|max:100',
            ]);
            
            $user = App\Models\User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
                'is_active_doctor' => 1, // New users are active by default
                'revenue_share' => $request->revenue_share,
                'lab_revenue_percentage' => $request->lab_revenue_percentage,
                'radiology_revenue_percentage' => $request->radiology_revenue_percentage,
                'pharmacy_revenue_percentage' => $request->pharmacy_revenue_percentage,
            ]);
            
            $user->assignRole($request->role);
            
            return redirect()->route('owner.users.index')->with('status', 'Staff member created successfully!');
        })->name('users.store');
        
        // Get user details for editing
        Route::get('/users/{user}', function (App\Models\User $user) {
            $roles = Spatie\Permission\Models\Role::all();
            return response()->json([
                'user' => $user,
                'user_roles' => $user->roles->pluck('name'),
                'available_roles' => $roles
            ]);
        })->name('users.show');
        
        // Update user
        Route::put('/users/{user}', function (Illuminate\Http\Request $request, App\Models\User $user) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'role' => 'required|string|exists:roles,name',
                'is_active_doctor' => 'required|boolean',
                'revenue_share' => 'nullable|numeric|min:0|max:100',
                'lab_revenue_percentage' => 'nullable|numeric|min:0|max:100',
                'radiology_revenue_percentage' => 'nullable|numeric|min:0|max:100',
                'pharmacy_revenue_percentage' => 'nullable|numeric|min:0|max:100',
            ]);
            
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'is_active_doctor' => $request->is_active_doctor,
                'revenue_share' => $request->revenue_share,
                'lab_revenue_percentage' => $request->lab_revenue_percentage,
                'radiology_revenue_percentage' => $request->radiology_revenue_percentage,
                'pharmacy_revenue_percentage' => $request->pharmacy_revenue_percentage,
            ]);
            
            // Update role
            $user->syncRoles([$request->role]);
            
            return response()->json(['message' => 'User updated successfully']);
        })->name('users.update');
        
        // Toggle user active status
        Route::patch('/users/{user}/toggle-status', function (App\Models\User $user) {
            $user->update(['is_active_doctor' => !$user->is_active_doctor]);
            return response()->json([
                'message' => 'User status updated successfully',
                'is_active' => $user->is_active_doctor
            ]);
        })->name('users.toggle-status');
        
        // Delete user
        Route::delete('/users/{user}', function (App\Models\User $user) {
            try {
                // Check for related records and handle them
                if ($user->salaries()->count() > 0) {
                    // Option 1: Delete related salaries
                    $user->salaries()->delete();
                }
                
                if ($user->expenses()->count() > 0) {
                    // Option 1: Transfer expenses to current admin or delete them
                    $user->expenses()->delete();
                }
                
                if ($user->doctorEarnings()->count() > 0) {
                    $user->doctorEarnings()->delete();
                }
                
                if ($user->invoices()->count() > 0) {
                    // Don't delete if user has invoices - this is important business data
                    return response()->json([
                        'error' => 'Cannot delete user with existing invoices. Please reassign invoices first.'
                    ], 400);
                }
                
                $user->delete();
                return response()->json(['message' => 'User deleted successfully']);
                
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Failed to delete user: ' . $e->getMessage()
                ], 500);
            }
        })->name('users.destroy');
        
        Route::get('/reports', function () {
            return view('owner.reports');
        })->name('reports');
    });
});

// Pharmacist Module Routes
Route::prefix('pharmacist')->name('pharmacist.')->group(function () {
    Route::get('/prescriptions', function () {
        return view('pharmacist.prescriptions');
    })->name('prescriptions');
    
    Route::get('/inventory', function () {
        return view('pharmacist.inventory');
    })->name('inventory');
    
    Route::get('/dispensing', function () {
        return view('pharmacist.dispensing');
    })->name('dispensing');
    
    Route::get('/counseling', function () {
        return view('pharmacist.counseling');
    })->name('counseling');
    
    Route::get('/earnings', function () {
        return view('pharmacist.earnings');
    })->name('earnings');
    
    Route::get('/reports', function () {
        return view('pharmacist.reports');
    })->name('reports');
});

// Lab Technician Module Routes
Route::prefix('lab-tech')->name('lab-tech.')->group(function () {
    Route::get('/samples', function () {
        return view('lab-tech.samples');
    })->name('samples');
    
    Route::get('/results', function () {
        return view('lab-tech.results');
    })->name('results');
    
    Route::get('/equipment', function () {
        return view('lab-tech.equipment');
    })->name('equipment');
    
    Route::get('/reports', function () {
        return view('lab-tech.reports');
    })->name('reports');
    
    Route::get('/quality-control', function () {
        return view('lab-tech.quality-control');
    })->name('quality-control');
});

// Radiologist Module Routes
Route::prefix('radiologist')->name('radiologist.')->group(function () {
    Route::get('/studies', function () {
        return view('radiologist.studies');
    })->name('studies');
    
    Route::get('/reports', function () {
        return view('radiologist.reports');
    })->name('reports');
    
    Route::get('/viewer', function () {
        return view('radiologist.viewer');
    })->name('viewer');
    
    Route::get('/critical-findings', function () {
        return view('radiologist.critical-findings');
    })->name('critical-findings');
    
    Route::get('/analytics', function () {
        return view('radiologist.analytics');
    })->name('analytics');
    
    Route::get('/templates', function () {
        return view('radiologist.templates');
    })->name('templates');
});

// Doctor Module Routes
Route::prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/appointments', function () {
        return view('doctor.appointments');
    })->name('appointments');
    
    Route::get('/prescriptions', function () {
        return view('doctor.prescriptions');
    })->name('prescriptions');
    
    Route::get('/test-results', function () {
        return view('doctor.test-results');
    })->name('test-results');
    
    Route::get('/earnings', function () {
        return view('doctor.earnings');
    })->name('earnings');
});

// Admin Module Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    
    Route::get('/reports', function () {
        return view('admin.reports');
    })->name('reports');
    
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('settings');
    
    Route::get('/audit-logs', function () {
        return view('admin.audit-logs');
    })->name('audit-logs');
    
    Route::get('/backup', function () {
        return view('admin.backup');
    })->name('backup');
    
    Route::get('/invoices', function () {
        return view('admin.invoices');
    })->name('invoices');
    
    // Web API routes for patient management (authenticated)
    Route::get('/api/doctors', function () {
        $doctors = User::select('id', 'name', 'email', 'role', 'is_active_doctor')
            ->where('is_active_doctor', 1) // Only active doctors
            ->where(function($query) {
                $query->where('role', 'doctor')
                      ->orWhere('role', 'Doctor')
                      ->orWhere('name', 'like', 'Dr.%') // Include users with "Dr." prefix
                      ->orWhereHas('roles', function($roleQuery) {
                          $roleQuery->where('name', 'Doctor')
                                    ->orWhere('name', 'doctor');
                      });
            })
            ->get();
            
        $formattedDoctors = [];
        foreach($doctors as $doctor) {
            // Ensure name is properly handled for display
            $name = $doctor->name;
            if (empty($name) || strlen($name) > 100 || str_contains($name, 'eyJ')) {
                // Fallback for encrypted or corrupted names
                $emailPrefix = explode('@', $doctor->email)[0];
                $name = 'Dr. ' . ucwords(str_replace(['.', '_', '-'], ' ', $emailPrefix));
            }
            
            $formattedDoctors[] = [
                'id' => $doctor->id,
                'name' => $name,
                'email' => $doctor->email,
                'role' => $doctor->role
            ];
        }
        
        return response()->json($formattedDoctors);
    });
    
    Route::post('/api/invoices', [InvoiceController::class, 'store']);
});

// ==================== PUBLIC INVOICE ROUTES ====================
// Invoice view route - accessible without authentication for PDF generation
Route::get('/invoices/{invoice}', [InvoiceController::class, 'view'])->name('invoices.view');

// ==================== API ROUTES FOR NEW FUNCTIONALITY ====================

// Pharmacist API Routes
Route::prefix('api/pharmacist')->name('api.pharmacist.')->group(function () {
    Route::get('/inventory', function () {
        return response()->json([
            'medications' => [
                ['id' => 1, 'name' => 'Amoxicillin 500mg', 'stock' => 25, 'min_stock' => 50, 'status' => 'low', 'supplier' => 'MedSupply Co', 'cost' => 0.85, 'price' => 2.50],
                ['id' => 2, 'name' => 'Ibuprofen 200mg', 'stock' => 0, 'min_stock' => 100, 'status' => 'out', 'supplier' => 'PharmaCorp', 'cost' => 0.15, 'price' => 0.75],
                ['id' => 3, 'name' => 'Metformin 850mg', 'stock' => 15, 'min_stock' => 30, 'status' => 'critical', 'supplier' => 'HealthDistrib', 'cost' => 0.25, 'price' => 1.20],
                ['id' => 4, 'name' => 'Lisinopril 10mg', 'stock' => 8, 'min_stock' => 25, 'status' => 'low', 'supplier' => 'MediSource', 'cost' => 0.45, 'price' => 2.10],
                ['id' => 5, 'name' => 'Atorvastatin 20mg', 'stock' => 45, 'min_stock' => 30, 'status' => 'good', 'supplier' => 'MedSupply Co', 'cost' => 1.25, 'price' => 4.80]
            ]
        ]);
    });
    
    Route::get('/prescriptions', function () {
        return response()->json([
            'prescriptions' => [
                ['id' => 1, 'medication' => 'Amoxicillin 500mg', 'patient' => 'Sarah Johnson', 'doctor' => 'Dr. Smith', 'status' => 'ready', 'quantity' => 30, 'time' => '10:30 AM'],
                ['id' => 2, 'medication' => 'Lisinopril 10mg', 'patient' => 'Mike Wilson', 'doctor' => 'Dr. Brown', 'status' => 'dispensed', 'quantity' => 90, 'time' => '11:15 AM'],
                ['id' => 3, 'medication' => 'Metformin 850mg', 'patient' => 'Lisa Brown', 'doctor' => 'Dr. Davis', 'status' => 'pending', 'quantity' => 60, 'time' => '11:45 AM'],
                ['id' => 4, 'medication' => 'Atorvastatin 20mg', 'patient' => 'John Smith', 'doctor' => 'Dr. Wilson', 'status' => 'ready', 'quantity' => 30, 'time' => '12:20 PM']
            ]
        ]);
    });
});

// Lab Tech API Routes
Route::prefix('api/lab-tech')->name('api.lab-tech.')->group(function () {
    Route::get('/samples', function () {
        return response()->json([
            'samples' => [
                ['id' => 1, 'patient' => 'John Doe', 'test_type' => 'CBC', 'status' => 'pending', 'priority' => 'routine', 'collection_time' => '09:30 AM'],
                ['id' => 2, 'patient' => 'Jane Smith', 'test_type' => 'Lipid Panel', 'status' => 'in_progress', 'priority' => 'urgent', 'collection_time' => '10:15 AM'],
                ['id' => 3, 'patient' => 'Mike Johnson', 'test_type' => 'HbA1c', 'status' => 'completed', 'priority' => 'routine', 'collection_time' => '08:45 AM'],
                ['id' => 4, 'patient' => 'Sarah Wilson', 'test_type' => 'Thyroid Panel', 'status' => 'pending', 'priority' => 'stat', 'collection_time' => '11:00 AM']
            ]
        ]);
    });
    
    Route::get('/equipment', function () {
        return response()->json([
            'equipment' => [
                ['id' => 1, 'name' => 'Chemistry Analyzer', 'status' => 'online', 'last_maintenance' => '2025-08-15', 'next_maintenance' => '2025-09-15'],
                ['id' => 2, 'name' => 'Hematology Analyzer', 'status' => 'maintenance', 'last_maintenance' => '2025-08-20', 'next_maintenance' => '2025-09-20'],
                ['id' => 3, 'name' => 'Microscope Station 1', 'status' => 'online', 'last_maintenance' => '2025-08-10', 'next_maintenance' => '2025-09-10'],
                ['id' => 4, 'name' => 'Centrifuge Unit', 'status' => 'offline', 'last_maintenance' => '2025-08-12', 'next_maintenance' => '2025-09-12']
            ]
        ]);
    });
});

// Radiologist API Routes
Route::prefix('api/radiologist')->name('api.radiologist.')->group(function () {
    Route::get('/studies', function () {
        return response()->json([
            'studies' => [
                ['id' => 1, 'patient' => 'Alice Brown', 'study_type' => 'CT Chest', 'status' => 'pending', 'priority' => 'urgent', 'scheduled_time' => '14:30'],
                ['id' => 2, 'patient' => 'Bob Davis', 'study_type' => 'MRI Brain', 'status' => 'in_progress', 'priority' => 'routine', 'scheduled_time' => '15:00'],
                ['id' => 3, 'patient' => 'Carol Smith', 'study_type' => 'X-Ray Chest', 'status' => 'completed', 'priority' => 'stat', 'scheduled_time' => '13:15'],
                ['id' => 4, 'patient' => 'David Wilson', 'study_type' => 'Ultrasound Abdomen', 'status' => 'pending', 'priority' => 'routine', 'scheduled_time' => '16:00']
            ]
        ]);
    });
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
            ->selectRaw('SUM(doctor_share) as total_earnings, COUNT(*) as total_procedures, SUM(patients_attended) as total_patients')
            ->first();
            
        return response()->json([
            'doctor' => $user->name,
            'total_earnings' => $earnings->total_earnings ?? 0,
            'total_procedures' => $earnings->total_procedures ?? 0,
            'total_patients' => $earnings->total_patients ?? 0,
            'revenue_share' => $user->revenue_share ?? 70,
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
    
    // Dashboard stats
    Route::get('/dashboard-stats', function () {
        return response()->json([
            'patients' => \App\Models\Patient::count(),
            'studies' => \App\Models\ImagingStudy::count(),
            'labs' => \App\Models\LabOrder::count(),
            'ai' => \App\Models\AiResult::count()
        ]);
    })->name('dashboard.stats');
    
    // Pharmacist Dashboard API
    Route::get('/dashboard/pharmacist', function () {
        return response()->json([
            'todays_revenue' => 2840,
            'weekly_revenue' => 18200,
            'monthly_revenue' => 76500,
            'profit_margin' => 28,
            'pending_prescriptions' => 15,
            'dispensed_today' => 28,
            'low_stock_items' => 7,
            'average_processing_time' => 4.2,
            'inventory_alerts' => [
                'low_stock' => 7,
                'expired_soon' => 3,
                'out_of_stock' => 2
            ],
            'supplier_deliveries' => [
                'scheduled_today' => 2,
                'pending_orders' => 5
            ]
        ]);
    })->name('dashboard.pharmacist');
    
    // Clinical API routes moved to api.php for proper routing
    
    // Doctor-Exclusive AI Analysis API routes
    Route::post('/api/ai-analysis/clinical', function (Illuminate\Http\Request $request) {
        $user = Auth::user();
        
        // Check if user is a doctor
        if (!RoleHelper::isDoctor($user)) {
            return response()->json(['error' => 'Access denied. This feature is exclusive to doctors.'], 403);
        }
        
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'patient_id' => 'required|integer|exists:patients,id',
            'additional_notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Get patient data
        $patient = \App\Models\Patient::find($request->patient_id);
        
        // Simulate AI analysis of clinical notes
        $analysis = "**Clinical Notes Analysis for " . $patient->name . "**\n\n";
        $analysis .= "**Patient Summary:**\n";
        $analysis .= "- Age: " . (isset($patient->dob) ? \Carbon\Carbon::parse($patient->dob)->age : 'Unknown') . " years\n";
        $analysis .= "- Sex: " . ($patient->sex ?? 'Unknown') . "\n";
        $analysis .= "- MRN: " . ($patient->mrn ?? 'N/A') . "\n\n";
        
        $analysis .= "**Clinical Documentation Review:**\n";
        $analysis .= "• Medical history analysis shows patterns consistent with routine preventive care\n";
        $analysis .= "• No significant red flags identified in available documentation\n";
        $analysis .= "• Patient appears to have regular follow-up care\n\n";
        
        if ($request->additional_notes) {
            $analysis .= "**Additional Clinical Context:**\n";
            $analysis .= $request->additional_notes . "\n\n";
        }
        
        $recommendations = "• Continue routine monitoring and preventive care\n";
        $recommendations .= "• Consider updating immunizations if due\n";
        $recommendations .= "• Schedule annual physical examination if overdue\n";
        $recommendations .= "• Monitor for any changes in symptoms or condition";

        return response()->json([
            'analysis' => $analysis,
            'recommendations' => $recommendations,
            'timestamp' => now()
        ]);
    })->name('api.ai.analysis.clinical');

    Route::post('/api/ai-analysis/labs', function (Illuminate\Http\Request $request) {
        $user = Auth::user();
        
        // Check if user is a doctor
        if (!RoleHelper::isDoctor($user)) {
            return response()->json(['error' => 'Access denied. This feature is exclusive to doctors.'], 403);
        }
        
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'patient_id' => 'required|integer|exists:patients,id',
            'focus' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $patient = \App\Models\Patient::find($request->patient_id);
        
        $analysis = "**Laboratory Results Analysis for " . $patient->name . "**\n\n";
        $analysis .= "**Lab Results Summary:**\n";
        $analysis .= "• Complete Blood Count (CBC): Within normal limits\n";
        $analysis .= "• Basic Metabolic Panel: All values within reference ranges\n";
        $analysis .= "• Lipid Panel: Total cholesterol slightly elevated at 215 mg/dL\n";
        $analysis .= "• HbA1c: 5.4% - Excellent glucose control\n\n";
        
        $analysis .= "**Trend Analysis:**\n";
        $analysis .= "• Glucose levels stable over past 6 months\n";
        $analysis .= "• Cholesterol showing mild upward trend - monitor\n";
        $analysis .= "• Kidney function markers stable\n\n";
        
        if ($request->focus) {
            $analysis .= "**Focused Analysis:**\n";
            $analysis .= "Based on your specific focus: " . $request->focus . "\n\n";
        }
        
        $recommendations = "• Consider dietary counseling for cholesterol management\n";
        $recommendations .= "• Repeat lipid panel in 3 months\n";
        $recommendations .= "• Continue current diabetes management if applicable\n";
        $recommendations .= "• Consider adding vitamin D level if not recently checked";

        return response()->json([
            'analysis' => $analysis,
            'recommendations' => $recommendations,
            'timestamp' => now()
        ]);
    })->name('api.ai.analysis.labs');

    Route::post('/api/ai-analysis/imaging', function (Illuminate\Http\Request $request) {
        $user = Auth::user();
        
        // Check if user is a doctor
        if (!RoleHelper::isDoctor($user)) {
            return response()->json(['error' => 'Access denied. This feature is exclusive to doctors.'], 403);
        }
        
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'patient_id' => 'required|integer|exists:patients,id',
            'questions' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $patient = \App\Models\Patient::find($request->patient_id);
        
        $analysis = "**Imaging Studies Analysis for " . $patient->name . "**\n\n";
        $analysis .= "**Available Imaging Studies:**\n";
        $analysis .= "• Chest X-ray (Date: " . now()->subDays(30)->format('Y-m-d') . "): Clear lung fields, normal cardiac silhouette\n";
        $analysis .= "• CT Abdomen/Pelvis (Date: " . now()->subDays(60)->format('Y-m-d') . "): No acute findings, normal organ enhancement\n\n";
        
        $analysis .= "**Comparative Analysis:**\n";
        $analysis .= "• Current study shows stability compared to previous imaging\n";
        $analysis .= "• No new concerning findings identified\n";
        $analysis .= "• Radiologist reports are consistent with clinical presentation\n\n";
        
        if ($request->questions) {
            $analysis .= "**Clinical Questions Addressed:**\n";
            $analysis .= $request->questions . "\n\n";
            $analysis .= "Based on available imaging, findings appear reassuring.\n\n";
        }
        
        $recommendations = "• Continue routine surveillance imaging as clinically indicated\n";
        $recommendations .= "• Correlate findings with clinical symptoms\n";
        $recommendations .= "• Consider follow-up imaging if symptoms change\n";
        $recommendations .= "• Discuss results with radiologist if clarification needed";

        return response()->json([
            'analysis' => $analysis,
            'recommendations' => $recommendations,
            'timestamp' => now()
        ]);
    })->name('api.ai.analysis.imaging');

    Route::post('/api/ai-analysis/comprehensive', function (Illuminate\Http\Request $request) {
        $user = Auth::user();
        
        // Check if user is a doctor
        if (!RoleHelper::isDoctor($user)) {
            return response()->json(['error' => 'Access denied. This feature is exclusive to doctors.'], 403);
        }
        
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'patient_id' => 'required|integer|exists:patients,id',
            'questions' => 'nullable|string',
            'date_range' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $patient = \App\Models\Patient::find($request->patient_id);
        
        $analysis = "**COMPREHENSIVE SECOND OPINION**\n";
        $analysis .= "**Patient:** " . $patient->name . " | **MRN:** " . ($patient->mrn ?? 'N/A') . "\n";
        $analysis .= "**Analysis Date:** " . now()->format('Y-m-d H:i:s') . "\n\n";
        
        $analysis .= "**INTEGRATED CLINICAL ASSESSMENT:**\n\n";
        
        $analysis .= "**1. Clinical History & Documentation:**\n";
        $analysis .= "• Patient has comprehensive medical records available\n";
        $analysis .= "• Regular follow-up care documented\n";
        $analysis .= "• No significant gaps in care identified\n";
        $analysis .= "• Clinical notes suggest stable condition\n\n";
        
        $analysis .= "**2. Laboratory Data Integration:**\n";
        $analysis .= "• Recent lab work shows overall normal parameters\n";
        $analysis .= "• Trending shows stability in key markers\n";
        $analysis .= "• No critical values requiring immediate attention\n";
        $analysis .= "• Lipid levels warrant monitoring and lifestyle modifications\n\n";
        
        $analysis .= "**3. Imaging Study Correlation:**\n";
        $analysis .= "• All imaging studies reviewed and correlated with clinical findings\n";
        $analysis .= "• No discordance between imaging and clinical presentation\n";
        $analysis .= "• Serial imaging demonstrates stability\n";
        $analysis .= "• No urgent imaging follow-up required\n\n";
        
        $analysis .= "**4. Risk Stratification:**\n";
        $analysis .= "• Overall low-moderate risk profile\n";
        $analysis .= "• Age-appropriate health maintenance up to date\n";
        $analysis .= "• Cardiovascular risk factors present but managed\n";
        $analysis .= "• No red flag symptoms or findings\n\n";
        
        if ($request->questions) {
            $analysis .= "**5. Clinical Questions Addressed:**\n";
            $analysis .= $request->questions . "\n\n";
            $analysis .= "**Assessment of Clinical Questions:**\n";
            $analysis .= "Based on comprehensive review, the clinical questions appear to be addressed appropriately by current management.\n\n";
        }
        
        $analysis .= "**DIFFERENTIAL DIAGNOSIS CONSIDERATIONS:**\n";
        $analysis .= "• Primary diagnosis appears well-supported by available data\n";
        $analysis .= "• Alternative diagnoses considered and appropriately ruled out\n";
        $analysis .= "• No concerning findings suggesting missed diagnosis\n\n";
        
        $recommendations = "**COMPREHENSIVE RECOMMENDATIONS:**\n\n";
        $recommendations .= "**Immediate Actions:**\n";
        $recommendations .= "• No immediate interventions required\n";
        $recommendations .= "• Continue current management plan\n\n";
        
        $recommendations .= "**Short-term (1-3 months):**\n";
        $recommendations .= "• Follow up on lipid management\n";
        $recommendations .= "• Monitor any symptom changes\n";
        $recommendations .= "• Continue routine medications\n\n";
        
        $recommendations .= "**Long-term (3-12 months):**\n";
        $recommendations .= "• Annual comprehensive physical examination\n";
        $recommendations .= "• Age-appropriate screening updates\n";
        $recommendations .= "• Lifestyle counseling reinforcement\n\n";
        
        $recommendations .= "**Drug Interactions & Alerts:**\n";
        $recommendations .= "• No significant drug interactions identified\n";
        $recommendations .= "• Current medication regimen appears appropriate\n";
        $recommendations .= "• Monitor for any new medications that could interact\n\n";
        
        $recommendations .= "**Follow-up Recommendations:**\n";
        $recommendations .= "• Routine follow-up in 3-6 months\n";
        $recommendations .= "• Earlier follow-up if symptoms change\n";
        $recommendations .= "• Patient education on when to seek care";

        return response()->json([
            'analysis' => $analysis,
            'recommendations' => $recommendations,
            'timestamp' => now()
        ]);
    })->name('api.ai.analysis.comprehensive');
});

// Owner Dashboard API (Owner-only access)
Route::get('/dashboard/owner', function () {
    $user = Auth::user();
    
    // Ensure only owners can access this endpoint
    if (!$user || !($user->role === 'owner' || $user->hasRole('owner'))) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    
    // Get financial data from database
    $totalRevenue = App\Models\Invoice::where('status', 'paid')->sum('amount');
    $totalProfit = App\Models\DoctorEarning::sum('admin_share');
    $profitMargin = $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0;
    
    // Get department-wise revenue (last 30 days)
    $departmentRevenue = App\Models\Invoice::join('users', 'invoices.doctor_id', '=', 'users.id')
        ->where('invoices.created_at', '>=', now()->subDays(30))
        ->where('invoices.status', 'paid')
        ->selectRaw('
            CASE 
                WHEN invoices.description LIKE "%CT%" OR invoices.description LIKE "%MRI%" OR invoices.description LIKE "%X-Ray%" OR invoices.description LIKE "%Ultrasound%" THEN "radiology"
                WHEN invoices.description LIKE "%Laboratory%" OR invoices.description LIKE "%Test%" OR invoices.description LIKE "%CBC%" THEN "laboratory" 
                WHEN invoices.description LIKE "%Prescription%" OR invoices.description LIKE "%Medication%" THEN "pharmacy"
                ELSE "consultation"
            END as department,
            SUM(invoices.amount) as revenue,
            COUNT(*) as procedures
        ')
        ->groupBy('department')
        ->get()
        ->keyBy('department');
    
    // Get monthly trends (last 6 months)
    $monthlyTrends = App\Models\Invoice::where('created_at', '>=', now()->subMonths(6))
        ->where('status', 'paid')
        ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as revenue')
        ->groupBy('month')
        ->orderBy('month')
        ->get();
    
    $monthlyProfit = App\Models\DoctorEarning::where('created_at', '>=', now()->subMonths(6))
        ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(admin_share) as profit')
        ->groupBy('month')
        ->orderBy('month')
        ->get();
    
    // Staff count
    $staffCount = [
        'total' => App\Models\User::count() - 1, // Exclude owner
        'doctors' => App\Models\User::where(function($q) { 
            $q->where('role', 'doctor')->orWhereHas('roles', function($r) { 
                $r->where('name', 'Doctor'); 
            }); 
        })->count(),
        'lab_techs' => App\Models\User::where(function($q) { 
            $q->where('role', 'lab_tech')->orWhereHas('roles', function($r) { 
                $r->where('name', 'Lab Technician'); 
            }); 
        })->count(),
        'radiologists' => App\Models\User::where(function($q) { 
            $q->where('role', 'radiologist')->orWhereHas('roles', function($r) { 
                $r->where('name', 'Radiologist'); 
            }); 
        })->count(),
        'pharmacists' => App\Models\User::where(function($q) { 
            $q->where('role', 'pharmacist')->orWhereHas('roles', function($r) { 
                $r->where('name', 'Pharmacist'); 
            }); 
        })->count(),
        'admins' => App\Models\User::where(function($q) { 
            $q->where('role', 'admin')->orWhereHas('roles', function($r) { 
                $r->where('name', 'Admin'); 
            }); 
        })->count(),
    ];
    
    return response()->json([
        'total_revenue' => round($totalRevenue, 2),
        'total_profit' => round($totalProfit, 2),
        'profit_margin' => round($profitMargin, 2),
        'growth_rate' => 8.5, // Can be calculated from trends
        'departments' => [
            'consultation' => [
                'revenue' => round($departmentRevenue->get('consultation')->revenue ?? 0, 2),
                'owner_profit' => round(($departmentRevenue->get('consultation')->revenue ?? 0) * 0.3, 2),
                'margin' => 30,
                'procedures' => $departmentRevenue->get('consultation')->procedures ?? 0
            ],
            'laboratory' => [
                'revenue' => round($departmentRevenue->get('laboratory')->revenue ?? 0, 2),
                'owner_profit' => round(($departmentRevenue->get('laboratory')->revenue ?? 0) * 0.3, 2),
                'margin' => 30,
                'procedures' => $departmentRevenue->get('laboratory')->procedures ?? 0
            ],
            'radiology' => [
                'revenue' => round($departmentRevenue->get('radiology')->revenue ?? 0, 2),
                'owner_profit' => round(($departmentRevenue->get('radiology')->revenue ?? 0) * 0.3, 2),
                'margin' => 30,
                'procedures' => $departmentRevenue->get('radiology')->procedures ?? 0
            ],
            'pharmacy' => [
                'revenue' => round($departmentRevenue->get('pharmacy')->revenue ?? 0, 2),
                'owner_profit' => round(($departmentRevenue->get('pharmacy')->revenue ?? 0) * 0.3, 2),
                'margin' => 30,
                'procedures' => $departmentRevenue->get('pharmacy')->procedures ?? 0
            ]
        ],
        'monthly_trends' => [
            'revenue' => $monthlyTrends->pluck('revenue')->toArray(),
            'profit' => $monthlyProfit->pluck('profit')->toArray(),
            'months' => $monthlyTrends->pluck('month')->toArray()
        ],
        'staff_count' => $staffCount,
        'performance_metrics' => [
            'patient_satisfaction' => 94,
            'staff_utilization' => 87,
            'equipment_uptime' => 96,
            'roi' => round($totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0, 1)
        ],
        'recent_activity' => [
            'new_patients_today' => App\Models\Patient::whereDate('created_at', today())->count(),
            'invoices_today' => App\Models\Invoice::whereDate('created_at', today())->count(),
            'revenue_today' => App\Models\Invoice::whereDate('created_at', today())->where('status', 'paid')->sum('amount')
        ]
    ]);
})->name('dashboard.owner');

    // Configuration API Routes for Imaging Tests
    Route::prefix('configuration')->name('configuration.')->group(function () {
        // Get all imaging tests
        Route::get('/imaging-tests', function () {
            return response()->json([
                'success' => true,
                'data' => [
                    [
                        'id' => 1,
                        'code' => 'CXR',
                        'name' => 'Chest X-Ray',
                        'modality' => 'X-RAY',
                        'body_part' => 'Chest',
                        'estimated_duration' => 15,
                        'is_active' => true,
                        'description' => 'Standard chest radiography for pulmonary evaluation',
                        'preparation_instructions' => ['Remove jewelry and metal objects', 'Wear hospital gown']
                    ],
                    [
                        'id' => 2,
                        'code' => 'CTHEAD',
                        'name' => 'CT Head',
                        'modality' => 'CT',
                        'body_part' => 'Head',
                        'estimated_duration' => 30,
                        'is_active' => true,
                        'description' => 'Non-contrast CT scan of the head',
                        'preparation_instructions' => ['Remove jewelry and hairpins', 'Inform if claustrophobic']
                    ],
                    [
                        'id' => 3,
                        'code' => 'MRIBRAIN',
                        'name' => 'MRI Brain',
                        'modality' => 'MRI',
                        'body_part' => 'Brain',
                        'estimated_duration' => 45,
                        'is_active' => true,
                        'description' => 'Magnetic resonance imaging of the brain',
                        'preparation_instructions' => ['Remove all metal objects', 'Complete MRI safety screening', 'Fast for 4 hours if contrast needed']
                    ],
                    [
                        'id' => 4,
                        'code' => 'USABD',
                        'name' => 'Abdominal Ultrasound',
                        'modality' => 'US',
                        'body_part' => 'Abdomen',
                        'estimated_duration' => 20,
                        'is_active' => true,
                        'description' => 'Ultrasound examination of abdominal organs',
                        'preparation_instructions' => ['Fast for 8 hours before exam', 'Drink water 1 hour before and hold']
                    ],
                    [
                        'id' => 5,
                        'code' => 'MAMMO',
                        'name' => 'Mammography',
                        'modality' => 'MAMMO',
                        'body_part' => 'Breast',
                        'estimated_duration' => 25,
                        'is_active' => true,
                        'description' => 'Breast imaging for cancer screening',
                        'preparation_instructions' => ['No deodorant or powder', 'Wear two-piece clothing', 'Schedule for week after period']
                    ],
                    [
                        'id' => 6,
                        'code' => 'CTCHEST',
                        'name' => 'CT Chest',
                        'modality' => 'CT',
                        'body_part' => 'Chest',
                        'estimated_duration' => 25,
                        'is_active' => true,
                        'description' => 'High-resolution CT scan of the chest',
                        'preparation_instructions' => ['Remove jewelry and metal objects', 'Hold breath when instructed']
                    ]
                ]
            ]);
        });
        
        // Create new imaging test
        Route::post('/imaging-tests', function (Illuminate\Http\Request $request) {
            $data = $request->validate([
                'code' => 'required|string|max:10',
                'name' => 'required|string|max:255',
                'modality' => 'required|string|max:50',
                'body_part' => 'nullable|string|max:100',
                'estimated_duration' => 'nullable|integer|min:1|max:999',
                'description' => 'nullable|string|max:1000',
                'preparation_instructions' => 'nullable|array',
                'is_active' => 'boolean'
            ]);
            
            // Simulate creating the test
            $newTest = array_merge($data, [
                'id' => rand(100, 999),
                'created_at' => now()->toISOString(),
                'updated_at' => now()->toISOString()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Imaging test created successfully',
                'data' => $newTest
            ], 201);
        });
        
        // Update imaging test
        Route::put('/imaging-tests/{id}', function (Illuminate\Http\Request $request, $id) {
            $data = $request->validate([
                'code' => 'required|string|max:10',
                'name' => 'required|string|max:255',
                'modality' => 'required|string|max:50',
                'body_part' => 'nullable|string|max:100',
                'estimated_duration' => 'nullable|integer|min:1|max:999',
                'description' => 'nullable|string|max:1000',
                'preparation_instructions' => 'nullable|array',
                'is_active' => 'boolean'
            ]);
            
            // Simulate updating the test
            $updatedTest = array_merge($data, [
                'id' => (int) $id,
                'updated_at' => now()->toISOString()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Imaging test updated successfully',
                'data' => $updatedTest
            ]);
        });
        
        // Delete imaging test
        Route::delete('/imaging-tests/{id}', function ($id) {
            return response()->json([
                'success' => true,
                'message' => 'Imaging test deleted successfully'
            ]);
        });
    });

});

// Dashboard Preview Routes
Route::get('/dashboard-preview', function () {
    return view('dashboard-preview');
})->name('dashboard.preview');

Route::get('/dashboard-file-preview/{dashboard}', function ($dashboard) {
    $dashboardFile = $dashboard;
    
    // Add .blade.php extension if not present
    if (!str_ends_with($dashboard, '.blade.php')) {
        $dashboardFile = $dashboard;
    }
    
    // Security check - only allow dashboard files
    $allowedDashboards = [
        'owner-dashboard',
        'admin-dashboard', 
        'doctor-dashboard',
        'radiologist-dashboard',
        'lab-tech-dashboard',
        'pharmacist-dashboard',
        'dashboard',
        'dashboard-admin',
        'doctor-enhanced-dashboard',
        'doctor-financial-dashboard',
        'doctor-dashboard-unified',
        'lab-dashboard-unified',
        'lab-tech-dashboard-clean',
        'pharmacist-dashboard-unified',
        'radiologist-dashboard-clean'
    ];
    
    if (!in_array($dashboardFile, $allowedDashboards)) {
        abort(404);
    }
    
    // Create mock data for dashboard previews
    $user = new stdClass();
    $user->name = 'Preview User';
    $user->email = 'preview@example.com';
    $user->role = 'preview';
    
    $dashboardData = [
        'totalRevenue' => 93252,
        'totalPatients' => 1247,
        'totalStudies' => 895,
        'totalInvoices' => 623,
        'revenueGrowth' => 12.5,
        'patientGrowth' => 8.3,
        'monthlyRevenue' => [75000, 82000, 89000, 93252],
        'recentActivity' => [],
        'staffCount' => [
            'doctors' => 15,
            'nurses' => 28,
            'lab_techs' => 12,
            'pharmacists' => 8,
            'radiologists' => 6
        ]
    ];
    
    try {
        return view($dashboardFile, compact('user', 'dashboardData'));
    } catch (Exception $e) {
        return view('errors.404')->with('message', 'Dashboard file not found or has errors: ' . $e->getMessage());
    }
})->name('dashboard.file.preview');

// End of routes
