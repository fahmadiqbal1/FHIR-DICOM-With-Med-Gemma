<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Management - Business Owner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #2c1810 0%, #8B4513 50%, #D4AF37 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            color: white;
        }
        .navbar {
            background: rgba(139, 69, 19, 0.2);
            backdrop-filter: blur(10px);
        }
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }
        .form-control::placeholder { color: rgba(255, 255, 255, 0.7); }
        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #D4AF37;
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25);
        }
        .table-dark {
            --bs-table-bg: rgba(255, 255, 255, 0.05);
            --bs-table-border-color: rgba(255, 255, 255, 0.1);
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(45deg, #D4AF37, #FFD700);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #2c1810;
        }
        .btn-owner {
            background: linear-gradient(45deg, #D4AF37, #FFD700);
            color: #2c1810;
            border: none;
            font-weight: bold;
        }
        .btn-owner:hover {
            background: linear-gradient(45deg, #FFD700, #D4AF37);
            color: #2c1810;
            transform: translateY(-2px);
        }
        .owner-highlight {
            background: linear-gradient(45deg, #D4AF37, #FFD700);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="/dashboard">
                <i class="fas fa-crown me-2"></i>User Management - Owner
            </a>
            <div class="ms-auto">
                <a href="/dashboard" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- User Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-users fa-2x mb-2 text-primary"></i>
                    <h5 class="owner-highlight">{{ count($users) }}</h5>
                    <small>Total Users</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-user-md fa-2x mb-2 text-success"></i>
                    <h5 class="owner-highlight">{{ $users->where('roles.*.name', 'doctor')->count() ?? 0 }}</h5>
                    <small>Doctors</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-flask fa-2x mb-2 text-info"></i>
                    <h5 class="owner-highlight">{{ $users->where('roles.*.name', 'lab-tech')->count() ?? 0 }}</h5>
                    <small>Lab Technicians</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-dollar-sign fa-2x mb-2 text-warning"></i>
                    <h5 class="owner-highlight">{{ $users->where('email_verified_at', '!=', null)->count() ?? 0 }}</h5>
                    <small>Active Users</small>
                </div>
            </div>
        </div>

        <!-- User Management Controls -->
        <div class="glass-card p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5><i class="fas fa-users-cog me-2"></i>Business Staff Management</h5>
                <div>
                    <button class="btn btn-outline-light me-2" data-bs-toggle="modal" data-bs-target="#bulkActionsModal">
                        <i class="fas fa-layer-group me-2"></i>Bulk Actions
                    </button>
                    <button class="btn btn-owner" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <i class="fas fa-plus me-2"></i>Add New Staff
                    </button>
                </div>
            </div>
            
            <!-- User Filter -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <select class="form-select" id="roleFilter" onchange="filterUsers()">
                        <option value="">All Roles</option>
                        <option value="doctor">Doctors</option>
                        <option value="lab-tech">Lab Technicians</option>
                        <option value="radiologist">Radiologists</option>
                        <option value="pharmacist">Pharmacists</option>
                        <option value="admin">Administrators</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" id="searchUsers" placeholder="Search users..." onkeyup="searchUsers()">
                </div>
                <div class="col-md-4">
                    <button class="btn btn-outline-light w-100" onclick="exportUsers()">
                        <i class="fas fa-download me-2"></i>Export Users
                    </button>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" class="form-check-input" id="selectAll" onchange="toggleSelectAll()">
                            </th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Revenue Contribution</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTable">
                        @forelse($users as $user)
                            <tr data-user-id="{{ $user->id }}">
                                <td>
                                    <input type="checkbox" class="form-check-input user-checkbox" value="{{ $user->id }}">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-3">
                                            {{ strtoupper(substr($user->name && !str_contains($user->name, 'eyJ') ? $user->name : ($user->email ? explode('@', $user->email)[0] : 'U'), 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>
                                                @if($user->name && !str_contains($user->name, 'eyJ') && strlen($user->name) < 100)
                                                    {{ $user->name }}
                                                @else
                                                    {{ 'User ' . $user->id }}
                                                    <small class="text-warning">(Name needs fixing)</small>
                                                @endif
                                            </strong>
                                            <br><small class="text-muted">ID: {{ $user->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if(!empty($rolesAvailable) && $rolesAvailable && $user->roles->count() > 0)
                                        @foreach($user->roles as $role)
                                            <span class="badge bg-secondary me-1">{{ ucfirst($role->name) }}</span>
                                        @endforeach
                                    @else
                                        <span class="badge bg-light text-dark">No Role</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->is_active_doctor == 1)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Active
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle me-1"></i>Inactive
                                        </span>
                                    @endif
                                    @if(!$user->email_verified_at)
                                        <br><small class="text-warning">Email not verified</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="owner-highlight">$0</span>
                                    <br><small class="text-muted">This month</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-info" onclick="viewUserDetails({{ $user->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-warning" onclick="editUser({{ $user->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-danger" onclick="deleteUser({{ $user->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <p>No staff members found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Revenue Analysis -->
        <div class="glass-card p-4">
            <h5 class="mb-3"><i class="fas fa-chart-line me-2"></i>Staff Revenue Performance</h5>
            <div class="row">
                <div class="col-md-6">
                    <canvas id="staffRevenueChart"></canvas>
                </div>
                <div class="col-md-6">
                    <div class="performance-summary">
                        <div class="mb-3">
                            <strong>Top Revenue Generators:</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Dr. Sarah Johnson</span>
                            <span class="owner-highlight">$12,500</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Lab Tech Mike Wilson</span>
                            <span class="owner-highlight">$8,200</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Dr. Ahmed Malik</span>
                            <span class="owner-highlight">$7,800</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create User Modal -->
    <div class="modal fade" id="createUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="background: rgba(44, 24, 16, 0.95); color: white;">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Add New Staff Member</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="post" action="{{ route('owner.users.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            @error('password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        @if(!empty($rolesAvailable) && $rolesAvailable)
                            <div class="mb-3">
                                <label for="role" class="form-label">Staff Role</label>
                                <select class="form-select" name="role" id="role" required>
                                    <option value="">Select a role...</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <label for="revenueShare" class="form-label">Revenue Share (%)</label>
                            <input type="number" class="form-control" id="revenueShare" name="revenue_share" value="70" min="0" max="100">
                            <small class="text-muted">Percentage of consultation revenue this staff member receives</small>
                        </div>
                        
                        <!-- Revenue Sharing from Ancillary Services -->
                        <div class="mb-3">
                            <h6 class="text-warning"><i class="fas fa-percentage me-2"></i>Ancillary Services Revenue Sharing</h6>
                            <small class="text-muted">Set doctor's percentage from orders/referrals they generate (optional)</small>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="labRevenue" class="form-label">
                                        <i class="fas fa-flask me-1"></i>Laboratory Share (%)
                                    </label>
                                    <input type="number" class="form-control" id="labRevenue" name="lab_revenue_percentage" min="0" max="100" step="0.01" placeholder="e.g., 5.00">
                                    <small class="text-muted">% from lab tests ordered</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="radiologyRevenue" class="form-label">
                                        <i class="fas fa-x-ray me-1"></i>Radiology Share (%)
                                    </label>
                                    <input type="number" class="form-control" id="radiologyRevenue" name="radiology_revenue_percentage" min="0" max="100" step="0.01" placeholder="e.g., 3.00">
                                    <small class="text-muted">% from imaging ordered</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="pharmacyRevenue" class="form-label">
                                        <i class="fas fa-pills me-1"></i>Pharmacy Share (%)
                                    </label>
                                    <input type="number" class="form-control" id="pharmacyRevenue" name="pharmacy_revenue_percentage" min="0" max="100" step="0.01" placeholder="e.g., 2.00">
                                    <small class="text-muted">% from prescriptions</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-owner">
                            <i class="fas fa-save me-2"></i>Create Staff Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background: rgba(44, 24, 16, 0.95); color: white;">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Edit Staff Member</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="editUserForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editUserId" name="user_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editName" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="editName" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editEmail" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="editEmail" name="email" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editRole" class="form-label">Staff Role</label>
                                    <select class="form-select" name="role" id="editRole" required>
                                        <option value="">Select a role...</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editRevenueShare" class="form-label">Revenue Share (%)</label>
                                    <input type="number" class="form-control" id="editRevenueShare" name="revenue_share" min="0" max="100">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editRevenueShare" class="form-label">Revenue Share (%)</label>
                                    <input type="number" class="form-control" id="editRevenueShare" name="revenue_share" min="0" max="100">
                                    <small class="text-muted">Doctor's share from consultations</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="editIsActive" name="is_active_doctor" value="1">
                                        <label class="form-check-label" for="editIsActive">
                                            <i class="fas fa-user-check me-2"></i>Active User (can login and work)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Revenue Sharing from Ancillary Services -->
                        <div class="mb-3">
                            <h6 class="text-warning"><i class="fas fa-percentage me-2"></i>Ancillary Services Revenue Sharing</h6>
                            <small class="text-muted">Set doctor's percentage from orders/referrals they generate (leave blank for 0%)</small>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="editLabRevenue" class="form-label">
                                        <i class="fas fa-flask me-1"></i>Laboratory Share (%)
                                    </label>
                                    <input type="number" class="form-control" id="editLabRevenue" name="lab_revenue_percentage" min="0" max="100" step="0.01" placeholder="e.g., 5.00">
                                    <small class="text-muted">% from lab tests ordered</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="editRadiologyRevenue" class="form-label">
                                        <i class="fas fa-x-ray me-1"></i>Radiology Share (%)
                                    </label>
                                    <input type="number" class="form-control" id="editRadiologyRevenue" name="radiology_revenue_percentage" min="0" max="100" step="0.01" placeholder="e.g., 3.00">
                                    <small class="text-muted">% from imaging ordered</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="editPharmacyRevenue" class="form-label">
                                        <i class="fas fa-pills me-1"></i>Pharmacy Share (%)
                                    </label>
                                    <input type="number" class="form-control" id="editPharmacyRevenue" name="pharmacy_revenue_percentage" min="0" max="100" step="0.01" placeholder="e.g., 2.00">
                                    <small class="text-muted">% from prescriptions</small>
                                </div>
                            </div>
                        </div>                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> Inactive users cannot login or appear in dropdown lists for invoice generation.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-warning me-2" onclick="toggleUserStatus()">
                            <i class="fas fa-toggle-on me-2"></i>Toggle Status
                        </button>
                        <button type="submit" class="btn btn-owner">
                            <i class="fas fa-save me-2"></i>Update Staff Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Modal -->
    <div class="modal fade" id="bulkActionsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="background: rgba(44, 24, 16, 0.95); color: white;">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-layer-group me-2"></i>Bulk Actions</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Action</label>
                        <select class="form-select" id="bulkAction">
                            <option value="activate">Activate Selected Users</option>
                            <option value="deactivate">Deactivate Selected Users</option>
                            <option value="change-role">Change Role</option>
                            <option value="send-notification">Send Notification</option>
                            <option value="export-selected">Export Selected</option>
                        </select>
                    </div>
                    <div class="mb-3" id="roleChangeOptions" style="display: none;">
                        <label class="form-label">New Role</label>
                        <select class="form-select">
                            <option value="doctor">Doctor</option>
                            <option value="lab-tech">Lab Technician</option>
                            <option value="radiologist">Radiologist</option>
                            <option value="pharmacist">Pharmacist</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-owner" onclick="executeBulkAction()">
                        <i class="fas fa-check me-2"></i>Execute Action
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeStaffChart();
            
            // Show role options when bulk action changes
            document.getElementById('bulkAction').addEventListener('change', function() {
                const roleOptions = document.getElementById('roleChangeOptions');
                if (this.value === 'change-role') {
                    roleOptions.style.display = 'block';
                } else {
                    roleOptions.style.display = 'none';
                }
            });
        });

        function initializeStaffChart() {
            const ctx = document.getElementById('staffRevenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Dr. Johnson', 'Lab Tech Mike', 'Dr. Malik', 'Radiologist Amy', 'Pharmacist John'],
                    datasets: [{
                        label: 'Monthly Revenue Generated',
                        data: [12500, 8200, 7800, 9500, 4200],
                        backgroundColor: [
                            'rgba(212, 175, 55, 0.8)',
                            'rgba(255, 215, 0, 0.8)',
                            'rgba(212, 175, 55, 0.6)',
                            'rgba(255, 215, 0, 0.6)',
                            'rgba(212, 175, 55, 0.4)'
                        ],
                        borderColor: '#D4AF37',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: { color: 'white' }
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                color: 'white',
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            },
                            grid: { color: 'rgba(255, 255, 255, 0.1)' }
                        },
                        x: {
                            ticks: { color: 'white' },
                            grid: { color: 'rgba(255, 255, 255, 0.1)' }
                        }
                    }
                }
            });
        }

        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
        }

        function filterUsers() {
            // Implement user filtering
            showToast('Filtering users by role...', 'info');
        }

        function searchUsers() {
            // Implement user search
            const searchTerm = document.getElementById('searchUsers').value.toLowerCase();
            // Add search logic here
        }

        function exportUsers() {
            showToast('Exporting user data...', 'success');
        }

        function viewUserDetails(userId) {
            showToast('Loading user details...', 'info');
        }

        function editUser(userId) {
            showToast('Loading user details...', 'info');
            
            fetch(`/owner/users/${userId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('User data received:', data);
                    
                    // Populate the edit form
                    document.getElementById('editUserId').value = data.user.id;
                    document.getElementById('editName').value = data.user.name || '';
                    document.getElementById('editEmail').value = data.user.email || '';
                    document.getElementById('editRevenueShare').value = data.user.revenue_share || 70;
                    document.getElementById('editIsActive').checked = data.user.is_active_doctor == 1;
                    
                    // Set revenue sharing percentages
                    document.getElementById('editLabRevenue').value = data.user.lab_revenue_percentage || '';
                    document.getElementById('editRadiologyRevenue').value = data.user.radiology_revenue_percentage || '';
                    document.getElementById('editPharmacyRevenue').value = data.user.pharmacy_revenue_percentage || '';
                    
                    // Set the role
                    const roleSelect = document.getElementById('editRole');
                    roleSelect.value = ''; // Clear first
                    if (data.user_roles && data.user_roles.length > 0) {
                        roleSelect.value = data.user_roles[0];
                    }
                    
                    // Show the modal
                    new bootstrap.Modal(document.getElementById('editUserModal')).show();
                    showToast('User details loaded successfully', 'success');
                })
                .catch(error => {
                    console.error('Error loading user:', error);
                    showToast(`Failed to load user details: ${error.message}`, 'error');
                });
        }

        function deleteUser(userId) {
            if (confirm('Are you sure you want to remove this staff member? This action cannot be undone.')) {
                showToast('Deleting user...', 'info');
                
                fetch(`/owner/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Delete response:', data);
                    showToast('Staff member removed successfully', 'success');
                    setTimeout(() => location.reload(), 1500);
                })
                .catch(error => {
                    console.error('Error deleting user:', error);
                    showToast(`Failed to delete user: ${error.message}`, 'error');
                });
            }
        }
        
        function toggleUserStatus() {
            const userId = document.getElementById('editUserId').value;
            showToast('Updating user status...', 'info');
            
            fetch(`/owner/users/${userId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Toggle status response:', data);
                document.getElementById('editIsActive').checked = data.is_active;
                showToast('User status updated successfully', 'success');
            })
            .catch(error => {
                console.error('Error toggling status:', error);
                showToast(`Failed to update user status: ${error.message}`, 'error');
            });
        }

        // Handle edit user form submission
        document.addEventListener('DOMContentLoaded', function() {
            const editForm = document.getElementById('editUserForm');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const userId = document.getElementById('editUserId').value;
                    const formData = new FormData(this);
                    const data = Object.fromEntries(formData);
                    data.is_active_doctor = document.getElementById('editIsActive').checked ? 1 : 0;
                    
                    console.log('Submitting user update:', data);
                    showToast('Updating staff member...', 'info');
                    
                    fetch(`/owner/users/${userId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Update response:', data);
                        showToast('Staff member updated successfully', 'success');
                        bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
                        setTimeout(() => location.reload(), 1500);
                    })
                    .catch(error => {
                        console.error('Error updating user:', error);
                        showToast(`Failed to update user: ${error.message}`, 'error');
                    });
                });
            }
        });

        function executeBulkAction() {
            const selected = document.querySelectorAll('.user-checkbox:checked').length;
            if (selected === 0) {
                alert('Please select at least one user.');
                return;
            }
            
            const action = document.getElementById('bulkAction').value;
            showToast(`Executing bulk action on ${selected} users...`, 'success');
            bootstrap.Modal.getInstance(document.getElementById('bulkActionsModal')).hide();
        }

        function showToast(message, type = 'info') {
            // Remove any existing toasts
            document.querySelectorAll('.toast-notification').forEach(toast => toast.remove());
            
            const toastColor = type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info';
            const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle';
            
            const toast = document.createElement('div');
            toast.className = `alert alert-${toastColor} position-fixed toast-notification`;
            toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; opacity: 0; transition: opacity 0.3s ease;';
            toast.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-${icon} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
            `;
            document.body.appendChild(toast);
            
            // Fade in
            setTimeout(() => {
                toast.style.opacity = '1';
            }, 10);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 300);
                }
            }, 5000);
        }
    </script>
</body>
</html>
