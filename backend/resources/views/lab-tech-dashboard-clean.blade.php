<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lab Technician Dashboard - MedGemma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
            background: linear-gradient(45deg, #28a745, #20c997);
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
        .sample-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
            border-left: 4px solid #28a745;
        }
        .financial-highlight {
            background: linear-gradient(45deg, #90EE90, #228B22);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
        }
        .chart-container {
            position: relative;
            height: 300px;
            margin: 20px 0;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
        }
        .status-pending { background: #ffc107; color: #000; }
        .status-processing { background: #17a2b8; color: white; }
        .status-completed { background: #28a745; color: white; }
        .status-urgent { background: #dc3545; color: white; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="#">
                <i class="fas fa-microscope me-2"></i>Lab Technician Dashboard
            </a>
            <div class="ms-auto">
                <span class="navbar-text me-3">Welcome, {{ Auth::user()->name ?? 'Lab Tech' }}</span>
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
                            <button class="btn btn-primary w-100" onclick="processSamples()">
                                <i class="fas fa-vial me-2"></i>Process Samples
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="enterResults()">
                                <i class="fas fa-clipboard-check me-2"></i>Enter Results
                            </button>
                        </div>
                        <div class="col-md-2">
                            <a href="/lab-tech-configuration" class="btn btn-outline-light w-100">
                                <i class="fas fa-cogs me-2"></i>Configuration
                            </a>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="viewEquipment()">
                                <i class="fas fa-microscope me-2"></i>Equipment
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="generateReports()">
                                <i class="fas fa-chart-bar me-2"></i>Reports
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="qualityControl()">
                                <i class="fas fa-shield-alt me-2"></i>Quality Control
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-flask fa-2x mb-2 text-success"></i>
                        <h3 class="financial-highlight mb-1" id="pending-samples">24</h3>
                        <small>Pending Samples</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                        <h3 class="financial-highlight mb-1" id="completed-today">18</h3>
                        <small>Completed Today</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2 text-warning"></i>
                        <h3 class="financial-highlight mb-1" id="urgent-tests">3</h3>
                        <small>Urgent Tests</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-cogs fa-2x mb-2 text-info"></i>
                        <h3 class="financial-highlight mb-1" id="equipment-status">5/6</h3>
                        <small>Equipment Online</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Pending Samples -->
            <div class="col-lg-6 mb-4">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-vial me-2"></i>Pending Samples</h5>
                    <div class="sample-list">
                        <div class="sample-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>S001 - Complete Blood Count</strong>
                                    <br><small>Patient: John Smith | MRN: MRN001</small>
                                </div>
                                <span class="status-badge status-urgent">STAT</span>
                            </div>
                        </div>
                        <div class="sample-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>S002 - Liver Function Panel</strong>
                                    <br><small>Patient: Sarah Johnson | MRN: MRN002</small>
                                </div>
                                <span class="status-badge status-pending">Pending</span>
                            </div>
                        </div>
                        <div class="sample-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>S003 - Lipid Panel</strong>
                                    <br><small>Patient: Mike Davis | MRN: MRN003</small>
                                </div>
                                <span class="status-badge status-processing">Processing</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button class="btn btn-primary btn-sm" onclick="viewAllSamples()">
                            View All Samples <i class="fas fa-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Daily Statistics -->
            <div class="col-lg-6 mb-4">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-chart-line me-2"></i>Daily Statistics</h5>
                    <div class="chart-container">
                        <canvas id="dailyStatsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Equipment Status -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-microscope me-2"></i>Equipment Status</h5>
                    <div class="row">
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <div class="text-center">
                                <i class="fas fa-circle text-success fa-2x"></i>
                                <br><strong>Analyzer A1</strong>
                                <br><small>Online</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <div class="text-center">
                                <i class="fas fa-circle text-success fa-2x"></i>
                                <br><strong>Centrifuge C1</strong>
                                <br><small>Online</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <div class="text-center">
                                <i class="fas fa-circle text-success fa-2x"></i>
                                <br><strong>Microscope M1</strong>
                                <br><small>Online</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <div class="text-center">
                                <i class="fas fa-circle text-success fa-2x"></i>
                                <br><strong>Incubator I1</strong>
                                <br><small>Online</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <div class="text-center">
                                <i class="fas fa-circle text-success fa-2x"></i>
                                <br><strong>PCR Machine</strong>
                                <br><small>Online</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <div class="text-center">
                                <i class="fas fa-circle text-danger fa-2x"></i>
                                <br><strong>Analyzer A2</strong>
                                <br><small>Maintenance</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row">
            <div class="col-12">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-history me-2"></i>Recent Activity</h5>
                    <div class="table-responsive">
                        <table class="table table-dark table-striped">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Sample ID</th>
                                    <th>Test</th>
                                    <th>Patient</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>10:30 AM</td>
                                    <td>S001</td>
                                    <td>CBC</td>
                                    <td>John Smith</td>
                                    <td><span class="status-badge status-completed">Completed</span></td>
                                    <td><button class="btn btn-sm btn-outline-light">View</button></td>
                                </tr>
                                <tr>
                                    <td>10:15 AM</td>
                                    <td>S005</td>
                                    <td>Chemistry Panel</td>
                                    <td>Sarah Johnson</td>
                                    <td><span class="status-badge status-processing">Processing</span></td>
                                    <td><button class="btn btn-sm btn-outline-light">Monitor</button></td>
                                </tr>
                                <tr>
                                    <td>09:45 AM</td>
                                    <td>S003</td>
                                    <td>Urinalysis</td>
                                    <td>Mike Davis</td>
                                    <td><span class="status-badge status-completed">Completed</span></td>
                                    <td><button class="btn btn-sm btn-outline-light">View</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize daily stats chart
        const ctx = document.getElementById('dailyStatsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Samples Processed',
                    data: [45, 52, 38, 61, 55, 42, 28],
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: 'white' }
                    }
                },
                scales: {
                    y: {
                        ticks: { color: 'white' },
                        grid: { color: 'rgba(255, 255, 255, 0.1)' }
                    },
                    x: {
                        ticks: { color: 'white' },
                        grid: { color: 'rgba(255, 255, 255, 0.1)' }
                    }
                }
            }
        });

        // Load dashboard statistics
        async function loadDashboardStats() {
            try {
                const response = await fetch('/api/dashboard/lab');
                const data = await response.json();
                
                document.getElementById('pending-samples').textContent = data.pending_samples || 24;
                document.getElementById('completed-today').textContent = data.completed_today || 18;
                document.getElementById('urgent-tests').textContent = data.urgent_tests || 3;
                document.getElementById('equipment-status').textContent = 
                    `${data.equipment_online || 5}/${data.equipment_total || 6}`;
            } catch (error) {
                console.log('Using demo data - API not available');
            }
        }

        // Navigation functions
        function processSamples() {
            window.location.href = '/lab-tech/samples';
        }

        function enterResults() {
            window.location.href = '/lab-tech/results';
        }

        function viewEquipment() {
            window.location.href = '/lab-tech/equipment';
        }

        function generateReports() {
            window.location.href = '/lab-tech/reports';
        }

        function qualityControl() {
            window.location.href = '/lab-tech/quality-control';
        }

        function viewAllSamples() {
            window.location.href = '/lab-tech/samples';
        }

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardStats();
        });
    </script>
</body>
</html>
