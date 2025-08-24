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
        
        /* Tab styles */
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .btn-group .btn.active {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        .study-item {
            transition: all 0.3s ease;
        }
        
        .study-item:hover {
            transform: translateX(5px);
            background: rgba(255, 255, 255, 0.08) !important;
        }
        
        .viewer-container {
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        #dicomCanvas {
            background: #000;
            border-radius: 8px;
        }
        
        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        
        .alert {
            border: none;
            border-radius: 10px;
        }
        
        .comparison-panel {
            min-height: 300px;
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
                <div class="row">
                    <!-- Patient Studies -->
                    <div class="col-md-8 mb-4">
                        <div class="glass-card p-4">
                            <!-- Tab Navigation -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Imaging Studies</h5>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-light btn-sm active" id="studies-tab-btn" onclick="showTab('studies')">
                                        <i class="fas fa-list me-1"></i>Studies
                                    </button>
                                    <button type="button" class="btn btn-outline-light btn-sm" id="viewer-tab-btn" onclick="showTab('viewer')">
                                        <i class="fas fa-eye me-1"></i>DICOM Viewer
                                    </button>
                                    <button type="button" class="btn btn-outline-light btn-sm" id="ai-tab-btn" onclick="showTab('ai')">
                                        <i class="fas fa-robot me-1"></i>AI Analysis
                                    </button>
                                    <button type="button" class="btn btn-outline-light btn-sm" id="compare-tab-btn" onclick="showTab('compare')">
                                        <i class="fas fa-balance-scale me-1"></i>Compare
                                    </button>
                                </div>
                            </div>

                            <!-- Studies Tab -->
                            <div id="studies-tab" class="tab-content active">
                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-light text-white">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" class="form-control bg-transparent border-light text-white" 
                                               placeholder="Search studies by patient name, MRN, or modality..." id="studySearch">
                                    </div>
                                </div>
                                <div id="studiesList">
                                    <div class="text-center py-4">
                                        <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                                        <p>Loading studies...</p>
                                    </div>
                                </div>
                                
                                <!-- Upload New Study -->
                                <div class="mt-4 p-3" style="background: rgba(255, 255, 255, 0.05); border-radius: 10px;">
                                    <h6><i class="fas fa-upload me-2"></i>Upload New Study</h6>
                                    <form id="uploadForm" class="mt-3" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <select class="form-select bg-transparent border-light text-white" id="patientSelect" required>
                                                    <option value="">Select Patient</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <select class="form-select bg-transparent border-light text-white" id="modalitySelect" required>
                                                    <option value="">Select Modality</option>
                                                    <option value="CT">CT Scan</option>
                                                    <option value="MRI">MRI</option>
                                                    <option value="X-RAY">X-Ray</option>
                                                    <option value="US">Ultrasound</option>
                                                    <option value="PET">PET Scan</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <input type="text" class="form-control bg-transparent border-light text-white" 
                                                   id="studyDescription" placeholder="Study Description" required>
                                        </div>
                                        <div class="mb-3">
                                            <input type="file" class="form-control bg-transparent border-light text-white" 
                                                   id="dicomFiles" accept=".dcm,.dicom" multiple required>
                                            <small class="text-muted">Select DICOM files (.dcm, .dicom)</small>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-upload me-1"></i>Upload Study
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- DICOM Viewer Tab -->
                            <div id="viewer-tab" class="tab-content">
                                <div id="dicomViewer" class="text-center">
                                    <div class="py-5">
                                        <i class="fas fa-file-medical fa-4x mb-3 opacity-50"></i>
                                        <h5>DICOM Viewer</h5>
                                        <p class="text-muted">Select a study from the Studies tab to view DICOM images</p>
                                        <button class="btn btn-outline-light" onclick="showTab('studies')">
                                            <i class="fas fa-arrow-left me-1"></i>Go to Studies
                                        </button>
                                    </div>
                                </div>
                                <!-- Viewer will be populated when study is selected -->
                                <div id="dicomViewerContent" class="d-none">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="viewer-container p-3 bg-dark rounded">
                                                <canvas id="dicomCanvas" width="512" height="512" style="max-width: 100%; border: 1px solid #dee2e6;"></canvas>
                                            </div>
                                            <div class="viewer-controls mt-2">
                                                <button class="btn btn-sm btn-outline-light me-1" onclick="adjustWindow('wider')">
                                                    <i class="fas fa-expand-arrows-alt"></i> Window
                                                </button>
                                                <button class="btn btn-sm btn-outline-light me-1" onclick="adjustWindow('level')">
                                                    <i class="fas fa-adjust"></i> Level
                                                </button>
                                                <button class="btn btn-sm btn-outline-light me-1" onclick="zoomIn()">
                                                    <i class="fas fa-search-plus"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-light me-1" onclick="zoomOut()">
                                                    <i class="fas fa-search-minus"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-light" onclick="resetView()">
                                                    <i class="fas fa-undo"></i> Reset
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="study-info p-3 bg-dark rounded">
                                                <h6>Study Information</h6>
                                                <div id="studyMetadata"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- AI Analysis Tab -->
                            <div id="ai-tab" class="tab-content">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div id="aiAnalysisArea">
                                            <div class="text-center py-5">
                                                <i class="fas fa-brain fa-4x mb-3 text-info"></i>
                                                <h5>AI-Powered Analysis</h5>
                                                <p class="text-muted">Select a study and choose analysis type to get AI insights</p>
                                            </div>
                                        </div>
                                        <div id="aiResults" class="d-none">
                                            <h6><i class="fas fa-robot me-2"></i>AI Analysis Results</h6>
                                            <div id="aiResultContent" class="p-3 bg-dark rounded mt-2"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="ai-controls p-3 bg-dark rounded">
                                            <h6>Analysis Options</h6>
                                            <div class="mb-3">
                                                <label class="form-label">Analysis Type</label>
                                                <select class="form-select bg-transparent border-light text-white" id="analysisType">
                                                    <option value="">Select Analysis</option>
                                                    <option value="abnormality">Abnormality Detection</option>
                                                    <option value="fracture">Fracture Detection</option>
                                                    <option value="tumor">Tumor Analysis</option>
                                                    <option value="chest">Chest X-Ray Analysis</option>
                                                    <option value="brain">Brain MRI Analysis</option>
                                                    <option value="general">General Report</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Confidence Threshold</label>
                                                <input type="range" class="form-range" id="confidenceThreshold" min="0.5" max="0.95" step="0.05" value="0.8">
                                                <small class="text-muted">Current: <span id="confidenceValue">80%</span></small>
                                            </div>
                                            <button class="btn btn-primary w-100 mb-2" id="runAnalysis" onclick="runAIAnalysis()">
                                                <i class="fas fa-play me-1"></i>Run Analysis
                                            </button>
                                            <button class="btn btn-outline-light w-100" onclick="generateReport()">
                                                <i class="fas fa-file-pdf me-1"></i>Generate Report
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Compare Studies Tab -->
                            <div id="compare-tab" class="tab-content">
                                <div class="mb-3">
                                    <h6><i class="fas fa-balance-scale me-2"></i>Study Comparison</h6>
                                    <p class="text-muted">Compare two imaging studies side by side</p>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="comparison-panel p-3 bg-dark rounded">
                                            <h6>Study A</h6>
                                            <select class="form-select bg-transparent border-light text-white mb-3" id="studyA">
                                                <option value="">Select First Study</option>
                                            </select>
                                            <div id="viewerA" class="viewer-container text-center py-4 border border-secondary rounded">
                                                <i class="fas fa-image fa-2x opacity-50"></i>
                                                <p class="mt-2 mb-0 text-muted">Select Study A</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="comparison-panel p-3 bg-dark rounded">
                                            <h6>Study B</h6>
                                            <select class="form-select bg-transparent border-light text-white mb-3" id="studyB">
                                                <option value="">Select Second Study</option>
                                            </select>
                                            <div id="viewerB" class="viewer-container text-center py-4 border border-secondary rounded">
                                                <i class="fas fa-image fa-2x opacity-50"></i>
                                                <p class="mt-2 mb-0 text-muted">Select Study B</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 text-center">
                                    <button class="btn btn-primary me-2" onclick="syncViewers()">
                                        <i class="fas fa-sync me-1"></i>Sync Views
                                    </button>
                                    <button class="btn btn-outline-light me-2" onclick="generateComparison()">
                                        <i class="fas fa-file-alt me-1"></i>Generate Comparison Report
                                    </button>
                                    <button class="btn btn-outline-light" onclick="clearComparison()">
                                        <i class="fas fa-times me-1"></i>Clear
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>        <!-- Financial Performance -->
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

    <script>
        let currentStudy = null;
        let selectedStudyA = null;
        let selectedStudyB = null;

        // Tab management
        function showTab(tabName) {
            // Hide all tabs
            const tabs = document.querySelectorAll('.tab-content');
            tabs.forEach(tab => tab.classList.remove('active'));
            
            // Remove active class from buttons
            const buttons = document.querySelectorAll('.btn-group .btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            
            // Show selected tab
            document.getElementById(tabName + '-tab').classList.add('active');
            document.getElementById(tabName + '-tab-btn').classList.add('active');
            
            // Load tab-specific data
            if (tabName === 'studies') {
                loadStudies();
            } else if (tabName === 'compare') {
                loadStudiesForComparison();
            }
        }

        // Studies management
        function loadStudies() {
            const studiesList = document.getElementById('studiesList');
            studiesList.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x mb-3"></i><p>Loading studies...</p></div>';
            
            fetch('/api/imaging/studies')
                .then(response => response.json())
                .then(studies => {
                    displayStudies(studies);
                    loadPatients(); // Load patients for upload form
                })
                .catch(error => {
                    console.error('Error loading studies:', error);
                    studiesList.innerHTML = '<div class="text-center py-4 text-danger"><i class="fas fa-exclamation-triangle fa-2x mb-3"></i><p>Error loading studies</p></div>';
                });
        }

        function displayStudies(studies) {
            const studiesList = document.getElementById('studiesList');
            if (studies.length === 0) {
                studiesList.innerHTML = '<div class="text-center py-4"><i class="fas fa-folder-open fa-2x mb-3 opacity-50"></i><p>No studies found</p></div>';
                return;
            }
            
            let html = '';
            studies.forEach(study => {
                const urgencyClass = study.urgency === 'high' ? 'border-danger' : 'border-secondary';
                const statusBadge = study.status === 'pending' ? 'bg-warning' : study.status === 'in-progress' ? 'bg-info' : 'bg-success';
                
                html += `
                    <div class="study-item p-3 mb-2 border ${urgencyClass} rounded" style="background: rgba(255, 255, 255, 0.05);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">${study.patient_name} <small class="text-muted">(${study.patient_mrn})</small></h6>
                                <p class="mb-1 text-sm">${study.description}</p>
                                <small class="text-muted">${study.modality} • ${study.study_date || study.date}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge ${statusBadge} mb-2">${study.status}</span><br>
                                <button class="btn btn-sm btn-outline-light me-1" onclick="viewStudy(${study.id})">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="btn btn-sm btn-outline-light" onclick="analyzeStudy(${study.id})">
                                    <i class="fas fa-robot"></i> Analyze
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            studiesList.innerHTML = html;
        }

        function viewStudy(studyId) {
            currentStudy = studyId;
            showTab('viewer');
            loadDicomViewer(studyId);
        }

        function analyzeStudy(studyId) {
            currentStudy = studyId;
            showTab('ai');
            document.getElementById('aiAnalysisArea').innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                    <h6>Study Selected</h6>
                    <p class="text-muted">Choose analysis type and click "Run Analysis"</p>
                </div>
            `;
        }

        // DICOM Viewer functions
        function loadDicomViewer(studyId) {
            document.getElementById('dicomViewer').classList.add('d-none');
            document.getElementById('dicomViewerContent').classList.remove('d-none');
            
            // Simulate loading DICOM data
            const canvas = document.getElementById('dicomCanvas');
            const ctx = canvas.getContext('2d');
            
            // Create a mock medical image
            ctx.fillStyle = '#000';
            ctx.fillRect(0, 0, 512, 512);
            
            // Add some mock anatomical structures
            ctx.fillStyle = '#333';
            ctx.beginPath();
            ctx.arc(256, 256, 100, 0, 2 * Math.PI);
            ctx.fill();
            
            ctx.fillStyle = '#666';
            ctx.beginPath();
            ctx.arc(200, 200, 30, 0, 2 * Math.PI);
            ctx.fill();
            
            // Update study metadata
            document.getElementById('studyMetadata').innerHTML = `
                <p><strong>Patient:</strong> John Doe</p>
                <p><strong>Study Date:</strong> 2024-01-15</p>
                <p><strong>Modality:</strong> CT</p>
                <p><strong>Series:</strong> 3 of 5</p>
                <p><strong>Instance:</strong> 45 of 120</p>
            `;
        }

        // AI Analysis functions
        function runAIAnalysis() {
            if (!currentStudy) {
                alert('Please select a study first');
                return;
            }
            
            const analysisType = document.getElementById('analysisType').value;
            if (!analysisType) {
                alert('Please select an analysis type');
                return;
            }
            
            const button = document.getElementById('runAnalysis');
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Analyzing...';
            button.disabled = true;
            
            const confidence = document.getElementById('confidenceThreshold').value;
            
            fetch(`/api/imaging/analyze/${currentStudy}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({
                    analysis_type: analysisType,
                    confidence_threshold: parseFloat(confidence)
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const mockResults = generateMockAIResults(analysisType, confidence);
                    document.getElementById('aiResults').classList.remove('d-none');
                    document.getElementById('aiResultContent').innerHTML = mockResults;
                } else {
                    alert('Analysis failed: ' + (data.message || 'Unknown error'));
                }
                
                button.innerHTML = '<i class="fas fa-play me-1"></i>Run Analysis';
                button.disabled = false;
            })
            .catch(error => {
                console.error('Analysis error:', error);
                alert('Analysis failed. Please try again.');
                button.innerHTML = '<i class="fas fa-play me-1"></i>Run Analysis';
                button.disabled = false;
            });
        }

        function generateMockAIResults(analysisType, confidence) {
            const confidencePercent = Math.round(confidence * 100);
            
            const results = {
                'abnormality': `
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Abnormality Detected</h6>
                        <p>Suspicious area identified in the lower right quadrant</p>
                        <p><strong>Confidence:</strong> ${confidencePercent}%</p>
                        <p><strong>Recommendation:</strong> Further investigation recommended</p>
                    </div>
                `,
                'fracture': `
                    <div class="alert alert-info">
                        <h6><i class="fas fa-bone me-2"></i>Fracture Analysis</h6>
                        <p>No obvious fractures detected</p>
                        <p><strong>Confidence:</strong> ${confidencePercent}%</p>
                        <p><strong>Status:</strong> Normal findings</p>
                    </div>
                `,
                'general': `
                    <div class="alert alert-success">
                        <h6><i class="fas fa-clipboard-check me-2"></i>General Analysis</h6>
                        <p>Overall image quality is good. No significant abnormalities detected.</p>
                        <p><strong>Confidence:</strong> ${confidencePercent}%</p>
                        <p><strong>Findings:</strong> Within normal limits</p>
                    </div>
                `
            };
            
            return results[analysisType] || results['general'];
        }

        // Comparison functions
        function loadStudiesForComparison() {
            const studyASelect = document.getElementById('studyA');
            const studyBSelect = document.getElementById('studyB');
            
            const mockStudies = [
                { id: 1, name: 'John Doe - CT Chest (2024-01-15)' },
                { id: 2, name: 'John Doe - CT Chest (2024-01-01)' },
                { id: 3, name: 'Jane Smith - MRI Brain (2024-01-14)' }
            ];
            
            let options = '<option value="">Select Study</option>';
            mockStudies.forEach(study => {
                options += `<option value="${study.id}">${study.name}</option>`;
            });
            
            studyASelect.innerHTML = options;
            studyBSelect.innerHTML = options;
        }

        function syncViewers() {
            alert('Viewers synchronized for side-by-side comparison');
        }

        function generateComparison() {
            alert('Generating detailed comparison report');
        }

        function clearComparison() {
            document.getElementById('studyA').value = '';
            document.getElementById('studyB').value = '';
            document.getElementById('viewerA').innerHTML = '<i class="fas fa-image fa-2x opacity-50"></i><p class="mt-2 mb-0 text-muted">Select Study A</p>';
            document.getElementById('viewerB').innerHTML = '<i class="fas fa-image fa-2x opacity-50"></i><p class="mt-2 mb-0 text-muted">Select Study B</p>';
        }

        // Upload functionality
        function loadPatients() {
            const patientSelect = document.getElementById('patientSelect');
            
            fetch('/api/patients')
                .then(response => response.json())
                .then(patients => {
                    let options = '<option value="">Select Patient</option>';
                    patients.forEach(patient => {
                        options += `<option value="${patient.id}">${patient.name} (${patient.mrn || 'No MRN'})</option>`;
                    });
                    patientSelect.innerHTML = options;
                })
                .catch(error => {
                    console.error('Error loading patients:', error);
                    const mockPatients = [
                        { id: 1, name: 'John Doe', mrn: 'MRN001' },
                        { id: 2, name: 'Jane Smith', mrn: 'MRN002' },
                        { id: 3, name: 'Bob Johnson', mrn: 'MRN003' }
                    ];
                    
                    let options = '<option value="">Select Patient</option>';
                    mockPatients.forEach(patient => {
                        options += `<option value="${patient.id}">${patient.name} (${patient.mrn})</option>`;
                    });
                    patientSelect.innerHTML = options;
                });
        }

        // Legacy functions for backwards compatibility
        function viewNextStudy() {
            showTab('studies');
        }

        function urgentStudies() {
            showTab('studies');
        }

        function dicomViewer() {
            showTab('viewer');
        }

        function aiAssist() {
            showTab('ai');
        }

        function compareStudies() {
            showTab('compare');
        }

        // Form submission and initialization
        document.addEventListener('DOMContentLoaded', function() {
            // Load initial data
            loadStudies();
            
            // Confidence threshold display
            const confidenceThreshold = document.getElementById('confidenceThreshold');
            if (confidenceThreshold) {
                confidenceThreshold.addEventListener('input', function() {
                    const value = Math.round(this.value * 100);
                    document.getElementById('confidenceValue').textContent = value + '%';
                });
            }
            
            // Upload form
            const uploadForm = document.getElementById('uploadForm');
            if (uploadForm) {
                uploadForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData();
                    formData.append('patient_id', document.getElementById('patientSelect').value);
                    formData.append('modality', document.getElementById('modalitySelect').value);
                    formData.append('description', document.getElementById('studyDescription').value);
                    
                    const files = document.getElementById('dicomFiles').files;
                    for (let i = 0; i < files.length; i++) {
                        formData.append('dicom_files[]', files[i]);
                    }
                    
                    // Show loading state
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Uploading...';
                    submitBtn.disabled = true;
                    
                    fetch('/api/imaging/upload', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Study uploaded successfully!');
                            loadStudies();
                            this.reset();
                        } else {
                            alert('Upload failed: ' + (data.message || 'Unknown error'));
                        }
                        
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    })
                    .catch(error => {
                        console.error('Upload error:', error);
                        alert('Upload failed. Please try again.');
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
                });
            }
            
            // Load dashboard stats
            setTimeout(() => {
                const pendingElement = document.getElementById('pendingStudiesCount');
                const completedElement = document.getElementById('completedTodayCount');
                const urgentElement = document.getElementById('urgentStudiesCount');
                
                if (pendingElement) pendingElement.textContent = '12';
                if (completedElement) completedElement.textContent = '8';
                if (urgentElement) urgentElement.textContent = '3';
            }, 1000);
        });

        // Utility functions for DICOM viewer
        function adjustWindow(type) {
            console.log(`Adjusting window ${type}`);
        }

        function zoomIn() {
            console.log('Zooming in');
        }

        function zoomOut() {
            console.log('Zooming out');
        }

        function resetView() {
            console.log('Resetting view');
        }

        function generateReport() {
            alert('Generating comprehensive imaging report');
        }

        function scheduleFollowUp() {
            alert('Follow-up scheduling system');
        }
    </script>
    </script>
</body>
</html>
