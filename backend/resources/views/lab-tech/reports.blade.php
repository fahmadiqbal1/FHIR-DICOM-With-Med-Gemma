<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lab Reports - Lab Technician</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
        .navbar { 
            background: rgba(255, 255, 255, 0.1); 
            backdrop-filter: blur(10px); 
        }
        .btn-primary {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
        }
        .report-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .report-card:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
        }
        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #28a745;
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        .chart-container {
            position: relative;
            height: 300px;
            margin: 20px 0;
        }
        .modal-content {
            background: rgba(25, 25, 25, 0.95);
            backdrop-filter: blur(10px);
        }
        .report-stats {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="/lab-tech-dashboard">
                <i class="fas fa-chart-bar me-2"></i>Lab Reports
            </a>
            <div class="ms-auto">
                <button class="btn btn-primary btn-sm me-2" onclick="showCustomReportModal()">
                    <i class="fas fa-plus me-1"></i>Custom Report
                </button>
                <button class="btn btn-outline-light btn-sm me-2" onclick="exportAllReports()">
                    <i class="fas fa-download me-1"></i>Export All
                </button>
                <a href="/lab-tech-dashboard" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <!-- Report Summary Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="report-stats">
                        <i class="fas fa-flask fa-2x mb-2 text-success"></i>
                        <h4 id="totalTests">0</h4>
                        <small>Total Tests This Month</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="report-stats">
                        <i class="fas fa-percentage fa-2x mb-2 text-info"></i>
                        <h4 id="completionRate">0%</h4>
                        <small>Completion Rate</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="report-stats">
                        <i class="fas fa-stopwatch fa-2x mb-2 text-warning"></i>
                        <h4 id="avgTurnaround">0</h4>
                        <small>Avg Turnaround (hrs)</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="report-stats">
                        <i class="fas fa-shield-alt fa-2x mb-2 text-primary"></i>
                        <h4 id="qcPass">0%</h4>
                        <small>QC Pass Rate</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pre-built Reports -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-file-alt me-2"></i>Available Reports</h5>
                    <div class="row">
                        <div class="col-md-6 col-lg-4">
                            <div class="report-card" onclick="generateReport('daily')">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-day fa-2x me-3 text-success"></i>
                                    <div>
                                        <h6 class="mb-1">Daily Summary Report</h6>
                                        <small class="text-muted">Today's lab activity and performance</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="report-card" onclick="generateReport('weekly')">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-week fa-2x me-3 text-info"></i>
                                    <div>
                                        <h6 class="mb-1">Weekly Performance</h6>
                                        <small class="text-muted">7-day lab productivity analysis</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="report-card" onclick="generateReport('monthly')">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-alt fa-2x me-3 text-warning"></i>
                                    <div>
                                        <h6 class="mb-1">Monthly Statistics</h6>
                                        <small class="text-muted">Comprehensive monthly overview</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="report-card" onclick="generateReport('quality')">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-shield-alt fa-2x me-3 text-primary"></i>
                                    <div>
                                        <h6 class="mb-1">Quality Control Report</h6>
                                        <small class="text-muted">QC metrics and compliance data</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="report-card" onclick="generateReport('equipment')">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-cogs fa-2x me-3 text-secondary"></i>
                                    <div>
                                        <h6 class="mb-1">Equipment Utilization</h6>
                                        <small class="text-muted">Equipment usage and efficiency</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="report-card" onclick="generateReport('turnaround')">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-stopwatch fa-2x me-3 text-danger"></i>
                                    <div>
                                        <h6 class="mb-1">Turnaround Times</h6>
                                        <small class="text-muted">Test processing time analysis</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Visualization -->
        <div class="row">
            <div class="col-lg-8">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-chart-line me-2"></i>Lab Performance Trends</h5>
                    <div class="chart-container">
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-history me-2"></i>Recent Reports</h5>
                    <div id="recentReports">
                        <!-- Dynamic content -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Report Modal -->
    <div class="modal fade" id="customReportModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-chart-bar me-2"></i>Create Custom Report
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="customReportForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Report Name *</label>
                                <input type="text" class="form-control" id="reportName" required placeholder="Enter report name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Report Type *</label>
                                <select class="form-select" id="reportType" required>
                                    <option value="">Select Type</option>
                                    <option value="summary">Summary Report</option>
                                    <option value="detailed">Detailed Analysis</option>
                                    <option value="comparison">Comparison Report</option>
                                    <option value="trend">Trend Analysis</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Date From *</label>
                                <input type="date" class="form-control" id="dateFrom" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date To *</label>
                                <input type="date" class="form-control" id="dateTo" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Include Data</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="includeTests" checked>
                                        <label class="form-check-label" for="includeTests">Test Results</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="includeQC">
                                        <label class="form-check-label" for="includeQC">Quality Control</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="includeEquipment">
                                        <label class="form-check-label" for="includeEquipment">Equipment Data</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Output Format</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="format" id="formatPDF" value="pdf" checked>
                                    <label class="form-check-label" for="formatPDF">PDF</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="format" id="formatExcel" value="excel">
                                    <label class="form-check-label" for="formatExcel">Excel</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="format" id="formatCSV" value="csv">
                                    <label class="form-check-label" for="formatCSV">CSV</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="generateCustomReport()">
                        <i class="fas fa-download me-2"></i>Generate Report
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let reportsData = [];

        document.addEventListener('DOMContentLoaded', function() {
            loadStats();
            loadRecentReports();
            initializeChart();
            setDefaultDates();
        });

        function loadStats() {
            // Mock statistics - in production from API
            document.getElementById('totalTests').textContent = '1,247';
            document.getElementById('completionRate').textContent = '98.5%';
            document.getElementById('avgTurnaround').textContent = '2.4';
            document.getElementById('qcPass').textContent = '99.2%';
        }

        function loadRecentReports() {
            const recentReportsData = [
                { name: 'Daily Summary', generated: '2025-08-23 10:30', type: 'PDF' },
                { name: 'Weekly Performance', generated: '2025-08-22 16:45', type: 'Excel' },
                { name: 'QC Report', generated: '2025-08-22 14:20', type: 'PDF' },
                { name: 'Monthly Statistics', generated: '2025-08-21 09:15', type: 'PDF' }
            ];

            document.getElementById('recentReports').innerHTML = recentReportsData.map(report => `
                <div class="mb-3 p-3" style="background: rgba(255,255,255,0.05); border-radius: 8px;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <strong>${report.name}</strong>
                            <small class="d-block text-muted">${report.generated}</small>
                        </div>
                        <span class="badge bg-primary">${report.type}</span>
                    </div>
                    <button class="btn btn-sm btn-outline-light mt-2" onclick="downloadReport('${report.name}')">
                        <i class="fas fa-download me-1"></i>Download
                    </button>
                </div>
            `).join('');
        }

        function generateReport(type) {
            showAlert(`Generating ${type} report...`, 'info');
            
            // Simulate report generation
            setTimeout(() => {
                const reportName = getReportName(type);
                showAlert(`${reportName} generated successfully!`, 'success');
                loadRecentReports(); // Refresh recent reports
            }, 2000);
        }

        function getReportName(type) {
            const names = {
                'daily': 'Daily Summary Report',
                'weekly': 'Weekly Performance Report',
                'monthly': 'Monthly Statistics Report',
                'quality': 'Quality Control Report',
                'equipment': 'Equipment Utilization Report',
                'turnaround': 'Turnaround Times Report'
            };
            return names[type] || 'Custom Report';
        }

        function showCustomReportModal() {
            new bootstrap.Modal(document.getElementById('customReportModal')).show();
        }

        function generateCustomReport() {
            const form = document.getElementById('customReportForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const reportName = document.getElementById('reportName').value;
            const format = document.querySelector('input[name="format"]:checked').value;

            showAlert(`Generating custom report "${reportName}" in ${format.toUpperCase()} format...`, 'info');
            
            setTimeout(() => {
                showAlert(`Custom report "${reportName}" generated successfully!`, 'success');
                bootstrap.Modal.getInstance(document.getElementById('customReportModal')).hide();
                form.reset();
                setDefaultDates();
                loadRecentReports();
            }, 3000);
        }

        function exportAllReports() {
            showAlert('Exporting all available reports...', 'info');
            setTimeout(() => {
                showAlert('All reports exported successfully!', 'success');
            }, 2000);
        }

        function downloadReport(reportName) {
            showAlert(`Downloading ${reportName}...`, 'info');
            setTimeout(() => {
                showAlert(`${reportName} downloaded successfully!`, 'success');
            }, 1500);
        }

        function setDefaultDates() {
            const today = new Date();
            const lastWeek = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
            
            document.getElementById('dateTo').value = today.toISOString().split('T')[0];
            document.getElementById('dateFrom').value = lastWeek.toISOString().split('T')[0];
        }

        function initializeChart() {
            const ctx = document.getElementById('performanceChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [
                        {
                            label: 'Tests Completed',
                            data: [65, 78, 84, 92, 87, 75, 45],
                            borderColor: '#28a745',
                            backgroundColor: 'rgba(40, 167, 69, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'QC Passed',
                            data: [64, 77, 83, 91, 86, 74, 44],
                            borderColor: '#20c997',
                            backgroundColor: 'rgba(32, 201, 151, 0.1)',
                            tension: 0.4,
                            fill: true
                        }
                    ]
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
        }

        function showAlert(message, type = 'info') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
            alertDiv.style.zIndex = '9999';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 5000);
        }
    </script>
</body>
</html>
