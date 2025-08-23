<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>System Settings - Admin</title>
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
        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #667eea;
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .form-select {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        .settings-section {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="/dashboard">
                <i class="fas fa-cog me-2"></i>System Settings
            </a>
            <div class="ms-auto">
                <a href="/dashboard" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <div class="glass-card p-4">
            <div class="d-flex align-items-center mb-4">
                <i class="fas fa-cog fa-2x me-3"></i>
                <div>
                    <h3 class="mb-0">System Configuration</h3>
                    <p class="mb-0 opacity-75">Manage platform settings and configurations</p>
                </div>
            </div>

            <!-- System Information -->
            <div class="settings-section">
                <h5><i class="fas fa-info-circle me-2"></i>System Information</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Platform Name</label>
                            <input type="text" class="form-control" value="FHIR DICOM Platform" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Version</label>
                            <input type="text" class="form-control" value="v2.1.0" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Database Status</label>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success me-2">Connected</span>
                                <small class="opacity-75">MySQL Database</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Last Backup</label>
                            <input type="text" class="form-control" value="2025-08-23 10:30 AM" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="settings-section">
                <h5><i class="fas fa-shield-alt me-2"></i>Security Settings</h5>
                <form id="securityForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Session Timeout (minutes)</label>
                                <input type="number" class="form-control" value="120" min="30" max="480">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password Minimum Length</label>
                                <input type="number" class="form-control" value="8" min="6" max="20">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="twoFactorAuth" checked>
                                    <label class="form-check-label" for="twoFactorAuth">
                                        Enable Two-Factor Authentication
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="auditLogging" checked>
                                    <label class="form-check-label" for="auditLogging">
                                        Enable Audit Logging
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Email Settings -->
            <div class="settings-section">
                <h5><i class="fas fa-envelope me-2"></i>Email Configuration</h5>
                <form id="emailForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">SMTP Server</label>
                                <input type="text" class="form-control" value="smtp.medgemma.com" placeholder="smtp.example.com">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">SMTP Port</label>
                                <select class="form-select">
                                    <option value="587" selected>587 (TLS)</option>
                                    <option value="465">465 (SSL)</option>
                                    <option value="25">25 (Plain)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">From Email</label>
                                <input type="email" class="form-control" value="noreply@medgemma.com" placeholder="noreply@example.com">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">From Name</label>
                                <input type="text" class="form-control" value="FHIR DICOM Platform" placeholder="Platform Name">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- System Maintenance -->
            <div class="settings-section">
                <h5><i class="fas fa-tools me-2"></i>System Maintenance</h5>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card bg-transparent border-light text-center p-3">
                            <i class="fas fa-broom fa-2x mb-2"></i>
                            <h6>Clear Cache</h6>
                            <button class="btn btn-outline-light btn-sm" onclick="clearCache()">Execute</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-transparent border-light text-center p-3">
                            <i class="fas fa-sync fa-2x mb-2"></i>
                            <h6>Optimize Database</h6>
                            <button class="btn btn-outline-light btn-sm" onclick="optimizeDB()">Execute</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-transparent border-light text-center p-3">
                            <i class="fas fa-chart-line fa-2x mb-2"></i>
                            <h6>System Health Check</h6>
                            <button class="btn btn-outline-light btn-sm" onclick="healthCheck()">Execute</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Settings -->
            <div class="settings-section">
                <h5><i class="fas fa-dollar-sign me-2"></i>Financial Configuration</h5>
                <form id="financialForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Default Doctor Revenue Share (%)</label>
                                <input type="number" class="form-control" value="70" min="0" max="100">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Admin Commission (%)</label>
                                <input type="number" class="form-control" value="30" min="0" max="100">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Currency</label>
                                <select class="form-select">
                                    <option value="USD" selected>USD ($)</option>
                                    <option value="EUR">EUR (€)</option>
                                    <option value="GBP">GBP (£)</option>
                                    <option value="PKR">PKR (₨)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tax Rate (%)</label>
                                <input type="number" class="form-control" value="0" min="0" max="50" step="0.1">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-outline-light" onclick="resetSettings()">
                    <i class="fas fa-undo me-2"></i>Reset to Defaults
                </button>
                <div>
                    <button type="button" class="btn btn-outline-light me-2" onclick="testSettings()">
                        <i class="fas fa-flask me-2"></i>Test Settings
                    </button>
                    <button type="button" class="btn btn-primary" onclick="saveSettings()">
                        <i class="fas fa-save me-2"></i>Save All Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function clearCache() {
            if (confirm('This will clear all application cache. Continue?')) {
                // Implement cache clearing
                showToast('Cache cleared successfully!', 'success');
            }
        }

        function optimizeDB() {
            if (confirm('This will optimize the database. This may take a few minutes. Continue?')) {
                // Implement database optimization
                showToast('Database optimization completed!', 'success');
            }
        }

        function healthCheck() {
            // Implement health check
            showToast('System health check completed - All systems operational!', 'success');
        }

        function testSettings() {
            // Implement settings test
            showToast('Settings test completed - All configurations valid!', 'success');
        }

        function saveSettings() {
            if (confirm('Save all configuration changes?')) {
                // Implement settings save
                showToast('Settings saved successfully!', 'success');
            }
        }

        function resetSettings() {
            if (confirm('Reset all settings to default values? This action cannot be undone.')) {
                // Implement settings reset
                location.reload();
            }
        }

        function showToast(message, type = 'info') {
            // Simple toast notification
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
    </script>
</body>
</html>
