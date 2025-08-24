<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Reports - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .glass-card { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 15px; color: white; }
        .navbar { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="/dashboard"><i class="fas fa-chart-bar me-2"></i>System Reports</a>
            <div class="ms-auto"><a href="/dashboard" class="btn btn-outline-light btn-sm"><i class="fas fa-arrow-left me-1"></i>Back</a></div>
        </div>
    </nav>
    <div class="container-fluid px-4 py-4">
        <!-- Reports Navigation -->
        <div class="glass-card p-4 mb-4">
            <h3><i class="fas fa-chart-bar me-2"></i>System Reports</h3>
            <p>Generate and view detailed reports on system activities, user performance, and financial metrics.</p>
            
            <!-- Tab Navigation -->
            <ul class="nav nav-pills mb-4" id="reportTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="user-activity-tab" data-bs-toggle="pill" data-bs-target="#user-activity" type="button" role="tab">
                        <i class="fas fa-users me-2"></i>User Activity
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="financial-summary-tab" data-bs-toggle="pill" data-bs-target="#financial-summary" type="button" role="tab">
                        <i class="fas fa-dollar-sign me-2"></i>Financial Summary
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="performance-metrics-tab" data-bs-toggle="pill" data-bs-target="#performance-metrics" type="button" role="tab">
                        <i class="fas fa-chart-line me-2"></i>Performance Metrics
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="reportTabsContent">
                <!-- User Activity Tab -->
                <div class="tab-pane fade show active" id="user-activity" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="glass-card p-3 mb-3">
                                <h5><i class="fas fa-user-clock me-2"></i>Recent Login Activity</h5>
                                <div id="loginActivity">
                                    <div class="d-flex justify-content-between py-2 border-bottom">
                                        <span>Admin User</span>
                                        <small class="text-muted">2 minutes ago</small>
                                    </div>
                                    <div class="d-flex justify-content-between py-2 border-bottom">
                                        <span>Dr. Smith</span>
                                        <small class="text-muted">15 minutes ago</small>
                                    </div>
                                    <div class="d-flex justify-content-between py-2 border-bottom">
                                        <span>Lab Tech Johnson</span>
                                        <small class="text-muted">1 hour ago</small>
                                    </div>
                                    <div class="d-flex justify-content-between py-2">
                                        <span>Radiologist Wilson</span>
                                        <small class="text-muted">2 hours ago</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="glass-card p-3 mb-3">
                                <h5><i class="fas fa-chart-bar me-2"></i>User Statistics</h5>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="text-center">
                                            <h3 class="text-primary" id="totalUsers">28</h3>
                                            <small>Total Users</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center">
                                            <h3 class="text-success" id="activeUsers">24</h3>
                                            <small>Active Today</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <div class="text-center">
                                            <h3 class="text-warning" id="totalPatients">156</h3>
                                            <small>Total Patients</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center">
                                            <h3 class="text-info" id="newPatientsToday">7</h3>
                                            <small>New Today</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="glass-card p-3">
                        <h5><i class="fas fa-tasks me-2"></i>Recent System Activities</h5>
                        <div id="systemActivities">
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div>
                                    <strong>New patient registration</strong>
                                    <br><small class="text-muted">Patient ID: PAT-2024-089 registered by Admin</small>
                                </div>
                                <small class="text-muted">5 min ago</small>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div>
                                    <strong>Lab test completed</strong>
                                    <br><small class="text-muted">Blood Panel for PAT-2024-087 completed</small>
                                </div>
                                <small class="text-muted">12 min ago</small>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2">
                                <div>
                                    <strong>Invoice generated</strong>
                                    <br><small class="text-muted">Invoice #INV-2024-034 for $250.00</small>
                                </div>
                                <small class="text-muted">18 min ago</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Summary Tab -->
                <div class="tab-pane fade" id="financial-summary" role="tabpanel">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="glass-card p-3 text-center">
                                <i class="fas fa-dollar-sign fa-2x mb-2 text-success"></i>
                                <h4 id="todayRevenue">$3,425</h4>
                                <small>Today's Revenue</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="glass-card p-3 text-center">
                                <i class="fas fa-calendar-week fa-2x mb-2 text-primary"></i>
                                <h4 id="weeklyRevenue">$18,900</h4>
                                <small>Weekly Revenue</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="glass-card p-3 text-center">
                                <i class="fas fa-calendar-alt fa-2x mb-2 text-info"></i>
                                <h4 id="monthlyRevenue">$87,450</h4>
                                <small>Monthly Revenue</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="glass-card p-3 text-center">
                                <i class="fas fa-chart-line fa-2x mb-2 text-warning"></i>
                                <h4 id="netProfit">$22,340</h4>
                                <small>Net Profit</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="glass-card p-3">
                                <h5><i class="fas fa-chart-pie me-2"></i>Revenue by Department</h5>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <span>Consultations</span>
                                        <strong>$1,250 (36%)</strong>
                                    </div>
                                    <div class="progress mb-2">
                                        <div class="progress-bar bg-success" style="width: 36%"></div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <span>Laboratory</span>
                                        <strong>$980 (29%)</strong>
                                    </div>
                                    <div class="progress mb-2">
                                        <div class="progress-bar bg-primary" style="width: 29%"></div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <span>Radiology</span>
                                        <strong>$825 (24%)</strong>
                                    </div>
                                    <div class="progress mb-2">
                                        <div class="progress-bar bg-info" style="width: 24%"></div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <span>Pharmacy</span>
                                        <strong>$370 (11%)</strong>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-warning" style="width: 11%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="glass-card p-3">
                                <h5><i class="fas fa-money-bill-wave me-2"></i>Top Revenue Sources</h5>
                                <div id="topRevenueSources">
                                    <div class="d-flex justify-content-between py-2 border-bottom">
                                        <div>
                                            <strong>Dr. Johnson</strong>
                                            <br><small class="text-muted">General Practice</small>
                                        </div>
                                        <div class="text-end">
                                            <strong class="text-success">$1,850</strong>
                                            <br><small class="text-muted">Today</small>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between py-2 border-bottom">
                                        <div>
                                            <strong>Lab Department</strong>
                                            <br><small class="text-muted">Blood Tests & Analysis</small>
                                        </div>
                                        <div class="text-end">
                                            <strong class="text-success">$980</strong>
                                            <br><small class="text-muted">Today</small>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between py-2">
                                        <div>
                                            <strong>Radiology Department</strong>
                                            <br><small class="text-muted">Imaging & Scans</small>
                                        </div>
                                        <div class="text-end">
                                            <strong class="text-success">$825</strong>
                                            <br><small class="text-muted">Today</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Metrics Tab -->
                <div class="tab-pane fade" id="performance-metrics" role="tabpanel">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="glass-card p-3">
                                <h5><i class="fas fa-user-md me-2"></i>Doctor Performance</h5>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Dr. Johnson</span>
                                        <span class="badge bg-success">95%</span>
                                    </div>
                                    <div class="progress mb-2">
                                        <div class="progress-bar bg-success" style="width: 95%"></div>
                                    </div>
                                    <small class="text-muted">14 patients today</small>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Dr. Smith</span>
                                        <span class="badge bg-success">92%</span>
                                    </div>
                                    <div class="progress mb-2">
                                        <div class="progress-bar bg-success" style="width: 92%"></div>
                                    </div>
                                    <small class="text-muted">11 patients today</small>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Dr. Wilson</span>
                                        <span class="badge bg-primary">88%</span>
                                    </div>
                                    <div class="progress mb-2">
                                        <div class="progress-bar bg-primary" style="width: 88%"></div>
                                    </div>
                                    <small class="text-muted">9 patients today</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="glass-card p-3">
                                <h5><i class="fas fa-clock me-2"></i>System Efficiency</h5>
                                <div class="text-center mb-3">
                                    <h3 class="text-success">96%</h3>
                                    <small>Overall System Uptime</small>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <span>Avg Response Time</span>
                                        <strong>1.2s</strong>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <span>Database Performance</span>
                                        <span class="badge bg-success">Excellent</span>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <span>API Endpoints</span>
                                        <span class="badge bg-success">All Active</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="glass-card p-3">
                                <h5><i class="fas fa-chart-bar me-2"></i>Department Metrics</h5>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Lab Turnaround</span>
                                        <strong>2.4 hrs avg</strong>
                                    </div>
                                    <div class="progress mb-1">
                                        <div class="progress-bar bg-success" style="width: 85%"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Radiology Reports</span>
                                        <strong>45 min avg</strong>
                                    </div>
                                    <div class="progress mb-1">
                                        <div class="progress-bar bg-success" style="width: 92%"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Pharmacy Filling</span>
                                        <strong>8 min avg</strong>
                                    </div>
                                    <div class="progress mb-1">
                                        <div class="progress-bar bg-success" style="width: 88%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="glass-card p-3">
                        <h5><i class="fas fa-trophy me-2"></i>Performance Highlights</h5>
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <div class="badge bg-success fs-6 p-2 mb-2">🏆</div>
                                <h6>Best Doctor</h6>
                                <p class="mb-0">Dr. Johnson<br><small class="text-muted">95% satisfaction</small></p>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="badge bg-primary fs-6 p-2 mb-2">⚡</div>
                                <h6>Fastest Department</h6>
                                <p class="mb-0">Pharmacy<br><small class="text-muted">8 min avg</small></p>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="badge bg-warning fs-6 p-2 mb-2">📈</div>
                                <h6>Highest Revenue</h6>
                                <p class="mb-0">Consultations<br><small class="text-muted">$1,250 today</small></p>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="badge bg-info fs-6 p-2 mb-2">⭐</div>
                                <h6>System Health</h6>
                                <p class="mb-0">96% Uptime<br><small class="text-muted">Excellent</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export and Actions -->
        <div class="glass-card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-download me-2"></i>Export Options</h6>
                <div>
                    <button class="btn btn-outline-light btn-sm me-2" onclick="exportReport('pdf')">
                        <i class="fas fa-file-pdf me-1"></i>Export PDF
                    </button>
                    <button class="btn btn-outline-light btn-sm me-2" onclick="exportReport('excel')">
                        <i class="fas fa-file-excel me-1"></i>Export Excel
                    </button>
                    <button class="btn btn-outline-light btn-sm" onclick="refreshReports()">
                        <i class="fas fa-sync-alt me-1"></i>Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-refresh data every 30 seconds
        setInterval(refreshReports, 30000);
        
        function refreshReports() {
            console.log('Refreshing report data...');
            // In a real implementation, this would fetch fresh data from the API
            
            // Simulate data refresh with slight variations
            const variation = () => Math.floor(Math.random() * 100) - 50;
            
            // Update some numbers to show the system is "live"
            document.getElementById('activeUsers').textContent = Math.max(20, 24 + Math.floor(variation() / 10));
            document.getElementById('newPatientsToday').textContent = Math.max(1, 7 + Math.floor(variation() / 20));
        }
        
        function exportReport(format) {
            alert(`Exporting reports in ${format.toUpperCase()} format. This feature will be implemented in the full system.`);
        }

        // Initialize with demo data
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Admin Reports Dashboard loaded successfully');
        });
    </script>
</body>
</html>
