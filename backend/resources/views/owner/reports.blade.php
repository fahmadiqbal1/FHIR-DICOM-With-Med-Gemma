<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Business Reports - Owner Dashboard</title>
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
        .report-card {
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        .report-card:hover {
            transform: translateY(-5px);
        }
        .revenue-trend {
            font-size: 0.9rem;
        }
        .trend-up { color: #28a745; }
        .trend-down { color: #dc3545; }
        .report-preview {
            max-height: 400px;
            overflow-y: auto;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            padding: 15px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="/dashboard">
                <i class="fas fa-crown me-2"></i>Business Reports - Owner
            </a>
            <div class="ms-auto">
                <a href="/dashboard" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <!-- Report Generation Dashboard -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="glass-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4><i class="fas fa-chart-bar me-2"></i>Business Intelligence Reports</h4>
                        <div>
                            <button class="btn btn-outline-light me-2" onclick="scheduleReport()">
                                <i class="fas fa-clock me-2"></i>Schedule Report
                            </button>
                            <button class="btn btn-owner" onclick="generateCustomReport()">
                                <i class="fas fa-magic me-2"></i>Custom Report
                            </button>
                        </div>
                    </div>

                    <!-- Quick Report Generation -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="reportType" class="form-label">Report Type</label>
                                <select class="form-select" id="reportType" onchange="updateReportOptions()">
                                    <option value="financial">Financial Summary</option>
                                    <option value="department">Department Performance</option>
                                    <option value="staff">Staff Performance</option>
                                    <option value="patient">Patient Analytics</option>
                                    <option value="revenue">Revenue Analysis</option>
                                    <option value="profit">Profit & Loss</option>
                                    <option value="comparison">Comparative Analysis</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="dateRange" class="form-label">Date Range</label>
                                <select class="form-select" id="dateRange" onchange="toggleCustomDates()">
                                    <option value="today">Today</option>
                                    <option value="yesterday">Yesterday</option>
                                    <option value="week">This Week</option>
                                    <option value="month">This Month</option>
                                    <option value="quarter">This Quarter</option>
                                    <option value="year">This Year</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5" id="customDateRange" style="display: none;">
                            <div class="row">
                                <div class="col-6">
                                    <label for="startDate" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="startDate">
                                </div>
                                <div class="col-6">
                                    <label for="endDate" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="endDate">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-8">
                            <button class="btn btn-owner btn-lg me-2" onclick="generateReport()">
                                <i class="fas fa-file-alt me-2"></i>Generate Report
                            </button>
                            <button class="btn btn-outline-light me-2" onclick="previewReport()">
                                <i class="fas fa-eye me-2"></i>Preview
                            </button>
                            <button class="btn btn-outline-light" onclick="exportOptions()">
                                <i class="fas fa-download me-2"></i>Export Options
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Reports Grid -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="glass-card report-card p-3" onclick="generateQuickReport('daily')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6><i class="fas fa-calendar-day me-2"></i>Daily Revenue</h6>
                            <h4 class="owner-highlight mb-0">$4,250</h4>
                            <small class="revenue-trend trend-up">
                                <i class="fas fa-arrow-up me-1"></i>+12.5% from yesterday
                            </small>
                        </div>
                        <i class="fas fa-chart-line fa-2x" style="color: #D4AF37;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card report-card p-3" onclick="generateQuickReport('weekly')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6><i class="fas fa-calendar-week me-2"></i>Weekly Summary</h6>
                            <h4 class="owner-highlight mb-0">$28,750</h4>
                            <small class="revenue-trend trend-up">
                                <i class="fas fa-arrow-up me-1"></i>+8.3% from last week
                            </small>
                        </div>
                        <i class="fas fa-chart-area fa-2x" style="color: #D4AF37;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card report-card p-3" onclick="generateQuickReport('monthly')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6><i class="fas fa-calendar-alt me-2"></i>Monthly Report</h6>
                            <h4 class="owner-highlight mb-0">$125,400</h4>
                            <small class="revenue-trend trend-down">
                                <i class="fas fa-arrow-down me-1"></i>-2.1% from last month
                            </small>
                        </div>
                        <i class="fas fa-chart-pie fa-2x" style="color: #D4AF37;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Reports -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-building me-2"></i>Department Performance</h5>
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center mb-3" onclick="generateDepartmentReport('consultation')">
                                <i class="fas fa-user-md fa-3x text-primary mb-2"></i>
                                <h6>Consultation</h6>
                                <span class="owner-highlight">$45,200</span>
                                <br><small class="text-muted">This month</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center mb-3" onclick="generateDepartmentReport('laboratory')">
                                <i class="fas fa-flask fa-3x text-success mb-2"></i>
                                <h6>Laboratory</h6>
                                <span class="owner-highlight">$32,800</span>
                                <br><small class="text-muted">This month</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center mb-3" onclick="generateDepartmentReport('radiology')">
                                <i class="fas fa-x-ray fa-3x text-info mb-2"></i>
                                <h6>Radiology</h6>
                                <span class="owner-highlight">$28,500</span>
                                <br><small class="text-muted">This month</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center mb-3" onclick="generateDepartmentReport('pharmacy')">
                                <i class="fas fa-pills fa-3x text-warning mb-2"></i>
                                <h6>Pharmacy</h6>
                                <span class="owner-highlight">$18,900</span>
                                <br><small class="text-muted">This month</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-chart-bar me-2"></i>Financial Trends</h5>
                    <canvas id="trendChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Reports -->
        <div class="glass-card p-4">
            <h5 class="mb-3"><i class="fas fa-history me-2"></i>Recent Reports</h5>
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>Report Name</th>
                            <th>Type</th>
                            <th>Date Range</th>
                            <th>Generated</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Monthly Financial Summary</td>
                            <td><span class="badge bg-primary">Financial</span></td>
                            <td>Dec 1-31, 2023</td>
                            <td>2 hours ago</td>
                            <td><span class="badge bg-success">Ready</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-info me-1" onclick="viewReport('monthly-financial')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-success me-1" onclick="downloadReport('monthly-financial')">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-primary" onclick="shareReport('monthly-financial')">
                                    <i class="fas fa-share"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>Department Performance Analysis</td>
                            <td><span class="badge bg-info">Department</span></td>
                            <td>Last 7 days</td>
                            <td>1 day ago</td>
                            <td><span class="badge bg-success">Ready</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-info me-1" onclick="viewReport('dept-performance')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-success me-1" onclick="downloadReport('dept-performance')">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-primary" onclick="shareReport('dept-performance')">
                                    <i class="fas fa-share"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>Staff Revenue Report</td>
                            <td><span class="badge bg-warning">Staff</span></td>
                            <td>Q4 2023</td>
                            <td>3 days ago</td>
                            <td><span class="badge bg-warning">Processing</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-secondary" disabled>
                                    <i class="fas fa-spinner fa-spin"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Report Preview Modal -->
    <div class="modal fade" id="reportPreviewModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="background: rgba(44, 24, 16, 0.95); color: white;">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-file-alt me-2"></i>Report Preview</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="reportPreviewContent" class="report-preview">
                        <!-- Report content will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="downloadCurrentReport()">
                        <i class="fas fa-download me-2"></i>Download PDF
                    </button>
                    <button type="button" class="btn btn-owner" onclick="generateFullReport()">
                        <i class="fas fa-file-pdf me-2"></i>Generate Full Report
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeTrendChart();
        });

        function initializeTrendChart() {
            const ctx = document.getElementById('trendChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Revenue',
                        data: [85000, 92000, 78000, 105000, 118000, 125000],
                        borderColor: '#D4AF37',
                        backgroundColor: 'rgba(212, 175, 55, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Profit',
                        data: [25500, 27600, 23400, 31500, 35400, 37500],
                        borderColor: '#FFD700',
                        backgroundColor: 'rgba(255, 215, 0, 0.1)',
                        tension: 0.4,
                        fill: true
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
                            ticks: {
                                color: 'white',
                                callback: function(value) {
                                    return '$' + (value / 1000) + 'K';
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

        function updateReportOptions() {
            const reportType = document.getElementById('reportType').value;
            // Update form options based on report type
            console.log('Report type changed to:', reportType);
        }

        function toggleCustomDates() {
            const dateRange = document.getElementById('dateRange').value;
            const customDateRange = document.getElementById('customDateRange');
            if (dateRange === 'custom') {
                customDateRange.style.display = 'block';
            } else {
                customDateRange.style.display = 'none';
            }
        }

        function generateReport() {
            const reportType = document.getElementById('reportType').value;
            const dateRange = document.getElementById('dateRange').value;
            
            showToast(`Generating ${reportType} report for ${dateRange}...`, 'info');
            
            // Simulate report generation
            setTimeout(() => {
                showToast('Report generated successfully!', 'success');
            }, 2000);
        }

        function previewReport() {
            const modal = new bootstrap.Modal(document.getElementById('reportPreviewModal'));
            const content = document.getElementById('reportPreviewContent');
            
            content.innerHTML = `
                <div class="text-center mb-4">
                    <h3 class="owner-highlight">Business Financial Summary</h3>
                    <p class="text-muted">December 2023 Report Preview</p>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h5 class="owner-highlight">$125,400</h5>
                            <small>Total Revenue</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h5 class="owner-highlight">$37,620</h5>
                            <small>Net Profit</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h5 class="owner-highlight">30%</h5>
                            <small>Profit Margin</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h5 class="owner-highlight">+8.5%</h5>
                            <small>Growth Rate</small>
                        </div>
                    </div>
                </div>
                
                <h6 class="mb-3">Department Breakdown:</h6>
                <table class="table table-sm table-dark">
                    <thead>
                        <tr>
                            <th>Department</th>
                            <th>Revenue</th>
                            <th>Profit</th>
                            <th>Margin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Consultation</td>
                            <td>$45,200</td>
                            <td>$13,560</td>
                            <td>30%</td>
                        </tr>
                        <tr>
                            <td>Laboratory</td>
                            <td>$32,800</td>
                            <td>$9,840</td>
                            <td>30%</td>
                        </tr>
                        <tr>
                            <td>Radiology</td>
                            <td>$28,500</td>
                            <td>$8,550</td>
                            <td>30%</td>
                        </tr>
                        <tr>
                            <td>Pharmacy</td>
                            <td>$18,900</td>
                            <td>$5,670</td>
                            <td>30%</td>
                        </tr>
                    </tbody>
                </table>
            `;
            
            modal.show();
        }

        function generateQuickReport(period) {
            showToast(`Generating ${period} report...`, 'info');
        }

        function generateDepartmentReport(department) {
            showToast(`Generating ${department} department report...`, 'info');
        }

        function generateCustomReport() {
            showToast('Opening custom report builder...', 'info');
        }

        function scheduleReport() {
            showToast('Opening report scheduler...', 'info');
        }

        function exportOptions() {
            showToast('Available formats: PDF, Excel, CSV', 'info');
        }

        function viewReport(reportId) {
            showToast(`Opening report: ${reportId}`, 'info');
        }

        function downloadReport(reportId) {
            showToast(`Downloading report: ${reportId}`, 'success');
        }

        function shareReport(reportId) {
            showToast(`Sharing report: ${reportId}`, 'info');
        }

        function downloadCurrentReport() {
            showToast('Downloading current report as PDF...', 'success');
        }

        function generateFullReport() {
            showToast('Generating comprehensive report...', 'info');
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
    </script>
</body>
</html>
