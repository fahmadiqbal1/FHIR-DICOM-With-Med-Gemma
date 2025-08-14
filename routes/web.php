// ...existing code...
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\FinancialDashboardController;
// ...existing code...
Route::middleware(['auth:sanctum', 'role:admin'])->get('/admin/audit-logs', [AuditLogController::class, 'index'])->name('admin.audit-logs');

// Financial Dashboard Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/financial/doctor-dashboard', [FinancialDashboardController::class, 'doctorDashboard'])->name('financial.doctor-dashboard');
    Route::middleware('role:admin')->get('/financial/admin-dashboard', [FinancialDashboardController::class, 'adminDashboard'])->name('financial.admin-dashboard');
});
// ...existing code...

