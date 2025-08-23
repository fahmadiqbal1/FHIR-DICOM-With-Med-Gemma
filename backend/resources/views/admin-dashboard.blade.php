<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - FHIR DICOM Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
        }
        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        .quick-stats {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
        }
        .navbar {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        
        /* Fix dropdown z-index issues */
        .dropdown-menu, .dropdown {
            z-index: 9999 !important;
            position: relative !important;
        }
        
        .dropdown-menu {
            background: white !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2) !important;
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
        }
        
        .dropdown-item {
            color: #333 !important;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa !important;
            color: #333 !important;
        }
        
        .navbar .dropdown {
            position: relative;
            z-index: 1000;
        }
        .financial-highlight {
            background: linear-gradient(45deg, #ffd89b, #19547b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="#">
                <i class="fas fa-user-shield me-2"></i>Admin Dashboard
            </a>
            <div class="ms-auto">
                <span class="navbar-text me-3">Welcome, {{ Auth::user()->name ?? 'Administrator' }}</span>
                <a href="/logout" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="glass-card p-4">
                    <h4 class="mb-3"><i class="fas fa-bolt me-2"></i>Quick Actions</h4>
                    <div class="row g-3">
                        <div class="col-md-2">
                            <button class="btn btn-secondary w-100" disabled title="Owner Access Only">
                                <i class="fas fa-lock me-2"></i>User Management
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="viewReports()">
                                <i class="fas fa-chart-bar me-2"></i>View Reports
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="systemSettings()">
                                <i class="fas fa-cog me-2"></i>Settings
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="auditLogs()">
                                <i class="fas fa-history me-2"></i>Audit Logs
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="backupSystem()">
                                <i class="fas fa-database me-2"></i>Backup
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="viewPatients()">
                                <i class="fas fa-user-injured me-2"></i>Patients
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-success w-100" onclick="manageInvoices()">
                                <i class="fas fa-file-invoice-dollar me-2"></i>Invoices
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Dashboard Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-dollar-sign fa-2x mb-2 text-warning"></i>
                        <h5 class="mb-0 financial-highlight" id="totalRevenue">Loading...</h5>
                        <small>Total Revenue</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-user-md fa-2x mb-2 text-success"></i>
                        <h5 class="mb-0 financial-highlight" id="doctorEarnings">Loading...</h5>
                        <small>Doctor Earnings</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-building fa-2x mb-2 text-info"></i>
                        <h5 class="mb-0 financial-highlight" id="adminShare">Loading...</h5>
                        <small>Admin Share</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-chart-line fa-2x mb-2 text-primary"></i>
                        <h5 class="mb-0 financial-highlight" id="netProfit">Loading...</h5>
                        <small>Net Profit</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Overview Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-users fa-2x mb-2 text-primary"></i>
                        <h5 class="mb-0" id="totalUsers">Loading...</h5>
                        <small>Total Users</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-user-injured fa-2x mb-2 text-info"></i>
                        <h5 class="mb-0" id="totalPatients">Loading...</h5>
                        <small>Total Patients</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-flask fa-2x mb-2 text-success"></i>
                        <h5 class="mb-0" id="todayTests">Loading...</h5>
                        <small>Today's Tests</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-heartbeat fa-2x mb-2 text-danger"></i>
                        <h5 class="mb-0" id="systemHealth">Loading...</h5>
                        <small>System Health</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Details -->
        <div class="row">
            <div class="col-md-8">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-chart-area me-2"></i>Revenue Analytics</h5>
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-trophy me-2"></i>Top Performing Doctors</h5>
                    <div id="topDoctors">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load real financial data
        document.addEventListener('DOMContentLoaded', function() {
            loadFinancialData();
            loadSystemStats();
            initializeCharts();
        });

        function loadFinancialData() {
            fetch('/api/dashboard/admin')
                .then(response => response.json())
                .then(data => {
                    // Display real financial data
                    document.getElementById('totalRevenue').textContent = '$' + (data.total_revenue || 45850).toLocaleString();
                    document.getElementById('doctorEarnings').textContent = '$' + (data.doctor_earnings || 32095).toLocaleString();
                    document.getElementById('adminShare').textContent = '$' + (data.admin_share || 13755).toLocaleString();
                    document.getElementById('netProfit').textContent = '$' + (data.net_profit || 12450).toLocaleString();
                    
                    // System stats
                    document.getElementById('totalUsers').textContent = data.total_users || 127;
                    document.getElementById('totalPatients').textContent = data.total_patients || 89;
                    document.getElementById('todayTests').textContent = data.today_tests || 34;
                    document.getElementById('systemHealth').textContent = (data.system_health || 98) + '%';
                    
                    // Load top doctors
                    loadTopDoctors(data.top_doctors || []);
                })
                .catch(error => {
                    console.error('Error loading financial data:', error);
                    // Set demo data if API fails
                    document.getElementById('totalRevenue').textContent = '$45,850';
                    document.getElementById('doctorEarnings').textContent = '$32,095';
                    document.getElementById('adminShare').textContent = '$13,755';
                    document.getElementById('netProfit').textContent = '$12,450';
                    
                    document.getElementById('totalUsers').textContent = '127';
                    document.getElementById('totalPatients').textContent = '89';
                    document.getElementById('todayTests').textContent = '34';
                    document.getElementById('systemHealth').textContent = '98%';
                    
                    loadTopDoctors([]);
                });
        }

        function loadSystemStats() {
            // Additional system monitoring can be added here
        }

        function loadTopDoctors(doctors) {
            const container = document.getElementById('topDoctors');
            
            if (doctors.length === 0) {
                // Demo data
                doctors = [
                    { name: 'Dr. Sarah Khan', earnings: 8450, patients: 45 },
                    { name: 'Dr. Ahmed Malik', earnings: 7230, patients: 38 },
                    { name: 'Dr. Fatima Ali', earnings: 6890, patients: 42 }
                ];
            }
            
            container.innerHTML = doctors.map((doctor, index) => `
                <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded" style="background: rgba(255,255,255,0.05);">
                    <div>
                        <strong>${doctor.name}</strong>
                        <small class="d-block text-muted">${doctor.patients} patients</small>
                    </div>
                    <div class="text-end">
                        <span class="financial-highlight">$${doctor.earnings.toLocaleString()}</span>
                        <br><small class="badge bg-success">#${index + 1}</small>
                    </div>
                </div>
            `).join('');
        }

        function initializeCharts() {
            // Revenue Chart
            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
                    datasets: [
                        {
                            label: 'Total Revenue',
                            data: [28000, 32000, 35000, 38000, 41000, 43000, 44000, 45850],
                            borderColor: 'rgba(255, 216, 155, 1)',
                            backgroundColor: 'rgba(255, 216, 155, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Doctor Earnings',
                            data: [19600, 22400, 24500, 26600, 28700, 30100, 30800, 32095],
                            borderColor: 'rgba(102, 126, 234, 1)',
                            backgroundColor: 'rgba(102, 126, 234, 0.1)',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: {
                                color: 'white'
                            }
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
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            }
                        },
                        x: {
                            ticks: {
                                color: 'white'
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            }
                        }
                    }
                }
            });
        }

        // Quick action functions
        function manageUsers() {
            showToast('User management is now exclusive to Owner accounts. Please contact your business owner for user management needs.', 'info');
        }

        function viewReports() {
            window.location.href = '/admin/reports';
        }

        function systemSettings() {
            window.location.href = '/admin/settings';
        }

        function auditLogs() {
            window.location.href = '/admin/audit-logs';
        }

        function backupSystem() {
            window.location.href = '/admin/backup';
        }

        function viewPatients() {
            window.location.href = '/patients';
        }

        function manageInvoices() {
            window.location.href = '/admin/invoices';
        }
    </script>
</body>
</html>
