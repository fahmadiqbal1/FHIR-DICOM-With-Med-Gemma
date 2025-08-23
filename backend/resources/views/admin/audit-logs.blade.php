<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Audit Logs - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
        .navbar {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        .log-entry {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
            border-left: 4px solid;
        }
        .log-success { border-left-color: #28a745; }
        .log-warning { border-left-color: #ffc107; }
        .log-danger { border-left-color: #dc3545; }
        .log-info { border-left-color: #17a2b8; }
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }
        .form-control::placeholder { color: rgba(255, 255, 255, 0.7); }
        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #667eea;
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .table-dark {
            --bs-table-bg: rgba(255, 255, 255, 0.05);
            --bs-table-border-color: rgba(255, 255, 255, 0.1);
        }
        .pagination .page-link {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            color: white;
        }
        .pagination .page-link:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }
        .pagination .page-item.active .page-link {
            background: #667eea;
            border-color: #667eea;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="/dashboard">
                <i class="fas fa-history me-2"></i>Audit Logs
            </a>
            <div class="ms-auto">
                <a href="/dashboard" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <!-- Filters -->
        <div class="glass-card p-4 mb-4">
            <h5><i class="fas fa-filter me-2"></i>Filter Logs</h5>
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Date Range</label>
                    <select class="form-select" id="dateFilter">
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month" selected>This Month</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Log Level</label>
                    <select class="form-select" id="levelFilter">
                        <option value="">All Levels</option>
                        <option value="info">Info</option>
                        <option value="warning">Warning</option>
                        <option value="error">Error</option>
                        <option value="success">Success</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">User</label>
                    <select class="form-select" id="userFilter">
                        <option value="">All Users</option>
                        <option value="admin">Admin</option>
                        <option value="doctor">Doctor</option>
                        <option value="lab-tech">Lab Tech</option>
                        <option value="radiologist">Radiologist</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Action</label>
                    <input type="text" class="form-control" id="actionFilter" placeholder="Search actions...">
                </div>
            </div>
            <div class="mt-3">
                <button class="btn btn-primary" onclick="applyFilters()">
                    <i class="fas fa-search me-2"></i>Apply Filters
                </button>
                <button class="btn btn-outline-light" onclick="clearFilters()">
                    <i class="fas fa-times me-2"></i>Clear
                </button>
                <button class="btn btn-outline-light ms-2" onclick="exportLogs()">
                    <i class="fas fa-download me-2"></i>Export
                </button>
            </div>
        </div>

        <!-- Log Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-info-circle fa-2x mb-2 text-info"></i>
                    <h5 id="infoCount">245</h5>
                    <small>Info Logs</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2 text-warning"></i>
                    <h5 id="warningCount">12</h5>
                    <small>Warning Logs</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-times-circle fa-2x mb-2 text-danger"></i>
                    <h5 id="errorCount">3</h5>
                    <small>Error Logs</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                    <h5 id="successCount">189</h5>
                    <small>Success Logs</small>
                </div>
            </div>
        </div>

        <!-- Recent Activity Summary -->
        <div class="glass-card p-4 mb-4">
            <h5><i class="fas fa-clock me-2"></i>Recent Activity Summary</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="log-entry log-success">
                        <div class="d-flex justify-content-between">
                            <strong>User Login</strong>
                            <span class="badge bg-success">Success</span>
                        </div>
                        <small class="text-muted">doctor1@medgemma.com - 2 minutes ago</small>
                    </div>
                    <div class="log-entry log-info">
                        <div class="d-flex justify-content-between">
                            <strong>Patient Record Access</strong>
                            <span class="badge bg-info">Info</span>
                        </div>
                        <small class="text-muted">labtech@medgemma.com - 5 minutes ago</small>
                    </div>
                    <div class="log-entry log-warning">
                        <div class="d-flex justify-content-between">
                            <strong>Failed Login Attempt</strong>
                            <span class="badge bg-warning">Warning</span>
                        </div>
                        <small class="text-muted">unknown@example.com - 10 minutes ago</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="log-entry log-success">
                        <div class="d-flex justify-content-between">
                            <strong>Test Results Submitted</strong>
                            <span class="badge bg-success">Success</span>
                        </div>
                        <small class="text-muted">labtech@medgemma.com - 12 minutes ago</small>
                    </div>
                    <div class="log-entry log-info">
                        <div class="d-flex justify-content-between">
                            <strong>Report Generated</strong>
                            <span class="badge bg-info">Info</span>
                        </div>
                        <small class="text-muted">admin@medgemma.com - 15 minutes ago</small>
                    </div>
                    <div class="log-entry log-success">
                        <div class="d-flex justify-content-between">
                            <strong>System Backup</strong>
                            <span class="badge bg-success">Success</span>
                        </div>
                        <small class="text-muted">System - 30 minutes ago</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Logs Table -->
        <div class="glass-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="fas fa-list me-2"></i>Detailed Audit Logs</h5>
                <div>
                    <button class="btn btn-outline-light btn-sm" onclick="refreshLogs()">
                        <i class="fas fa-sync me-1"></i>Refresh
                    </button>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>Level</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>IP Address</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody id="logsTableBody">
                        <tr>
                            <td>2025-08-23 14:30:25</td>
                            <td><span class="badge bg-success">Success</span></td>
                            <td>doctor1@medgemma.com</td>
                            <td>User Login</td>
                            <td>192.168.1.100</td>
                            <td>
                                <button class="btn btn-outline-light btn-sm" onclick="viewDetails(1)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>2025-08-23 14:25:10</td>
                            <td><span class="badge bg-info">Info</span></td>
                            <td>labtech@medgemma.com</td>
                            <td>Test Result Entry</td>
                            <td>192.168.1.105</td>
                            <td>
                                <button class="btn btn-outline-light btn-sm" onclick="viewDetails(2)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>2025-08-23 14:20:15</td>
                            <td><span class="badge bg-warning">Warning</span></td>
                            <td>unknown@example.com</td>
                            <td>Failed Login</td>
                            <td>203.0.113.25</td>
                            <td>
                                <button class="btn btn-outline-light btn-sm" onclick="viewDetails(3)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>2025-08-23 14:15:30</td>
                            <td><span class="badge bg-success">Success</span></td>
                            <td>admin@medgemma.com</td>
                            <td>User Created</td>
                            <td>192.168.1.1</td>
                            <td>
                                <button class="btn btn-outline-light btn-sm" onclick="viewDetails(4)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>2025-08-23 14:10:45</td>
                            <td><span class="badge bg-danger">Error</span></td>
                            <td>System</td>
                            <td>Database Connection Failed</td>
                            <td>127.0.0.1</td>
                            <td>
                                <button class="btn btn-outline-light btn-sm" onclick="viewDetails(5)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Log Details Modal -->
    <div class="modal fade" id="logDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background: rgba(0, 0, 0, 0.9); color: white;">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Log Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="logDetailsContent">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function applyFilters() {
            const dateFilter = document.getElementById('dateFilter').value;
            const levelFilter = document.getElementById('levelFilter').value;
            const userFilter = document.getElementById('userFilter').value;
            const actionFilter = document.getElementById('actionFilter').value;
            
            // Implement filtering logic
            showToast('Filters applied successfully!', 'success');
        }

        function clearFilters() {
            document.getElementById('dateFilter').value = 'month';
            document.getElementById('levelFilter').value = '';
            document.getElementById('userFilter').value = '';
            document.getElementById('actionFilter').value = '';
            showToast('Filters cleared!', 'info');
        }

        function exportLogs() {
            // Implement export functionality
            showToast('Audit logs exported successfully!', 'success');
        }

        function refreshLogs() {
            // Implement log refresh
            showToast('Logs refreshed!', 'success');
        }

        function viewDetails(logId) {
            // Sample log details
            const logDetails = {
                1: {
                    timestamp: '2025-08-23 14:30:25',
                    level: 'Success',
                    user: 'doctor1@medgemma.com',
                    action: 'User Login',
                    ip: '192.168.1.100',
                    userAgent: 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
                    sessionId: 'sess_123456789',
                    details: 'User successfully authenticated via email/password'
                },
                2: {
                    timestamp: '2025-08-23 14:25:10',
                    level: 'Info',
                    user: 'labtech@medgemma.com',
                    action: 'Test Result Entry',
                    ip: '192.168.1.105',
                    userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    sessionId: 'sess_987654321',
                    details: 'Lab test results entered for Patient ID: PAT001, Test: Complete Blood Count'
                }
                // Add more sample data as needed
            };

            const log = logDetails[logId] || logDetails[1]; // Fallback to first entry
            
            document.getElementById('logDetailsContent').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Timestamp:</strong><br>${log.timestamp}</p>
                        <p><strong>Level:</strong><br><span class="badge bg-${log.level.toLowerCase()}">${log.level}</span></p>
                        <p><strong>User:</strong><br>${log.user}</p>
                        <p><strong>Action:</strong><br>${log.action}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>IP Address:</strong><br>${log.ip}</p>
                        <p><strong>Session ID:</strong><br><code>${log.sessionId}</code></p>
                        <p><strong>User Agent:</strong><br><small>${log.userAgent}</small></p>
                    </div>
                </div>
                <hr>
                <p><strong>Details:</strong></p>
                <p class="text-muted">${log.details}</p>
            `;
            
            new bootstrap.Modal(document.getElementById('logDetailsModal')).show();
        }

        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `alert alert-${type === 'success' ? 'success' : 'info'} position-fixed`;
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

        // Auto-refresh logs every 30 seconds
        setInterval(refreshLogs, 30000);
    </script>
</body>
</html>
