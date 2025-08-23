<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>System Backup - Admin</title>
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
        .backup-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid;
        }
        .backup-success { border-left-color: #28a745; }
        .backup-pending { border-left-color: #ffc107; }
        .backup-failed { border-left-color: #dc3545; }
        .backup-running { border-left-color: #17a2b8; }
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
        .progress {
            background-color: rgba(255, 255, 255, 0.1);
        }
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        .storage-meter {
            background: rgba(255, 255, 255, 0.1);
            height: 20px;
            border-radius: 10px;
            overflow: hidden;
        }
        .storage-fill {
            height: 100%;
            background: linear-gradient(90deg, #28a745, #ffc107, #dc3545);
            transition: width 0.5s ease;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="/dashboard">
                <i class="fas fa-database me-2"></i>System Backup
            </a>
            <div class="ms-auto">
                <a href="/dashboard" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <!-- Backup Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                    <h5 id="successfulBackups">47</h5>
                    <small>Successful Backups</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-clock fa-2x mb-2 text-info"></i>
                    <h5 id="lastBackup">2 hours ago</h5>
                    <small>Last Backup</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-hdd fa-2x mb-2 text-warning"></i>
                    <h5 id="totalSize">2.4 GB</h5>
                    <small>Total Backup Size</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-calendar fa-2x mb-2 text-primary"></i>
                    <h5 id="nextBackup">Daily at 2:00 AM</h5>
                    <small>Next Scheduled</small>
                </div>
            </div>
        </div>

        <!-- Storage Information -->
        <div class="glass-card p-4 mb-4">
            <h5><i class="fas fa-server me-2"></i>Storage Information</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Available Storage</label>
                        <div class="storage-meter">
                            <div class="storage-fill" style="width: 68%;"></div>
                        </div>
                        <small class="text-muted">68% used (3.2 GB of 4.7 GB)</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Backup Retention</label>
                        <p class="mb-0">30 days • 12 weekly • 6 monthly backups retained</p>
                        <small class="text-muted">Automatic cleanup enabled</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Manual Backup -->
        <div class="glass-card p-4 mb-4">
            <h5><i class="fas fa-play-circle me-2"></i>Manual Backup</h5>
            <p>Create an immediate backup of your system data</p>
            <div class="row">
                <div class="col-md-8">
                    <h6>Backup Components</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="backupDatabase" checked>
                                <label class="form-check-label" for="backupDatabase">
                                    Database (1.8 GB)
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="backupFiles" checked>
                                <label class="form-check-label" for="backupFiles">
                                    Application Files (450 MB)
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="backupUploads" checked>
                                <label class="form-check-label" for="backupUploads">
                                    User Uploads (320 MB)
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="backupLogs" checked>
                                <label class="form-check-label" for="backupLogs">
                                    System Logs (85 MB)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <button class="btn btn-primary btn-lg" onclick="startManualBackup()" id="manualBackupBtn">
                        <i class="fas fa-download me-2"></i>Start Backup
                    </button>
                    <div id="backupProgress" style="display: none;" class="mt-3">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                 role="progressbar" id="progressBar" style="width: 0%"></div>
                        </div>
                        <small id="progressText" class="text-muted">Preparing backup...</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Backup Schedule -->
        <div class="glass-card p-4 mb-4">
            <h5><i class="fas fa-calendar-alt me-2"></i>Backup Schedule</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Frequency</label>
                        <select class="form-select" id="backupFrequency">
                            <option value="daily" selected>Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Backup Time</label>
                        <input type="time" class="form-control" value="02:00">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Retention Period</label>
                        <select class="form-select">
                            <option value="30" selected>30 days</option>
                            <option value="60">60 days</option>
                            <option value="90">90 days</option>
                            <option value="180">6 months</option>
                            <option value="365">1 year</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                            <label class="form-check-label" for="emailNotifications">
                                Email Notifications
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-outline-light" onclick="updateSchedule()">
                <i class="fas fa-save me-2"></i>Update Schedule
            </button>
        </div>

        <!-- Recent Backups -->
        <div class="glass-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="fas fa-history me-2"></i>Recent Backups</h5>
                <button class="btn btn-outline-light btn-sm" onclick="refreshBackupList()">
                    <i class="fas fa-sync me-1"></i>Refresh
                </button>
            </div>
            
            <div id="backupList">
                <!-- Recent Backup Items -->
                <div class="backup-item backup-success">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Successful</strong>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Today, 2:00 AM</small><br>
                            <span>backup_2025_08_23_020000.zip</span>
                        </div>
                        <div class="col-md-2">
                            <span class="badge bg-secondary">2.4 GB</span>
                        </div>
                        <div class="col-md-2">
                            <span class="text-muted">Duration: 4m 32s</span>
                        </div>
                        <div class="col-md-2 text-end">
                            <button class="btn btn-outline-light btn-sm me-1" onclick="downloadBackup('backup_2025_08_23_020000.zip')">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="btn btn-outline-light btn-sm" onclick="restoreBackup('backup_2025_08_23_020000.zip')">
                                <i class="fas fa-undo"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="backup-item backup-success">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Successful</strong>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Yesterday, 2:00 AM</small><br>
                            <span>backup_2025_08_22_020000.zip</span>
                        </div>
                        <div class="col-md-2">
                            <span class="badge bg-secondary">2.3 GB</span>
                        </div>
                        <div class="col-md-2">
                            <span class="text-muted">Duration: 4m 18s</span>
                        </div>
                        <div class="col-md-2 text-end">
                            <button class="btn btn-outline-light btn-sm me-1" onclick="downloadBackup('backup_2025_08_22_020000.zip')">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="btn btn-outline-light btn-sm" onclick="restoreBackup('backup_2025_08_22_020000.zip')">
                                <i class="fas fa-undo"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="backup-item backup-success">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Successful</strong>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">2 days ago, 2:00 AM</small><br>
                            <span>backup_2025_08_21_020000.zip</span>
                        </div>
                        <div class="col-md-2">
                            <span class="badge bg-secondary">2.2 GB</span>
                        </div>
                        <div class="col-md-2">
                            <span class="text-muted">Duration: 4m 05s</span>
                        </div>
                        <div class="col-md-2 text-end">
                            <button class="btn btn-outline-light btn-sm me-1" onclick="downloadBackup('backup_2025_08_21_020000.zip')">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="btn btn-outline-light btn-sm" onclick="restoreBackup('backup_2025_08_21_020000.zip')">
                                <i class="fas fa-undo"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="backup-item backup-failed">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <i class="fas fa-times-circle text-danger me-2"></i>
                            <strong>Failed</strong>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">3 days ago, 2:00 AM</small><br>
                            <span class="text-danger">backup_failed_2025_08_20</span>
                        </div>
                        <div class="col-md-2">
                            <span class="badge bg-danger">Error</span>
                        </div>
                        <div class="col-md-2">
                            <span class="text-muted">Duration: 0m 12s</span>
                        </div>
                        <div class="col-md-2 text-end">
                            <button class="btn btn-outline-light btn-sm" onclick="viewError('backup_failed_2025_08_20')">
                                <i class="fas fa-exclamation-triangle"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Show More Button -->
            <div class="text-center mt-3">
                <button class="btn btn-outline-light btn-sm" onclick="loadMoreBackups()">
                    <i class="fas fa-chevron-down me-2"></i>Show More Backups
                </button>
            </div>
        </div>
    </div>

    <!-- Restore Confirmation Modal -->
    <div class="modal fade" id="restoreModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="background: rgba(0, 0, 0, 0.9); color: white;">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Restore</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Warning:</strong> This action will restore the selected backup and may overwrite current data.</p>
                    <p>Are you sure you want to restore backup: <strong id="restoreBackupName"></strong>?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i>
                        It's recommended to create a current backup before restoring.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" onclick="confirmRestore()">
                        <i class="fas fa-undo me-2"></i>Restore Backup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentRestoreFile = '';

        function startManualBackup() {
            const btn = document.getElementById('manualBackupBtn');
            const progress = document.getElementById('backupProgress');
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Backup...';
            progress.style.display = 'block';

            // Simulate backup progress
            let percent = 0;
            const interval = setInterval(() => {
                percent += Math.random() * 15;
                if (percent > 100) percent = 100;

                progressBar.style.width = percent + '%';
                
                if (percent < 25) {
                    progressText.textContent = 'Preparing database backup...';
                } else if (percent < 50) {
                    progressText.textContent = 'Backing up application files...';
                } else if (percent < 75) {
                    progressText.textContent = 'Compressing backup archive...';
                } else if (percent < 100) {
                    progressText.textContent = 'Finalizing backup...';
                } else {
                    progressText.textContent = 'Backup completed successfully!';
                    clearInterval(interval);
                    
                    setTimeout(() => {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-download me-2"></i>Start Backup';
                        progress.style.display = 'none';
                        progressBar.style.width = '0%';
                        showToast('Manual backup created successfully!', 'success');
                    }, 2000);
                }
            }, 200);
        }

        function downloadBackup(filename) {
            showToast('Preparing backup download: ' + filename, 'info');
            // Implement download functionality
        }

        function restoreBackup(filename) {
            currentRestoreFile = filename;
            document.getElementById('restoreBackupName').textContent = filename;
            new bootstrap.Modal(document.getElementById('restoreModal')).show();
        }

        function confirmRestore() {
            bootstrap.Modal.getInstance(document.getElementById('restoreModal')).hide();
            showToast('Restore process started for: ' + currentRestoreFile, 'info');
            // Implement restore functionality
        }

        function viewError(filename) {
            showToast('Error details: Database connection timeout during backup', 'error');
        }

        function updateSchedule() {
            showToast('Backup schedule updated successfully!', 'success');
        }

        function refreshBackupList() {
            showToast('Backup list refreshed!', 'success');
        }

        function loadMoreBackups() {
            showToast('Loading additional backup history...', 'info');
        }

        function showToast(message, type = 'info') {
            const toastColor = type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info';
            const toast = document.createElement('div');
            toast.className = `alert alert-${toastColor} position-fixed`;
            toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            toast.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
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
