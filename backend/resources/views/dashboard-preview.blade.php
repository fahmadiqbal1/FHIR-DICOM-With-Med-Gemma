<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Preview Tool</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            background: #2563eb;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .dashboard-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border: 2px solid transparent;
        }
        .dashboard-card.active {
            border-color: #10b981;
            background-color: #f0fdf4;
        }
        .dashboard-card.duplicate {
            border-color: #f59e0b;
            background-color: #fffbeb;
        }
        .dashboard-card.empty {
            border-color: #ef4444;
            background-color: #fef2f2;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .card-title {
            font-weight: bold;
            color: #1f2937;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-active { background: #10b981; color: white; }
        .status-duplicate { background: #f59e0b; color: white; }
        .status-empty { background: #ef4444; color: white; }
        .file-info {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 10px;
        }
        .preview-btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
        }
        .preview-btn:hover {
            background: #1d4ed8;
        }
        .legend {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            padding: 15px;
            background: white;
            border-radius: 8px;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 3px;
        }
        .active-color { background: #10b981; }
        .duplicate-color { background: #f59e0b; }
        .empty-color { background: #ef4444; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Dashboard Preview Tool</h1>
            <p>Review all dashboard files before cleanup. Click "Preview" to see each dashboard in a new tab.</p>
        </div>

        <div class="legend">
            <div class="legend-item">
                <div class="legend-color active-color"></div>
                <span><strong>Active:</strong> Currently used by the application</span>
            </div>
            <div class="legend-item">
                <div class="legend-color duplicate-color"></div>
                <span><strong>Duplicate:</strong> Backup/old versions</span>
            </div>
            <div class="legend-item">
                <div class="legend-color empty-color"></div>
                <span><strong>Empty:</strong> 0 bytes - safe to delete</span>
            </div>
        </div>

        <div class="dashboard-grid">
            <!-- ACTIVE DASHBOARDS -->
            <div class="dashboard-card active">
                <div class="card-header">
                    <div class="card-title">Owner Dashboard</div>
                    <span class="status-badge status-active">ACTIVE</span>
                </div>
                <div class="file-info">owner-dashboard.blade.php (209 KB)</div>
                <div class="file-info">Main dashboard with comprehensive financial data</div>
                <a href="/dashboard-file-preview/owner-dashboard" target="_blank" class="preview-btn">Preview</a>
            </div>

            <div class="dashboard-card active">
                <div class="card-header">
                    <div class="card-title">Admin Dashboard</div>
                    <span class="status-badge status-active">ACTIVE</span>
                </div>
                <div class="file-info">admin-dashboard.blade.php (16 KB)</div>
                <div class="file-info">Administrative control panel</div>
                <a href="/dashboard-file-preview/admin-dashboard" target="_blank" class="preview-btn">Preview</a>
            </div>

            <div class="dashboard-card active">
                <div class="card-header">
                    <div class="card-title">Doctor Dashboard</div>
                    <span class="status-badge status-active">ACTIVE</span>
                </div>
                <div class="file-info">doctor-dashboard.blade.php (21 KB)</div>
                <div class="file-info">Doctor interface with patient management</div>
                <a href="/dashboard-file-preview/doctor-dashboard" target="_blank" class="preview-btn">Preview</a>
            </div>

            <div class="dashboard-card active">
                <div class="card-header">
                    <div class="card-title">Radiologist Dashboard</div>
                    <span class="status-badge status-active">ACTIVE</span>
                </div>
                <div class="file-info">radiologist-dashboard.blade.php (50 KB)</div>
                <div class="file-info">DICOM viewer and radiology tools</div>
                <a href="/dashboard-file-preview/radiologist-dashboard" target="_blank" class="preview-btn">Preview</a>
            </div>

            <div class="dashboard-card active">
                <div class="card-header">
                    <div class="card-title">Lab Tech Dashboard</div>
                    <span class="status-badge status-active">ACTIVE</span>
                </div>
                <div class="file-info">lab-tech-dashboard.blade.php (18 KB)</div>
                <div class="file-info">Lab equipment and test configuration</div>
                <a href="/dashboard-file-preview/lab-tech-dashboard" target="_blank" class="preview-btn">Preview</a>
            </div>

            <div class="dashboard-card active">
                <div class="card-header">
                    <div class="card-title">Pharmacist Dashboard</div>
                    <span class="status-badge status-active">ACTIVE</span>
                </div>
                <div class="file-info">pharmacist-dashboard.blade.php (21 KB)</div>
                <div class="file-info">Prescription and inventory management</div>
                <a href="/dashboard-file-preview/pharmacist-dashboard" target="_blank" class="preview-btn">Preview</a>
            </div>

            <div class="dashboard-card active">
                <div class="card-header">
                    <div class="card-title">General Dashboard</div>
                    <span class="status-badge status-active">ACTIVE</span>
                </div>
                <div class="file-info">dashboard.blade.php (17 KB)</div>
                <div class="file-info">Fallback dashboard for undefined roles</div>
                <a href="/dashboard-file-preview/dashboard" target="_blank" class="preview-btn">Preview</a>
            </div>

            <!-- DUPLICATE DASHBOARDS -->
            <div class="dashboard-card duplicate">
                <div class="card-header">
                    <div class="card-title">Admin Dashboard Alt</div>
                    <span class="status-badge status-duplicate">DUPLICATE</span>
                </div>
                <div class="file-info">dashboard-admin.blade.php (9 KB)</div>
                <div class="file-info">Alternative admin dashboard version</div>
                <a href="/dashboard-file-preview/dashboard-admin" target="_blank" class="preview-btn">Preview</a>
            </div>

            <div class="dashboard-card duplicate">
                <div class="card-header">
                    <div class="card-title">Doctor Enhanced</div>
                    <span class="status-badge status-duplicate">DUPLICATE</span>
                </div>
                <div class="file-info">doctor-enhanced-dashboard.blade.php (29 KB)</div>
                <div class="file-info">Enhanced doctor dashboard with extra features</div>
                <a href="/dashboard-file-preview/doctor-enhanced-dashboard" target="_blank" class="preview-btn">Preview</a>
            </div>

            <div class="dashboard-card duplicate">
                <div class="card-header">
                    <div class="card-title">Doctor Financial</div>
                    <span class="status-badge status-duplicate">DUPLICATE</span>
                </div>
                <div class="file-info">doctor-financial-dashboard.blade.php (13 KB)</div>
                <div class="file-info">Doctor financial analytics version</div>
                <a href="/dashboard-file-preview/doctor-financial-dashboard" target="_blank" class="preview-btn">Preview</a>
            </div>

            <div class="dashboard-card duplicate">
                <div class="card-header">
                    <div class="card-title">Doctor Unified</div>
                    <span class="status-badge status-duplicate">DUPLICATE</span>
                </div>
                <div class="file-info">doctor-dashboard-unified.blade.php (20 KB)</div>
                <div class="file-info">Unified doctor dashboard version</div>
                <a href="/dashboard-file-preview/doctor-dashboard-unified" target="_blank" class="preview-btn">Preview</a>
            </div>

            <div class="dashboard-card duplicate">
                <div class="card-header">
                    <div class="card-title">Lab Dashboard Unified</div>
                    <span class="status-badge status-duplicate">DUPLICATE</span>
                </div>
                <div class="file-info">lab-dashboard-unified.blade.php (22 KB)</div>
                <div class="file-info">Unified lab dashboard version</div>
                <a href="/dashboard-file-preview/lab-dashboard-unified" target="_blank" class="preview-btn">Preview</a>
            </div>

            <div class="dashboard-card duplicate">
                <div class="card-header">
                    <div class="card-title">Lab Tech Clean</div>
                    <span class="status-badge status-duplicate">DUPLICATE</span>
                </div>
                <div class="file-info">lab-tech-dashboard-clean.blade.php (18 KB)</div>
                <div class="file-info">Clean version of lab tech dashboard</div>
                <a href="/dashboard-file-preview/lab-tech-dashboard-clean" target="_blank" class="preview-btn">Preview</a>
            </div>

            <div class="dashboard-card duplicate">
                <div class="card-header">
                    <div class="card-title">Pharmacist Unified</div>
                    <span class="status-badge status-duplicate">DUPLICATE</span>
                </div>
                <div class="file-info">pharmacist-dashboard-unified.blade.php (20 KB)</div>
                <div class="file-info">Unified pharmacist dashboard version</div>
                <a href="/dashboard-file-preview/pharmacist-dashboard-unified" target="_blank" class="preview-btn">Preview</a>
            </div>

            <div class="dashboard-card duplicate">
                <div class="card-header">
                    <div class="card-title">Radiologist Clean</div>
                    <span class="status-badge status-duplicate">DUPLICATE</span>
                </div>
                <div class="file-info">radiologist-dashboard-clean.blade.php (17 KB)</div>
                <div class="file-info">Clean version of radiologist dashboard</div>
                <a href="/dashboard-file-preview/radiologist-dashboard-clean" target="_blank" class="preview-btn">Preview</a>
            </div>

            <!-- EMPTY DASHBOARDS -->
            <div class="dashboard-card empty">
                <div class="card-header">
                    <div class="card-title">Admin Unified (Empty)</div>
                    <span class="status-badge status-empty">EMPTY</span>
                </div>
                <div class="file-info">admin-dashboard-unified.blade.php (0 bytes)</div>
                <div class="file-info">Empty file - safe to delete</div>
            </div>

            <div class="dashboard-card empty">
                <div class="card-header">
                    <div class="card-title">Doctor Modern (Empty)</div>
                    <span class="status-badge status-empty">EMPTY</span>
                </div>
                <div class="file-info">doctor-dashboard-modern.blade.php (0 bytes)</div>
                <div class="file-info">Empty file - safe to delete</div>
            </div>

            <div class="dashboard-card empty">
                <div class="card-header">
                    <div class="card-title">Lab Tech Temp (Empty)</div>
                    <span class="status-badge status-empty">EMPTY</span>
                </div>
                <div class="file-info">lab-tech-dashboard-temp.blade.php (0 bytes)</div>
                <div class="file-info">Empty file - safe to delete</div>
            </div>

            <div class="dashboard-card empty">
                <div class="card-header">
                    <div class="card-title">Lab Tech Test (Empty)</div>
                    <span class="status-badge status-empty">EMPTY</span>
                </div>
                <div class="file-info">lab-tech-dashboard-test.blade.php (0 bytes)</div>
                <div class="file-info">Empty file - safe to delete</div>
            </div>

            <div class="dashboard-card empty">
                <div class="card-header">
                    <div class="card-title">Lab Tech Corrected (Empty)</div>
                    <span class="status-badge status-empty">EMPTY</span>
                </div>
                <div class="file-info">lab-tech-dashboard-corrected.blade.php (0 bytes)</div>
                <div class="file-info">Empty file - safe to delete</div>
            </div>

            <div class="dashboard-card empty">
                <div class="card-header">
                    <div class="card-title">Lab Tech Restored (Empty)</div>
                    <span class="status-badge status-empty">EMPTY</span>
                </div>
                <div class="file-info">lab-tech-dashboard-restored.blade.php (0 bytes)</div>
                <div class="file-info">Empty file - safe to delete</div>
            </div>

            <div class="dashboard-card empty">
                <div class="card-header">
                    <div class="card-title">Owner Unified (Empty)</div>
                    <span class="status-badge status-empty">EMPTY</span>
                </div>
                <div class="file-info">owner-dashboard-unified.blade.php (0 bytes)</div>
                <div class="file-info">Empty file - safe to delete</div>
            </div>

            <div class="dashboard-card empty">
                <div class="card-header">
                    <div class="card-title">Radiologist Unified (Empty)</div>
                    <span class="status-badge status-empty">EMPTY</span>
                </div>
                <div class="file-info">radiologist-dashboard-unified.blade.php (0 bytes)</div>
                <div class="file-info">Empty file - safe to delete</div>
            </div>

            <div class="dashboard-card empty">
                <div class="card-header">
                    <div class="card-title">Unified Dashboard (Empty)</div>
                    <span class="status-badge status-empty">EMPTY</span>
                </div>
                <div class="file-info">unified-dashboard.blade.php (0 bytes)</div>
                <div class="file-info">Empty file - safe to delete</div>
            </div>
        </div>
    </div>
</body>
</html>
