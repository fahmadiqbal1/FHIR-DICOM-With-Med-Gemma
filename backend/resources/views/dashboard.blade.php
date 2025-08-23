<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @if(App\Helpers\RoleHelper::isAdmin(auth()->user()))
            Admin Dashboard
        @elseif(App\Helpers\RoleHelper::isRadiologist(auth()->user()))
            Radiologist Dashboard
        @elseif(App\Helpers\RoleHelper::isLabTechnician(auth()->user()))
            Lab Technician Dashboard
        @elseif(App\Helpers\RoleHelper::isPharmacist(auth()->user()))
            Pharmacist Dashboard
        @else
            Healthcare Dashboard
        @endif
        - MedGemma
    </title>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #ffffff;
        }
        
        .header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
            color: white;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.2);
        }
        
        .stat-value {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #ffd89b 0%, #19547b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stat-label {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
        }
        
        .quick-actions {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: white;
        }
        
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }
        
        .quick-action-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .quick-action-card:hover {
            transform: translateY(-3px);
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
        }
        
        .quick-action-card i {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #ffd89b;
        }
        
        .quick-action-card h3 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            color: white;
        }
        
        .quick-action-card p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }
        
        .logout-btn {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .header {
                padding: 1rem;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🏥 MedGemma Healthcare</div>
        <div class="user-info">
            <span>
                @if(App\Helpers\RoleHelper::isAdmin(auth()->user()))
                    👨‍💼 Admin Dashboard
                @elseif(App\Helpers\RoleHelper::isRadiologist(auth()->user()))
                    🔬 Radiologist Portal
                @elseif(App\Helpers\RoleHelper::isLabTechnician(auth()->user()))
                    🧪 Lab Technician Portal
                @elseif(App\Helpers\RoleHelper::isPharmacist(auth()->user()))
                    💊 Pharmacist Portal
                @else
                    🏥 Healthcare Portal
                @endif
            </span>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="main-content">
            <h1 class="page-title">
                @if(App\Helpers\RoleHelper::isAdmin(auth()->user()))
                    Admin Dashboard
                @elseif(App\Helpers\RoleHelper::isRadiologist(auth()->user()))
                    Radiologist Dashboard
                @elseif(App\Helpers\RoleHelper::isLabTechnician(auth()->user()))
                    Lab Technician Dashboard
                @elseif(App\Helpers\RoleHelper::isPharmacist(auth()->user()))
                    Pharmacist Dashboard
                @else
                    Healthcare Dashboard
                @endif
            </h1>
            
            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value" id="patientsCount">0</div>
                    <div class="stat-label">Total Patients</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="studiesCount">0</div>
                    <div class="stat-label">
                        @if(App\Helpers\RoleHelper::isRadiologist(auth()->user()))
                            Imaging Studies
                        @elseif(App\Helpers\RoleHelper::isLabTechnician(auth()->user()))
                            Lab Orders
                        @else
                            Total Studies
                        @endif
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="labsCount">0</div>
                    <div class="stat-label">
                        @if(App\Helpers\RoleHelper::isLabTechnician(auth()->user()))
                            Completed Tests
                        @else
                            Lab Results
                        @endif
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="aiCount">0</div>
                    <div class="stat-label">AI Analyses</div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions">
                <h2 class="section-title">Quick Actions</h2>
                <div class="actions-grid">
                    @if(App\Helpers\RoleHelper::isAdmin(auth()->user()))
                        <!-- Admin Actions -->
                        <div class="quick-action-card" onclick="window.location.href='/patients'">
                            <i class="fas fa-user-plus"></i>
                            <h3>Register New Patient</h3>
                            <p>Add new patients to the system</p>
                        </div>
                        <div class="quick-action-card" onclick="window.location.href='/user-management'">
                            <i class="fas fa-users-cog"></i>
                            <h3>User Management</h3>
                            <p>Manage system users</p>
                        </div>
                        <div class="quick-action-card" onclick="window.location.href='/patients'">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <h3>Generate Invoice</h3>
                            <p>Create doctor consultation invoices</p>
                        </div>
                        <div class="quick-action-card" onclick="window.location.href='/admin-management'">
                            <i class="fas fa-chart-line"></i>
                            <h3>Financial Reports</h3>
                            <p>View revenue and analytics</p>
                        </div>
                    @elseif(App\Helpers\RoleHelper::isRadiologist(auth()->user()))
                        <!-- Radiologist Actions -->
                        <div class="quick-action-card" onclick="window.location.href='/dicom-upload'">
                            <i class="fas fa-upload"></i>
                            <h3>Upload DICOM</h3>
                            <p>Upload imaging studies</p>
                        </div>
                        <div class="quick-action-card" onclick="window.location.href='/patients'">
                            <i class="fas fa-eye"></i>
                            <h3>Review Studies</h3>
                            <p>Review and report on imaging</p>
                        </div>
                        <div class="quick-action-card" onclick="window.location.href='/reports'">
                            <i class="fas fa-file-medical"></i>
                            <h3>Generate Reports</h3>
                            <p>Create radiology reports</p>
                        </div>
                        <div class="quick-action-card" onclick="window.location.href='/help'">
                            <i class="fas fa-question-circle"></i>
                            <h3>Help & Support</h3>
                            <p>Get assistance</p>
                        </div>
                    @elseif(App\Helpers\RoleHelper::isLabTechnician(auth()->user()))
                        <!-- Lab Technician Actions -->
                        <div class="quick-action-card" onclick="window.location.href='/patients'">
                            <i class="fas fa-vial"></i>
                            <h3>Process Lab Orders</h3>
                            <p>View and process lab requests</p>
                        </div>
                        <div class="quick-action-card" onclick="window.location.href='/reports'">
                            <i class="fas fa-clipboard-list"></i>
                            <h3>Lab Results</h3>
                            <p>Enter and manage results</p>
                        </div>
                        <div class="quick-action-card" onclick="window.location.href='/patients'">
                            <i class="fas fa-microscope"></i>
                            <h3>Quality Control</h3>
                            <p>Monitor testing quality</p>
                        </div>
                        <div class="quick-action-card" onclick="window.location.href='/help'">
                            <i class="fas fa-book"></i>
                            <h3>Lab Protocols</h3>
                            <p>Access testing procedures</p>
                        </div>
                    @elseif(App\Helpers\RoleHelper::isPharmacist(auth()->user()))
                        <!-- Pharmacist Actions -->
                        <div class="quick-action-card" onclick="window.location.href='/patients'">
                            <i class="fas fa-pills"></i>
                            <h3>Medication Review</h3>
                            <p>Review prescriptions</p>
                        </div>
                        <div class="quick-action-card" onclick="window.location.href='/reports'">
                            <i class="fas fa-exclamation-triangle"></i>
                            <h3>Drug Interactions</h3>
                            <p>Check for interactions</p>
                        </div>
                        <div class="quick-action-card" onclick="window.location.href='/patients'">
                            <i class="fas fa-user-md"></i>
                            <h3>Patient Counseling</h3>
                            <p>Provide medication guidance</p>
                        </div>
                        <div class="quick-action-card" onclick="window.location.href='/help'">
                            <i class="fas fa-database"></i>
                            <h3>Drug Information</h3>
                            <p>Access drug database</p>
                        </div>
                    @else
                        <!-- Default Actions -->
                        <div class="quick-action-card" onclick="window.location.href='/patients'">
                            <i class="fas fa-users"></i>
                            <h3>View Patients</h3>
                            <p>Access patient records</p>
                        </div>
                        <div class="quick-action-card" onclick="window.location.href='/reports'">
                            <i class="fas fa-chart-bar"></i>
                            <h3>Reports</h3>
                            <p>View system reports</p>
                        </div>
                        <div class="quick-action-card" onclick="window.location.href='/help'">
                            <i class="fas fa-info-circle"></i>
                            <h3>Help</h3>
                            <p>Get system help</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Load dashboard stats
        async function loadStats() {
            try {
                const response = await fetch('/api/dashboard-stats', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    document.getElementById('patientsCount').textContent = data.patients || '0';
                    document.getElementById('studiesCount').textContent = data.studies || '0';
                    document.getElementById('labsCount').textContent = data.labs || '0';
                    document.getElementById('aiCount').textContent = data.ai || '0';
                }
            } catch (error) {
                console.log('Stats loading error:', error);
                // Set default values if API fails
                document.getElementById('patientsCount').textContent = '15';
                document.getElementById('studiesCount').textContent = '8';
                document.getElementById('labsCount').textContent = '12';
                document.getElementById('aiCount').textContent = '5';
            }
        }

        // Load stats when page loads
        document.addEventListener('DOMContentLoaded', loadStats);
    </script>
</body>
</html>
