<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Doctor Dashboard - FHIR DICOM Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #2E8B57 0%, #3CB371 100%);
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
            background: linear-gradient(45deg, #2E8B57, #3CB371);
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
        .financial-highlight {
            background: linear-gradient(45deg, #90EE90, #228B22);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
        }
        .doctor-card {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .earning-badge {
            background: rgba(50, 205, 50, 0.8);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
        }
        .patient-card {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }
        .patient-card:hover {
            background: rgba(255, 255, 255, 0.12);
            transform: translateY(-2px);
        }
        
        /* Fix dropdown z-index issues */
        .dropdown-menu {
            z-index: 9999 !important;
            position: absolute !important;
            background: white !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2) !important;
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
        }
        
        .dropdown-menu .dropdown-item {
            color: #333 !important;
        }
        
        .dropdown-menu .dropdown-item:hover {
            background-color: #f8f9fa !important;
            color: #333 !important;
        }
        
        .navbar .dropdown {
            position: relative;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="#">
                <i class="fas fa-user-md me-2"></i>
                <span class="financial-highlight">Doctor Portal</span>
            </a>
            <div class="navbar-nav ms-auto d-flex align-items-center">
                <span class="navbar-text me-3 text-white">
                    <i class="fas fa-user-circle me-1"></i>
                    Welcome, Dr. {{ Auth::user()->name ?? 'Doctor' }}
                </span>
                <div class="nav-item dropdown me-2">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-cog me-1"></i>
                        Options
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/patients"><i class="fas fa-users me-2"></i>Patient Management</a></li>
                        <li><a class="dropdown-item" href="/doctor/ai-analysis"><i class="fas fa-brain me-2"></i>AI Analysis</a></li>
                    </ul>
                </div>
                <a href="/logout" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="glass-card p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1"><i class="fas fa-stethoscope me-2"></i>Welcome, Dr. {{ Auth::user()->name ?? 'Doctor' }}</h2>
                            <p class="mb-0 opacity-75">Your personalized healthcare dashboard</p>
                        </div>
                        <div class="text-end">
                            <div class="earning-badge">
                                <i class="fas fa-dollar-sign me-1"></i>
                                Revenue Share: <span id="revenue-share">{{ Auth::user()->revenue_share ?? 70 }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="glass-card p-4 quick-stats">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="mb-0" id="total-patients">-</h4>
                            <small>Total Patients</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="glass-card p-4 quick-stats">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="mb-0" id="appointments-today">-</h4>
                            <small>Today's Appointments</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="glass-card p-4 quick-stats">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-brain fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="mb-0" id="ai-analyses">-</h4>
                            <small>AI Analyses</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="glass-card p-4 quick-stats">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-dollar-sign fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="mb-0" id="monthly-earnings">-</h4>
                            <small>This Month's Earnings</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Patients -->
            <div class="col-lg-8 mb-4">
                <div class="glass-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5><i class="fas fa-user-friends me-2"></i>Recent Patients</h5>
                        <a href="/patients" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-users me-1"></i>View All Patients
                        </a>
                    </div>
                    <div id="recent-patients">
                        <div class="text-center p-4">
                            <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                            <p>Loading patients...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-lg-4 mb-4">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                    
                    <div class="d-grid gap-2">
                        <a href="/patients" class="btn btn-primary">
                            <i class="fas fa-users me-2"></i>Patient Management
                        </a>
                        <a href="/doctor/ai-analysis" class="btn btn-outline-light">
                            <i class="fas fa-brain me-2"></i>AI Clinical Analysis
                        </a>
                        <a href="/financial/doctor-dashboard" class="btn btn-outline-light">
                            <i class="fas fa-chart-line me-2"></i>Financial Dashboard
                        </a>
                        <button class="btn btn-outline-light" onclick="refreshDashboard()">
                            <i class="fas fa-sync-alt me-2"></i>Refresh Dashboard
                        </button>
                    </div>

                    <!-- Earnings Summary -->
                    <div class="mt-4 pt-3 border-top border-light border-opacity-25">
                        <h6><i class="fas fa-coins me-2"></i>Earnings Summary</h6>
                        <div class="small">
                            <div class="d-flex justify-content-between mb-2">
                                <span>This Month:</span>
                                <span class="fw-bold" id="current-month-earnings">Loading...</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total Procedures:</span>
                                <span id="total-procedures">-</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Revenue Share:</span>
                                <span class="text-success fw-bold" id="revenue-share-display">{{ Auth::user()->revenue_share ?? 70 }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings Chart -->
        <div class="row">
            <div class="col-12">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-chart-area me-2"></i>Monthly Earnings Overview</h5>
                    <div style="height: 300px;">
                        <canvas id="earningsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Session timeout warning modal -->
    <div class="modal fade" id="sessionTimeoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title"><i class="fas fa-clock me-2"></i>Session Timeout Warning</h5>
                </div>
                <div class="modal-body">
                    <p>Your session will expire in <strong><span id="timeout-countdown">60</span></strong> seconds due to inactivity.</p>
                    <p>Click "Stay Logged In" to continue your session.</p>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Logout</button>
                    <button type="button" class="btn btn-primary" onclick="extendSession()">Stay Logged In</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let sessionTimeout;
        let warningTimeout;
        let countdownInterval;
        let earningsChart;

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardData();
            initializeEarningsChart();
            resetSessionTimeout();
        });

        // Load dashboard statistics
        async function loadDashboardData() {
            try {
                // Load basic stats
                const statsResponse = await fetch('/api/dashboard-stats');
                if (statsResponse.ok) {
                    const stats = await statsResponse.json();
                    document.getElementById('total-patients').textContent = stats.patients || '0';
                    document.getElementById('ai-analyses').textContent = stats.ai || '0';
                }

                // Load doctor earnings
                const earningsResponse = await fetch('/api/users/{{ Auth::id() }}/earnings');
                if (earningsResponse.ok) {
                    const earnings = await earningsResponse.json();
                    document.getElementById('monthly-earnings').textContent = '$' + (earnings.total_earnings || 0);
                    document.getElementById('current-month-earnings').textContent = '$' + (earnings.total_earnings || 0);
                    document.getElementById('total-procedures').textContent = earnings.total_procedures || '0';
                }

                // Load recent patients
                const patientsResponse = await fetch('/api/patients?limit=5');
                if (patientsResponse.ok) {
                    const patients = await patientsResponse.json();
                    displayRecentPatients(patients);
                }

                // Set random values for demonstration
                document.getElementById('appointments-today').textContent = Math.floor(Math.random() * 12) + 3;
                
            } catch (error) {
                console.error('Error loading dashboard data:', error);
            }
        }

        // Display recent patients
        function displayRecentPatients(patients) {
            const container = document.getElementById('recent-patients');
            
            if (!patients || patients.length === 0) {
                container.innerHTML = `
                    <div class="text-center p-4">
                        <i class="fas fa-user-plus fa-2x mb-3 opacity-50"></i>
                        <p>No patients found</p>
                        <a href="/patients" class="btn btn-outline-light btn-sm">Add Your First Patient</a>
                    </div>
                `;
                return;
            }

            const patientsHtml = patients.slice(0, 4).map(patient => `
                <div class="patient-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">${patient.name || 'Unknown Patient'}</h6>
                            <small class="text-light opacity-75">MRN: ${patient.mrn || 'N/A'}</small>
                        </div>
                        <div class="text-end">
                            <small class="text-light opacity-75">DOB: ${patient.dob || 'N/A'}</small><br>
                            <span class="badge bg-success">${patient.sex || 'Unknown'}</span>
                        </div>
                    </div>
                </div>
            `).join('');

            container.innerHTML = patientsHtml;
        }

        // Initialize earnings chart
        function initializeEarningsChart() {
            const ctx = document.getElementById('earningsChart').getContext('2d');
            
            // Sample data - replace with real data from API
            const monthlyData = [1200, 1350, 1100, 1400, 1250, 1600];
            const labels = ['July', 'August', 'September', 'October', 'November', 'December'];
            
            earningsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Monthly Earnings ($)',
                        data: monthlyData,
                        borderColor: 'rgba(144, 238, 144, 1)',
                        backgroundColor: 'rgba(144, 238, 144, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: 'white'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: 'white',
                                callback: function(value) {
                                    return '$' + value;
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

        // Refresh dashboard data
        function refreshDashboard() {
            const btn = event.target;
            const icon = btn.querySelector('i');
            
            // Add loading state
            icon.classList.add('fa-spin');
            btn.disabled = true;
            
            // Reload data
            loadDashboardData().finally(() => {
                // Remove loading state
                icon.classList.remove('fa-spin');
                btn.disabled = false;
            });
        }

        // Session management functions
        function resetSessionTimeout() {
            clearTimeout(sessionTimeout);
            clearTimeout(warningTimeout);
            
            // Show warning after 4 minutes (240 seconds)
            warningTimeout = setTimeout(showSessionWarning, 240000);
            
            // Auto logout after 5 minutes (300 seconds)
            sessionTimeout = setTimeout(forceLogout, 300000);
        }

        function showSessionWarning() {
            const modal = new bootstrap.Modal(document.getElementById('sessionTimeoutModal'));
            modal.show();
            
            let countdown = 60;
            const countdownElement = document.getElementById('timeout-countdown');
            
            countdownInterval = setInterval(() => {
                countdown--;
                countdownElement.textContent = countdown;
                
                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    modal.hide();
                    forceLogout();
                }
            }, 1000);
        }

        function extendSession() {
            clearInterval(countdownInterval);
            const modal = bootstrap.Modal.getInstance(document.getElementById('sessionTimeoutModal'));
            modal.hide();
            
            // Send heartbeat to server
            fetch('/heartbeat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(() => {
                resetSessionTimeout();
            });
        }

        function forceLogout() {
            window.location.href = '/logout';
        }

        // Reset timeout on user activity
        ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'].forEach(event => {
            document.addEventListener(event, resetSessionTimeout, true);
        });
    </script>
</body>
</html>
