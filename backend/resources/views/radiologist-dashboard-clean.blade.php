<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Radiologist Dashboard - FHIR DICOM Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
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
            background: linear-gradient(45deg, #6f42c1, #e83e8c);
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
        .study-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid #6f42c1;
        }
        .urgent { border-left-color: #dc3545; }
        .stat { border-left-color: #ffc107; }
        .routine { border-left-color: #28a745; }
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
                <i class="fas fa-x-ray me-2"></i>Radiologist Dashboard
            </a>
            <div class="ms-auto">
                <span class="navbar-text me-3">Welcome, Dr. {{ Auth::user()->name ?? 'Radiologist' }}</span>
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
                            <button class="btn btn-primary w-100" onclick="viewNextStudy()">
                                <i class="fas fa-play me-2"></i>Next Study
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="urgentStudies()">
                                <i class="fas fa-exclamation-triangle me-2"></i>Urgent
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="dicomViewer()">
                                <i class="fas fa-eye me-2"></i>DICOM Viewer
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="aiAssist()">
                                <i class="fas fa-robot me-2"></i>AI Assist
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="compareStudies()">
                                <i class="fas fa-columns me-2"></i>Compare
                            </button>
                        </div>
                        <div class="col-md-2">
                            <a href="/radiologist-configuration" class="btn btn-outline-light w-100">
                                <i class="fas fa-cogs me-2"></i>Configuration
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Performance -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-dollar-sign fa-2x mb-2 text-warning"></i>
                        <h5 class="mb-0 financial-highlight" id="todayEarnings">Loading...</h5>
                        <small>Today's Earnings</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-calendar-week fa-2x mb-2 text-info"></i>
                        <h5 class="mb-0 financial-highlight" id="weeklyEarnings">Loading...</h5>
                        <small>Weekly Earnings</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-calendar-alt fa-2x mb-2 text-primary"></i>
                        <h5 class="mb-0 financial-highlight" id="monthlyEarnings">Loading...</h5>
                        <small>Monthly Earnings</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-percentage fa-2x mb-2 text-success"></i>
                        <h5 class="mb-0" id="revenueShare">60%</h5>
                        <small>Revenue Share</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Radiology Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-hourglass-half fa-2x mb-2 text-warning"></i>
                        <h5 class="mb-0" id="pendingStudies">Loading...</h5>
                        <small>Pending Studies</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                        <h5 class="mb-0" id="completedToday">Loading...</h5>
                        <small>Completed Today</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-exclamation-circle fa-2x mb-2 text-danger"></i>
                        <h5 class="mb-0" id="urgentCount">Loading...</h5>
                        <small>Urgent Studies</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-clock fa-2x mb-2 text-info"></i>
                        <h5 class="mb-0" id="avgReadTime">Loading...</h5>
                        <small>Avg Read Time</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="row">
            <div class="col-md-8">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-list-ul me-2"></i>Pending Studies (Prioritized)</h5>
                    <div id="pendingStudiesList">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-chart-pie me-2"></i>Study Types Distribution</h5>
                    <canvas id="studyTypesChart" height="150"></canvas>
                </div>
            </div>
        </div>

        <!-- Reading Statistics -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-chart-line me-2"></i>Reading Performance</h5>
                    <canvas id="performanceChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadRadiologyStats();
            loadPendingStudies();
            initializeCharts();
        });

        function loadRadiologyStats() {
            fetch('/api/dashboard/radiology')
                .then(response => response.json())
                .then(data => {
                    // Financial data
                    document.getElementById('todayEarnings').textContent = '$' + (data.today_earnings || 720).toLocaleString();
                    document.getElementById('weeklyEarnings').textContent = '$' + (data.weekly_earnings || 3600).toLocaleString();
                    document.getElementById('monthlyEarnings').textContent = '$' + (data.monthly_earnings || 15400).toLocaleString();
                    
                    // Study stats
                    document.getElementById('pendingStudies').textContent = data.pending_studies || 18;
                    document.getElementById('completedToday').textContent = data.completed_studies || 22;
                    document.getElementById('urgentCount').textContent = data.urgent_studies || 3;
                    document.getElementById('avgReadTime').textContent = (data.avg_read_time || 12) + ' min';
                })
                .catch(error => {
                    console.error('Error loading radiology stats:', error);
                    // Set demo data
                    document.getElementById('todayEarnings').textContent = '$15,000';
                    document.getElementById('weeklyEarnings').textContent = '$15,000';
                    document.getElementById('monthlyEarnings').textContent = '$15,000';
                    
                    document.getElementById('pendingStudies').textContent = '18';
                    document.getElementById('completedToday').textContent = '22';
                    document.getElementById('urgentCount').textContent = '3';
                    document.getElementById('avgReadTime').textContent = '12 min';
                });
        }

        function loadPendingStudies() {
            const studiesContainer = document.getElementById('pendingStudiesList');
            const studies = [
                { id: 'S001', patient: 'John Doe', modality: 'CT Chest', priority: 'urgent', ordered: '1 hour ago' },
                { id: 'S002', patient: 'Jane Smith', modality: 'MRI Brain', priority: 'stat', ordered: '30 min ago' },
                { id: 'S003', patient: 'Bob Johnson', modality: 'X-Ray Knee', priority: 'routine', ordered: '2 hours ago' },
                { id: 'S004', patient: 'Alice Brown', modality: 'CT Abdomen', priority: 'urgent', ordered: '45 min ago' }
            ];

            studiesContainer.innerHTML = studies.map(study => `
                <div class="study-card ${study.priority}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${study.id} - ${study.modality}</strong>
                            <small class="d-block">Patient: ${study.patient}</small>
                            <small class="text-info">Ordered: ${study.ordered}</small>
                        </div>
                        <div>
                            <span class="badge bg-${study.priority === 'urgent' ? 'danger' : study.priority === 'stat' ? 'warning' : 'success'} me-2">
                                ${study.priority.toUpperCase()}
                            </span>
                            <button class="btn btn-sm btn-primary" onclick="readStudy('${study.id}')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function initializeCharts() {
            // Study Types Chart
            const ctx1 = document.getElementById('studyTypesChart').getContext('2d');
            new Chart(ctx1, {
                type: 'doughnut',
                data: {
                    labels: ['CT', 'MRI', 'X-Ray', 'Ultrasound', 'Mammography'],
                    datasets: [{
                        data: [30, 25, 20, 15, 10],
                        backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: {
                                color: 'white'
                            }
                        }
                    }
                }
            });

            // Performance Chart
            const ctx2 = document.getElementById('performanceChart').getContext('2d');
            new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Studies Read',
                        data: [15, 22, 18, 25, 20, 16, 19],
                        borderColor: 'rgba(255, 255, 255, 0.8)',
                        backgroundColor: 'rgba(255, 255, 255, 0.1)',
                        tension: 0.4
                    }]
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
                                color: 'white'
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

        // Action functions
        function readStudy(studyId) {
            window.location.href = `/radiologist/viewer?study=${studyId}`;
        }

        function viewNextStudy() {
            window.location.href = '/radiologist/studies';
        }

        function urgentStudies() {
            window.location.href = '/radiologist/studies?filter=urgent';
        }

        function dicomViewer() {
            window.location.href = '/radiologist/viewer';
        }

        function aiAssist() {
            window.location.href = '/medgemma';
        }

        function compareStudies() {
            alert('Study comparison feature - Advanced DICOM comparison tools available');
        }
    </script>
</body>
</html>
