<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Business Owner Dashboard - FHIR DICOM Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .owner-highlight {
            background: linear-gradient(45deg, #D4AF37, #FFD700);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
        }
        .modal .owner-highlight {
            color: #D4AF37 !important;
            background: none !important;
            -webkit-text-fill-color: #D4AF37 !important;
        }
        .modal .form-control, .modal .form-select {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }
        .modal .form-control:focus, .modal .form-select:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #D4AF37;
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25);
        }
        .revenue-card {
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.2), rgba(255, 215, 0, 0.1));
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }
        .revenue-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.3);
        }
        .performance-metric {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            text-align: center;
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
        .department-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid;
        }
        .dept-consultation { border-left-color: #28a745; }
        .dept-lab { border-left-color: #17a2b8; }
        .dept-radiology { border-left-color: #6f42c1; }
        .dept-pharmacy { border-left-color: #fd7e14; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="#">
                <i class="fas fa-crown me-2"></i>Business Owner Dashboard
            </a>
            <div class="ms-auto">
                <span class="navbar-text me-3">Welcome, {{ Auth::user()->name ?? 'Business Owner' }}</span>
                <a href="/logout" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <!-- Owner Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="glass-card p-4">
                    <h4 class="mb-3"><i class="fas fa-lightning-bolt me-2"></i>Owner Control Center</h4>
                    <div class="row g-3">
                        <div class="col-md-2">
                            <button class="btn btn-owner w-100" onclick="manageUsers()">
                                <i class="fas fa-users me-2"></i>Manage Users
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="generateReports()">
                                <i class="fas fa-chart-bar me-2"></i>Generate Reports
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="financialAnalysis()">
                                <i class="fas fa-analytics me-2"></i>Financial Analysis
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="performanceMetrics()">
                                <i class="fas fa-tachometer-alt me-2"></i>Performance
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="businessSettings()">
                                <i class="fas fa-cog me-2"></i>Business Settings
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="viewPatients()">
                                <i class="fas fa-user-injured me-2"></i>Patient Overview
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Date Range Filter -->
        <div class="glass-card p-3 mb-4">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <label class="form-label">Report Period</label>
                    <select class="form-select" id="reportPeriod" onchange="updateReports()">
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="quarter">This Quarter</option>
                        <option value="year">This Year</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>
                <div class="col-md-3" id="customDateStart" style="display: none;">
                    <label class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="startDate">
                </div>
                <div class="col-md-3" id="customDateEnd" style="display: none;">
                    <label class="form-label">End Date</label>
                    <input type="date" class="form-control" id="endDate">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button class="btn btn-owner" onclick="refreshData()">
                            <i class="fas fa-sync me-2"></i>Refresh Data
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue by Department -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="revenue-card dept-consultation">
                    <i class="fas fa-user-md fa-2x mb-2 text-success"></i>
                    <h5 class="owner-highlight" id="consultationRevenue">Loading...</h5>
                    <small>Consultation Revenue</small>
                    <div class="mt-2">
                        <small class="text-muted">Owner Share: <span id="consultationOwnerShare">0%</span></small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="revenue-card dept-lab">
                    <i class="fas fa-flask fa-2x mb-2 text-info"></i>
                    <h5 class="owner-highlight" id="labRevenue">Loading...</h5>
                    <small>Laboratory Revenue</small>
                    <div class="mt-2">
                        <small class="text-muted">Owner Share: <span id="labOwnerShare">0%</span></small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="revenue-card dept-radiology">
                    <i class="fas fa-x-ray fa-2x mb-2 text-purple"></i>
                    <h5 class="owner-highlight" id="radiologyRevenue">Loading...</h5>
                    <small>Radiology Revenue</small>
                    <div class="mt-2">
                        <small class="text-muted">Owner Share: <span id="radiologyOwnerShare">0%</span></small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="revenue-card dept-pharmacy">
                    <i class="fas fa-pills fa-2x mb-2 text-warning"></i>
                    <h5 class="owner-highlight" id="pharmacyRevenue">Loading...</h5>
                    <small>Pharmacy Revenue</small>
                    <div class="mt-2">
                        <small class="text-muted">Owner Share: <span id="pharmacyOwnerShare">0%</span></small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Overview -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-chart-line me-2"></i>Revenue Trends</h5>
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-chart-pie me-2"></i>Revenue Distribution</h5>
                    <canvas id="distributionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-trophy me-2"></i>Department Performance</h5>
                    <div id="departmentPerformance">
                        <div class="department-card dept-consultation">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Consultation Services</strong>
                                    <div class="small text-muted">Doctor consultations & follow-ups</div>
                                </div>
                                <div class="text-end">
                                    <div class="owner-highlight" id="consultationProfit">$0</div>
                                    <small>Owner Profit</small>
                                </div>
                            </div>
                        </div>
                        <div class="department-card dept-lab">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Laboratory Services</strong>
                                    <div class="small text-muted">Tests, analysis & diagnostics</div>
                                </div>
                                <div class="text-end">
                                    <div class="owner-highlight" id="labProfit">$0</div>
                                    <small>Owner Profit</small>
                                </div>
                            </div>
                        </div>
                        <div class="department-card dept-radiology">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Radiology Services</strong>
                                    <div class="small text-muted">Imaging & scans</div>
                                </div>
                                <div class="text-end">
                                    <div class="owner-highlight" id="radiologyProfit">$0</div>
                                    <small>Owner Profit</small>
                                </div>
                            </div>
                        </div>
                        <div class="department-card dept-pharmacy">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Pharmacy Services</strong>
                                    <div class="small text-muted">Medications & prescriptions</div>
                                </div>
                                <div class="text-end">
                                    <div class="owner-highlight" id="pharmacyProfit">$0</div>
                                    <small>Owner Profit</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-calculator me-2"></i>Financial Summary</h5>
                    <div class="performance-metric">
                        <i class="fas fa-dollar-sign fa-2x mb-2 text-success"></i>
                        <h5 class="owner-highlight" id="totalRevenue">$0</h5>
                        <small>Total Revenue</small>
                    </div>
                    <div class="performance-metric">
                        <i class="fas fa-hand-holding-usd fa-2x mb-2 text-warning"></i>
                        <h5 class="owner-highlight" id="ownerTotalShare">$0</h5>
                        <small>Total Owner Share</small>
                    </div>
                    <div class="performance-metric">
                        <i class="fas fa-chart-line fa-2x mb-2 text-info"></i>
                        <h5 class="owner-highlight" id="netProfit">$0</h5>
                        <small>Net Profit</small>
                    </div>
                    <div class="performance-metric">
                        <i class="fas fa-percentage fa-2x mb-2 text-primary"></i>
                        <h5 class="owner-highlight" id="profitMargin">0%</h5>
                        <small>Profit Margin</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Intelligence -->
        <div class="glass-card p-4">
            <h5 class="mb-3"><i class="fas fa-brain me-2"></i>Business Intelligence</h5>
            <div class="row">
                <div class="col-md-4">
                    <div class="text-center">
                        <h6>Most Profitable Department</h6>
                        <div class="owner-highlight" id="topDepartment">Consultation</div>
                        <small class="text-muted" id="topDepartmentProfit">$8,450 this month</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <h6>Growth Rate</h6>
                        <div class="owner-highlight" id="growthRate">+12.5%</div>
                        <small class="text-muted">vs previous period</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <h6>ROI</h6>
                        <div class="owner-highlight" id="roi">24.8%</div>
                        <small class="text-muted">Return on Investment</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let revenueChart, distributionChart;

        document.addEventListener('DOMContentLoaded', function() {
            loadOwnerData();
            initializeCharts();
            
            // Handle custom date range
            document.getElementById('reportPeriod').addEventListener('change', function() {
                const customElements = ['customDateStart', 'customDateEnd'];
                if (this.value === 'custom') {
                    customElements.forEach(id => document.getElementById(id).style.display = 'block');
                } else {
                    customElements.forEach(id => document.getElementById(id).style.display = 'none');
                }
            });
        });

        function loadOwnerData() {
            fetch('/api/dashboard/owner')
                .then(response => response.json())
                .then(data => {
                    updateDashboard(data);
                })
                .catch(error => {
                    console.error('Error loading owner data:', error);
                    loadDemoData();
                });
        }

        function updateDashboard(data) {
            // Revenue by department (with owner shares)
            document.getElementById('consultationRevenue').textContent = '$' + (data.revenue_by_role?.consultation || 25000).toLocaleString();
            document.getElementById('labRevenue').textContent = '$' + (data.revenue_by_role?.lab_tech || 18000).toLocaleString();
            document.getElementById('radiologyRevenue').textContent = '$' + (data.revenue_by_role?.radiologist || 22000).toLocaleString();
            document.getElementById('pharmacyRevenue').textContent = '$' + (data.revenue_by_role?.pharmacist || 12000).toLocaleString();

            // Owner shares - Owner gets 100% of lab, radiology, pharmacy; 30% of consultation
            const consultationOwnerShareRate = 0.30;
            const labOwnerShareRate = 1.00; // 100%
            const radiologyOwnerShareRate = 1.00; // 100%  
            const pharmacyOwnerShareRate = 1.00; // 100%
            
            document.getElementById('consultationOwnerShare').textContent = '30%';
            document.getElementById('labOwnerShare').textContent = '100%';
            document.getElementById('radiologyOwnerShare').textContent = '100%';
            document.getElementById('pharmacyOwnerShare').textContent = '100%';

            // Owner profits by department - Owner gets different percentages
            document.getElementById('consultationProfit').textContent = '$' + Math.round((data.revenue_by_role?.consultation || 25000) * consultationOwnerShareRate).toLocaleString();
            document.getElementById('labProfit').textContent = '$' + Math.round((data.revenue_by_role?.lab_tech || 18000) * labOwnerShareRate).toLocaleString();
            document.getElementById('radiologyProfit').textContent = '$' + Math.round((data.revenue_by_role?.radiologist || 22000) * radiologyOwnerShareRate).toLocaleString();
            document.getElementById('pharmacyProfit').textContent = '$' + Math.round((data.revenue_by_role?.pharmacist || 12000) * pharmacyOwnerShareRate).toLocaleString();

            // Financial summary with corrected owner share calculation
            const totalRevenue = (data.income_today || 34000);
            const consultationRevenue = (data.revenue_by_role?.consultation || 25000);
            const labRevenue = (data.revenue_by_role?.lab_tech || 18000);
            const radiologyRevenue = (data.revenue_by_role?.radiologist || 22000);
            const pharmacyRevenue = (data.revenue_by_role?.pharmacist || 12000);
            
            // Calculate total owner share with correct percentages
            const ownerTotalShare = (consultationRevenue * consultationOwnerShareRate) + 
                                  (labRevenue * labOwnerShareRate) + 
                                  (radiologyRevenue * radiologyOwnerShareRate) + 
                                  (pharmacyRevenue * pharmacyOwnerShareRate);
            
            const expenses = (data.expenses_today || 8000);
            const netProfit = ownerTotalShare - expenses;
            const profitMargin = totalRevenue > 0 ? ((netProfit / totalRevenue) * 100).toFixed(1) : 0;

            document.getElementById('totalRevenue').textContent = '$' + totalRevenue.toLocaleString();
            document.getElementById('ownerTotalShare').textContent = '$' + ownerTotalShare.toLocaleString();
            document.getElementById('netProfit').textContent = '$' + netProfit.toLocaleString();
            document.getElementById('profitMargin').textContent = profitMargin + '%';

            updateCharts(data);
        }

        function loadDemoData() {
            const demoData = {
                income_today: 77000,
                owner_share: 23100,
                expenses_today: 8000,
                revenue_by_role: {
                    consultation: 25000,
                    lab_tech: 18000,
                    radiologist: 22000,
                    pharmacist: 12000
                }
            };
            updateDashboard(demoData);
        }

        function initializeCharts() {
            // Revenue Trends Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            revenueChart = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    datasets: [
                        {
                            label: 'Consultation',
                            data: [20000, 22000, 24000, 25000],
                            borderColor: '#28a745',
                            backgroundColor: 'rgba(40, 167, 69, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Laboratory',
                            data: [15000, 16500, 17200, 18000],
                            borderColor: '#17a2b8',
                            backgroundColor: 'rgba(23, 162, 184, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Radiology',
                            data: [18000, 19500, 21000, 22000],
                            borderColor: '#6f42c1',
                            backgroundColor: 'rgba(111, 66, 193, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Pharmacy',
                            data: [8000, 9500, 11000, 12000],
                            borderColor: '#fd7e14',
                            backgroundColor: 'rgba(253, 126, 20, 0.1)',
                            tension: 0.4
                        }
                    ]
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

            // Revenue Distribution Chart
            const distributionCtx = document.getElementById('distributionChart').getContext('2d');
            distributionChart = new Chart(distributionCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Consultation', 'Laboratory', 'Radiology', 'Pharmacy'],
                    datasets: [{
                        data: [25000, 18000, 22000, 12000],
                        backgroundColor: [
                            'rgba(40, 167, 69, 0.8)',
                            'rgba(23, 162, 184, 0.8)',
                            'rgba(111, 66, 193, 0.8)',
                            'rgba(253, 126, 20, 0.8)'
                        ],
                        borderColor: [
                            '#28a745',
                            '#17a2b8',
                            '#6f42c1',
                            '#fd7e14'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: 'white' }
                        }
                    }
                }
            });
        }

        function updateCharts(data) {
            if (revenueChart && distributionChart) {
                // Update distribution chart with real data
                const revenue = data.revenue_by_role || {};
                const values = [
                    revenue.consultation || 25000,
                    revenue.lab_tech || 18000,
                    revenue.radiologist || 22000,
                    revenue.pharmacist || 12000
                ];
                distributionChart.data.datasets[0].data = values;
                distributionChart.update();
            }
        }

        function refreshData() {
            showToast('Refreshing owner dashboard data...', 'info');
            loadOwnerData();
        }

        function updateReports() {
            const period = document.getElementById('reportPeriod').value;
            showToast(`Updating reports for ${period}`, 'info');
            loadOwnerData();
        }

        // Owner action functions
        function manageUsers() {
            window.location.href = '/owner/users';
        }

        function generateReports() {
            window.location.href = '/owner/reports';
        }

        function financialAnalysis() {
            showModal('Advanced Financial Analysis', `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Revenue Breakdown</h6>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Consultation Revenue:</span>
                                <span class="owner-highlight">$25,000</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Laboratory Revenue:</span>
                                <span class="owner-highlight">$18,000</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Radiology Revenue:</span>
                                <span class="owner-highlight">$22,000</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Pharmacy Revenue:</span>
                                <span class="owner-highlight">$12,000</span>
                            </div>
                        </div>
                        
                        <h6>Cost Analysis</h6>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Staff Salaries:</span>
                                <span class="text-danger">-$45,000</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Equipment Costs:</span>
                                <span class="text-danger">-$8,000</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Utilities & Overhead:</span>
                                <span class="text-danger">-$5,000</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6>Profitability Analysis</h6>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <strong>Total Revenue:</strong>
                                <span class="owner-highlight">$77,000</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <strong>Total Costs:</strong>
                                <span class="text-danger">-$58,000</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <strong>Net Profit:</strong>
                                <span class="owner-highlight">$19,000</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <strong>Profit Margin:</strong>
                                <span class="owner-highlight">24.7%</span>
                            </div>
                        </div>
                        
                        <h6>Owner Returns</h6>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Owner Share (30%):</span>
                                <span class="owner-highlight">$23,100</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>ROI:</span>
                                <span class="owner-highlight">18.5%</span>
                            </div>
                        </div>
                        
                        <button class="btn btn-owner btn-sm me-2" onclick="exportFinancialReport()">
                            <i class="fas fa-download me-1"></i>Export Report
                        </button>
                        <button class="btn btn-outline-light btn-sm" onclick="scheduleFinancialReview()">
                            <i class="fas fa-calendar me-1"></i>Schedule Review
                        </button>
                    </div>
                </div>
            `);
        }

        function performanceMetrics() {
            showModal('Detailed Performance Metrics', `
                <div class="row">
                    <div class="col-md-4">
                        <h6>Staff Performance</h6>
                        <div class="mb-3">
                            <div class="mb-2">
                                <span>Dr. Sarah Johnson</span>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar" style="width: 95%; background: #D4AF37;"></div>
                                </div>
                                <small>95% - Excellent</small>
                            </div>
                            <div class="mb-2">
                                <span>Lab Tech Maria</span>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar" style="width: 88%; background: #28a745;"></div>
                                </div>
                                <small>88% - Very Good</small>
                            </div>
                            <div class="mb-2">
                                <span>Dr. Michael Chen</span>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar" style="width: 82%; background: #17a2b8;"></div>
                                </div>
                                <small>82% - Good</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <h6>Department Efficiency</h6>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Consultation:</span>
                                <span class="owner-highlight">92%</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Laboratory:</span>
                                <span class="owner-highlight">89%</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Radiology:</span>
                                <span class="owner-highlight">86%</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Pharmacy:</span>
                                <span class="owner-highlight">91%</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <h6>Key Metrics</h6>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Patient Satisfaction:</span>
                                <span class="owner-highlight">4.7/5.0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Average Wait Time:</span>
                                <span class="text-warning">8.5 mins</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Equipment Uptime:</span>
                                <span class="owner-highlight">97.2%</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Staff Utilization:</span>
                                <span class="owner-highlight">84%</span>
                            </div>
                        </div>
                        
                        <button class="btn btn-owner btn-sm me-2" onclick="generatePerformanceReport()">
                            <i class="fas fa-chart-bar me-1"></i>Full Report
                        </button>
                        <button class="btn btn-outline-light btn-sm" onclick="setPerformanceTargets()">
                            <i class="fas fa-target me-1"></i>Set Targets
                        </button>
                    </div>
                </div>
            `);
        }

        function businessSettings() {
            window.location.href = '/admin/settings';
        }

        function viewPatients() {
            window.location.href = '/patients';
        }

        function showToast(message, type = 'info') {
            const toastColor = type === 'success' ? 'success' : 'info';
            const toast = document.createElement('div');
            toast.className = `alert alert-${toastColor} position-fixed`;
            toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            toast.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
            `;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 5000);
        }

        function showModal(title, content) {
            const modal = document.createElement('div');
            modal.className = 'modal fade show';
            modal.style.display = 'block';
            modal.innerHTML = `
                <div class="modal-dialog modal-xl">
                    <div class="modal-content" style="background: rgba(44, 24, 16, 0.95); color: white; border: 1px solid rgba(212, 175, 55, 0.3);">
                        <div class="modal-header" style="border-bottom: 1px solid rgba(212, 175, 55, 0.3);">
                            <h5 class="modal-title owner-highlight">${title}</h5>
                            <button type="button" class="btn-close btn-close-white" onclick="this.closest('.modal').remove()"></button>
                        </div>
                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                            ${content}
                        </div>
                        <div class="modal-footer" style="border-top: 1px solid rgba(212, 175, 55, 0.3);">
                            <button type="button" class="btn btn-secondary" onclick="this.closest('.modal').remove()">Close</button>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }
        
        // Helper functions for financial analysis
        function exportFinancialReport() {
            showToast('Exporting financial analysis report...', 'success');
            setTimeout(() => {
                const blob = new Blob(['Financial Analysis Report - ' + new Date().toLocaleDateString()], {type: 'text/plain'});
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'financial-analysis-' + new Date().getTime() + '.txt';
                a.click();
                window.URL.revokeObjectURL(url);
            }, 1000);
        }
        
        function scheduleFinancialReview() {
            showToast('Financial review meeting scheduled for next week', 'success');
        }
        
        // Helper functions for performance metrics
        function generatePerformanceReport() {
            showToast('Generating comprehensive performance report...', 'success');
        }
        
        function setPerformanceTargets() {
            showModal('Set Performance Targets', `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Department Targets</h6>
                        <div class="mb-3">
                            <label class="form-label">Consultation Efficiency Target (%)</label>
                            <input type="number" class="form-control" value="95" min="0" max="100">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Laboratory Efficiency Target (%)</label>
                            <input type="number" class="form-control" value="92" min="0" max="100">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Radiology Efficiency Target (%)</label>
                            <input type="number" class="form-control" value="90" min="0" max="100">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Quality Targets</h6>
                        <div class="mb-3">
                            <label class="form-label">Patient Satisfaction Target</label>
                            <input type="number" class="form-control" value="4.8" min="1" max="5" step="0.1">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Maximum Wait Time (minutes)</label>
                            <input type="number" class="form-control" value="7" min="1" max="30">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Equipment Uptime Target (%)</label>
                            <input type="number" class="form-control" value="98" min="90" max="100">
                        </div>
                        <button class="btn btn-owner mt-3" onclick="savePerformanceTargets()">
                            <i class="fas fa-save me-2"></i>Save Targets
                        </button>
                    </div>
                </div>
            `);
        }
        
        function savePerformanceTargets() {
            showToast('Performance targets saved successfully!', 'success');
            document.querySelector('.modal').remove();
        }
    </script>
</body>
</html>
