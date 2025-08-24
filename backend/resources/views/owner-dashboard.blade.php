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
        
        /* Notification styles */
        .notification-bell {
            position: relative;
            cursor: pointer;
        }
        
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .notification-dropdown {
            width: 350px;
            max-height: 400px;
            overflow-y: auto;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border: none;
            border-radius: 12px;
            background: white;
        }
        
        .notification-item {
            border-bottom: 1px solid #f0f0f0;
            padding: 15px;
            transition: background-color 0.2s;
        }
        
        .notification-item:hover {
            background-color: #f8f9fa;
        }
        
        .notification-item.unread {
            background-color: rgba(0, 123, 255, 0.05);
            border-left: 4px solid #007bff;
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
        
        /* Tab Styles for Owner Dashboard */
        .nav-tabs {
            border-bottom: 2px solid rgba(212, 175, 55, 0.3);
        }
        .nav-tabs .nav-link {
            color: rgba(255, 255, 255, 0.7);
            border: none;
            border-bottom: 2px solid transparent;
            background: none;
            padding: 12px 20px;
            margin-right: 10px;
            border-radius: 8px 8px 0 0;
            transition: all 0.3s ease;
        }
        .nav-tabs .nav-link:hover {
            color: #D4AF37;
            background: rgba(212, 175, 55, 0.1);
            border-bottom-color: #D4AF37;
        }
        .nav-tabs .nav-link.active {
            color: #D4AF37;
            background: rgba(212, 175, 55, 0.2);
            border-bottom-color: #D4AF37;
            font-weight: bold;
        }
        .tab-content {
            min-height: 300px;
        }
        .supplier-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        .supplier-card:hover {
            background: rgba(212, 175, 55, 0.1);
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
        
        /* Doctor Earnings Cards */
        .doctor-earnings-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 15px;
            padding: 20px;
            height: 100%;
            transition: all 0.3s ease;
        }
        .doctor-earnings-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.2);
            border-color: rgba(212, 175, 55, 0.5);
        }
        .doctor-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .doctor-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(45deg, #D4AF37, #FFD700);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: #2c1810;
            font-size: 1.5rem;
        }
        .doctor-info {
            flex: 1;
        }
        .doctor-info h6 {
            color: #D4AF37;
            font-weight: 600;
            margin: 0;
        }
        .doctor-total {
            text-align: right;
        }
        .doctor-total .owner-highlight {
            font-size: 1.8rem;
            font-weight: bold;
        }
        .earnings-breakdown {
            margin: 20px 0;
        }
        .earning-item {
            margin-bottom: 15px;
        }
        .earning-item .progress {
            height: 6px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }
        .earning-item .progress-bar {
            border-radius: 3px;
        }
        .doctor-actions {
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }
        .stat-item .owner-highlight {
            font-size: 1.5rem;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        
        /* Expense Tracker Styles */
        .expense-metric {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 15px;
        }
        
        .expense-metric .d-flex {
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        
        .expense-breakdown {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 6px;
            padding: 10px;
        }
        
        .expense-breakdown .d-flex {
            margin-bottom: 5px;
            font-size: 0.85rem;
        }
        
        .progress {
            background: rgba(255, 255, 255, 0.1) !important;
        }
        
        /* AI Insights Styling */
        #aiInsightsSection .alert {
            background: linear-gradient(135deg, rgba(23, 162, 184, 0.1), rgba(0, 123, 255, 0.1));
            border: 1px solid rgba(23, 162, 184, 0.3);
            color: white;
        }
        
        #aiInsights p strong {
            color: #17a2b8;
        }
        
        /* Business Intelligence Cards */
        .bi-metric-card {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            margin-bottom: 15px;
            border-left: 4px solid;
            transition: all 0.3s ease;
        }
        
        .bi-metric-card:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.12);
        }
        
        .bi-positive { border-left-color: #28a745; }
        .bi-warning { border-left-color: #ffc107; }
        .bi-danger { border-left-color: #dc3545; }
        .bi-info { border-left-color: #17a2b8; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="#">
                <i class="fas fa-crown me-2"></i>Business Owner Dashboard
            </a>
            <div class="ms-auto d-flex align-items-center">
                <!-- Notifications Bell -->
                <div class="dropdown me-3">
                    <div class="notification-bell" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell text-white fs-5"></i>
                        <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                    </div>
                    <ul class="dropdown-menu notification-dropdown dropdown-menu-end p-0">
                        <li class="dropdown-header bg-primary text-white">
                            <strong>Notifications</strong>
                            <button class="btn btn-sm btn-outline-light float-end" onclick="markAllAsRead()">
                                Mark all read
                            </button>
                        </li>
                        <div id="notificationsList">
                            <li class="notification-item text-center text-muted py-4">
                                Loading notifications...
                            </li>
                        </div>
                        <li class="dropdown-footer text-center border-top">
                            <a href="#" class="btn btn-link btn-sm text-primary" onclick="viewAllNotifications()">
                                View All Notifications
                            </a>
                        </li>
                    </ul>
                </div>
                
                <span class="navbar-text me-3">Welcome, {{ Auth::user()->name ?? 'Business Owner' }}</span>
                <a href="/logout" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <!-- Owner Control Center with Tabs -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="glass-card p-4">
                    <h4 class="mb-3"><i class="fas fa-lightning-bolt me-2"></i>Owner Control Center</h4>
                    
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs mb-4" id="ownerTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                                <i class="fas fa-chart-line me-2"></i>Business Overview
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">
                                <i class="fas fa-users me-2"></i>Staff Management
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="suppliers-tab" data-bs-toggle="tab" data-bs-target="#suppliers" type="button" role="tab">
                                <i class="fas fa-truck me-2"></i>Supplier Management
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports" type="button" role="tab">
                                <i class="fas fa-chart-bar me-2"></i>Reports & Analytics
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab">
                                <i class="fas fa-cog me-2"></i>Business Settings
                            </button>
                        </li>
                    </ul>
                    
                    <!-- Tab Content -->
                    <div class="tab-content" id="ownerTabContent">
                        <!-- Business Overview Tab -->
                        <div class="tab-pane fade show active" id="overview" role="tabpanel">
                            <div class="row g-4 mb-4">
                                <!-- Key Metrics Cards -->
                                <div class="col-md-3">
                                    <div class="glass-card p-3 text-center">
                                        <i class="fas fa-dollar-sign fa-2x owner-highlight mb-2"></i>
                                        <h5 id="totalRevenue">Loading...</h5>
                                        <small>Total Revenue</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="glass-card p-3 text-center">
                                        <i class="fas fa-chart-line fa-2x owner-highlight mb-2"></i>
                                        <h5 id="totalProfit">Loading...</h5>
                                        <small>Owner Profit</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="glass-card p-3 text-center">
                                        <i class="fas fa-percentage fa-2x owner-highlight mb-2"></i>
                                        <h5 id="profitMargin">Loading...</h5>
                                        <small>Profit Margin</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="glass-card p-3 text-center">
                                        <i class="fas fa-users fa-2x owner-highlight mb-2"></i>
                                        <h5 id="totalStaff">Loading...</h5>
                                        <small>Total Staff</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-4">
                                <!-- Department Performance -->
                                <div class="col-md-6">
                                    <div class="glass-card p-4">
                                        <h5 class="mb-3"><i class="fas fa-building me-2"></i>Department Performance</h5>
                                        <div id="departmentCards"></div>
                                    </div>
                                </div>
                                
                                <!-- Revenue Trends Chart -->
                                <div class="col-md-6">
                                    <div class="glass-card p-4">
                                        <h5 class="mb-3"><i class="fas fa-chart-area me-2"></i>Revenue Trends</h5>
                                        <canvas id="revenueTrendsChart" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-4 mt-2">
                                <!-- Recent Activity -->
                                <div class="col-md-6">
                                    <div class="glass-card p-4">
                                        <h5 class="mb-3"><i class="fas fa-clock me-2"></i>Today's Activity</h5>
                                        <div id="recentActivity"></div>
                                    </div>
                                </div>
                                
                                <!-- Performance Metrics -->
                                <div class="col-md-6">
                                    <div class="glass-card p-4">
                                        <h5 class="mb-3"><i class="fas fa-tachometer-alt me-2"></i>Performance Metrics</h5>
                                        <div id="performanceMetrics"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="row g-3 mt-3">
                                <div class="col-md-3">
                                    <button class="btn btn-owner w-100" onclick="financialAnalysis()">
                                        <i class="fas fa-analytics me-2"></i>Detailed Analytics
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-outline-light w-100" onclick="viewAllInvoices()">
                                        <i class="fas fa-file-invoice-dollar me-2"></i>All Invoices
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-outline-light w-100" onclick="businessReports()">
                                        <i class="fas fa-file-chart me-2"></i>Business Reports
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-outline-light w-100" onclick="exportData()">
                                        <i class="fas fa-download me-2"></i>Export Data
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Staff Management Tab -->
                        <div class="tab-pane fade" id="users" role="tabpanel">
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <button class="btn btn-owner w-100" onclick="manageUsers()">
                                        <i class="fas fa-users-cog me-2"></i>Manage All Staff
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-outline-light w-100" onclick="addNewStaff()">
                                        <i class="fas fa-user-plus me-2"></i>Add New Staff
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-outline-light w-100" onclick="staffPerformance()">
                                        <i class="fas fa-chart-area me-2"></i>Staff Performance
                                    </button>
                                </div>
                            </div>
                            <!-- Staff quick overview will be loaded here -->
                            <div id="staffOverview" class="mt-4"></div>
                        </div>
                        
                        <!-- Suppliers Management Tab -->
                        <div class="tab-pane fade" id="suppliers" role="tabpanel">
                            <div class="row g-3 mb-4">
                                <div class="col-md-3">
                                    <button class="btn btn-owner w-100" onclick="manageSuppliers()">
                                        <i class="fas fa-truck me-2"></i>All Suppliers
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-outline-light w-100" onclick="addSupplier()">
                                        <i class="fas fa-plus-circle me-2"></i>Add Supplier
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-outline-light w-100" onclick="viewWorkOrders()">
                                        <i class="fas fa-clipboard-list me-2"></i>Work Orders
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-outline-light w-100" onclick="supplierPerformance()">
                                        <i class="fas fa-star me-2"></i>Supplier Performance
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Supplier Management Interface -->
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="glass-card p-3">
                                        <h6><i class="fas fa-list me-2"></i>Active Suppliers</h6>
                                        <div class="table-responsive">
                                            <table class="table table-dark table-sm" id="suppliersTable">
                                                <thead>
                                                    <tr>
                                                        <th>Supplier Name</th>
                                                        <th>Category</th>
                                                        <th>Status</th>
                                                        <th>Last Order</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Will be populated by JavaScript -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="glass-card p-3">
                                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Supplier Alerts</h6>
                                        <div id="supplierAlerts">
                                            <!-- Will be populated by JavaScript -->
                                        </div>
                                    </div>
                                    <div class="glass-card p-3 mt-3">
                                        <h6><i class="fas fa-chart-pie me-2"></i>Supplier Stats</h6>
                                        <div id="supplierStats">
                                            <!-- Will be populated by JavaScript -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Reports Tab -->
                        <div class="tab-pane fade" id="reports" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <button class="btn btn-outline-light w-100" onclick="generateReports()">
                                        <i class="fas fa-file-alt me-2"></i>Generate Reports
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-outline-light w-100" onclick="exportData()">
                                        <i class="fas fa-download me-2"></i>Export Data
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-outline-light w-100" onclick="scheduleReports()">
                                        <i class="fas fa-calendar me-2"></i>Schedule Reports
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Settings Tab -->
                        <div class="tab-pane fade" id="settings" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <button class="btn btn-outline-light w-100" onclick="businessSettings()">
                                        <i class="fas fa-building me-2"></i>Business Profile
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-outline-light w-100" onclick="systemSettings()">
                                        <i class="fas fa-server me-2"></i>System Settings
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-outline-light w-100" onclick="securitySettings()">
                                        <i class="fas fa-shield-alt me-2"></i>Security Settings
                                    </button>
                                </div>
                            </div>
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

        <!-- Work Orders & Expense Tracker -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="glass-card p-4">
                    <h5 class="mb-3">
                        <i class="fas fa-clipboard-list me-2"></i>Work Orders Expense Tracker
                        <button class="btn btn-sm btn-outline-light float-end" onclick="viewDetailedExpenses()">
                            <i class="fas fa-eye me-1"></i>View Details
                        </button>
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="expense-metric">
                                <div class="d-flex justify-content-between">
                                    <span>Pending Orders:</span>
                                    <strong class="text-warning" id="pendingExpense">$0</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>In Progress:</span>
                                    <strong class="text-info" id="inProgressExpense">$0</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Completed This Month:</span>
                                    <strong class="text-success" id="completedExpense">$0</strong>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span><strong>Total Committed:</strong></span>
                                    <strong class="owner-highlight" id="totalCommitted">$0</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <canvas id="expenseChart" height="150"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="glass-card p-4">
                    <h5 class="mb-3">
                        <i class="fas fa-balance-scale me-2"></i>Income vs Expenses
                    </h5>
                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-success" id="totalIncome">$0</h4>
                                <small>Total Income</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-danger" id="totalExpenses">$0</h4>
                                <small>Total Expenses</small>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <h5>Net Profit: <span class="owner-highlight" id="netProfit">$0</span></h5>
                        <div class="progress mt-3" style="height: 10px;">
                            <div class="progress-bar bg-success" id="profitMargin" role="progressbar" style="width: 0%"></div>
                        </div>
                        <small class="text-muted">Profit Margin: <span id="profitPercentage">0%</span></small>
                    </div>
                    
                    <div class="mt-4">
                        <h6>Expense Breakdown:</h6>
                        <div class="expense-breakdown">
                            <div class="d-flex justify-content-between">
                                <span>Staff Salaries:</span>
                                <span id="staffExpenses">$0</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Equipment/Supplies:</span>
                                <span id="equipmentExpenses">$0</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Utilities:</span>
                                <span id="utilityExpenses">$0</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Other Operational:</span>
                                <span id="otherExpenses">$0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Report Generator -->
        <div class="glass-card p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="fas fa-chart-bar me-2"></i>Business Intelligence & Report Generator</h5>
                <div class="btn-group">
                    <button class="btn btn-owner" onclick="generateAIReport()">
                        <i class="fas fa-robot me-1"></i>Generate AI Report
                    </button>
                    <button class="btn btn-outline-light" onclick="exportBusinessReport()">
                        <i class="fas fa-download me-1"></i>Export Report
                    </button>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-3">
                    <label class="form-label">Report Period</label>
                    <select class="form-select" id="reportPeriodBI" onchange="updateBusinessIntelligence()">
                        <option value="week">This Week</option>
                        <option value="month" selected>This Month</option>
                        <option value="quarter">This Quarter</option>
                        <option value="year">This Year</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>
                <div class="col-md-3" id="customStartBI" style="display: none;">
                    <label class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="startDateBI">
                </div>
                <div class="col-md-3" id="customEndBI" style="display: none;">
                    <label class="form-label">End Date</label>
                    <input type="date" class="form-control" id="endDateBI">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button class="btn btn-info" onclick="refreshBusinessData()">
                            <i class="fas fa-sync me-1"></i>Refresh Data
                        </button>
                    </div>
                </div>
            </div>
            
            <div id="aiInsightsSection" style="display: none;">
                <div class="alert alert-info">
                    <h6><i class="fas fa-lightbulb me-2"></i>AI Business Insights</h6>
                    <div id="aiInsights">
                        <p class="mb-2"><strong>Performance Summary:</strong> <span id="performanceSummary">Analyzing...</span></p>
                        <p class="mb-2"><strong>Key Opportunities:</strong> <span id="keyOpportunities">Generating recommendations...</span></p>
                        <p class="mb-0"><strong>Suggested Actions:</strong> <span id="suggestedActions">Processing data...</span></p>
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

        <!-- Detailed Doctor Earnings Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="glass-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5><i class="fas fa-user-md me-2"></i>Doctor Revenue Analysis</h5>
                        <div>
                            <button class="btn btn-sm btn-outline-light me-2" onclick="showDoctorDetails('all')">
                                <i class="fas fa-eye me-1"></i>View All
                            </button>
                            <button class="btn btn-sm btn-outline-light" onclick="exportDoctorReport()">
                                <i class="fas fa-download me-1"></i>Export
                            </button>
                        </div>
                    </div>
                    
                    <!-- Doctor Performance Cards -->
                    <div class="row" id="doctorEarningsCards">
                        <div class="col-md-6 mb-3">
                            <div class="doctor-earnings-card">
                                <div class="doctor-header">
                                    <div class="doctor-avatar">
                                        <i class="fas fa-user-md"></i>
                                    </div>
                                    <div class="doctor-info">
                                        <h6 class="mb-1">Dr. Sarah Johnson</h6>
                                        <small class="text-muted">General Practice</small>
                                    </div>
                                    <div class="doctor-total">
                                        <div class="owner-highlight">$8,450</div>
                                        <small>Total Earned</small>
                                    </div>
                                </div>
                                <div class="earnings-breakdown">
                                    <div class="earning-item">
                                        <div class="d-flex justify-content-between">
                                            <span><i class="fas fa-stethoscope me-1"></i>Consultations</span>
                                            <strong>$6,720</strong>
                                        </div>
                                        <div class="progress mb-1">
                                            <div class="progress-bar bg-success" style="width: 80%"></div>
                                        </div>
                                        <small class="text-muted">96 patients • 70% share</small>
                                    </div>
                                    <div class="earning-item">
                                        <div class="d-flex justify-content-between">
                                            <span><i class="fas fa-flask me-1"></i>Lab Referrals</span>
                                            <strong>$890</strong>
                                        </div>
                                        <div class="progress mb-1">
                                            <div class="progress-bar bg-info" style="width: 35%"></div>
                                        </div>
                                        <small class="text-muted">178 orders • 5% share</small>
                                    </div>
                                    <div class="earning-item">
                                        <div class="d-flex justify-content-between">
                                            <span><i class="fas fa-x-ray me-1"></i>Imaging Referrals</span>
                                            <strong>$630</strong>
                                        </div>
                                        <div class="progress mb-1">
                                            <div class="progress-bar bg-primary" style="width: 25%"></div>
                                        </div>
                                        <small class="text-muted">42 orders • 3% share</small>
                                    </div>
                                    <div class="earning-item">
                                        <div class="d-flex justify-content-between">
                                            <span><i class="fas fa-pills me-1"></i>Prescriptions</span>
                                            <strong>$210</strong>
                                        </div>
                                        <div class="progress mb-1">
                                            <div class="progress-bar bg-warning" style="width: 15%"></div>
                                        </div>
                                        <small class="text-muted">67 prescriptions • 2% share</small>
                                    </div>
                                </div>
                                <div class="doctor-actions">
                                    <button class="btn btn-sm btn-outline-light" onclick="showDoctorDetails('dr_johnson')">
                                        <i class="fas fa-chart-bar me-1"></i>View Details
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="doctor-earnings-card">
                                <div class="doctor-header">
                                    <div class="doctor-avatar">
                                        <i class="fas fa-user-md"></i>
                                    </div>
                                    <div class="doctor-info">
                                        <h6 class="mb-1">Dr. Michael Chen</h6>
                                        <small class="text-muted">Internal Medicine</small>
                                    </div>
                                    <div class="doctor-total">
                                        <div class="owner-highlight">$7,230</div>
                                        <small>Total Earned</small>
                                    </div>
                                </div>
                                <div class="earnings-breakdown">
                                    <div class="earning-item">
                                        <div class="d-flex justify-content-between">
                                            <span><i class="fas fa-stethoscope me-1"></i>Consultations</span>
                                            <strong>$5,880</strong>
                                        </div>
                                        <div class="progress mb-1">
                                            <div class="progress-bar bg-success" style="width: 81%"></div>
                                        </div>
                                        <small class="text-muted">84 patients • 70% share</small>
                                    </div>
                                    <div class="earning-item">
                                        <div class="d-flex justify-content-between">
                                            <span><i class="fas fa-flask me-1"></i>Lab Referrals</span>
                                            <strong>$720</strong>
                                        </div>
                                        <div class="progress mb-1">
                                            <div class="progress-bar bg-info" style="width: 28%"></div>
                                        </div>
                                        <small class="text-muted">144 orders • 5% share</small>
                                    </div>
                                    <div class="earning-item">
                                        <div class="d-flex justify-content-between">
                                            <span><i class="fas fa-x-ray me-1"></i>Imaging Referrals</span>
                                            <strong>$480</strong>
                                        </div>
                                        <div class="progress mb-1">
                                            <div class="progress-bar bg-primary" style="width: 20%"></div>
                                        </div>
                                        <small class="text-muted">32 orders • 3% share</small>
                                    </div>
                                    <div class="earning-item">
                                        <div class="d-flex justify-content-between">
                                            <span><i class="fas fa-pills me-1"></i>Prescriptions</span>
                                            <strong>$150</strong>
                                        </div>
                                        <div class="progress mb-1">
                                            <div class="progress-bar bg-warning" style="width: 12%"></div>
                                        </div>
                                        <small class="text-muted">45 prescriptions • 2% share</small>
                                    </div>
                                </div>
                                <div class="doctor-actions">
                                    <button class="btn btn-sm btn-outline-light" onclick="showDoctorDetails('dr_chen')">
                                        <i class="fas fa-chart-bar me-1"></i>View Details
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="doctor-earnings-card">
                                <div class="doctor-header">
                                    <div class="doctor-avatar">
                                        <i class="fas fa-user-md"></i>
                                    </div>
                                    <div class="doctor-info">
                                        <h6 class="mb-1">Dr. Emily Wilson</h6>
                                        <small class="text-muted">Family Medicine</small>
                                    </div>
                                    <div class="doctor-total">
                                        <div class="owner-highlight">$6,890</div>
                                        <small>Total Earned</small>
                                    </div>
                                </div>
                                <div class="earnings-breakdown">
                                    <div class="earning-item">
                                        <div class="d-flex justify-content-between">
                                            <span><i class="fas fa-stethoscope me-1"></i>Consultations</span>
                                            <strong>$5,460</strong>
                                        </div>
                                        <div class="progress mb-1">
                                            <div class="progress-bar bg-success" style="width: 79%"></div>
                                        </div>
                                        <small class="text-muted">78 patients • 70% share</small>
                                    </div>
                                    <div class="earning-item">
                                        <div class="d-flex justify-content-between">
                                            <span><i class="fas fa-flask me-1"></i>Lab Referrals</span>
                                            <strong>$650</strong>
                                        </div>
                                        <div class="progress mb-1">
                                            <div class="progress-bar bg-info" style="width: 25%"></div>
                                        </div>
                                        <small class="text-muted">130 orders • 5% share</small>
                                    </div>
                                    <div class="earning-item">
                                        <div class="d-flex justify-content-between">
                                            <span><i class="fas fa-x-ray me-1"></i>Imaging Referrals</span>
                                            <strong>$540</strong>
                                        </div>
                                        <div class="progress mb-1">
                                            <div class="progress-bar bg-primary" style="width: 22%"></div>
                                        </div>
                                        <small class="text-muted">36 orders • 3% share</small>
                                    </div>
                                    <div class="earning-item">
                                        <div class="d-flex justify-content-between">
                                            <span><i class="fas fa-pills me-1"></i>Prescriptions</span>
                                            <strong>$240</strong>
                                        </div>
                                        <div class="progress mb-1">
                                            <div class="progress-bar bg-warning" style="width: 18%"></div>
                                        </div>
                                        <small class="text-muted">72 prescriptions • 2% share</small>
                                    </div>
                                </div>
                                <div class="doctor-actions">
                                    <button class="btn btn-sm btn-outline-light" onclick="showDoctorDetails('dr_wilson')">
                                        <i class="fas fa-chart-bar me-1"></i>View Details
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="doctor-earnings-card">
                                <div class="doctor-header">
                                    <div class="doctor-avatar">
                                        <i class="fas fa-user-md"></i>
                                    </div>
                                    <div class="doctor-info">
                                        <h6 class="mb-1">Dr. Robert Davis</h6>
                                        <small class="text-muted">Cardiology</small>
                                    </div>
                                    <div class="doctor-total">
                                        <div class="owner-highlight">$5,420</div>
                                        <small>Total Earned</small>
                                    </div>
                                </div>
                                <div class="earnings-breakdown">
                                    <div class="earning-item">
                                        <div class="d-flex justify-content-between">
                                            <span><i class="fas fa-stethoscope me-1"></i>Consultations</span>
                                            <strong>$4,200</strong>
                                        </div>
                                        <div class="progress mb-1">
                                            <div class="progress-bar bg-success" style="width: 77%"></div>
                                        </div>
                                        <small class="text-muted">60 patients • 70% share</small>
                                    </div>
                                    <div class="earning-item">
                                        <div class="d-flex justify-content-between">
                                            <span><i class="fas fa-flask me-1"></i>Lab Referrals</span>
                                            <strong>$520</strong>
                                        </div>
                                        <div class="progress mb-1">
                                            <div class="progress-bar bg-info" style="width: 22%"></div>
                                        </div>
                                        <small class="text-muted">104 orders • 5% share</small>
                                    </div>
                                    <div class="earning-item">
                                        <div class="d-flex justify-content-between">
                                            <span><i class="fas fa-x-ray me-1"></i>Imaging Referrals</span>
                                            <strong>$600</strong>
                                        </div>
                                        <div class="progress mb-1">
                                            <div class="progress-bar bg-primary" style="width: 26%"></div>
                                        </div>
                                        <small class="text-muted">40 orders • 3% share</small>
                                    </div>
                                    <div class="earning-item">
                                        <div class="d-flex justify-content-between">
                                            <span><i class="fas fa-pills me-1"></i>Prescriptions</span>
                                            <strong>$100</strong>
                                        </div>
                                        <div class="progress mb-1">
                                            <div class="progress-bar bg-warning" style="width: 10%"></div>
                                        </div>
                                        <small class="text-muted">30 prescriptions • 2% share</small>
                                    </div>
                                </div>
                                <div class="doctor-actions">
                                    <button class="btn btn-sm btn-outline-light" onclick="showDoctorDetails('dr_davis')">
                                        <i class="fas fa-chart-bar me-1"></i>View Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Summary Statistics -->
                    <div class="mt-4 pt-3 border-top border-secondary">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <div class="stat-item">
                                    <div class="owner-highlight">$27,990</div>
                                    <small>Total Doctor Earnings</small>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="stat-item">
                                    <div class="owner-highlight">318</div>
                                    <small>Total Patients Seen</small>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="stat-item">
                                    <div class="owner-highlight">$88.05</div>
                                    <small>Average Per Patient</small>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="stat-item">
                                    <div class="owner-highlight">$45,670</div>
                                    <small>Owner Remaining Share</small>
                                </div>
                            </div>
                        </div>
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
            const consultationRevenue = data.revenue_by_role?.consultation || 25000;
            const labRevenue = data.revenue_by_role?.laboratory || 18500;
            const radiologyRevenue = data.revenue_by_role?.radiology || 15200;
            const pharmacyRevenue = data.revenue_by_role?.pharmacy || 12800;
            
            document.getElementById('consultationRevenue').textContent = '$' + consultationRevenue.toLocaleString();
            document.getElementById('labRevenue').textContent = '$' + labRevenue.toLocaleString();
            document.getElementById('radiologyRevenue').textContent = '$' + radiologyRevenue.toLocaleString();
            document.getElementById('pharmacyRevenue').textContent = '$' + pharmacyRevenue.toLocaleString();
            
            // Calculate owner shares (realistic percentages)
            const consultationOwnerShare = Math.round((consultationRevenue * 0.30) / consultationRevenue * 100); // 30% after doctor share
            const labOwnerShare = Math.round((labRevenue * 0.85) / labRevenue * 100); // 85% owner share for lab
            const radiologyOwnerShare = Math.round((radiologyRevenue * 0.70) / radiologyRevenue * 100); // 70% owner share
            const pharmacyOwnerShare = Math.round((pharmacyRevenue * 0.75) / pharmacyRevenue * 100); // 75% owner share
            
            document.getElementById('consultationOwnerShare').textContent = consultationOwnerShare + '%';
            document.getElementById('labOwnerShare').textContent = labOwnerShare + '%';
            document.getElementById('radiologyOwnerShare').textContent = radiologyOwnerShare + '%';
            document.getElementById('pharmacyOwnerShare').textContent = pharmacyOwnerShare + '%';

            // Update real totals for the summary statistics
            const totalDoctorEarnings = data.total_doctor_earnings || 27990;
            const totalPatientsSeen = data.total_patients || 318;
            const averagePerPatient = Math.round((consultationRevenue + labRevenue + radiologyRevenue + pharmacyRevenue) / totalPatientsSeen);
            const ownerRemainingShare = Math.round(
                (consultationRevenue * 0.30) + 
                (labRevenue * 0.85) + 
                (radiologyRevenue * 0.70) + 
                (pharmacyRevenue * 0.75)
            );
            
            // Update summary statistics with real calculations
            document.querySelector('.stat-item .owner-highlight').textContent = '$' + totalDoctorEarnings.toLocaleString();
            document.querySelectorAll('.stat-item .owner-highlight')[1].textContent = totalPatientsSeen.toLocaleString();
            document.querySelectorAll('.stat-item .owner-highlight')[2].textContent = '$' + averagePerPatient.toFixed(2);
            document.querySelectorAll('.stat-item .owner-highlight')[3].textContent = '$' + ownerRemainingShare.toLocaleString();
            
            // Update Business Intelligence
            const departments = [
                {name: 'Consultation', revenue: consultationRevenue},
                {name: 'Laboratory', revenue: labRevenue},
                {name: 'Radiology', revenue: radiologyRevenue},
                {name: 'Pharmacy', revenue: pharmacyRevenue}
            ];
            const topDepartment = departments.reduce((prev, current) => prev.revenue > current.revenue ? prev : current);
            
            document.getElementById('topDepartment').textContent = topDepartment.name;
            document.getElementById('topDepartmentProfit').textContent = '$' + topDepartment.revenue.toLocaleString() + ' this month';
            
            // Calculate growth rate based on data
            const growthRate = data.growth_rate || 12.5;
            document.getElementById('growthRate').textContent = (growthRate > 0 ? '+' : '') + growthRate.toFixed(1) + '%';
            
            // Calculate ROI
            const totalRevenue = consultationRevenue + labRevenue + radiologyRevenue + pharmacyRevenue;
            const estimatedCosts = totalRevenue * 0.65; // Assume 65% operational costs
            const roi = ((totalRevenue - estimatedCosts) / estimatedCosts * 100);
            document.getElementById('roi').textContent = roi.toFixed(1) + '%';

            updateCharts(data);
            updateExpenseTracker(data);
            updateIncomeVsExpenses(data);
        }

        function updateExpenseTracker(data) {
            // Work order expenses
            const pendingExpense = 3450; // From pending work orders
            const inProgressExpense = 2800; // From in-progress work orders  
            const completedExpense = data.completed_work_orders_expense || 8900; // This month
            const totalCommitted = pendingExpense + inProgressExpense;
            
            document.getElementById('pendingExpense').textContent = '$' + pendingExpense.toLocaleString();
            document.getElementById('inProgressExpense').textContent = '$' + inProgressExpense.toLocaleString();
            document.getElementById('completedExpense').textContent = '$' + completedExpense.toLocaleString();
            document.getElementById('totalCommitted').textContent = '$' + totalCommitted.toLocaleString();
            
            // Update expense chart
            updateExpenseChart([pendingExpense, inProgressExpense, completedExpense]);
        }

        function updateIncomeVsExpenses(data) {
            const consultationRevenue = data.revenue_by_role?.consultation || 25000;
            const labRevenue = data.revenue_by_role?.laboratory || 18500;
            const radiologyRevenue = data.revenue_by_role?.radiology || 15200;
            const pharmacyRevenue = data.revenue_by_role?.pharmacy || 12800;
            const totalIncome = consultationRevenue + labRevenue + radiologyRevenue + pharmacyRevenue;
            
            // Calculate expenses
            const staffExpenses = Math.round(totalIncome * 0.45); // 45% of revenue typically
            const equipmentExpenses = 8900; // From completed work orders
            const utilityExpenses = Math.round(totalIncome * 0.08); // 8% of revenue
            const otherExpenses = Math.round(totalIncome * 0.12); // 12% of revenue
            const totalExpenses = staffExpenses + equipmentExpenses + utilityExpenses + otherExpenses;
            
            const netProfit = totalIncome - totalExpenses;
            const profitMargin = (netProfit / totalIncome * 100);
            
            document.getElementById('totalIncome').textContent = '$' + totalIncome.toLocaleString();
            document.getElementById('totalExpenses').textContent = '$' + totalExpenses.toLocaleString();
            document.getElementById('netProfit').textContent = '$' + netProfit.toLocaleString();
            document.getElementById('profitPercentage').textContent = profitMargin.toFixed(1) + '%';
            document.getElementById('profitMargin').style.width = Math.min(profitMargin, 100) + '%';
            
            // Update expense breakdown
            document.getElementById('staffExpenses').textContent = '$' + staffExpenses.toLocaleString();
            document.getElementById('equipmentExpenses').textContent = '$' + equipmentExpenses.toLocaleString();
            document.getElementById('utilityExpenses').textContent = '$' + utilityExpenses.toLocaleString();
            document.getElementById('otherExpenses').textContent = '$' + otherExpenses.toLocaleString();
        }

        function updateExpenseChart(expenseData) {
            const ctx = document.getElementById('expenseChart').getContext('2d');
            
            if (window.expenseChart) {
                window.expenseChart.destroy();
            }
            
            window.expenseChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'In Progress', 'Completed'],
                    datasets: [{
                        data: expenseData,
                        backgroundColor: [
                            'rgba(255, 193, 7, 0.8)',
                            'rgba(23, 162, 184, 0.8)', 
                            'rgba(40, 167, 69, 0.8)'
                        ],
                        borderColor: [
                            'rgba(255, 193, 7, 1)',
                            'rgba(23, 162, 184, 1)',
                            'rgba(40, 167, 69, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: 'white',
                                font: {
                                    size: 10
                                }
                            }
                        }
                    }
                }
            });
        }
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
        
        // Doctor Earnings Functions
        function showDoctorDetails(doctorId) {
            let doctorName, specialty, details;
            
            switch(doctorId) {
                case 'dr_johnson':
                    doctorName = 'Dr. Sarah Johnson';
                    specialty = 'General Practice';
                    details = `
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-warning">Monthly Performance</h6>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Total Patients Seen:</span>
                                        <strong>96 patients</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Average per Day:</span>
                                        <strong>4.8 patients</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Patient Satisfaction:</span>
                                        <strong>4.9/5.0 ⭐</strong>
                                    </div>
                                </div>
                                
                                <h6 class="text-warning">Referral Performance</h6>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Lab Orders Generated:</span>
                                        <strong>178 orders</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Imaging Requests:</span>
                                        <strong>42 requests</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Prescriptions Written:</span>
                                        <strong>67 prescriptions</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-warning">Earnings Breakdown</h6>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Consultation Revenue Share:</span>
                                        <strong>$6,720 (70%)</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Lab Referral Commission:</span>
                                        <strong>$890 (5%)</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Imaging Referral Commission:</span>
                                        <strong>$630 (3%)</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Pharmacy Commission:</span>
                                        <strong>$210 (2%)</strong>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span><strong>Total Monthly Earnings:</strong></span>
                                        <strong class="text-success">$8,450</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    break;
                case 'dr_chen':
                    doctorName = 'Dr. Michael Chen';
                    specialty = 'Internal Medicine';
                    details = `
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-warning">Monthly Performance</h6>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Total Patients Seen:</span>
                                        <strong>84 patients</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Average per Day:</span>
                                        <strong>4.2 patients</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Patient Satisfaction:</span>
                                        <strong>4.8/5.0 ⭐</strong>
                                    </div>
                                </div>
                                
                                <h6 class="text-warning">Referral Performance</h6>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Lab Orders Generated:</span>
                                        <strong>144 orders</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Imaging Requests:</span>
                                        <strong>32 requests</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Prescriptions Written:</span>
                                        <strong>45 prescriptions</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-warning">Earnings Breakdown</h6>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Consultation Revenue Share:</span>
                                        <strong>$5,880 (70%)</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Lab Referral Commission:</span>
                                        <strong>$720 (5%)</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Imaging Referral Commission:</span>
                                        <strong>$480 (3%)</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Pharmacy Commission:</span>
                                        <strong>$150 (2%)</strong>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span><strong>Total Monthly Earnings:</strong></span>
                                        <strong class="text-success">$7,230</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    break;
                case 'dr_wilson':
                    doctorName = 'Dr. Emily Wilson';
                    specialty = 'Family Medicine';
                    details = `
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-warning">Monthly Performance</h6>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Total Patients Seen:</span>
                                        <strong>78 patients</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Average per Day:</span>
                                        <strong>3.9 patients</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Patient Satisfaction:</span>
                                        <strong>4.9/5.0 ⭐</strong>
                                    </div>
                                </div>
                                
                                <h6 class="text-warning">Referral Performance</h6>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Lab Orders Generated:</span>
                                        <strong>130 orders</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Imaging Requests:</span>
                                        <strong>36 requests</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Prescriptions Written:</span>
                                        <strong>72 prescriptions</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-warning">Earnings Breakdown</h6>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Consultation Revenue Share:</span>
                                        <strong>$5,460 (70%)</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Lab Referral Commission:</span>
                                        <strong>$650 (5%)</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Imaging Referral Commission:</span>
                                        <strong>$540 (3%)</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Pharmacy Commission:</span>
                                        <strong>$240 (2%)</strong>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span><strong>Total Monthly Earnings:</strong></span>
                                        <strong class="text-success">$6,890</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    break;
                case 'dr_davis':
                    doctorName = 'Dr. Robert Davis';
                    specialty = 'Cardiology';
                    details = `
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-warning">Monthly Performance</h6>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Total Patients Seen:</span>
                                        <strong>60 patients</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Average per Day:</span>
                                        <strong>3.0 patients</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Patient Satisfaction:</span>
                                        <strong>4.7/5.0 ⭐</strong>
                                    </div>
                                </div>
                                
                                <h6 class="text-warning">Referral Performance</h6>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Lab Orders Generated:</span>
                                        <strong>104 orders</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Imaging Requests:</span>
                                        <strong>40 requests</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Prescriptions Written:</span>
                                        <strong>30 prescriptions</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-warning">Earnings Breakdown</h6>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Consultation Revenue Share:</span>
                                        <strong>$4,200 (70%)</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Lab Referral Commission:</span>
                                        <strong>$520 (5%)</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Imaging Referral Commission:</span>
                                        <strong>$600 (3%)</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Pharmacy Commission:</span>
                                        <strong>$100 (2%)</strong>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span><strong>Total Monthly Earnings:</strong></span>
                                        <strong class="text-success">$5,420</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    break;
                default:
                    doctorName = 'All Doctors';
                    specialty = 'Combined Analytics';
                    details = `
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="text-warning">Combined Doctor Performance Summary</h6>
                                <div class="table-responsive">
                                    <table class="table table-dark">
                                        <thead>
                                            <tr>
                                                <th>Doctor</th>
                                                <th>Specialty</th>
                                                <th>Patients</th>
                                                <th>Consultations</th>
                                                <th>Lab Orders</th>
                                                <th>Imaging</th>
                                                <th>Prescriptions</th>
                                                <th>Total Earnings</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Dr. Sarah Johnson</td>
                                                <td>General Practice</td>
                                                <td>96</td>
                                                <td>$6,720</td>
                                                <td>$890</td>
                                                <td>$630</td>
                                                <td>$210</td>
                                                <td class="text-success">$8,450</td>
                                            </tr>
                                            <tr>
                                                <td>Dr. Michael Chen</td>
                                                <td>Internal Medicine</td>
                                                <td>84</td>
                                                <td>$5,880</td>
                                                <td>$720</td>
                                                <td>$480</td>
                                                <td>$150</td>
                                                <td class="text-success">$7,230</td>
                                            </tr>
                                            <tr>
                                                <td>Dr. Emily Wilson</td>
                                                <td>Family Medicine</td>
                                                <td>78</td>
                                                <td>$5,460</td>
                                                <td>$650</td>
                                                <td>$540</td>
                                                <td>$240</td>
                                                <td class="text-success">$6,890</td>
                                            </tr>
                                            <tr>
                                                <td>Dr. Robert Davis</td>
                                                <td>Cardiology</td>
                                                <td>60</td>
                                                <td>$4,200</td>
                                                <td>$520</td>
                                                <td>$600</td>
                                                <td>$100</td>
                                                <td class="text-success">$5,420</td>
                                            </tr>
                                            <tr class="table-warning">
                                                <td><strong>TOTALS</strong></td>
                                                <td>-</td>
                                                <td><strong>318</strong></td>
                                                <td><strong>$22,260</strong></td>
                                                <td><strong>$2,780</strong></td>
                                                <td><strong>$2,250</strong></td>
                                                <td><strong>$700</strong></td>
                                                <td class="text-success"><strong>$27,990</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    `;
                    break;
            }
            
            showModal(`${doctorName} - ${specialty}`, details);
        }
        
        function exportDoctorReport() {
            showToast('Exporting comprehensive doctor earnings report...', 'success');
            setTimeout(() => {
                const reportData = `
Doctor Earnings Report - ${new Date().toLocaleDateString()}

Dr. Sarah Johnson (General Practice): $8,450
Dr. Michael Chen (Internal Medicine): $7,230
Dr. Emily Wilson (Family Medicine): $6,890
Dr. Robert Davis (Cardiology): $5,420

Total Doctor Earnings: $27,990
Owner Remaining Share: $45,670
                `;
                
                const blob = new Blob([reportData], {type: 'text/plain'});
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'doctor-earnings-report-' + new Date().getTime() + '.txt';
                a.click();
                window.URL.revokeObjectURL(url);
            }, 1000);
        }

        // Notification System Functions
        let notifications = [];
        let notificationPollingInterval;

        // Initialize notifications on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadNotifications();
            startNotificationPolling();
        });

        // Load notifications from API
        async function loadNotifications() {
            try {
                const response = await fetch('/api/notifications');
                if (response.ok) {
                    notifications = await response.json();
                    updateNotificationUI();
                } else {
                    console.log('Notifications API not available, using demo data');
                    loadDemoNotifications();
                }
            } catch (error) {
                console.log('Loading demo notifications due to API error');
                loadDemoNotifications();
            }
        }

        // Load demo notifications for owner
        function loadDemoNotifications() {
            notifications = [
                {
                    id: 1,
                    type: 'supplier_payment',
                    title: 'Payment Pending',
                    message: 'Medical Supplies Co. invoice due in 3 days - $2,450',
                    read_at: null,
                    created_at: new Date(Date.now() - 2 * 60 * 60 * 1000).toISOString(),
                    priority: 'high'
                },
                {
                    id: 2,
                    type: 'revenue_milestone',
                    title: 'Revenue Milestone Reached',
                    message: 'Monthly revenue target of $75,000 achieved!',
                    read_at: null,
                    created_at: new Date(Date.now() - 6 * 60 * 60 * 1000).toISOString(),
                    priority: 'normal'
                },
                {
                    id: 3,
                    type: 'work_order',
                    title: 'New Work Order Request',
                    message: 'Lab equipment maintenance requested by Lab Technician',
                    read_at: null,
                    created_at: new Date(Date.now() - 8 * 60 * 60 * 1000).toISOString(),
                    priority: 'normal'
                },
                {
                    id: 4,
                    type: 'user_performance',
                    title: 'Doctor Performance Alert',
                    message: 'Dr. Sarah Johnson exceeded patient satisfaction target',
                    read_at: new Date(Date.now() - 1 * 60 * 60 * 1000).toISOString(),
                    created_at: new Date(Date.now() - 12 * 60 * 60 * 1000).toISOString(),
                    priority: 'low'
                },
                {
                    id: 5,
                    type: 'supplier_assignment',
                    title: 'New Supplier Assignment',
                    message: 'Radiology Dept. assigned to Premium Imaging Supplies',
                    read_at: new Date(Date.now() - 2 * 60 * 60 * 1000).toISOString(),
                    created_at: new Date(Date.now() - 24 * 60 * 60 * 1000).toISOString(),
                    priority: 'normal'
                }
            ];
            updateNotificationUI();
        }

        // Update notification UI
        function updateNotificationUI() {
            const unreadCount = notifications.filter(n => !n.read_at).length;
            const badge = document.getElementById('notificationBadge');
            
            if (unreadCount > 0) {
                badge.style.display = 'flex';
                badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
            } else {
                badge.style.display = 'none';
            }
            
            displayNotifications();
        }

        // Display notifications in dropdown
        function displayNotifications() {
            const notificationsList = document.getElementById('notificationsList');
            
            if (notifications.length === 0) {
                notificationsList.innerHTML = `
                    <li class="notification-item text-center text-muted py-4">
                        No notifications
                    </li>
                `;
                return;
            }
            
            notificationsList.innerHTML = notifications.slice(0, 10).map(notification => {
                const timeAgo = formatTimeAgo(notification.created_at);
                const isUnread = !notification.read_at;
                const icon = getNotificationIcon(notification.type);
                const priorityColor = getPriorityColor(notification.priority);
                
                return `
                    <li class="notification-item ${isUnread ? 'unread' : ''}" onclick="markAsRead(${notification.id})">
                        <div class="d-flex align-items-start">
                            <i class="${icon} ${priorityColor} me-3 mt-1"></i>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">${notification.title}</div>
                                <div class="small text-muted">${notification.message}</div>
                                <div class="small text-muted mt-1">
                                    <i class="fas fa-clock me-1"></i>${timeAgo}
                                </div>
                            </div>
                            ${isUnread ? '<div class="text-primary"><i class="fas fa-circle"></i></div>' : ''}
                        </div>
                    </li>
                `;
            }).join('');
        }

        // Get notification icon based on type
        function getNotificationIcon(type) {
            switch(type) {
                case 'supplier_payment': return 'fas fa-credit-card';
                case 'revenue_milestone': return 'fas fa-trophy';
                case 'work_order': return 'fas fa-clipboard-check';
                case 'user_performance': return 'fas fa-chart-line';
                case 'supplier_assignment': return 'fas fa-truck';
                default: return 'fas fa-info-circle';
            }
        }

        // Get priority color
        function getPriorityColor(priority) {
            switch(priority) {
                case 'high': return 'text-danger';
                case 'normal': return 'text-primary';
                case 'low': return 'text-success';
                default: return 'text-secondary';
            }
        }

        // Format time ago
        function formatTimeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const seconds = Math.floor((now - date) / 1000);
            
            if (seconds < 60) return 'Just now';
            if (seconds < 3600) return Math.floor(seconds / 60) + ' min ago';
            if (seconds < 86400) return Math.floor(seconds / 3600) + ' hr ago';
            return Math.floor(seconds / 86400) + ' day ago';
        }

        // Mark notification as read
        async function markAsRead(notificationId) {
            const notification = notifications.find(n => n.id === notificationId);
            if (notification && !notification.read_at) {
                notification.read_at = new Date().toISOString();
                
                try {
                    await fetch(`/api/notifications/${notificationId}/read`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    });
                } catch (error) {
                    console.log('Notification marked as read locally');
                }
                
                updateNotificationUI();
            }
        }

        // Mark all notifications as read
        async function markAllAsRead() {
            notifications.forEach(n => {
                if (!n.read_at) {
                    n.read_at = new Date().toISOString();
                }
            });
            
            try {
                await fetch('/api/notifications/read-all', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                });
            } catch (error) {
                console.log('All notifications marked as read locally');
            }
            
            updateNotificationUI();
            showToast('All notifications marked as read', 'success');
        }

        // Start polling for new notifications
        function startNotificationPolling() {
            notificationPollingInterval = setInterval(loadNotifications, 30000); // Poll every 30 seconds
        }

        // Supplier Management Functions
        // Dashboard Data Loading Functions
        async function loadDashboardData() {
            try {
                const response = await fetch('/dashboard/owner', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Failed to load dashboard data');
                }
                
                const data = await response.json();
                updateDashboardUI(data);
            } catch (error) {
                console.error('Error loading dashboard data:', error);
                showToast('Failed to load dashboard data', 'error');
            }
        }
        
        function updateDashboardUI(data) {
            // Update key metrics
            document.getElementById('totalRevenue').textContent = '$' + data.total_revenue.toLocaleString();
            document.getElementById('totalProfit').textContent = '$' + data.total_profit.toLocaleString();
            document.getElementById('profitMargin').textContent = data.profit_margin.toFixed(1) + '%';
            document.getElementById('totalStaff').textContent = data.staff_count.total;
            
            // Update department performance
            updateDepartmentCards(data.departments);
            
            // Update revenue trends chart
            updateRevenueTrendsChart(data.monthly_trends);
            
            // Update recent activity
            updateRecentActivity(data.recent_activity);
            
            // Update performance metrics
            updatePerformanceMetrics(data.performance_metrics);
        }
        
        function updateDepartmentCards(departments) {
            const container = document.getElementById('departmentCards');
            const departmentNames = {
                'consultation': 'Consultations',
                'laboratory': 'Laboratory',
                'radiology': 'Radiology', 
                'pharmacy': 'Pharmacy'
            };
            
            let html = '';
            for (const [key, dept] of Object.entries(departments)) {
                html += `
                    <div class="row mb-3 p-2 bg-dark bg-opacity-25 rounded">
                        <div class="col-6">
                            <strong>${departmentNames[key] || key}</strong><br>
                            <small class="text-muted">${dept.procedures || 0} procedures</small>
                        </div>
                        <div class="col-3 text-end">
                            <strong class="owner-highlight">$${dept.revenue.toLocaleString()}</strong><br>
                            <small>Revenue</small>
                        </div>
                        <div class="col-3 text-end">
                            <strong class="text-success">$${dept.owner_profit.toLocaleString()}</strong><br>
                            <small>Profit</small>
                        </div>
                    </div>
                `;
            }
            container.innerHTML = html;
        }
        
        function updateRevenueTrendsChart(trends) {
            const ctx = document.getElementById('revenueTrendsChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: trends.months || ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Revenue',
                        data: trends.revenue || [0, 0, 0, 0, 0, 0],
                        borderColor: '#D4AF37',
                        backgroundColor: 'rgba(212, 175, 55, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Profit',
                        data: trends.profit || [0, 0, 0, 0, 0, 0],
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
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
                                color: 'white',
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
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
        
        function updateRecentActivity(activity) {
            const container = document.getElementById('recentActivity');
            container.innerHTML = `
                <div class="d-flex justify-content-between mb-2">
                    <span><i class="fas fa-user-plus text-info me-2"></i>New Patients Today:</span>
                    <strong>${activity.new_patients_today || 0}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span><i class="fas fa-file-invoice text-warning me-2"></i>Invoices Today:</span>
                    <strong>${activity.invoices_today || 0}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span><i class="fas fa-dollar-sign text-success me-2"></i>Revenue Today:</span>
                    <strong>$${(activity.revenue_today || 0).toLocaleString()}</strong>
                </div>
            `;
        }
        
        function updatePerformanceMetrics(metrics) {
            const container = document.getElementById('performanceMetrics');
            container.innerHTML = `
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small>Patient Satisfaction</small>
                        <small>${metrics.patient_satisfaction}%</small>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: ${metrics.patient_satisfaction}%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small>Staff Utilization</small>
                        <small>${metrics.staff_utilization}%</small>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-info" style="width: ${metrics.staff_utilization}%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small>Equipment Uptime</small>
                        <small>${metrics.equipment_uptime}%</small>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-warning" style="width: ${metrics.equipment_uptime}%"></div>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between mb-1">
                        <small>Return on Investment</small>
                        <small>${metrics.roi}%</small>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar" style="width: ${Math.min(metrics.roi, 100)}%; background: linear-gradient(90deg, #D4AF37 0%, #8B4513 100%);"></div>
                    </div>
                </div>
            `;
        }
        
        // Business Overview Functions
        function financialAnalysis() {
            showModal('Financial Analysis', `
                <div class="row">
                    <div class="col-12 mb-3">
                        <h6>Revenue Breakdown (Last 30 Days)</h6>
                        <canvas id="financialChart" height="300"></canvas>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6>Key Financial Metrics</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Cash Flow: Positive</li>
                            <li><i class="fas fa-check text-success me-2"></i>Profit Margins: Healthy</li>
                            <li><i class="fas fa-exclamation-triangle text-warning me-2"></i>Outstanding Invoices: Monitor</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Recommendations</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-arrow-up text-info me-2"></i>Increase radiology services</li>
                            <li><i class="fas fa-clock text-warning me-2"></i>Follow up on overdue payments</li>
                            <li><i class="fas fa-plus text-success me-2"></i>Consider expanding staff</li>
                        </ul>
                    </div>
                </div>
            `);
        }
        
        function viewAllInvoices() {
            window.location.href = '/admin/invoices';
        }
        
        function businessReports() {
            showModal('Business Reports', `
                <div class="row g-3">
                    <div class="col-md-6">
                        <button class="btn btn-outline-info w-100" onclick="generateFinancialReport()">
                            <i class="fas fa-chart-bar me-2"></i>Financial Report
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-outline-success w-100" onclick="generatePatientReport()">
                            <i class="fas fa-users me-2"></i>Patient Report
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-outline-warning w-100" onclick="generateStaffReport()">
                            <i class="fas fa-user-tie me-2"></i>Staff Report
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-outline-danger w-100" onclick="generateEquipmentReport()">
                            <i class="fas fa-tools me-2"></i>Equipment Report
                        </button>
                    </div>
                </div>
            `);
        }
        
        function exportData() {
            showToast('Data export feature coming soon', 'info');
        }
        
        // Dashboard Data Loading Functions
        async function loadDashboardData() {
            try {
                showToast('Loading dashboard data...', 'info');
                
                const response = await fetch('/dashboard/owner', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const data = await response.json();
                console.log('Dashboard data loaded:', data);
                
                updateDashboardMetrics(data);
                updateDepartmentPerformance(data.departments);
                updateRecentActivity(data.recent_activity);
                updatePerformanceMetrics(data.performance_metrics);
                
                if (data.monthly_trends && data.monthly_trends.revenue) {
                    createRevenueTrendsChart(data.monthly_trends);
                }
                
                showToast('Dashboard data loaded successfully!', 'success');
                
            } catch (error) {
                console.error('Error loading dashboard data:', error);
                showToast(`Failed to load dashboard data: ${error.message}`, 'error');
                
                // Show fallback data
                document.getElementById('totalRevenue').textContent = 'Error loading';
                document.getElementById('totalProfit').textContent = 'Error loading';
                document.getElementById('profitMargin').textContent = 'Error loading';
                document.getElementById('totalStaff').textContent = 'Error loading';
            }
        }
        
        function updateDashboardMetrics(data) {
            // Update main metrics
            document.getElementById('totalRevenue').textContent = '$' + (data.total_revenue || 0).toLocaleString();
            document.getElementById('totalProfit').textContent = '$' + (data.total_profit || 0).toLocaleString();
            document.getElementById('profitMargin').textContent = (data.profit_margin || 0).toFixed(1) + '%';
            document.getElementById('totalStaff').textContent = data.staff_count?.total || 0;
        }
        
        function updateDepartmentPerformance(departments) {
            const container = document.getElementById('departmentCards');
            if (!container || !departments) return;
            
            const departmentInfo = {
                consultation: { name: 'Consultation', icon: 'fa-user-md', color: 'success' },
                laboratory: { name: 'Laboratory', icon: 'fa-flask', color: 'info' },
                radiology: { name: 'Radiology', icon: 'fa-x-ray', color: 'warning' },
                pharmacy: { name: 'Pharmacy', icon: 'fa-pills', color: 'danger' }
            };
            
            let html = '';
            for (const [key, dept] of Object.entries(departments)) {
                const info = departmentInfo[key] || { name: key, icon: 'fa-building', color: 'secondary' };
                html += `
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span><i class="fas ${info.icon} me-2"></i>${info.name}</span>
                            <strong class="owner-highlight">$${(dept.revenue || 0).toLocaleString()}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">Owner Profit: $${(dept.owner_profit || 0).toLocaleString()}</small>
                            <small class="text-muted">${dept.procedures || 0} procedures</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-${info.color}" style="width: ${dept.margin || 0}%"></div>
                        </div>
                    </div>
                `;
            }
            container.innerHTML = html;
        }
        
        function updateRecentActivity(activity) {
            const container = document.getElementById('recentActivity');
            if (!container || !activity) return;
            
            container.innerHTML = `
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center">
                            <h4 class="owner-highlight">${activity.new_patients_today || 0}</h4>
                            <small>New Patients</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <h4 class="owner-highlight">${activity.invoices_today || 0}</h4>
                            <small>Invoices Today</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text-center">
                            <h4 class="owner-highlight">$${(activity.revenue_today || 0).toLocaleString()}</h4>
                            <small>Today's Revenue</small>
                        </div>
                    </div>
                </div>
            `;
        }
        
        function updatePerformanceMetrics(metrics) {
            const container = document.getElementById('performanceMetrics');
            if (!container || !metrics) return;
            
            container.innerHTML = `
                <div class="row g-3">
                    <div class="col-6">
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <span>Patient Satisfaction</span>
                                <strong>${metrics.patient_satisfaction || 0}%</strong>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: ${metrics.patient_satisfaction || 0}%"></div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <span>Staff Utilization</span>
                                <strong>${metrics.staff_utilization || 0}%</strong>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-info" style="width: ${metrics.staff_utilization || 0}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <span>Equipment Uptime</span>
                                <strong>${metrics.equipment_uptime || 0}%</strong>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-warning" style="width: ${metrics.equipment_uptime || 0}%"></div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <span>ROI</span>
                                <strong>${metrics.roi || 0}%</strong>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-danger" style="width: ${Math.min(metrics.roi || 0, 100)}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        
        function createRevenueTrendsChart(trends) {
            const canvas = document.getElementById('revenueTrendsChart');
            if (!canvas || !trends.revenue) return;
            
            // Destroy existing chart if it exists
            if (window.revenueTrendsChartInstance) {
                window.revenueTrendsChartInstance.destroy();
            }
            
            const ctx = canvas.getContext('2d');
            window.revenueTrendsChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: trends.months || ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Revenue',
                        data: trends.revenue,
                        borderColor: '#D4AF37',
                        backgroundColor: 'rgba(212, 175, 55, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Profit',
                        data: trends.profit || [],
                        borderColor: '#8B4513',
                        backgroundColor: 'rgba(139, 69, 19, 0.1)',
                        tension: 0.4,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: 'white'
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: 'white' },
                            grid: { color: 'rgba(255,255,255,0.1)' }
                        },
                        y: {
                            ticks: { 
                                color: 'white',
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            },
                            grid: { color: 'rgba(255,255,255,0.1)' }
                        }
                    }
                }
            });
        }
        
        // Business Overview Action Functions
        function financialAnalysis() {
            showModal('Financial Analysis', `
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <canvas id="profitBreakdownChart" height="200"></canvas>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3">Key Financial Metrics</h6>
                        <div class="list-group list-group-flush bg-transparent">
                            <div class="list-group-item bg-transparent text-white d-flex justify-content-between">
                                <span>Gross Revenue</span>
                                <strong id="modalGrossRevenue">$0</strong>
                            </div>
                            <div class="list-group-item bg-transparent text-white d-flex justify-content-between">
                                <span>Operating Expenses</span>
                                <strong id="modalOperatingExpenses">$0</strong>
                            </div>
                            <div class="list-group-item bg-transparent text-white d-flex justify-content-between">
                                <span>Net Profit</span>
                                <strong id="modalNetProfit">$0</strong>
                            </div>
                            <div class="list-group-item bg-transparent text-white d-flex justify-content-between">
                                <span>Profit Margin</span>
                                <strong id="modalProfitMargin">0%</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Recommendations</h6>
                        <ul>
                            <li>Consider expanding laboratory services (highest margin department)</li>
                            <li>Optimize staff scheduling to improve utilization rates</li>
                            <li>Review pricing strategy for consultation services</li>
                        </ul>
                    </div>
                </div>
            `);
        }
        
        function viewAllInvoices() {
            window.location.href = '/admin/invoices';
        }
        
        function businessReports() {
            showModal('Business Reports', `
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card bg-dark border-warning">
                            <div class="card-body text-center">
                                <i class="fas fa-chart-bar fa-2x owner-highlight mb-2"></i>
                                <h6>Monthly Performance</h6>
                                <button class="btn btn-outline-light btn-sm" onclick="generateMonthlyReport()">Generate</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-dark border-warning">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-2x owner-highlight mb-2"></i>
                                <h6>Staff Performance</h6>
                                <button class="btn btn-outline-light btn-sm" onclick="generateStaffReport()">Generate</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-dark border-warning">
                            <div class="card-body text-center">
                                <i class="fas fa-dollar-sign fa-2x owner-highlight mb-2"></i>
                                <h6>Financial Summary</h6>
                                <button class="btn btn-outline-light btn-sm" onclick="generateFinancialReport()">Generate</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-dark border-warning">
                            <div class="card-body text-center">
                                <i class="fas fa-chart-pie fa-2x owner-highlight mb-2"></i>
                                <h6>Department Analysis</h6>
                                <button class="btn btn-outline-light btn-sm" onclick="generateDepartmentReport()">Generate</button>
                            </div>
                        </div>
                    </div>
                </div>
            `);
        }
        
        function generateMonthlyReport() {
            showToast('Generating monthly performance report...', 'info');
        }
        
        function generateStaffReport() {
            showToast('Generating staff performance report...', 'info');
        }
        
        function generateFinancialReport() {
            showToast('Generating financial summary report...', 'info');
        }
        
        function generateDepartmentReport() {
            showToast('Generating department analysis report...', 'info');
        }
        
        // Staff Management Functions
        async function loadStaffData() {
            try {
                const response = await fetch('/owner/api/staff-data', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Failed to load staff data');
                }
                
                const users = await response.json();
                updateStaffOverview(users);
                showToast('Staff data loaded!', 'success');
            } catch (error) {
                console.error('Error loading staff data:', error);
                document.getElementById('staffOverview').innerHTML = '<p class="text-muted">Failed to load staff data</p>';
                showToast('Failed to load staff data', 'error');
            }
        }
        
        function updateStaffOverview(users) {
            const container = document.getElementById('staffOverview');
            
            // Group users by role
            const roleGroups = {};
            users.forEach(user => {
                const role = user.roles?.[0]?.name || user.role || 'Unknown';
                if (!roleGroups[role]) {
                    roleGroups[role] = [];
                }
                roleGroups[role].push(user);
            });
            
            let html = '<div class="row g-3">';
            
            for (const [role, roleUsers] of Object.entries(roleGroups)) {
                if (role.toLowerCase() === 'owner') continue; // Skip owner
                
                html += `
                    <div class="col-md-6 col-lg-4">
                        <div class="glass-card p-3">
                            <h6 class="owner-highlight mb-3">
                                <i class="fas fa-${getRoleIcon(role)} me-2"></i>${role}s (${roleUsers.length})
                            </h6>
                            <div class="staff-list">
                `;
                
                roleUsers.slice(0, 3).forEach(user => {
                    const isActive = user.is_active_doctor !== false;
                    html += `
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <small><strong>${user.name || 'Unknown'}</strong></small><br>
                                <small class="text-muted">${user.email}</small>
                            </div>
                            <span class="badge ${isActive ? 'bg-success' : 'bg-warning'}">${isActive ? 'Active' : 'Inactive'}</span>
                        </div>
                    `;
                });
                
                if (roleUsers.length > 3) {
                    html += `<small class="text-muted">+${roleUsers.length - 3} more...</small>`;
                }
                
                html += `
                            </div>
                            <button class="btn btn-outline-light btn-sm mt-2" onclick="viewRoleStaff('${role}')">
                                View All ${role}s
                            </button>
                        </div>
                    </div>
                `;
            }
            
            html += '</div>';
            container.innerHTML = html;
        }
        
        function getRoleIcon(role) {
            const icons = {
                'Doctor': 'user-md',
                'Admin': 'user-shield',
                'Radiologist': 'x-ray',
                'Lab Technician': 'flask',
                'Pharmacist': 'pills'
            };
            return icons[role] || 'user';
        }
        
        function manageUsers() {
            window.location.href = '/owner/users';
        }
        
        function addNewStaff() {
            showModal('Add New Staff Member', `
                <form id="addStaffForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" name="role" required>
                                <option value="">Select Role</option>
                                <option value="Doctor">Doctor</option>
                                <option value="Admin">Admin</option>
                                <option value="Radiologist">Radiologist</option>
                                <option value="Lab Technician">Lab Technician</option>
                                <option value="Pharmacist">Pharmacist</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Revenue Share (%)</label>
                            <input type="number" class="form-control" name="revenue_share" min="0" max="100" value="70">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Temporary Password</label>
                            <input type="password" class="form-control" name="password" required>
                            <small class="form-text text-muted">Staff member should change this on first login</small>
                        </div>
                    </div>
                </form>
            `, [
                {
                    text: 'Cancel',
                    class: 'btn-secondary',
                    dismiss: true
                },
                {
                    text: 'Add Staff Member',
                    class: 'btn-success',
                    onclick: () => {
                        // Handle form submission
                        showToast('Staff member will be added', 'info');
                        return true; // Close modal
                    }
                }
            ]);
        }
        
        function staffPerformance() {
            showModal('Staff Performance Overview', `
                <div class="row">
                    <div class="col-12 mb-3">
                        <h6>Performance Metrics</h6>
                        <canvas id="staffPerformanceChart" height="300"></canvas>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 text-center">
                        <h5 class="text-success">94%</h5>
                        <small>Average Performance</small>
                    </div>
                    <div class="col-md-4 text-center">
                        <h5 class="text-info">87%</h5>
                        <small>Utilization Rate</small>
                    </div>
                    <div class="col-md-4 text-center">
                        <h5 class="text-warning">12</h5>
                        <small>Active Staff</small>
                    </div>
                </div>
            `);
        }
        
        function viewRoleStaff(role) {
            showToast(`Loading ${role} details...`, 'info');
        }
        
        function manageSuppliers() {
            // Switch to suppliers tab if not already active
            const suppliersTab = document.getElementById('suppliers-tab');
            if (suppliersTab) {
                suppliersTab.click();
            }
            
            // Load suppliers data into the table
            loadSuppliersData();
        }
        
        function loadSuppliersData() {
            const suppliersTableBody = document.querySelector('#suppliersTable tbody');
            if (!suppliersTableBody) return;
            
            // Sample supplier data
            const suppliers = [
                {
                    id: 1,
                    name: 'Medical Supplies Co.',
                    category: 'Medical Equipment',
                    status: 'Active',
                    lastOrder: '2024-08-20',
                    performance: 95,
                    contact: 'contact@medsupplies.com',
                    phone: '+1-555-0101'
                },
                {
                    id: 2,
                    name: 'Lab Equipment Pro',
                    category: 'Laboratory',
                    status: 'Active',
                    lastOrder: '2024-08-18',
                    performance: 88,
                    contact: 'sales@labequippro.com',
                    phone: '+1-555-0102'
                },
                {
                    id: 3,
                    name: 'PharmaCorp Distributors',
                    category: 'Pharmaceuticals',
                    status: 'On Hold',
                    lastOrder: '2024-07-15',
                    performance: 72,
                    contact: 'orders@pharmacorp.com',
                    phone: '+1-555-0103'
                },
                {
                    id: 4,
                    name: 'Imaging Solutions Inc.',
                    category: 'Radiology Equipment',
                    status: 'Active',
                    lastOrder: '2024-08-22',
                    performance: 92,
                    contact: 'support@imagingsol.com',
                    phone: '+1-555-0104'
                }
            ];
            
            suppliersTableBody.innerHTML = suppliers.map(supplier => `
                <tr>
                    <td>
                        <strong>${supplier.name}</strong><br>
                        <small class="text-muted">${supplier.contact}</small>
                    </td>
                    <td><span class="badge bg-info">${supplier.category}</span></td>
                    <td><span class="badge ${supplier.status === 'Active' ? 'bg-success' : 'bg-warning'}">${supplier.status}</span></td>
                    <td>${supplier.lastOrder}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-info" onclick="viewSupplier(${supplier.id})" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-outline-warning" onclick="editSupplier(${supplier.id})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-primary" onclick="createWorkOrder(${supplier.id})" title="Create Work Order">
                                <i class="fas fa-clipboard-list"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
            
            // Update supplier alerts
            updateSupplierAlerts(suppliers);
            updateSupplierStats(suppliers);
        }
        
        function updateSupplierAlerts(suppliers) {
            const alertsContainer = document.getElementById('supplierAlerts');
            if (!alertsContainer) return;
            
            const onHoldSuppliers = suppliers.filter(s => s.status === 'On Hold').length;
            const lowPerformance = suppliers.filter(s => s.performance < 80).length;
            
            alertsContainer.innerHTML = `
                <div class="alert alert-warning alert-sm mb-2">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>${onHoldSuppliers}</strong> supplier(s) on hold
                </div>
                <div class="alert alert-danger alert-sm mb-2">
                    <i class="fas fa-chart-line me-2"></i>
                    <strong>${lowPerformance}</strong> supplier(s) underperforming
                </div>
                <div class="alert alert-info alert-sm">
                    <i class="fas fa-clock me-2"></i>
                    Next review: <strong>Sept 1, 2024</strong>
                </div>
            `;
        }
        
        function updateSupplierStats(suppliers) {
            const statsContainer = document.getElementById('supplierStats');
            if (!statsContainer) return;
            
            const activeSuppliers = suppliers.filter(s => s.status === 'Active').length;
            const avgPerformance = suppliers.reduce((sum, s) => sum + s.performance, 0) / suppliers.length;
            
            statsContainer.innerHTML = `
                <div class="d-flex justify-content-between mb-2">
                    <span>Active Suppliers:</span>
                    <strong class="text-success">${activeSuppliers}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Suppliers:</span>
                    <strong>${suppliers.length}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Avg Performance:</span>
                    <strong class="owner-highlight">${avgPerformance.toFixed(1)}%</strong>
                </div>
                <div class="progress mt-2" style="height: 8px;">
                    <div class="progress-bar bg-success" style="width: ${avgPerformance}%"></div>
                </div>
            `;
        }
        
        function addSupplier() {
            showModal('Add New Supplier', `
                <form id="addSupplierForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Supplier Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category" required>
                                <option value="">Select Category</option>
                                <option value="Medical Equipment">Medical Equipment</option>
                                <option value="Laboratory">Laboratory</option>
                                <option value="Pharmaceuticals">Pharmaceuticals</option>
                                <option value="Radiology Equipment">Radiology Equipment</option>
                                <option value="Office Supplies">Office Supplies</option>
                                <option value="Maintenance">Maintenance</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" name="phone" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="3"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Payment Terms</label>
                            <select class="form-select" name="payment_terms">
                                <option value="Net 30">Net 30</option>
                                <option value="Net 60">Net 60</option>
                                <option value="Due on Receipt">Due on Receipt</option>
                                <option value="Cash on Delivery">Cash on Delivery</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="Active">Active</option>
                                <option value="On Hold">On Hold</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </form>
            `, [
                {
                    text: 'Cancel',
                    class: 'btn-secondary',
                    dismiss: true
                },
                {
                    text: 'Add Supplier',
                    class: 'btn-success',
                    onclick: () => {
                        // Handle form submission
                        showToast('Supplier added successfully!', 'success');
                        loadSuppliersData(); // Refresh the table
                        return true; // Close modal
                    }
                }
            ]);
        }
        
        function viewSupplier(id) {
            showToast('Loading supplier details...', 'info');
            // Implementation for viewing supplier details
        }
        
        function editSupplier(id) {
            showToast('Opening supplier edit form...', 'info');
            // Implementation for editing supplier
        }
        
        function createWorkOrder(id) {
            showToast('Creating work order...', 'info');
            // Implementation for creating work order
        }
        
        function viewWorkOrders() {
            showModal('Work Orders Management', `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <button class="btn btn-success" onclick="createNewWorkOrder()">
                            <i class="fas fa-plus me-1"></i>New Work Order
                        </button>
                    </div>
                    <div class="col-md-6 text-end">
                        <select class="form-select d-inline-block w-auto">
                            <option>All Status</option>
                            <option>Pending</option>
                            <option>In Progress</option>
                            <option>Completed</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-dark table-striped">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Supplier</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th>Value</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>WO-2024-001</strong></td>
                                <td>Medical Supplies Co.</td>
                                <td><span class="badge bg-primary">Medical Equipment</span></td>
                                <td><span class="badge bg-warning">In Progress</span></td>
                                <td>2024-08-30</td>
                                <td>$15,240</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>WO-2024-002</strong></td>
                                <td>Lab Equipment Pro</td>
                                <td><span class="badge bg-info">Laboratory</span></td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td>2024-08-25</td>
                                <td>$8,950</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-success" title="Invoice">
                                            <i class="fas fa-file-invoice-dollar"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            `);
        }
        
        function supplierPerformance() {
            showModal('Supplier Performance Report', `
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="text-center">
                            <h4 class="text-success">92%</h4>
                            <small>Average Performance</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-center">
                            <h4 class="text-warning">3</h4>
                            <small>Suppliers Under Review</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-center">
                            <h4 class="text-info">15</h4>
                            <small>Active Suppliers</small>
                        </div>
                    </div>
                </div>
                <canvas id="performanceChart" height="300"></canvas>
                <div class="mt-3">
                    <h6>Top Performing Suppliers:</h6>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item bg-transparent text-white d-flex justify-content-between">
                            <span>Medical Supplies Co.</span>
                            <span class="badge bg-success">95%</span>
                        </div>
                        <div class="list-group-item bg-transparent text-white d-flex justify-content-between">
                            <span>Imaging Solutions Inc.</span>
                            <span class="badge bg-success">92%</span>
                        </div>
                        <div class="list-group-item bg-transparent text-white d-flex justify-content-between">
                            <span>Lab Equipment Pro</span>
                            <span class="badge bg-info">88%</span>
                        </div>
                    </div>
                </div>
            `);
        }
                                    <tr>
                                        <td>Medical Supplies Co.</td>
                                        <td><span class="badge bg-primary">Medical Equipment</span></td>
                                        <td>John Smith<br><small>john@medsupply.com</small></td>
                                        <td><span class="badge bg-warning">3</span></td>
                                        <td><span class="text-success">95%</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-light me-1" onclick="editSupplier(1)">Edit</button>
                                            <button class="btn btn-sm btn-outline-info" onclick="assignSupplier(1)">Assign</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Premium Imaging Supplies</td>
                                        <td><span class="badge bg-info">Imaging</span></td>
                                        <td>Sarah Wilson<br><small>sarah@premium-imaging.com</small></td>
                                        <td><span class="badge bg-success">1</span></td>
                                        <td><span class="text-success">98%</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-light me-1" onclick="editSupplier(2)">Edit</button>
                                            <button class="btn btn-sm btn-outline-info" onclick="assignSupplier(2)">Assign</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>PharmaCorp Distributors</td>
                                        <td><span class="badge bg-success">Pharmaceutical</span></td>
                                        <td>Mike Johnson<br><small>mike@pharmacorp.com</small></td>
                                        <td><span class="badge bg-secondary">0</span></td>
                                        <td><span class="text-warning">89%</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-light me-1" onclick="editSupplier(3)">Edit</button>
                                            <button class="btn btn-sm btn-outline-info" onclick="assignSupplier(3)">Assign</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            `);
        }

        function addSupplier() {
            showToast('Supplier registration form will be implemented', 'info');
        }

        function viewWorkOrders() {
            showModal('Work Orders Overview', `
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="card bg-warning text-dark text-center">
                            <div class="card-body">
                                <h4>12</h4>
                                <small>Pending</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white text-center">
                            <div class="card-body">
                                <h4>8</h4>
                                <small>In Progress</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white text-center">
                            <div class="card-body">
                                <h4>45</h4>
                                <small>Completed</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white text-center">
                            <div class="card-body">
                                <h4>2</h4>
                                <small>Overdue</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-dark table-striped">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Department</th>
                                <th>Supplier</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#WO-001</td>
                                <td>Lab</td>
                                <td>Medical Supplies Co.</td>
                                <td>Equipment maintenance</td>
                                <td><span class="badge bg-warning">Pending</span></td>
                                <td>2025-01-18</td>
                                <td>$1,200</td>
                            </tr>
                            <tr>
                                <td>#WO-002</td>
                                <td>Radiology</td>
                                <td>Premium Imaging</td>
                                <td>X-ray film supplies</td>
                                <td><span class="badge bg-info">In Progress</span></td>
                                <td>2025-01-20</td>
                                <td>$850</td>
                            </tr>
                            <tr>
                                <td>#WO-003</td>
                                <td>Admin</td>
                                <td>Office Supplies Inc.</td>
                                <td>General office supplies</td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td>2025-01-15</td>
                                <td>$400</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            `);
        }

        function viewAllNotifications() {
            showToast('Comprehensive notification center will be implemented', 'info');
        }

        function viewSupplierPerformance() {
            showToast('Supplier performance analytics will be implemented', 'info');
        }

        function manageAssignments() {
            showToast('Supplier assignment interface will be implemented', 'info');
        }

        function editSupplier(id) {
            showToast(`Edit supplier ${id} form will be implemented`, 'info');
        }

        function assignSupplier(id) {
            showToast(`Assign supplier ${id} to departments interface will be implemented`, 'info');
        }

        // Business Intelligence & Report Generation Functions
        function generateAIReport() {
            const aiSection = document.getElementById('aiInsightsSection');
            aiSection.style.display = 'block';
            
            showToast('Generating AI-powered business report...', 'info');
            
            // Simulate AI analysis with realistic business insights
            setTimeout(() => {
                const currentData = getCurrentBusinessData();
                const insights = generateBusinessInsights(currentData);
                
                document.getElementById('performanceSummary').textContent = insights.summary;
                document.getElementById('keyOpportunities').textContent = insights.opportunities;
                document.getElementById('suggestedActions').textContent = insights.actions;
                
                showToast('AI Business Report Generated Successfully!', 'success');
            }, 2000);
        }

        function getCurrentBusinessData() {
            return {
                totalRevenue: 71500,
                totalExpenses: 46585,
                netProfit: 24915,
                profitMargin: 34.8,
                departments: {
                    consultation: { revenue: 25000, growth: 12.5 },
                    laboratory: { revenue: 18500, growth: 8.3 },
                    radiology: { revenue: 15200, growth: 15.2 },
                    pharmacy: { revenue: 12800, growth: 6.8 }
                },
                workOrderExpenses: 15150,
                patientCount: 318,
                avgRevenuePerPatient: 224.84
            };
        }

        function generateBusinessInsights(data) {
            const highestGrowth = Object.entries(data.departments)
                .sort(([,a], [,b]) => b.growth - a.growth)[0];
            
            const lowestGrowth = Object.entries(data.departments)
                .sort(([,a], [,b]) => a.growth - b.growth)[0];

            return {
                summary: `Strong performance with ${data.profitMargin.toFixed(1)}% profit margin. ${highestGrowth[0]} showing highest growth at ${highestGrowth[1].growth}%. Total patient volume: ${data.patientCount} (avg. $${data.avgRevenuePerPatient.toFixed(2)} per patient).`,
                
                opportunities: `1) ${lowestGrowth[0]} underperforming at ${lowestGrowth[1].growth}% growth - consider marketing/staff training. 2) Work order expenses at $${data.workOrderExpenses.toLocaleString()} - optimize supplier negotiations. 3) Patient volume growth potential in high-margin services.`,
                
                actions: `1) Implement patient retention program for ${highestGrowth[0]}. 2) Review ${lowestGrowth[0]} pricing and operational efficiency. 3) Negotiate better supplier contracts to reduce work order costs by 15-20%. 4) Expand high-performing services.`
            };
        }

        function exportBusinessReport() {
            const reportPeriod = document.getElementById('reportPeriodBI').value;
            const currentData = getCurrentBusinessData();
            const insights = generateBusinessInsights(currentData);
            
            const reportContent = `
BUSINESS PERFORMANCE REPORT
Generated: ${new Date().toLocaleDateString()}
Period: ${reportPeriod.toUpperCase()}

=== FINANCIAL SUMMARY ===
Total Revenue: $${currentData.totalRevenue.toLocaleString()}
Total Expenses: $${currentData.totalExpenses.toLocaleString()}
Net Profit: $${currentData.netProfit.toLocaleString()}
Profit Margin: ${currentData.profitMargin.toFixed(1)}%

=== DEPARTMENT PERFORMANCE ===
Consultation: $${currentData.departments.consultation.revenue.toLocaleString()} (${currentData.departments.consultation.growth}% growth)
Laboratory: $${currentData.departments.laboratory.revenue.toLocaleString()} (${currentData.departments.laboratory.growth}% growth)
Radiology: $${currentData.departments.radiology.revenue.toLocaleString()} (${currentData.departments.radiology.growth}% growth)
Pharmacy: $${currentData.departments.pharmacy.revenue.toLocaleString()} (${currentData.departments.pharmacy.growth}% growth)

=== OPERATIONAL METRICS ===
Total Patients Served: ${currentData.patientCount}
Average Revenue per Patient: $${currentData.avgRevenuePerPatient.toFixed(2)}
Work Order Expenses: $${currentData.workOrderExpenses.toLocaleString()}

=== AI BUSINESS INSIGHTS ===
Performance Summary: ${insights.summary}

Key Opportunities: ${insights.opportunities}

Recommended Actions: ${insights.actions}

=== EXPENSE BREAKDOWN ===
Staff Salaries: $${(currentData.totalRevenue * 0.45).toLocaleString()} (45% of revenue)
Equipment/Supplies: $${currentData.workOrderExpenses.toLocaleString()}
Utilities: $${(currentData.totalRevenue * 0.08).toLocaleString()} (8% of revenue)
Other Operational: $${(currentData.totalRevenue * 0.12).toLocaleString()} (12% of revenue)

Generated by FHIR-DICOM Healthcare Management Platform
            `;
            
            const blob = new Blob([reportContent], {type: 'text/plain'});
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `business-report-${reportPeriod}-${new Date().getTime()}.txt`;
            a.click();
            window.URL.revokeObjectURL(url);
            
            showToast('Business Report Exported Successfully!', 'success');
        }

        function updateBusinessIntelligence() {
            const period = document.getElementById('reportPeriodBI').value;
            const customElements = ['customStartBI', 'customEndBI'];
            
            if (period === 'custom') {
                customElements.forEach(id => document.getElementById(id).style.display = 'block');
            } else {
                customElements.forEach(id => document.getElementById(id).style.display = 'none');
            }
            
            // Hide AI insights when period changes
            document.getElementById('aiInsightsSection').style.display = 'none';
        }

        function refreshBusinessData() {
            showToast('Refreshing business intelligence data...', 'info');
            loadOwnerData();
        }

        function viewDetailedExpenses() {
            showModal('Detailed Work Order Expenses', `
                <div class="table-responsive">
                    <table class="table table-dark table-striped">
                        <thead>
                            <tr>
                                <th>Work Order</th>
                                <th>Department</th>
                                <th>Supplier</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#WO-001</td>
                                <td>Laboratory</td>
                                <td>Medical Supplies Co.</td>
                                <td><span class="badge bg-warning">Pending</span></td>
                                <td>$1,200</td>
                                <td>Jan 15, 2025</td>
                            </tr>
                            <tr>
                                <td>#WO-002</td>
                                <td>Radiology</td>
                                <td>Premium Imaging</td>
                                <td><span class="badge bg-info">In Progress</span></td>
                                <td>$850</td>
                                <td>Jan 16, 2025</td>
                            </tr>
                            <tr>
                                <td>#WO-003</td>
                                <td>Laboratory</td>
                                <td>Lab Equipment Inc.</td>
                                <td><span class="badge bg-warning">Pending</span></td>
                                <td>$2,250</td>
                                <td>Jan 17, 2025</td>
                            </tr>
                            <tr>
                                <td>#WO-004</td>
                                <td>Radiology</td>
                                <td>Premium Imaging</td>
                                <td><span class="badge bg-info">In Progress</span></td>
                                <td>$1,950</td>
                                <td>Jan 18, 2025</td>
                            </tr>
                            <tr>
                                <td>#WO-005</td>
                                <td>Administration</td>
                                <td>Office Supplies Pro</td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td>$400</td>
                                <td>Jan 12, 2025</td>
                            </tr>
                            <tr>
                                <td>#WO-006</td>
                                <td>Laboratory</td>
                                <td>Medical Supplies Co.</td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td>$3,200</td>
                                <td>Jan 10, 2025</td>
                            </tr>
                            <tr>
                                <td>#WO-007</td>
                                <td>Pharmacy</td>
                                <td>PharmaCorp Distributors</td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td>$5,300</td>
                                <td>Jan 8, 2025</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="bi-metric-card bi-warning">
                            <h5>$3,450</h5>
                            <small>Pending Orders (2)</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bi-metric-card bi-info">
                            <h5>$2,800</h5>
                            <small>In Progress (2)</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bi-metric-card bi-positive">
                            <h5>$8,900</h5>
                            <small>Completed This Month (3)</small>
                        </div>
                    </div>
                </div>
            `);
        }
        
        // Helper Functions
        function showModal(title, content, buttons = null) {
            // Remove existing modal if present
            const existingModal = document.querySelector('.custom-modal');
            if (existingModal) {
                existingModal.remove();
            }
            
            // Default buttons
            if (!buttons) {
                buttons = [{
                    text: 'Close',
                    class: 'btn-secondary',
                    dismiss: true
                }];
            }
            
            const buttonHtml = buttons.map((btn, index) => 
                `<button type="button" class="btn ${btn.class}" 
                    ${btn.dismiss ? 'data-bs-dismiss="modal"' : ''} 
                    ${btn.onclick ? `onclick="if((${btn.onclick})() !== false) { document.querySelector('.custom-modal').remove(); }"` : ''}
                    id="modalBtn${index}">
                    ${btn.text}
                </button>`
            ).join('');
            
            const modalHtml = `
                <div class="modal fade custom-modal" tabindex="-1" style="display: block; background: rgba(0,0,0,0.8);">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content bg-dark text-white border-warning">
                            <div class="modal-header border-warning">
                                <h5 class="modal-title owner-highlight">${title}</h5>
                                <button type="button" class="btn-close btn-close-white" onclick="document.querySelector('.custom-modal').remove()"></button>
                            </div>
                            <div class="modal-body">
                                ${content}
                            </div>
                            <div class="modal-footer border-warning">
                                ${buttonHtml}
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modalHtml);
        }
        
        function showToast(message, type = 'info') {
            const toastContainer = document.getElementById('toastContainer') || createToastContainer();
            
            const toastHtml = `
                <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : type === 'warning' ? 'warning' : 'info'} border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;
            
            toastContainer.insertAdjacentHTML('beforeend', toastHtml);
            
            const toastElement = toastContainer.lastElementChild;
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (toastElement.parentNode) {
                    toastElement.remove();
                }
            }, 5000);
        }
        
        function createToastContainer() {
            const container = document.createElement('div');
            container.id = 'toastContainer';
            container.className = 'position-fixed top-0 end-0 p-3';
            container.style.zIndex = '9999';
            document.body.appendChild(container);
            return container;
        }
        
        // Initialize dashboard when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Load server-side data if available
            @if(isset($dashboardData))
                const dashboardData = @json($dashboardData);
                console.log('Server-side dashboard data:', dashboardData);
                
                updateDashboardMetrics(dashboardData);
                updateDepartmentPerformance(dashboardData.departments);
                updateRecentActivity(dashboardData.recent_activity);
                updatePerformanceMetrics(dashboardData.performance_metrics);
                
                showToast('Dashboard loaded with real data!', 'success');
            @else
                // Fallback to AJAX loading
                loadDashboardData();
            @endif
            
            // Set up tab switching
            const tabs = document.querySelectorAll('[data-bs-toggle="tab"]');
            tabs.forEach(tab => {
                tab.addEventListener('shown.bs.tab', function() {
                    const target = this.getAttribute('href');
                    if (target === '#suppliers') {
                        setTimeout(loadSuppliersData, 100); // Small delay for tab animation
                    } else if (target === '#users') {
                        setTimeout(loadStaffData, 100);
                    }
                });
            });
            
            // Load suppliers data if suppliers tab is active on page load
            if (document.querySelector('#suppliers-tab.active')) {
                setTimeout(loadSuppliersData, 100);
            }
        });
    </script>
</body>
</html>
