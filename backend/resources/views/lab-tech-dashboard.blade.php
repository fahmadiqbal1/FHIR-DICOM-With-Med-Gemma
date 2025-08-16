@extends('layouts.main')

@section('title', 'Lab Technician Dashboard')

@section('content')
<style>
/* Lab Tech Dashboard Specific Styles */
.dashboard-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem 1.5rem;
}

.dashboard-header {
    background: var(--glass-background);
    backdrop-filter: var(--backdrop-blur);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-xl);
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-md);
}

.dashboard-title {
    font-size: 2.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 0.5rem;
}

.dashboard-subtitle {
    color: var(--text-secondary);
    font-size: 1.1rem;
    margin-bottom: 0;
}

.role-badge {
    background: var(--primary-gradient);
    color: white;
    padding: 0.7rem 1.5rem;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.95rem;
    box-shadow: var(--shadow-sm);
}

.date-filter {
    background: var(--bg-input);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-md);
    color: var(--text-primary);
    padding: 0.7rem 1rem;
    font-size: 0.95rem;
    backdrop-filter: var(--backdrop-blur);
}

.date-filter:focus {
    background: var(--bg-input-focus);
    border-color: var(--glass-border-hover);
    outline: none;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.nav-tabs-container {
    background: var(--glass-background);
    backdrop-filter: var(--backdrop-blur);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-xl);
    padding: 1rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-md);
}

.custom-nav-tabs {
    display: flex;
    gap: 0.5rem;
    background: none;
    border: none;
    padding: 0;
    margin: 0;
}

.custom-nav-tab {
    background: transparent;
    border: 1px solid var(--glass-border);
    color: var(--text-secondary);
    padding: 1rem 1.5rem;
    border-radius: var(--radius-lg);
    text-decoration: none;
    font-weight: 500;
    font-size: 1rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex: 1;
    justify-content: center;
    cursor: pointer;
}

.custom-nav-tab:hover {
    background: var(--glass-background-hover);
    border-color: var(--glass-border-hover);
    color: var(--text-primary);
    transform: translateY(-2px);
}

.custom-nav-tab.active {
    background: var(--primary-gradient);
    border-color: transparent;
    color: white;
    box-shadow: var(--shadow-sm);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stats-card {
    background: var(--glass-background);
    backdrop-filter: var(--backdrop-blur);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-sm);
}

.stats-card:hover {
    background: var(--glass-background-hover);
    border-color: var(--glass-border-hover);
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
}

.stats-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: var(--radius-full);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    flex-shrink: 0;
}

.stats-info h3 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.stats-info p {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-secondary);
    margin-bottom: 0.25rem;
}

.stats-info small {
    font-size: 0.85rem;
    color: var(--text-muted);
}

.stats-progress {
    width: 100%;
    height: 4px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 2px;
    margin-top: 1rem;
    overflow: hidden;
}

.stats-progress-bar {
    height: 100%;
    border-radius: 2px;
    transition: width 0.8s ease;
}

.content-card {
    background: var(--glass-background);
    backdrop-filter: var(--backdrop-blur);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
    margin-bottom: 2rem;
}

.content-card-header {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--glass-border);
    display: flex;
    justify-content: between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.content-card-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0;
}

.content-card-body {
    padding: 2rem;
}

.table-container {
    background: var(--glass-background);
    border-radius: var(--radius-lg);
    overflow: hidden;
    border: 1px solid var(--glass-border);
}

.custom-table {
    width: 100%;
    border-collapse: collapse;
    background: transparent;
}

.custom-table th {
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-primary);
    font-weight: 600;
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--glass-border);
}

.custom-table td {
    padding: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    color: var(--text-secondary);
}

.custom-table tr:hover {
    background: rgba(255, 255, 255, 0.03);
}

.btn-primary {
    background: var(--primary-gradient);
    border: none;
    color: white;
    padding: 0.7rem 1.5rem;
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: var(--shadow-sm);
}

.btn-primary:hover {
    background: var(--primary-gradient-hover);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-secondary {
    background: var(--glass-background);
    border: 1px solid var(--glass-border);
    color: var(--text-primary);
    padding: 0.7rem 1.5rem;
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-secondary:hover {
    background: var(--glass-background-hover);
    border-color: var(--glass-border-hover);
    transform: translateY(-2px);
}

.form-control {
    background: var(--bg-input);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-md);
    color: var(--text-primary);
    padding: 0.7rem 1rem;
    font-size: 0.95rem;
    backdrop-filter: var(--backdrop-blur);
    width: 100%;
}

.form-control:focus {
    background: var(--bg-input-focus);
    border-color: var(--glass-border-hover);
    outline: none;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-label {
    color: var(--text-secondary);
    font-weight: 500;
    margin-bottom: 0.5rem;
    display: block;
}

.badge {
    padding: 0.35rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
}

.badge-success {
    background: var(--success-bg);
    color: var(--success-color);
    border: 1px solid var(--success-border);
}

.badge-warning {
    background: var(--warning-bg);
    color: var(--warning-color);
    border: 1px solid var(--warning-border);
}

.badge-danger {
    background: var(--error-bg);
    color: var(--error-color);
    border: 1px solid var(--error-border);
}

.badge-info {
    background: var(--info-bg);
    color: var(--info-color);
    border: 1px solid var(--info-border);
}

.alert {
    padding: 1rem 1.5rem;
    border-radius: var(--radius-lg);
    margin-bottom: 1rem;
    border: 1px solid;
}

.alert-success {
    background: var(--success-bg);
    color: var(--success-color);
    border-color: var(--success-border);
}

.alert-error {
    background: var(--error-bg);
    color: var(--error-color);
    border-color: var(--error-border);
}

/* Animation Classes */
.fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

/* Responsive Design */
@media (max-width: 768px) {
    .dashboard-container {
        padding: 1rem;
    }
    
    .dashboard-header {
        padding: 1.5rem;
    }
    
    .dashboard-title {
        font-size: 2rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .custom-nav-tabs {
        flex-direction: column;
    }
    
    .content-card-header {
        padding: 1rem;
        flex-direction: column;
        align-items: flex-start;
    }
    
    .content-card-body {
        padding: 1rem;
    }
}
</style>

<script>
// Custom Tab Functionality
function initializeTabs() {
    const tabs = document.querySelectorAll('.custom-nav-tab');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs and panes
            tabs.forEach(t => t.classList.remove('active'));
            tabPanes.forEach(pane => {
                pane.classList.remove('show', 'active');
                pane.style.display = 'none';
            });
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Show corresponding pane
            const targetId = this.getAttribute('data-bs-target');
            const targetPane = document.querySelector(targetId);
            if (targetPane) {
                targetPane.style.display = 'block';
                targetPane.classList.add('show', 'active', 'fade-in');
                
                // Trigger any load functions for the tab
                const tabId = targetId.replace('#', '');
                if (tabId === 'equipment') {
                    loadEquipmentData();
                } else if (tabId === 'invoices') {
                    loadLabInvoices();
                } else if (tabId === 'analytics') {
                    loadAnalytics();
                }
            }
        });
    });
}

// Initialize tabs when document is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeTabs();
});
</script>

<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1 class="dashboard-title">🧪 Lab Technician Dashboard</h1>
                <p class="dashboard-subtitle">Manage lab orders, equipment integration, and financial tracking</p>
            </div>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <input type="date" id="dateFilter" class="date-filter" value="{{ date('Y-m-d') }}">
                <div class="role-badge">
                    <i class="fas fa-user-check" style="margin-right: 0.5rem;"></i>
                    Lab Technician
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="nav-tabs-container">
        <div class="custom-nav-tabs" id="labTechTabs" role="tablist">
            <button class="custom-nav-tab active" id="orders-tab" data-bs-toggle="pill" data-bs-target="#orders" type="button" role="tab">
                <i class="fas fa-vials"></i>Lab Orders
            </button>
            <button class="custom-nav-tab" id="equipment-tab" data-bs-toggle="pill" data-bs-target="#equipment" type="button" role="tab">
                <i class="fas fa-microscope"></i>Equipment & Results
            </button>
            <button class="custom-nav-tab" id="invoices-tab" data-bs-toggle="pill" data-bs-target="#invoices" type="button" role="tab">
                <i class="fas fa-flask"></i>Lab Financials
            </button>
            <button class="custom-nav-tab" id="analytics-tab" data-bs-toggle="pill" data-bs-target="#analytics" type="button" role="tab">
                <i class="fas fa-chart-line"></i>Analytics
            </button>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content" id="labTechTabContent">
        
        <!-- Lab Orders Tab -->
        <div class="tab-pane fade show active" id="orders" role="tabpanel">
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stats-card">
                    <div class="stats-content">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stats-info">
                            <h3 id="pendingOrders">--</h3>
                            <p>Pending Orders</p>
                            <small>Awaiting collection</small>
                        </div>
                    </div>
                    <div class="stats-progress">
                        <div class="stats-progress-bar" style="background: linear-gradient(135deg, #f59e0b, #d97706); width: 65%;"></div>
                    </div>
                </div>
                
                <div class="stats-card">
                    <div class="stats-content">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #06b6d4, #0891b2);">
                            <i class="fas fa-vial"></i>
                        </div>
                        <div class="stats-info">
                            <h3 id="samplesCollected">--</h3>
                            <p>Samples Today</p>
                            <small>Collected samples</small>
                        </div>
                    </div>
                    <div class="stats-progress">
                        <div class="stats-progress-bar" style="background: linear-gradient(135deg, #06b6d4, #0891b2); width: 80%;"></div>
                    </div>
                </div>
                
                <div class="stats-card">
                    <div class="stats-content">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stats-info">
                            <h3 id="completedOrders">--</h3>
                            <p>Completed</p>
                            <small>Results submitted</small>
                        </div>
                    </div>
                    <div class="stats-progress">
                        <div class="stats-progress-bar" style="background: linear-gradient(135deg, #10b981, #059669); width: 90%;"></div>
                    </div>
                </div>
                
                <div class="stats-card">
                    <div class="stats-content">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                            <i class="fas fa-flask"></i>
                        </div>
                        <div class="stats-info">
                            <h3 id="inProgressOrders">--</h3>
                            <p>In Progress</p>
                            <small>Being processed</small>
                        </div>
                    </div>
                    <div class="stats-progress">
                        <div class="stats-progress-bar" style="background: linear-gradient(135deg, #667eea, #764ba2); width: 45%;"></div>
                    </div>
                </div>
            </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-gradient-success me-3">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <h6 class="text-light-gray mb-1">Completed</h6>
                                    <h4 class="text-white mb-0" id="completedOrders">--</h4>
                                    <small class="text-light-gray">Results submitted</small>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height: 4px;">
                                <div class="progress-bar bg-gradient-success" role="progressbar" style="width: 90%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="glass-card stats-card-enhanced">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-gradient-primary me-3">
                                    <i class="fas fa-flask"></i>
                                </div>
                                <div>
                                    <h6 class="text-light-gray mb-1">In Progress</h6>
                                    <h4 class="text-white mb-0" id="inProgressOrders">--</h4>
                                    <small class="text-light-gray">Being processed</small>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height: 4px;">
                                <div class="progress-bar bg-gradient-primary" role="progressbar" style="width: 45%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card stats-completed">
                        <div class="stats-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stats-content">
                            <h3 id="resultsSubmitted">--</h3>
                            <p>Results Today</p>
                            <small>Submitted results</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card stats-total">
                        <div class="stats-icon">
                            <i class="fas fa-flask"></i>
                        </div>
                        <div class="stats-content">
                            <h3 id="totalTests">--</h3>
                            <p>Total Tests</p>
                            <small>Today's workload</small>
                        </div>
                    </div>
                </div>
            <!-- Lab Orders Management -->
            <div class="content-card">
                <div class="content-card-header">
                    <h2 class="content-card-title">
                        <i class="fas fa-list-ul"></i>
                        Lab Orders Management
                    </h2>
                    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                        <select id="statusFilter" class="form-control" style="max-width: 150px;">
                            <option value="">All Orders</option>
                            <option value="ordered">Ordered</option>
                            <option value="collected">Sample Collected</option>
                            <option value="processing">Processing</option>
                            <option value="resulted">Results Ready</option>
                        </select>
                        <select id="priorityFilter" class="form-control" style="max-width: 150px;">
                            <option value="">All Priorities</option>
                            <option value="stat">STAT</option>
                            <option value="urgent">Urgent</option>
                            <option value="routine">Routine</option>
                        </select>
                        <button class="btn-secondary" onclick="loadLabOrders()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="content-card-body" style="padding: 0;">
                    <div class="table-container">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Patient</th>
                                    <th>Test</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Ordered</th>
                                    <th>Collection</th>
                                    <th>Results</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="labOrdersTable">
                                <tr>
                                    <td colspan="9" style="text-align: center; padding: 3rem;">
                                        <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                                            <div class="pulse" style="width: 20px; height: 20px; background: var(--primary-gradient); border-radius: 50%;"></div>
                                            Loading lab orders...
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Equipment & Results Tab -->
        <div class="tab-pane fade" id="equipment" role="tabpanel">
            <!-- Equipment Status Cards -->
            <div class="stats-grid">
                <div class="stats-card">
                    <div class="stats-content">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                            <i class="fas fa-microscope"></i>
                        </div>
                        <div class="stats-info">
                            <h3 id="activeEquipmentCount">0</h3>
                            <p>Active Equipment</p>
                            <small>Currently online</small>
                        </div>
                    </div>
                </div>
                
                <div class="stats-card">
                    <div class="stats-content">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="fas fa-wifi"></i>
                        </div>
                        <div class="stats-info">
                            <h3 id="onlineEquipmentCount">0</h3>
                            <p>Online Equipment</p>
                            <small>Connected devices</small>
                        </div>
                    </div>
                </div>
                
                <div class="stats-card">
                    <div class="stats-content">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stats-info">
                            <h3 id="pendingVerificationCount">0</h3>
                            <p>Pending Verification</p>
                            <small>Requires review</small>
                        </div>
                    </div>
                </div>
            </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="glass-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-gradient-success me-3">
                                    <i class="fas fa-wifi"></i>
                                </div>
                                <div>
                                    <h6 class="text-light-gray mb-1">Online Equipment</h6>
                                    <h4 class="text-white mb-0" id="onlineEquipmentCount">0</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="glass-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-gradient-warning me-3">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <h6 class="text-light-gray mb-1">Pending Verification</h6>
                                    <h4 class="text-white mb-0" id="pendingVerificationCount">0</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Equipment Management -->
            <div class="content-card">
                <div class="content-card-header">
                    <h2 class="content-card-title">
                        <i class="fas fa-cogs"></i>Lab Equipment Status
                    </h2>
                    <button class="btn-primary" onclick="fetchAllResults()">
                        <i class="fas fa-sync-alt"></i>Fetch All Results
                    </button>
                </div>
                <div class="content-card-body">
                    <div id="equipmentList" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                        <!-- Equipment cards will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- OCR Result Upload -->
            <div class="content-card">
                <div class="content-card-header">
                    <h2 class="content-card-title">
                        <i class="fas fa-camera"></i>OCR Result Upload
                    </h2>
                </div>
                <div class="content-card-body">
                    <form id="ocrUploadForm" enctype="multipart/form-data">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                            <div>
                                <label class="form-label">Lab Order</label>
                                <select class="form-control" id="ocrLabOrder" required>
                                    <option value="">Select Lab Order</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Equipment (Optional)</label>
                                <select class="form-control" id="ocrEquipment">
                                    <option value="">Manual Entry</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Result Image</label>
                                        <input type="file" class="form-control bg-dark text-white border-purple" 
                                               id="resultImage" accept="image/*" required>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-gradient-success">
                                            <i class="fas fa-upload me-1"></i>Process OCR
                                        </button>
                                        <button type="button" class="btn btn-gradient-secondary ms-2" 
                                                onclick="openCamera()">
                                            <i class="fas fa-camera me-1"></i>Take Photo
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Camera Modal -->
            <div class="modal fade" id="cameraModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content bg-dark">
                        <div class="modal-header border-purple">
                            <h5 class="modal-title text-white">
                                <i class="fas fa-camera me-2"></i>Capture Result Image
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <video id="cameraVideo" width="100%" height="400" autoplay></video>
                            <canvas id="cameraCanvas" style="display: none;"></canvas>
                            <div class="mt-3">
                                <button class="btn btn-gradient-primary" onclick="capturePhoto()">
                                    <i class="fas fa-camera me-1"></i>Capture
                                </button>
                                <button class="btn btn-gradient-secondary ms-2" onclick="retakePhoto()">
                                    <i class="fas fa-redo me-1"></i>Retake
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Results -->
            <div class="row">
                <div class="col-12">
                    <div class="glass-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="text-white mb-0">
                                <i class="fas fa-list-alt me-2"></i>Recent Results
                            </h5>
                            <div class="d-flex gap-2">
                                <select class="form-select form-select-sm bg-dark text-white border-purple" 
                                        id="resultSourceFilter" style="max-width: 150px;">
                                    <option value="">All Sources</option>
                                    <option value="equipment">Equipment</option>
                                    <option value="ocr">OCR</option>
                                    <option value="manual">Manual</option>
                                </select>
                                <select class="form-select form-select-sm bg-dark text-white border-purple" 
                                        id="resultStatusFilter" style="max-width: 150px;">
                                    <option value="">All Status</option>
                                    <option value="preliminary">Preliminary</option>
                                    <option value="final">Final</option>
                                    <option value="needs_verification">Needs Verification</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-dark table-hover">
                                    <thead>
                                        <tr>
                                            <th>Test Name</th>
                                            <th>Patient</th>
                                            <th>Result</th>
                                            <th>Source</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="recentResultsTable">
                                        <!-- Results will be loaded here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lab Financial Dashboard Tab -->
        <div class="tab-pane fade" id="invoices" role="tabpanel" style="display: none;">
            <!-- Lab Financial Analytics -->
            <div class="stats-grid">
                <div class="stats-card">
                    <div class="stats-content">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="fas fa-flask"></i>
                        </div>
                        <div class="stats-info">
                            <h3 id="labTotalRevenue">$--</h3>
                            <p>Lab Revenue</p>
                            <small>From lab orders</small>
                        </div>
                    </div>
                    <div class="stats-progress">
                        <div class="stats-progress-bar" style="background: linear-gradient(135deg, #10b981, #059669); width: 85%;"></div>
                    </div>
                </div>
                
                <div class="stats-card">
                    <div class="stats-content">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <div class="stats-info">
                            <h3 id="pendingLabInvoices">$--</h3>
                            <p>Pending Lab Invoices</p>
                            <small>Unpaid lab bills</small>
                        </div>
                    </div>
                    <div class="stats-progress">
                        <div class="stats-progress-bar" style="background: linear-gradient(135deg, #f59e0b, #d97706); width: 65%;"></div>
                    </div>
                </div>
                
                <div class="stats-card">
                    <div class="stats-content">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #06b6d4, #0891b2);">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="stats-info">
                            <h3 id="todayLabRevenue">$--</h3>
                            <p>Today's Lab Revenue</p>
                            <small>Collections today</small>
                        </div>
                    </div>
                    <div class="stats-progress">
                        <div class="stats-progress-bar" style="background: linear-gradient(135deg, #06b6d4, #0891b2); width: 70%;"></div>
                    </div>
                </div>
                
                <div class="stats-card">
                    <div class="stats-content">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                            <i class="fas fa-vial"></i>
                        </div>
                        <div class="stats-info">
                            <h3 id="labOrdersValue">$--</h3>
                            <p>Lab Orders Value</p>
                            <small>Total order value</small>
                        </div>
                    </div>
                    <div class="stats-progress">
                        <div class="stats-progress-bar" style="background: linear-gradient(135deg, #667eea, #764ba2); width: 90%;"></div>
                    </div>
                </div>
            </div>
            
            <!-- Lab Financial Charts -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                <div class="content-card">
                    <div class="content-card-header">
                        <h2 class="content-card-title">
                            <i class="fas fa-chart-line"></i>Lab Revenue Trend
                        </h2>
                    </div>
                    <div class="content-card-body">
                        <canvas id="labRevenueChart" height="300"></canvas>
                    </div>
                </div>
                <div class="content-card">
                    <div class="content-card-header">
                        <h2 class="content-card-title">
                            <i class="fas fa-chart-pie"></i>Test Categories Revenue
                        </h2>
                    </div>
                    <div class="content-card-body">
                        <canvas id="testCategoriesChart" height="300"></canvas>
                    </div>
                </div>
            </div>
                                <i class="fas fa-chart-pie me-2"></i>Test Categories Revenue
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="testCategoriesChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lab Invoice Generation Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="glass-card">
                        <div class="card-header">
                            <h5 class="text-white mb-0">
                                <i class="fas fa-plus-circle me-2"></i>
                                Generate Lab Invoice
                            </h5>
                        </div>
                        <div class="card-body">
                            <form id="labInvoiceForm" class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label text-light-gray">Select Patient</label>
                                    <select id="labInvoicePatient" class="form-select bg-dark text-white border-purple" required>
                                        <option value="">Choose patient...</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-light-gray">Completed Lab Orders</label>
                                    <select id="labInvoiceOrders" class="form-select bg-dark text-white border-purple" multiple required>
                                        <option value="">Select patient first...</option>
                                    </select>
                                    <small class="text-light-gray">Hold Ctrl/Cmd to select multiple completed orders</small>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label text-light-gray">Lab Tests Total</label>
                                    <input type="number" id="labInvoiceAmount" class="form-control bg-dark text-white border-purple" step="0.01" required readonly>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label text-light-gray">Discount (%)</label>
                                    <input type="number" id="labDiscount" class="form-control bg-dark text-white border-purple" min="0" max="100" value="0">
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-gradient-primary w-100">
                                        <i class="fas fa-file-invoice me-1"></i>Generate
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lab Invoices Management -->
            <div class="row">
                <div class="col-12">
                    <div class="glass-card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="text-white mb-0">
                                    <i class="fas fa-file-invoice-dollar me-2"></i>
                                    Lab Invoice Management
                                </h5>
                                <div class="d-flex gap-2">
                                    <select id="labInvoiceStatusFilter" class="form-select form-select-sm bg-dark text-white border-purple">
                                        <option value="">All Lab Invoices</option>
                                        <option value="pending">Pending Payment</option>
                                        <option value="paid">Paid</option>
                                        <option value="overdue">Overdue</option>
                                        <option value="partial">Partially Paid</option>
                                    </select>
                                    <select id="labTestTypeFilter" class="form-select form-select-sm bg-dark text-white border-purple">
                                        <option value="">All Test Types</option>
                                        <option value="hematology">Hematology</option>
                                        <option value="biochemistry">Biochemistry</option>
                                        <option value="electrolyte">Electrolyte</option>
                                        <option value="microbiology">Microbiology</option>
                                    </select>
                                    <button class="btn btn-sm btn-gradient-primary" onclick="loadLabInvoices()">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-dark table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Invoice #</th>
                                            <th>Patient</th>
                                            <th>Lab Tests</th>
                                            <th>Test Count</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date Generated</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="labInvoicesTable">
                                        <!-- Lab invoices will be loaded here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Tab -->
        <div class="tab-pane fade" id="analytics" role="tabpanel" style="display: none;">
            <div class="content-card">
                <div class="content-card-header">
                    <h2 class="content-card-title">
                        <i class="fas fa-chart-bar"></i>Analytics Dashboard
                    </h2>
                </div>
                <div class="content-card-body">
                    <p style="text-align: center; color: var(--text-secondary); padding: 3rem;">
                        Analytics dashboard coming soon...
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Tab -->
        <div class="tab-pane fade" id="analytics" role="tabpanel">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="glass-card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-pie me-2"></i>
                                Test Distribution
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="testDistributionChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="glass-card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-line me-2"></i>
                                Daily Revenue
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="revenueChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="glass-card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-stopwatch me-2"></i>
                                Performance Metrics
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="metric-item">
                                        <h4 id="avgProcessingTime">-- min</h4>
                                        <p>Avg Processing Time</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="metric-item">
                                        <h4 id="todayCompletionRate">-- %</h4>
                                        <p>Today's Completion Rate</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="metric-item">
                                        <h4 id="criticalResults">--</h4>
                                        <p>Critical Results</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="metric-item">
                                        <h4 id="qualityScore">-- %</h4>
                                        <p>Quality Score</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Collection Time Modal -->
<div class="modal fade" id="collectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">
                    <i class="fas fa-vial me-2"></i>
                    Record Sample Collection
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="collectionForm">
                    <input type="hidden" id="collectionOrderId">
                    <div class="mb-3">
                        <label class="form-label">Patient:</label>
                        <p id="collectionPatientName" class="text-light-gray"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Test:</label>
                        <p id="collectionTestName" class="text-light-gray"></p>
                    </div>
                    <div class="mb-3">
                        <label for="collectionTime" class="form-label">Collection Date & Time:</label>
                        <input type="datetime-local" id="collectionTime" class="form-control bg-dark text-white border-secondary" required>
                    </div>
                    <div class="mb-3">
                        <label for="collectionNotes" class="form-label">Collection Notes (Optional):</label>
                        <textarea id="collectionNotes" class="form-control bg-dark text-white border-secondary" rows="3" placeholder="Enter any special notes about the collection..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitCollection()">
                    <i class="fas fa-save me-1"></i>
                    Record Collection
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Result Submission Modal -->
<div class="modal fade" id="resultModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">
                    <i class="fas fa-clipboard-check me-2"></i>
                    Submit Test Results
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="resultForm">
                    <input type="hidden" id="resultOrderId">
                    <div class="mb-3">
                        <label class="form-label">Patient:</label>
                        <p id="resultPatientName" class="text-light-gray"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Test:</label>
                        <p id="resultTestName" class="text-light-gray"></p>
                    </div>
                    <div class="mb-3">
                        <label for="resultValue" class="form-label">Result Value:</label>
                        <input type="text" id="resultValue" class="form-control bg-dark text-white border-secondary" required placeholder="Enter test result...">
                    </div>
                    <div class="mb-3">
                        <label for="resultFlag" class="form-label">Result Flag:</label>
                        <select id="resultFlag" class="form-select bg-dark text-white border-secondary">
                            <option value="normal">Normal</option>
                            <option value="high">High</option>
                            <option value="low">Low</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="resultNotes" class="form-label">Result Notes:</label>
                        <textarea id="resultNotes" class="form-control bg-dark text-white border-secondary" rows="3" placeholder="Enter any notes about the results..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="resultTime" class="form-label">Result Submission Time:</label>
                        <input type="datetime-local" id="resultTime" class="form-control bg-dark text-white border-secondary" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="submitResult()">
                    <i class="fas fa-check me-1"></i>
                    Submit Results
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.icon-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    transition: all 0.3s ease;
    animation: pulse-glow 2s infinite;
}

.icon-circle:hover {
    transform: scale(1.1);
    box-shadow: 0 0 20px rgba(138, 43, 226, 0.5);
}

@keyframes pulse-glow {
    0%, 100% { 
        box-shadow: 0 0 10px rgba(138, 43, 226, 0.3);
        transform: scale(1);
    }
    50% { 
        box-shadow: 0 0 20px rgba(138, 43, 226, 0.6);
        transform: scale(1.02);
    }
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.glass-card {
    background: rgba(30, 30, 50, 0.9);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(138, 43, 226, 0.3);
    border-radius: 15px;
    box-shadow: 
        0 8px 32px rgba(0, 0, 0, 0.3),
        inset 0 1px 0 rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
    animation: slideInUp 0.6s ease-out;
    position: relative;
    overflow: hidden;
}

.glass-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(138, 43, 226, 0.1), transparent);
    transition: left 0.5s;
}

.glass-card:hover::before {
    left: 100%;
}

.glass-card:hover {
    transform: translateY(-5px) scale(1.02);
    border-color: rgba(138, 43, 226, 0.6);
    box-shadow: 
        0 15px 40px rgba(0, 0, 0, 0.4),
        0 0 25px rgba(138, 43, 226, 0.2),
        inset 0 1px 0 rgba(255, 255, 255, 0.2);
}

.nav-pills .nav-link {
    background: rgba(30, 30, 50, 0.6);
    border: 1px solid rgba(138, 43, 226, 0.3);
    color: #9ca3af;
    margin: 0 5px;
    border-radius: 10px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.nav-pills .nav-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, #8A2BE2, #9966CC);
    transition: left 0.3s ease;
    z-index: -1;
}

.nav-pills .nav-link:hover::before,
.nav-pills .nav-link.active::before {
    left: 0;
}

.nav-pills .nav-link:hover,
.nav-pills .nav-link.active {
    color: white;
    border-color: rgba(138, 43, 226, 0.8);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(138, 43, 226, 0.3);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    animation: gradient-shift 3s ease infinite;
}

.bg-gradient-success {
    background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
    animation: gradient-shift 3s ease infinite;
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    animation: gradient-shift 3s ease infinite;
}

.bg-gradient-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    animation: gradient-shift 3s ease infinite;
}

.bg-gradient-purple {
    background: linear-gradient(135deg, #8A2BE2 0%, #9966CC 100%);
    animation: gradient-shift 3s ease infinite;
}

@keyframes gradient-shift {
    0%, 100% { 
        filter: hue-rotate(0deg) brightness(1);
    }
    50% { 
        filter: hue-rotate(10deg) brightness(1.1);
    }
}

.btn {
    border-radius: 8px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    transition: all 0.3s ease;
    transform: translate(-50%, -50%);
}

.btn:hover::before {
    width: 300px;
    height: 300px;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.btn-gradient-primary {
    background: linear-gradient(45deg, #667eea, #764ba2);
    border: none;
    color: white;
}

.btn-gradient-success {
    background: linear-gradient(45deg, #4ecdc4, #44a08d);
    border: none;
    color: white;
}

.btn-gradient-warning {
    background: linear-gradient(45deg, #f093fb, #f5576c);
    border: none;
    color: white;
}

.btn-gradient-info {
    background: linear-gradient(45deg, #4facfe, #00f2fe);
    border: none;
    color: white;
}

.btn-gradient-secondary {
    background: linear-gradient(45deg, #6c757d, #495057);
    border: none;
    color: white;
}

.gradient-text {
    background: linear-gradient(45deg, #8A2BE2, #9966CC, #DA70D6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: gradient-flow 3s ease-in-out infinite;
}

@keyframes gradient-flow {
    0%, 100% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
}

.text-light-gray {
    color: #9ca3af;
}

.border-purple {
    border-color: rgba(138, 43, 226, 0.5) !important;
}

.border-purple:focus {
    border-color: rgba(138, 43, 226, 0.8) !important;
    box-shadow: 0 0 0 0.25rem rgba(138, 43, 226, 0.25);
}

.table-dark {
    background: rgba(30, 30, 50, 0.8);
    backdrop-filter: blur(5px);
}

.table-dark th {
    border-color: rgba(138, 43, 226, 0.3);
    background: rgba(138, 43, 226, 0.2);
    color: white;
    font-weight: 600;
}

.table-dark td {
    border-color: rgba(138, 43, 226, 0.2);
    color: #e5e7eb;
    vertical-align: middle;
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background: rgba(138, 43, 226, 0.1);
    transform: scale(1.02);
    box-shadow: 0 4px 15px rgba(138, 43, 226, 0.2);
}

.badge {
    border-radius: 20px;
    padding: 8px 16px;
    font-weight: 500;
    animation: fadeInScale 0.5s ease-out;
}

.form-control, .form-select {
    border-radius: 8px;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    transform: scale(1.02);
    box-shadow: 0 0 0 0.25rem rgba(138, 43, 226, 0.25);
}

.card-header {
    background: rgba(138, 43, 226, 0.2);
    border-bottom: 1px solid rgba(138, 43, 226, 0.3);
    border-radius: 15px 15px 0 0 !important;
}

.modal-content {
    border-radius: 15px;
    border: 1px solid rgba(138, 43, 226, 0.3);
    animation: fadeInScale 0.3s ease-out;
}

.equipment-status-online {
    animation: pulse-green 2s infinite;
}

.equipment-status-offline {
    animation: pulse-red 2s infinite;
}

@keyframes pulse-green {
    0%, 100% { 
        box-shadow: 0 0 5px rgba(34, 197, 94, 0.3);
    }
    50% { 
        box-shadow: 0 0 20px rgba(34, 197, 94, 0.6);
    }
}

@keyframes pulse-red {
    0%, 100% { 
        box-shadow: 0 0 5px rgba(239, 68, 68, 0.3);
    }
    50% { 
        box-shadow: 0 0 20px rgba(239, 68, 68, 0.6);
    }
}

.loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(138, 43, 226, 0.3);
    border-radius: 50%;
    border-top-color: #8A2BE2;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.progress-bar {
    background: linear-gradient(45deg, #8A2BE2, #9966CC);
    animation: progress-glow 2s ease-in-out infinite;
}

@keyframes progress-glow {
    0%, 100% { 
        box-shadow: 0 0 5px rgba(138, 43, 226, 0.3);
    }
    50% { 
        box-shadow: 0 0 15px rgba(138, 43, 226, 0.6);
    }
}

.camera-preview {
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    .glass-card {
        margin-bottom: 1rem;
    }
    
    .nav-pills .nav-link {
        margin: 2px;
        font-size: 0.9rem;
    }
    
    .icon-circle {
        width: 50px;
        height: 50px;
    }
    
    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
}

/* Dark theme scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: rgba(30, 30, 50, 0.5);
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(45deg, #8A2BE2, #9966CC);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(45deg, #9966CC, #8A2BE2);
}

/* Animation delays for staggered loading */
.glass-card:nth-child(1) { animation-delay: 0.1s; }
.glass-card:nth-child(2) { animation-delay: 0.2s; }
.glass-card:nth-child(3) { animation-delay: 0.3s; }
.glass-card:nth-child(4) { animation-delay: 0.4s; }

/* Enhanced table animations */
.table tbody tr:nth-child(odd) { animation-delay: 0.1s; }
.table tbody tr:nth-child(even) { animation-delay: 0.2s; }
</style>
    font-size: 1.5rem;
}

.bg-gradient-orange {
    background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
}

.bg-gradient-blue {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.bg-gradient-green {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.bg-gradient-purple {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.border-purple {
    border-color: #764ba2 !important;
}

.glass-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 15px;
}

.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.text-light-gray {
    color: rgba(255, 255, 255, 0.7) !important;
}

.table-dark {
    --bs-table-bg: rgba(0, 0, 0, 0.2);
}

.priority-stat {
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.priority-stat.stat { background: #dc3545; color: white; }
.priority-urgent { background: #fd7e14; color: white; }
.priority-routine { background: #6c757d; color: white; }

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-ordered { background: #ffc107; color: #000; }
.status-collected { background: #17a2b8; color: white; }
.status-processing { background: #6f42c1; color: white; }
.status-resulted { background: #28a745; color: white; }

.btn-action {
    padding: 0.25rem 0.5rem;
    font-size: 0.8rem;
    border-radius: 6px;
    margin: 0 0.1rem;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let labOrders = [];
let labInvoices = []; // Changed from invoices to labInvoices
let patients = [];
let filteredOrders = [];
let filteredLabInvoices = []; // Changed from filteredInvoices to filteredLabInvoices

// Test prices for different lab tests
const testPrices = {
    'Complete Blood Count': 45.00,
    'Basic Metabolic Panel': 35.00,
    'Lipid Panel': 55.00,
    'Liver Function Test': 65.00,
    'Thyroid Function Test': 85.00,
    'Urinalysis': 25.00,
    'Blood Glucose': 20.00,
    'Hemoglobin A1C': 40.00,
    'Vitamin D': 75.00,
    'PSA': 60.00
};

// Load initial data
document.addEventListener('DOMContentLoaded', function() {
    loadStats();
    loadLabOrders();
    loadInvoices();
    loadPatients();
    
    // Auto refresh every 30 seconds
    setInterval(() => {
        loadStats();
        loadLabOrders();
        loadInvoices();
    }, 30000);
    
    // Filter event listeners
    document.getElementById('statusFilter').addEventListener('change', loadLabOrders);
    document.getElementById('priorityFilter').addEventListener('change', loadLabOrders);
    document.getElementById('dateFilter').addEventListener('change', loadLabOrders);
    document.getElementById('invoiceStatusFilter').addEventListener('change', filterInvoices);
    
    // Invoice form event listeners
    document.getElementById('invoicePatient').addEventListener('change', loadPatientOrders);
    document.getElementById('invoiceOrders').addEventListener('change', calculateInvoiceAmount);
    document.getElementById('invoiceForm').addEventListener('submit', generateInvoice);
    
    // Set current time for modals
    setCurrentDateTime();
});

function setCurrentDateTime() {
    const now = new Date();
    const timeString = now.toISOString().slice(0, 16);
    document.getElementById('collectionTime').value = timeString;
    document.getElementById('resultTime').value = timeString;
}

async function loadStats() {
    try {
        const response = await fetch('/api/lab-tech/stats', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const data = await response.json();
        
        document.getElementById('pendingOrders').textContent = data.pending_orders || 0;
        document.getElementById('samplesCollected').textContent = data.samples_collected_today || 0;
        document.getElementById('resultsSubmitted').textContent = data.results_submitted_today || 0;
        document.getElementById('totalTests').textContent = data.total_tests_today || 0;
        
        // Financial stats
        document.getElementById('totalRevenue').textContent = '$' + (data.total_revenue || 0).toFixed(2);
        document.getElementById('pendingPayments').textContent = '$' + (data.pending_payments || 0).toFixed(2);
        document.getElementById('todayRevenue').textContent = '$' + (data.today_revenue || 0).toFixed(2);
        document.getElementById('totalInvoices').textContent = data.total_invoices || 0;
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

async function loadLabOrders() {
    try {
        const statusFilter = document.getElementById('statusFilter').value;
        const priorityFilter = document.getElementById('priorityFilter').value;
        const dateFilter = document.getElementById('dateFilter').value;
        
        const params = new URLSearchParams();
        if (statusFilter) params.append('status', statusFilter);
        if (priorityFilter) params.append('priority', priorityFilter);
        if (dateFilter) params.append('date', dateFilter);
        
        const response = await fetch(`/api/lab-tech/orders?${params}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const orders = await response.json();
        
        labOrders = orders;
        filteredOrders = orders;
        renderLabOrders(orders);
    } catch (error) {
        console.error('Error loading lab orders:', error);
        document.getElementById('labOrdersTable').innerHTML = `
            <tr>
                <td colspan="9" class="text-center text-danger py-4">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Error loading lab orders
                </td>
            </tr>
        `;
    }
}

// Load lab-specific financial data and invoices
async function loadLabInvoices() {
    try {
        const response = await fetch('/api/lab-tech/lab-invoices', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const data = await response.json();
        
        labInvoices = data.invoices;
        filteredLabInvoices = data.invoices;
        renderLabInvoices(data.invoices);
        updateLabFinancialStats(data.stats);
    } catch (error) {
        console.error('Error loading lab invoices:', error);
        // For demo purposes, use mock lab financial data
        const mockStats = {
            total_lab_revenue: 12450.00,
            pending_lab_invoices: 2340.00,
            today_lab_revenue: 890.00,
            lab_orders_value: 15200.00,
            monthly_revenue: [3200, 3800, 4100, 3900, 4200, 4800, 5100, 4900, 4600, 4300, 3900, 4150],
            test_categories: {
                hematology: 35,
                biochemistry: 28,
                electrolyte: 22,
                microbiology: 15
            }
        };
        
        labInvoices = [
            {
                id: 1,
                invoice_number: 'LAB20250816001',
                patient_name: 'John Smith',
                patient_mrn: 'MRN001',
                lab_tests: ['CBC', 'Lipid Panel', 'Liver Function'],
                test_count: 3,
                amount: 180.00,
                status: 'pending',
                created_at: new Date().toISOString(),
                test_types: ['hematology', 'biochemistry']
            },
            {
                id: 2,
                invoice_number: 'LAB20250816002',
                patient_name: 'Jane Doe',
                patient_mrn: 'MRN002',
                lab_tests: ['Electrolyte Panel', 'BUN/Creatinine'],
                test_count: 2,
                amount: 125.00,
                status: 'paid',
                created_at: new Date(Date.now() - 86400000).toISOString(),
                test_types: ['electrolyte', 'biochemistry']
            },
            {
                id: 3,
                invoice_number: 'LAB20250816003',
                patient_name: 'Bob Johnson',
                patient_mrn: 'MRN003',
                lab_tests: ['Blood Culture', 'Urine Culture'],
                test_count: 2,
                amount: 95.00,
                status: 'overdue',
                created_at: new Date(Date.now() - 172800000).toISOString(),
                test_types: ['microbiology']
            }
        ];
        filteredLabInvoices = labInvoices;
        renderLabInvoices(labInvoices);
        updateLabFinancialStats(mockStats);
        initializeLabCharts(mockStats);
    }
}

// Update lab financial statistics
function updateLabFinancialStats(stats) {
    if (document.getElementById('labTotalRevenue')) {
        animateValue(document.getElementById('labTotalRevenue'), 
            parseFloat(document.getElementById('labTotalRevenue').textContent.replace(/[$,]/g, '')) || 0, 
            stats.total_lab_revenue, 1500);
        setTimeout(() => {
            document.getElementById('labTotalRevenue').textContent = `$${stats.total_lab_revenue.toLocaleString()}`;
        }, 1500);
    }
    
    if (document.getElementById('pendingLabInvoices')) {
        animateValue(document.getElementById('pendingLabInvoices'), 
            parseFloat(document.getElementById('pendingLabInvoices').textContent.replace(/[$,]/g, '')) || 0, 
            stats.pending_lab_invoices, 1500);
        setTimeout(() => {
            document.getElementById('pendingLabInvoices').textContent = `$${stats.pending_lab_invoices.toLocaleString()}`;
        }, 1500);
    }
    
    if (document.getElementById('todayLabRevenue')) {
        animateValue(document.getElementById('todayLabRevenue'), 
            parseFloat(document.getElementById('todayLabRevenue').textContent.replace(/[$,]/g, '')) || 0, 
            stats.today_lab_revenue, 1500);
        setTimeout(() => {
            document.getElementById('todayLabRevenue').textContent = `$${stats.today_lab_revenue.toLocaleString()}`;
        }, 1500);
    }
    
    if (document.getElementById('labOrdersValue')) {
        animateValue(document.getElementById('labOrdersValue'), 
            parseFloat(document.getElementById('labOrdersValue').textContent.replace(/[$,]/g, '')) || 0, 
            stats.lab_orders_value, 1500);
        setTimeout(() => {
            document.getElementById('labOrdersValue').textContent = `$${stats.lab_orders_value.toLocaleString()}`;
        }, 1500);
    }
}

// Render lab invoices table
function renderLabInvoices(invoices) {
    const tbody = document.getElementById('labInvoicesTable');
    if (!tbody) return;
    
    if (invoices.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center text-light-gray py-4">
                    <i class="fas fa-file-invoice me-2"></i>
                    No lab invoices found
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = invoices.map(invoice => {
        const statusClass = {
            'pending': 'badge-warning',
            'paid': 'badge-success',
            'overdue': 'badge-danger',
            'partial': 'badge-info'
        }[invoice.status] || 'badge-secondary';
        
        return `
            <tr class="table-hover-row">
                <td class="fw-bold">${invoice.invoice_number}</td>
                <td>
                    <div>
                        <div class="text-white">${invoice.patient_name}</div>
                        <small class="text-light-gray">${invoice.patient_mrn}</small>
                    </div>
                </td>
                <td>
                    <div class="lab-tests-list">
                        ${invoice.lab_tests.slice(0, 2).map(test => 
                            `<span class="badge bg-gradient-primary me-1 mb-1">${test}</span>`
                        ).join('')}
                        ${invoice.lab_tests.length > 2 ? 
                            `<span class="badge bg-gradient-secondary">+${invoice.lab_tests.length - 2} more</span>` : ''
                        }
                    </div>
                </td>
                <td>
                    <span class="badge bg-gradient-info">${invoice.test_count} tests</span>
                </td>
                <td class="fw-bold text-success">$${invoice.amount.toFixed(2)}</td>
                <td>
                    <span class="badge ${statusClass}">${invoice.status.charAt(0).toUpperCase() + invoice.status.slice(1)}</span>
                </td>
                <td class="text-light-gray">${new Date(invoice.created_at).toLocaleDateString()}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-gradient-primary btn-sm" onclick="viewLabInvoice(${invoice.id})">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-gradient-secondary btn-sm" onclick="downloadLabInvoice(${invoice.id})">
                            <i class="fas fa-download"></i>
                        </button>
                        ${invoice.status === 'pending' || invoice.status === 'partial' ? 
                            `<button class="btn btn-gradient-success btn-sm" onclick="markLabInvoicePaid(${invoice.id})">
                                <i class="fas fa-check"></i>
                            </button>` : ''
                        }
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

// Initialize lab financial charts
function initializeLabCharts(stats) {
    // Lab Revenue Trend Chart
    const revenueCtx = document.getElementById('labRevenueChart');
    if (revenueCtx && stats.monthly_revenue) {
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Lab Revenue ($)',
                    data: stats.monthly_revenue,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: '#fff' }
                    }
                },
                scales: {
                    x: { 
                        ticks: { color: '#fff' },
                        grid: { color: 'rgba(255, 255, 255, 0.1)' }
                    },
                    y: { 
                        ticks: { color: '#fff' },
                        grid: { color: 'rgba(255, 255, 255, 0.1)' }
                    }
                }
            }
        });
    }
    
    // Test Categories Chart
    const categoriesCtx = document.getElementById('testCategoriesChart');
    if (categoriesCtx && stats.test_categories) {
        new Chart(categoriesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Hematology', 'Biochemistry', 'Electrolyte', 'Microbiology'],
                datasets: [{
                    data: Object.values(stats.test_categories),
                    backgroundColor: [
                        '#667eea',
                        '#764ba2',
                        '#f093fb',
                        '#4facfe'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#fff' }
                    }
                }
            }
        });
    }
}

async function loadInvoices() {
    // Redirect to lab-specific function
    return loadLabInvoices();
}

async function loadPatients() {
    try {
        const response = await fetch('/api/lab-tech/patients', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const data = await response.json();
        
        patients = data;
        const select = document.getElementById('invoicePatient');
        select.innerHTML = '<option value="">Choose patient...</option>' + 
            data.map(p => `<option value="${p.id}">${p.name} (${p.mrn})</option>`).join('');
    } catch (error) {
        console.error('Error loading patients:', error);
        // For demo purposes, use mock data
        patients = [
            {id: 1, name: 'John Smith', mrn: 'MRN001'},
            {id: 2, name: 'Jane Doe', mrn: 'MRN002'},
            {id: 3, name: 'Bob Johnson', mrn: 'MRN003'},
            {id: 4, name: 'Alice Brown', mrn: 'MRN004'}
        ];
        
        const select = document.getElementById('invoicePatient');
        select.innerHTML = '<option value="">Choose patient...</option>' + 
            patients.map(p => `<option value="${p.id}">${p.name} (${p.mrn})</option>`).join('');
    }
}

async function loadPatientOrders() {
    const patientId = document.getElementById('invoicePatient').value;
    const ordersSelect = document.getElementById('invoiceOrders');
    
    if (!patientId) {
        ordersSelect.innerHTML = '<option value="">Select patient first...</option>';
        return;
    }
    
    try {
        const response = await fetch(`/api/lab-tech/patients/${patientId}/orders`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const orders = await response.json();
        
        ordersSelect.innerHTML = orders.length > 0 ?
            orders.map(order => 
                `<option value="${order.id}" data-price="${order.price}">${order.test_name} - $${order.price.toFixed(2)}</option>`
            ).join('') :
            '<option value="">No completed orders available</option>';
    } catch (error) {
        console.error('Error loading patient orders:', error);
        // Filter mock orders for selected patient
        const patientOrders = labOrders.filter(order => 
            order.patient_id == patientId && 
            order.status === 'resulted' &&
            !order.invoiced
        );
        
        ordersSelect.innerHTML = patientOrders.length > 0 ?
            patientOrders.map(order => 
                `<option value="${order.id}" data-price="${testPrices[order.test_name] || 50}">${order.test_name} - $${(testPrices[order.test_name] || 50).toFixed(2)}</option>`
            ).join('') :
            '<option value="">No completed orders available</option>';
    }
}

function calculateInvoiceAmount() {
    const selectedOptions = Array.from(document.getElementById('invoiceOrders').selectedOptions);
    const total = selectedOptions.reduce((sum, option) => {
        return sum + parseFloat(option.dataset.price || 0);
    }, 0);
    
    document.getElementById('invoiceAmount').value = total.toFixed(2);
}

async function generateInvoice(event) {
    event.preventDefault();
    
    const patientId = document.getElementById('invoicePatient').value;
    const selectedOrders = Array.from(document.getElementById('invoiceOrders').selectedOptions);
    const amount = document.getElementById('invoiceAmount').value;
    
    if (!patientId || selectedOrders.length === 0 || !amount) {
        alert('Please fill in all required fields');
        return;
    }
    
    try {
        const response = await fetch('/api/lab-tech/invoices', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                patient_id: patientId,
                order_ids: selectedOrders.map(opt => opt.value),
                total_amount: parseFloat(amount)
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Clear form
            document.getElementById('invoiceForm').reset();
            document.getElementById('invoiceOrders').innerHTML = '<option value="">Select patient first...</option>';
            
            // Reload data
            loadInvoices();
            loadStats();
            
            showNotification('Invoice generated successfully!', 'success');
        } else {
            alert('Error generating invoice: ' + (result.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error generating invoice:', error);
        
        // For demo purposes, simulate invoice generation
        const patient = patients.find(p => p.id == patientId);
        const newInvoice = {
            id: invoices.length + 1,
            invoice_number: 'LAB' + new Date().toISOString().slice(0,10).replace(/-/g,'') + String(invoices.length + 1).padStart(3, '0'),
            patient_name: patient ? patient.name : 'Unknown Patient',
            patient_mrn: patient ? patient.mrn : 'Unknown',
            lab_orders: selectedOrders.map(opt => opt.text.split(' - ')[0]),
            amount: parseFloat(amount),
            status: 'pending',
            created_at: new Date().toISOString()
        };
        
        invoices.unshift(newInvoice);
        filteredInvoices = invoices;
        renderInvoices(filteredInvoices);
        
        // Clear form
        document.getElementById('invoiceForm').reset();
        document.getElementById('invoiceOrders').innerHTML = '<option value="">Select patient first...</option>';
        
        showNotification('Invoice generated successfully!', 'success');
    }
}

function renderLabOrders(orders) {
    const tbody = document.getElementById('labOrdersTable');
    
    if (!orders || orders.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center py-4">
                    <i class="fas fa-inbox me-2"></i>
                    No lab orders found
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = orders.map(order => {
        const priorityClass = `priority-${order.priority}`;
        const statusClass = `status-${order.status}`;
        
        let actions = '';
        if (order.status === 'ordered') {
            actions = `<button class="btn btn-primary action-btn btn-sm" onclick="showCollectionModal(${order.id}, '${order.patient_name}', '${order.test_name}')">
                <i class="fas fa-vial"></i>
            </button>`;
        } else if (order.status === 'collected' || order.status === 'processing') {
            actions = `<button class="btn btn-success action-btn btn-sm" onclick="showResultModal(${order.id}, '${order.patient_name}', '${order.test_name}')">
                <i class="fas fa-clipboard-check"></i>
            </button>`;
        } else if (order.status === 'resulted') {
            actions = `<span class="text-success"><i class="fas fa-check-circle"></i></span>`;
        }
        
        return `
            <tr>
                <td>#${order.id}</td>
                <td>
                    <div>
                        <strong>${order.patient_name}</strong><br>
                        <small class="text-muted">MRN: ${order.patient_mrn || order.patient_id}</small>
                    </div>
                </td>
                <td>
                    <div>
                        <strong>${order.test_name}</strong><br>
                        <small class="text-muted">${order.test_code || 'N/A'}</small>
                    </div>
                </td>
                <td><span class="priority-badge ${priorityClass}">${order.priority.toUpperCase()}</span></td>
                <td><span class="status-badge ${statusClass}">${order.status_display || order.status.toUpperCase()}</span></td>
                <td>
                    <small>
                        ${formatDateTime(order.ordered_at)}<br>
                        <span class="text-muted">by ${order.ordered_by_name || 'Dr. System'}</span>
                    </small>
                </td>
                <td>${order.collected_at ? formatDateTime(order.collected_at) : '<span class="text-muted">Not collected</span>'}</td>
                <td>${order.resulted_at ? formatDateTime(order.resulted_at) : '<span class="text-muted">Pending</span>'}</td>
                <td>${actions}</td>
            </tr>
        `;
    }).join('');
}

function renderInvoices(invoices) {
    const tbody = document.getElementById('invoicesTable');
    
    if (invoices.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-4">
                    <i class="fas fa-inbox me-2"></i>
                    No invoices found
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = invoices.map(invoice => {
        const statusClass = `invoice-${invoice.status}`;
        
        return `
            <tr>
                <td><strong>${invoice.invoice_number}</strong></td>
                <td>
                    <div>
                        <strong>${invoice.patient_name}</strong><br>
                        <small class="text-muted">MRN: ${invoice.patient_mrn}</small>
                    </div>
                </td>
                <td>
                    <small>${Array.isArray(invoice.lab_orders) ? invoice.lab_orders.join(', ') : 'Lab Tests'}</small>
                </td>
                <td><strong>$${(invoice.amount || invoice.total_amount || 0).toFixed(2)}</strong></td>
                <td>
                    <span class="${statusClass}">
                        <i class="fas fa-circle me-1"></i>
                        ${invoice.status.toUpperCase()}
                    </span>
                </td>
                <td>
                    <small>${formatDateTime(invoice.created_at)}</small>
                </td>
                <td>
                    <div class="d-flex gap-1">
                        ${getInvoiceActionButtons(invoice)}
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

function getInvoiceActionButtons(invoice) {
    let buttons = [];
    
    if (invoice.status === 'pending') {
        buttons.push(`
            <button class="btn btn-success action-btn btn-sm" onclick="openPaymentModal(${invoice.id})" title="Collect Payment">
                <i class="fas fa-dollar-sign"></i>
            </button>
        `);
    }
    
    buttons.push(`
        <button class="btn btn-outline-light action-btn btn-sm" onclick="printInvoice(${invoice.id})" title="Print Invoice">
            <i class="fas fa-print"></i>
        </button>
    `);
    
    return buttons.join('');
}

function showCollectionModal(orderId, patientName, testName) {
    document.getElementById('collectionOrderId').value = orderId;
    document.getElementById('collectionPatientName').textContent = patientName;
    document.getElementById('collectionTestName').textContent = testName;
    setCurrentDateTime();
    
    const modal = new bootstrap.Modal(document.getElementById('collectionModal'));
    modal.show();
}

function showResultModal(orderId, patientName, testName) {
    document.getElementById('resultOrderId').value = orderId;
    document.getElementById('resultPatientName').textContent = patientName;
    document.getElementById('resultTestName').textContent = testName;
    setCurrentDateTime();
    
    const modal = new bootstrap.Modal(document.getElementById('resultModal'));
    modal.show();
}

function openPaymentModal(invoiceId) {
    const invoice = invoices.find(inv => inv.id === invoiceId);
    if (invoice) {
        document.getElementById('paymentInvoiceId').value = invoiceId;
        document.getElementById('amountReceived').value = (invoice.amount || invoice.total_amount || 0).toFixed(2);
        const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
        modal.show();
    }
}

async function submitCollection() {
    const orderId = document.getElementById('collectionOrderId').value;
    const collectionTime = document.getElementById('collectionTime').value;
    const notes = document.getElementById('collectionNotes').value;
    
    try {
        const response = await fetch(`/api/lab-tech/orders/${orderId}/collect`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                collected_at: collectionTime,
                collection_notes: notes
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('Sample collection recorded successfully!', 'success');
            bootstrap.Modal.getInstance(document.getElementById('collectionModal')).hide();
            loadLabOrders();
            loadStats();
        } else {
            showAlert(result.message || 'Error recording collection', 'error');
        }
    } catch (error) {
        console.error('Error submitting collection:', error);
        showAlert('Error recording collection', 'error');
    }
}

async function submitResult() {
    const orderId = document.getElementById('resultOrderId').value;
    const resultValue = document.getElementById('resultValue').value;
    const resultFlag = document.getElementById('resultFlag').value;
    const resultNotes = document.getElementById('resultNotes').value;
    const resultTime = document.getElementById('resultTime').value;
    
    if (!resultValue.trim()) {
        showAlert('Please enter a result value', 'warning');
        return;
    }
    
    try {
        const response = await fetch(`/api/lab-tech/orders/${orderId}/result`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                result_value: resultValue,
                result_flag: resultFlag,
                result_notes: resultNotes,
                resulted_at: resultTime
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('Test results submitted successfully!', 'success');
            bootstrap.Modal.getInstance(document.getElementById('resultModal')).hide();
            loadLabOrders();
            loadStats();
            
            // Clear form
            document.getElementById('resultForm').reset();
        } else {
            showAlert(result.message || 'Error submitting results', 'error');
        }
    } catch (error) {
        console.error('Error submitting results:', error);
        showAlert('Error submitting results', 'error');
    }
}

async function collectPayment() {
    const invoiceId = document.getElementById('paymentInvoiceId').value;
    const paymentMethod = document.getElementById('paymentMethod').value;
    const amountReceived = document.getElementById('amountReceived').value;
    const notes = document.getElementById('paymentNotes').value;
    
    if (!paymentMethod || !amountReceived) {
        alert('Please fill in required fields');
        return;
    }
    
    try {
        const response = await fetch('/api/lab-tech/invoices/payment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                invoice_id: invoiceId,
                payment_method: paymentMethod,
                amount_received: parseFloat(amountReceived),
                payment_notes: notes
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            bootstrap.Modal.getInstance(document.getElementById('paymentModal')).hide();
            
            // Clear form
            document.getElementById('paymentForm').reset();
            
            showNotification('Payment collected successfully!', 'success');
            loadInvoices();
            loadStats();
        } else {
            alert('Error collecting payment: ' + (result.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error collecting payment:', error);
        
        // For demo purposes, simulate payment collection
        const invoice = invoices.find(inv => inv.id == invoiceId);
        if (invoice) {
            invoice.status = 'paid';
            invoice.payment_method = paymentMethod;
            invoice.amount_received = parseFloat(amountReceived);
            invoice.payment_notes = notes;
            invoice.paid_at = new Date().toISOString();
            
            renderInvoices(filteredInvoices);
            bootstrap.Modal.getInstance(document.getElementById('paymentModal')).hide();
            
            // Clear form
            document.getElementById('paymentForm').reset();
            
            showNotification('Payment collected successfully!', 'success');
        }
    }
}

function filterInvoices() {
    const statusFilter = document.getElementById('invoiceStatusFilter').value;
    
    filteredInvoices = invoices.filter(invoice => {
        return !statusFilter || invoice.status === statusFilter;
    });
    
    renderInvoices(filteredInvoices);
}

function printInvoice(invoiceId) {
    const invoice = invoices.find(inv => inv.id === invoiceId);
    if (invoice) {
        // Create a simple print window
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Invoice ${invoice.invoice_number}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; margin-bottom: 30px; }
                        .invoice-details { margin-bottom: 20px; }
                        .total { font-size: 18px; font-weight: bold; margin-top: 20px; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>MedGemma Lab Invoice</h1>
                        <h2>${invoice.invoice_number}</h2>
                    </div>
                    <div class="invoice-details">
                        <p><strong>Patient:</strong> ${invoice.patient_name}</p>
                        <p><strong>MRN:</strong> ${invoice.patient_mrn}</p>
                        <p><strong>Date:</strong> ${new Date(invoice.created_at).toLocaleDateString()}</p>
                        <p><strong>Lab Tests:</strong></p>
                        <ul>
                            ${(Array.isArray(invoice.lab_orders) ? invoice.lab_orders : ['Lab Tests']).map(test => `<li>${test}</li>`).join('')}
                        </ul>
                    </div>
                    <div class="total">
                        <p>Total Amount: $${(invoice.amount || invoice.total_amount || 0).toFixed(2)}</p>
                        <p>Status: ${invoice.status.toUpperCase()}</p>
                    </div>
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }
}

function formatDateTime(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

function showNotification(message, type = 'info') {
    showAlert(message, type);
}

// Equipment Integration Functions
let equipmentData = [];
let recentResults = [];
let cameraStream = null;

// Load equipment data
async function loadEquipmentData() {
    try {
        const response = await fetch('/api/lab-equipment/');
        const data = await response.json();
        equipmentData = data.equipment;
        renderEquipmentCards();
        
        // Load equipment statistics
        await loadEquipmentStatistics();
    } catch (error) {
        console.error('Error loading equipment data:', error);
        showAlert('Failed to load equipment data', 'error');
    }
}

// Load equipment statistics
async function loadEquipmentStatistics() {
    try {
        const response = await fetch('/api/lab-equipment/statistics');
        const stats = await response.json();
        
        document.getElementById('activeEquipmentCount').textContent = stats.active_equipment;
        document.getElementById('onlineEquipmentCount').textContent = stats.online_equipment;
        document.getElementById('pendingVerificationCount').textContent = stats.pending_verification;
    } catch (error) {
        console.error('Error loading equipment statistics:', error);
    }
}

// Render equipment cards
function renderEquipmentCards() {
    const container = document.getElementById('equipmentList');
    container.innerHTML = '';
    
    equipmentData.forEach(equipment => {
        const statusClass = equipment.is_online ? 'success' : 'danger';
        const statusIcon = equipment.is_online ? 'wifi' : 'wifi-off';
        
        const card = document.createElement('div');
        card.className = 'col-lg-4 col-md-6';
        card.innerHTML = `
            <div class="glass-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h6 class="text-white mb-1">${equipment.name}</h6>
                        <span class="badge bg-${statusClass}">
                            <i class="fas fa-${statusIcon} me-1"></i>
                            ${equipment.is_online ? 'Online' : 'Offline'}
                        </span>
                    </div>
                    <p class="text-light-gray small mb-2">${equipment.model} - ${equipment.manufacturer}</p>
                    <p class="text-light-gray small mb-3">Results Today: ${equipment.recent_results_count}</p>
                    <div class="d-flex gap-2">
                        <button class="btn btn-gradient-primary btn-sm flex-fill" 
                                onclick="fetchEquipmentResults(${equipment.id})">
                            <i class="fas fa-download me-1"></i>Fetch
                        </button>
                        <button class="btn btn-gradient-secondary btn-sm" 
                                onclick="testEquipmentConnection(${equipment.id})">
                            <i class="fas fa-plug me-1"></i>Test
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(card);
    });
}

// Fetch results from specific equipment
async function fetchEquipmentResults(equipmentId) {
    try {
        const response = await fetch('/api/lab-equipment/fetch-results', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ equipment_id: equipmentId })
        });
        
        const result = await response.json();
        if (result.success) {
            showAlert(`Successfully fetched ${result.results.length} results from ${result.equipment}`, 'success');
            await loadRecentResults();
            await loadEquipmentStatistics();
        } else {
            showAlert(`Failed to fetch results: ${result.error}`, 'error');
        }
    } catch (error) {
        console.error('Error fetching equipment results:', error);
        showAlert('Failed to fetch equipment results', 'error');
    }
}

// Fetch results from all equipment
async function fetchAllResults() {
    try {
        const response = await fetch('/api/lab-equipment/fetch-results', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        });
        
        const result = await response.json();
        if (result.success) {
            let totalResults = 0;
            Object.values(result.results).forEach(equipmentResults => {
                if (Array.isArray(equipmentResults)) {
                    totalResults += equipmentResults.length;
                }
            });
            
            showAlert(`Successfully fetched ${totalResults} results from all equipment`, 'success');
            await loadRecentResults();
            await loadEquipmentStatistics();
        } else {
            showAlert(`Failed to fetch results: ${result.error}`, 'error');
        }
    } catch (error) {
        console.error('Error fetching all results:', error);
        showAlert('Failed to fetch equipment results', 'error');
    }
}

// Test equipment connection
async function testEquipmentConnection(equipmentId) {
    try {
        const response = await fetch(`/api/lab-equipment/${equipmentId}/test-connection`, {
            method: 'POST'
        });
        
        const result = await response.json();
        if (result.success) {
            showAlert(result.message, 'success');
        } else {
            showAlert(`Connection failed: ${result.error}`, 'error');
        }
        
        // Refresh equipment data
        await loadEquipmentData();
    } catch (error) {
        console.error('Error testing equipment connection:', error);
        showAlert('Failed to test equipment connection', 'error');
    }
}

// Load recent results
async function loadRecentResults() {
    try {
        const sourceFilter = document.getElementById('resultSourceFilter').value;
        const statusFilter = document.getElementById('resultStatusFilter').value;
        
        const params = new URLSearchParams();
        if (sourceFilter) params.append('source_type', sourceFilter);
        if (statusFilter) params.append('status', statusFilter);
        
        const response = await fetch(`/api/lab-equipment/results?${params}`);
        const data = await response.json();
        recentResults = data.results;
        renderRecentResults();
    } catch (error) {
        console.error('Error loading recent results:', error);
        showAlert('Failed to load recent results', 'error');
    }
}

// Render recent results table
function renderRecentResults() {
    const tbody = document.getElementById('recentResultsTable');
    tbody.innerHTML = '';
    
    recentResults.forEach(result => {
        const row = document.createElement('tr');
        const sourceIcon = {
            'equipment': 'fas fa-microscope text-primary',
            'ocr': 'fas fa-camera text-warning',
            'manual': 'fas fa-keyboard text-info'
        }[result.source_type] || 'fas fa-question text-secondary';
        
        const statusBadge = {
            'preliminary': 'badge bg-warning',
            'final': 'badge bg-success',
            'needs_verification': 'badge bg-danger',
            'corrected': 'badge bg-info'
        }[result.result_status] || 'badge bg-secondary';
        
        row.innerHTML = `
            <td>${result.test_name}</td>
            <td>${result.lab_order?.patient?.name || 'Unknown'}</td>
            <td>${result.result_value} ${result.result_units || ''}</td>
            <td><i class="${sourceIcon}"></i> ${result.source_type}</td>
            <td><span class="${statusBadge}">${result.result_status}</span></td>
            <td>${new Date(result.performed_at).toLocaleDateString()}</td>
            <td>
                ${result.result_status === 'needs_verification' ? 
                    `<button class="btn btn-gradient-warning btn-sm" onclick="verifyResult(${result.id})">
                        <i class="fas fa-check me-1"></i>Verify
                    </button>` : 
                    `<button class="btn btn-gradient-info btn-sm" onclick="viewResult(${result.id})">
                        <i class="fas fa-eye me-1"></i>View
                    </button>`
                }
            </td>
        `;
        tbody.appendChild(row);
    });
}

// OCR Functions
async function loadOCRDropdowns() {
    try {
        // Load lab orders for OCR
        const ordersResponse = await fetch('/api/lab-tech/orders');
        const ordersData = await ordersResponse.json();
        
        const orderSelect = document.getElementById('ocrLabOrder');
        orderSelect.innerHTML = '<option value="">Select Lab Order</option>';
        
        ordersData.orders?.forEach(order => {
            const option = document.createElement('option');
            option.value = order.id;
            option.textContent = `#${order.id} - ${order.patient_name} - ${order.test_name}`;
            orderSelect.appendChild(option);
        });
        
        // Load equipment for OCR
        const equipmentSelect = document.getElementById('ocrEquipment');
        equipmentSelect.innerHTML = '<option value="">Manual Entry</option>';
        
        equipmentData.forEach(equipment => {
            const option = document.createElement('option');
            option.value = equipment.id;
            option.textContent = equipment.name;
            equipmentSelect.appendChild(option);
        });
    } catch (error) {
        console.error('Error loading OCR dropdowns:', error);
    }
}

// Handle OCR form submission
document.getElementById('ocrUploadForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData();
    formData.append('lab_order_id', document.getElementById('ocrLabOrder').value);
    formData.append('image', document.getElementById('resultImage').files[0]);
    
    const equipmentId = document.getElementById('ocrEquipment').value;
    if (equipmentId) {
        formData.append('equipment_id', equipmentId);
    }
    
    try {
        const response = await fetch('/api/lab-equipment/upload-result-image', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        if (result.success) {
            showAlert('OCR processing completed successfully', 'success');
            document.getElementById('ocrUploadForm').reset();
            await loadRecentResults();
            await loadEquipmentStatistics();
        } else {
            showAlert(`OCR processing failed: ${result.error}`, 'error');
        }
    } catch (error) {
        console.error('Error processing OCR:', error);
        showAlert('Failed to process OCR', 'error');
    }
});

// Camera functions
async function openCamera() {
    try {
        cameraStream = await navigator.mediaDevices.getUserMedia({ 
            video: { facingMode: 'environment' } // Use back camera if available
        });
        
        const video = document.getElementById('cameraVideo');
        video.srcObject = cameraStream;
        
        const modal = new bootstrap.Modal(document.getElementById('cameraModal'));
        modal.show();
    } catch (error) {
        console.error('Error accessing camera:', error);
        showAlert('Failed to access camera. Please check permissions.', 'error');
    }
}

function capturePhoto() {
    const video = document.getElementById('cameraVideo');
    const canvas = document.getElementById('cameraCanvas');
    const ctx = canvas.getContext('2d');
    
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    ctx.drawImage(video, 0, 0);
    
    canvas.toBlob(function(blob) {
        const file = new File([blob], 'captured_result.jpg', { type: 'image/jpeg' });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        document.getElementById('resultImage').files = dataTransfer.files;
        
        // Close camera modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('cameraModal'));
        modal.hide();
        
        // Stop camera stream
        if (cameraStream) {
            cameraStream.getTracks().forEach(track => track.stop());
        }
        
        showAlert('Photo captured successfully', 'success');
    }, 'image/jpeg', 0.8);
}

function retakePhoto() {
    // Just keep the camera running for retake
    showAlert('Ready to take another photo', 'info');
}

// Verify result function
async function verifyResult(resultId) {
    try {
        const response = await fetch(`/api/lab-equipment/results/${resultId}/verify`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ verified: true })
        });
        
        const result = await response.json();
        if (result.success) {
            showAlert('Result verified successfully', 'success');
            await loadRecentResults();
            await loadEquipmentStatistics();
        } else {
            showAlert(`Verification failed: ${result.error}`, 'error');
        }
    } catch (error) {
        console.error('Error verifying result:', error);
        showAlert('Failed to verify result', 'error');
    }
}

// View result function
function viewResult(resultId) {
    const result = recentResults.find(r => r.id === resultId);
    if (result) {
        let modalContent = `
            <div class="modal fade" id="resultModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content bg-dark">
                        <div class="modal-header border-purple">
                            <h5 class="modal-title text-white">Lab Result Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="text-light-gray small">Test Name</label>
                                    <p class="text-white">${result.test_name}</p>
                                </div>
                                <div class="col-6">
                                    <label class="text-light-gray small">Result</label>
                                    <p class="text-white">${result.result_value} ${result.result_units || ''}</p>
                                </div>
                                <div class="col-6">
                                    <label class="text-light-gray small">Source</label>
                                    <p class="text-white">${result.source_type}</p>
                                </div>
                                <div class="col-6">
                                    <label class="text-light-gray small">Status</label>
                                    <p class="text-white">${result.result_status}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalContent);
        const modal = new bootstrap.Modal(document.getElementById('resultModal'));
        modal.show();
        
        // Remove modal after hiding
        document.getElementById('resultModal').addEventListener('hidden.bs.modal', function() {
            this.remove();
        });
    }
}

// Lab Invoice Helper Functions

// View lab invoice details
function viewLabInvoice(invoiceId) {
    const invoice = labInvoices.find(inv => inv.id === invoiceId);
    if (!invoice) return;
    
    let modalContent = `
        <div class="modal fade" id="labInvoiceModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content bg-dark">
                    <div class="modal-header border-purple">
                        <h5 class="modal-title text-white">
                            <i class="fas fa-file-invoice me-2"></i>Lab Invoice Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-light-gray small">Invoice Number</label>
                                <p class="text-white fw-bold">${invoice.invoice_number}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-light-gray small">Patient</label>
                                <p class="text-white">${invoice.patient_name} (${invoice.patient_mrn})</p>
                            </div>
                            <div class="col-12">
                                <label class="text-light-gray small">Lab Tests</label>
                                <div class="lab-tests-detail">
                                    ${invoice.lab_tests.map(test => 
                                        `<span class="badge bg-gradient-primary me-2 mb-2">${test}</span>`
                                    ).join('')}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-light-gray small">Test Count</label>
                                <p class="text-white">${invoice.test_count} tests</p>
                            </div>
                            <div class="col-md-4">
                                <label class="text-light-gray small">Amount</label>
                                <p class="text-white fw-bold">$${invoice.amount.toFixed(2)}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="text-light-gray small">Status</label>
                                <p class="text-white">
                                    <span class="badge ${
                                        invoice.status === 'paid' ? 'badge-success' : 
                                        invoice.status === 'pending' ? 'badge-warning' : 
                                        invoice.status === 'overdue' ? 'badge-danger' : 'badge-info'
                                    }">${invoice.status.charAt(0).toUpperCase() + invoice.status.slice(1)}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-purple">
                        <button class="btn btn-gradient-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-gradient-primary" onclick="downloadLabInvoice(${invoice.id})">
                            <i class="fas fa-download me-1"></i>Download PDF
                        </button>
                        ${invoice.status !== 'paid' ? 
                            `<button class="btn btn-gradient-success" onclick="markLabInvoicePaid(${invoice.id})">
                                <i class="fas fa-check me-1"></i>Mark as Paid
                            </button>` : ''
                        }
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalContent);
    const modal = new bootstrap.Modal(document.getElementById('labInvoiceModal'));
    modal.show();
    
    document.getElementById('labInvoiceModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

// Download lab invoice
async function downloadLabInvoice(invoiceId) {
    try {
        const response = await fetch(`/api/lab-tech/lab-invoices/${invoiceId}/download`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `lab-invoice-${invoiceId}.pdf`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            showAlert('Invoice downloaded successfully', 'success');
        } else {
            showAlert('Failed to download invoice', 'error');
        }
    } catch (error) {
        console.error('Error downloading invoice:', error);
        showAlert('Failed to download invoice', 'error');
    }
}

// Mark lab invoice as paid
async function markLabInvoicePaid(invoiceId) {
    try {
        const response = await fetch(`/api/lab-tech/lab-invoices/${invoiceId}/mark-paid`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const result = await response.json();
        if (result.success) {
            showAlert('Invoice marked as paid', 'success');
            loadLabInvoices(); // Refresh the invoice list
            
            // Close modal if open
            const modal = document.getElementById('labInvoiceModal');
            if (modal) {
                bootstrap.Modal.getInstance(modal).hide();
            }
        } else {
            showAlert('Failed to update invoice status', 'error');
        }
    } catch (error) {
        console.error('Error updating invoice:', error);
        showAlert('Failed to update invoice status', 'error');
    }
}

// Filter lab invoices
function filterLabInvoices() {
    const statusFilter = document.getElementById('labInvoiceStatusFilter').value;
    const testTypeFilter = document.getElementById('labTestTypeFilter').value;
    
    filteredLabInvoices = labInvoices.filter(invoice => {
        const statusMatch = !statusFilter || invoice.status === statusFilter;
        const testTypeMatch = !testTypeFilter || 
            (invoice.test_types && invoice.test_types.includes(testTypeFilter));
        
        return statusMatch && testTypeMatch;
    });
    
    renderLabInvoices(filteredLabInvoices);
}

// Generate lab invoice form submission
async function generateLabInvoice(event) {
    event.preventDefault();
    
    const formData = {
        patient_id: document.getElementById('labInvoicePatient').value,
        lab_orders: Array.from(document.getElementById('labInvoiceOrders').selectedOptions).map(option => option.value),
        discount: parseFloat(document.getElementById('labDiscount').value) || 0
    };
    
    if (!formData.patient_id || formData.lab_orders.length === 0) {
        showAlert('Please select patient and lab orders', 'error');
        return;
    }
    
    try {
        const response = await fetch('/api/lab-tech/generate-lab-invoice', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(formData)
        });
        
        const result = await response.json();
        if (result.success) {
            showAlert(`Lab invoice ${result.invoice_number} generated successfully`, 'success');
            document.getElementById('labInvoiceForm').reset();
            loadLabInvoices(); // Refresh the invoice list
        } else {
            showAlert('Failed to generate lab invoice', 'error');
        }
    } catch (error) {
        console.error('Error generating lab invoice:', error);
        showAlert('Failed to generate lab invoice', 'error');
    }
}

// Load patient lab orders for invoice generation
async function loadPatientLabOrders() {
    const patientId = document.getElementById('labInvoicePatient').value;
    const ordersSelect = document.getElementById('labInvoiceOrders');
    
    if (!patientId) {
        ordersSelect.innerHTML = '<option value="">Select patient first...</option>';
        return;
    }
    
    try {
        const response = await fetch(`/api/lab-tech/patients/${patientId}/completed-lab-orders`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const orders = await response.json();
        
        ordersSelect.innerHTML = orders.length > 0 ?
            orders.map(order => 
                `<option value="${order.id}" data-price="${order.total_amount}">
                    ${order.test_names.join(', ')} - $${order.total_amount.toFixed(2)}
                </option>`
            ).join('') :
            '<option value="">No completed lab orders available</option>';
    } catch (error) {
        console.error('Error loading patient lab orders:', error);
        // For demo purposes, use mock data
        const mockOrders = [
            {id: 1, test_names: ['Complete Blood Count'], total_amount: 45.00},
            {id: 2, test_names: ['Lipid Panel'], total_amount: 55.00},
            {id: 3, test_names: ['Liver Function Tests'], total_amount: 80.00},
            {id: 4, test_names: ['Electrolyte Panel'], total_amount: 35.00}
        ];
        
        ordersSelect.innerHTML = mockOrders.map(order => 
            `<option value="${order.id}" data-price="${order.total_amount}">
                ${order.test_names.join(', ')} - $${order.total_amount.toFixed(2)}
            </option>`
        ).join('');
    }
}

// Calculate lab invoice amount with discount
function calculateLabInvoiceAmount() {
    const ordersSelect = document.getElementById('labInvoiceOrders');
    const amountInput = document.getElementById('labInvoiceAmount');
    const discountInput = document.getElementById('labDiscount');
    
    let total = 0;
    Array.from(ordersSelect.selectedOptions).forEach(option => {
        total += parseFloat(option.dataset.price) || 0;
    });
    
    const discount = parseFloat(discountInput.value) || 0;
    const discountAmount = total * (discount / 100);
    const finalAmount = total - discountAmount;
    
    amountInput.value = finalAmount.toFixed(2);
}

// Load patients for lab invoice generation
async function loadPatientsForLabInvoice() {
    try {
        const response = await fetch('/api/lab-tech/patients-with-completed-orders', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const data = await response.json();
        
        const select = document.getElementById('labInvoicePatient');
        select.innerHTML = '<option value="">Choose patient...</option>' + 
            data.map(p => `<option value="${p.id}">${p.name} (${p.mrn})</option>`).join('');
    } catch (error) {
        console.error('Error loading patients:', error);
        // For demo purposes, use mock data
        const patients = [
            {id: 1, name: 'John Smith', mrn: 'MRN001'},
            {id: 2, name: 'Jane Doe', mrn: 'MRN002'},
            {id: 3, name: 'Bob Johnson', mrn: 'MRN003'},
            {id: 4, name: 'Alice Brown', mrn: 'MRN004'}
        ];
        
        const select = document.getElementById('labInvoicePatient');
        select.innerHTML = '<option value="">Choose patient...</option>' + 
            patients.map(p => `<option value="${p.id}">${p.name} (${p.mrn})</option>`).join('');
    }
}

// Enhanced document ready
document.addEventListener('DOMContentLoaded', function() {
    // Existing initialization...
    loadStats();
    loadLabOrders();
    loadLabInvoices(); // Changed from loadInvoices
    loadPatientsForLabInvoice(); // Changed from loadPatients
    
    // New equipment initialization
    loadEquipmentData();
    loadOCRDropdowns();
    loadRecentResults();
    
    // Enhanced Interactive Features
    
    // Smooth number animations for stats
    window.animateValue = function(element, start, end, duration = 1000) {
        if (!element) return;
        
        const startTimestamp = performance.now();
        const step = (timestamp) => {
            const elapsed = timestamp - startTimestamp;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function for smooth animation
            const easeOutCubic = 1 - Math.pow(1 - progress, 3);
            const current = Math.floor(start + (end - start) * easeOutCubic);
            
            element.textContent = current;
            
            if (progress < 1) {
                requestAnimationFrame(step);
            }
        };
        requestAnimationFrame(step);
    };
    
    // Enhanced table interactions
    function enhanceTableInteractivity() {
        // Add click-to-expand functionality
        setTimeout(() => {
            document.querySelectorAll('.table tbody tr').forEach(row => {
                row.addEventListener('click', function() {
                    // Add highlighting effect
                    document.querySelectorAll('.table tbody tr').forEach(r => r.classList.remove('table-active'));
                    this.classList.add('table-active');
                    
                    // Add pulse effect
                    this.style.animation = 'pulse 0.5s ease-in-out';
                    setTimeout(() => {
                        this.style.animation = '';
                    }, 500);
                });
            });
        }, 1000);
    }
    
    // Real-time status indicators
    function updateStatusIndicators() {
        // Equipment status indicators
        document.querySelectorAll('.equipment-status').forEach(indicator => {
            const status = indicator.dataset.status;
            indicator.classList.remove('pulse-slow');
            if (status === 'online') {
                indicator.classList.add('pulse-slow');
            }
        });
    }
    
    // Enhanced search functionality
    function setupEnhancedSearch() {
        const searchInputs = document.querySelectorAll('.search-input');
        searchInputs.forEach(input => {
            input.addEventListener('input', debounce(function() {
                const searchTerm = this.value.toLowerCase();
                const tableRows = this.closest('.card').querySelectorAll('tbody tr');
                
                tableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        row.style.display = '';
                        row.style.animation = 'fadeIn 0.3s ease-in-out';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }, 300));
        });
    }
    
    // Debounce function for search
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Tab switching with smooth transitions
    function setupSmoothTabSwitching() {
        const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
        tabButtons.forEach(button => {
            button.addEventListener('shown.bs.tab', function() {
                const targetTab = document.querySelector(this.getAttribute('data-bs-target'));
                if (targetTab) {
                    targetTab.style.opacity = '0';
                    targetTab.style.transform = 'translateY(20px)';
                    
                    setTimeout(() => {
                        targetTab.style.transition = 'all 0.3s ease-in-out';
                        targetTab.style.opacity = '1';
                        targetTab.style.transform = 'translateY(0)';
                    }, 10);
                }
            });
        });
    }
    
    // Initialize enhanced features
    setupEnhancedSearch();
    setupSmoothTabSwitching();
    enhanceTableInteractivity();
    updateStatusIndicators();
    
    // Enhanced stats updater with animations
    const originalLoadStats = window.loadStats;
    window.loadStats = async function() {
        try {
            const response = await fetch('/api/lab-stats');
            const stats = await response.json();
            
            // Animate the numbers instead of just setting them
            if (document.getElementById('pendingOrders')) {
                animateValue(document.getElementById('pendingOrders'), 
                    parseInt(document.getElementById('pendingOrders').textContent) || 0, 
                    stats.pending || 0);
            }
            if (document.getElementById('samplesCollected')) {
                animateValue(document.getElementById('samplesCollected'), 
                    parseInt(document.getElementById('samplesCollected').textContent) || 0, 
                    stats.collected || 0);
            }
            if (document.getElementById('completedOrders')) {
                animateValue(document.getElementById('completedOrders'), 
                    parseInt(document.getElementById('completedOrders').textContent) || 0, 
                    stats.completed || 0);
            }
            if (document.getElementById('inProgressOrders')) {
                animateValue(document.getElementById('inProgressOrders'), 
                    parseInt(document.getElementById('inProgressOrders').textContent) || 0, 
                    stats.in_progress || 0);
            }
            
            updateStatusIndicators();
            enhanceTableInteractivity();
        } catch (error) {
            console.error('Error loading stats:', error);
            // Fallback to original function if it exists
            if (originalLoadStats) originalLoadStats();
        }
    };
    
    // Auto refresh every 30 seconds
    setInterval(() => {
        loadStats();
        loadLabOrders();
        loadLabInvoices(); // Changed from loadInvoices to loadLabInvoices
        loadEquipmentData();
        loadRecentResults();
    }, 30000);
    
    // Enhanced filter event listeners
    document.getElementById('statusFilter').addEventListener('change', loadLabOrders);
    document.getElementById('priorityFilter').addEventListener('change', loadLabOrders);
    document.getElementById('dateFilter').addEventListener('change', loadLabOrders);
    document.getElementById('labInvoiceStatusFilter').addEventListener('change', filterLabInvoices); // Updated
    document.getElementById('labTestTypeFilter').addEventListener('change', filterLabInvoices); // Added
    document.getElementById('resultSourceFilter').addEventListener('change', loadRecentResults);
    document.getElementById('resultStatusFilter').addEventListener('change', loadRecentResults);
    
    // Lab-specific form listeners
    document.getElementById('labInvoicePatient').addEventListener('change', loadPatientLabOrders); // Updated
    document.getElementById('labInvoiceOrders').addEventListener('change', calculateLabInvoiceAmount); // Updated
    document.getElementById('labInvoiceForm').addEventListener('submit', generateLabInvoice); // Updated
    document.getElementById('labDiscount').addEventListener('input', calculateLabInvoiceAmount); // Added discount calculation
});
</script>
@endsection
