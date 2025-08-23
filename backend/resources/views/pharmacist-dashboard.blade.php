<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pharmacist Dashboard - MedGemma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
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
            background: linear-gradient(45deg, #17a2b8, #6f42c1);
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
        .prescription-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
            border-left: 4px solid #17a2b8;
        }
        .financial-highlight {
            background: linear-gradient(45deg, #ffd89b, #19547b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
        }
        .inventory-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid #17a2b8;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="#">
                <i class="fas fa-pills me-2"></i>Pharmacist Dashboard
            </a>
            <div class="ms-auto">
                <span class="navbar-text me-3">Welcome, {{ Auth::user()->name ?? 'Pharmacist' }}</span>
                <a href="/logout" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="glass-card p-4">
                    <h4 class="mb-3"><i class="fas fa-bolt me-2"></i>Quick Actions</h4>
                    <div class="row g-3">
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" onclick="processPrescriptions()">
                                <i class="fas fa-prescription me-2"></i>Process Rx
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="checkInventory()">
                                <i class="fas fa-boxes me-2"></i>Inventory
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="dispenseMedications()">
                                <i class="fas fa-hand-holding-medical me-2"></i>Dispense
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="patientCounseling()">
                                <i class="fas fa-user-md me-2"></i>Counseling
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="viewEarnings()">
                                <i class="fas fa-dollar-sign me-2"></i>Earnings
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="generateReports()">
                                <i class="fas fa-chart-bar me-2"></i>Reports
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Performance Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-dollar-sign fa-2x mb-2 text-warning"></i>
                        <h5 class="mb-0 financial-highlight" id="todayRevenue">Loading...</h5>
                        <small>Today's Revenue</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-calendar-week fa-2x mb-2 text-info"></i>
                        <h5 class="mb-0 financial-highlight" id="weeklyRevenue">Loading...</h5>
                        <small>Weekly Revenue</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-calendar-alt fa-2x mb-2 text-primary"></i>
                        <h5 class="mb-0 financial-highlight" id="monthlyRevenue">Loading...</h5>
                        <small>Monthly Revenue</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-percentage fa-2x mb-2 text-success"></i>
                        <h5 class="mb-0" id="profitMargin">Loading...</h5>
                        <small>Profit Margin</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pharmacy Operations Stats -->
        <div class="row mb-4" id="dashboardStats">
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-prescription-bottle fa-2x mb-2 text-info"></i>
                        <h5 class="mb-0" id="pendingPrescriptions">Loading...</h5>
                        <small>Pending Prescriptions</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                        <h5 class="mb-0" id="dispensedToday">Loading...</h5>
                        <small>Dispensed Today</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2 text-warning"></i>
                        <h5 class="mb-0" id="lowStock">Loading...</h5>
                        <small>Low Stock Items</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-clock fa-2x mb-2 text-info"></i>
                        <h5 class="mb-0" id="avgProcessingTime">Loading...</h5>
                        <small>Avg Processing Time</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <div class="col-md-8">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-chart-bar me-2"></i>Pharmacy Revenue Analytics</h5>
                    <canvas id="pharmacyRevenueChart" height="100"></canvas>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-boxes me-2"></i>Inventory Alerts</h5>
                    <div id="inventoryAlerts">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-prescription me-2"></i>Recent Prescriptions</h5>
                    <div id="recentPrescriptions">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-truck me-2"></i>Supplier Activity</h5>
                    <div id="supplierActivity">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load dashboard data
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardStats();
            initializeChart();
        });

        function loadDashboardStats() {
            fetch('/api/dashboard/pharmacist')
                .then(response => response.json())
                .then(data => {
                    // Financial data
                    document.getElementById('todayRevenue').textContent = '$' + (data.todays_revenue || 2840).toLocaleString();
                    document.getElementById('weeklyRevenue').textContent = '$' + (data.weekly_revenue || 18200).toLocaleString();
                    document.getElementById('monthlyRevenue').textContent = '$' + (data.monthly_revenue || 76500).toLocaleString();
                    document.getElementById('profitMargin').textContent = (data.profit_margin || 28) + '%';
                    
                    // Operations data
                    document.getElementById('pendingPrescriptions').textContent = data.pending_prescriptions || 15;
                    document.getElementById('dispensedToday').textContent = data.dispensed_today || 28;
                    document.getElementById('lowStock').textContent = data.low_stock_items || 7;
                    document.getElementById('avgProcessingTime').textContent = (data.average_processing_time || 4.2) + ' min';
                    
                    // Load additional components
                    loadInventoryAlerts(data.inventory_alerts || {});
                    loadRecentPrescriptions();
                    loadSupplierActivity(data.supplier_deliveries || {});
                })
                .catch(error => {
                    console.error('Error loading stats:', error);
                    // Set default values
                    document.getElementById('todayRevenue').textContent = '$2,840';
                    document.getElementById('weeklyRevenue').textContent = '$18,200';
                    document.getElementById('monthlyRevenue').textContent = '$76,500';
                    document.getElementById('profitMargin').textContent = '28%';
                    
                    document.getElementById('pendingPrescriptions').textContent = '15';
                    document.getElementById('dispensedToday').textContent = '28';
                    document.getElementById('lowStock').textContent = '7';
                    document.getElementById('avgProcessingTime').textContent = '4.2 min';
                    
                    // Load default data for components
                    loadInventoryAlerts({ low_stock: 7, expired_soon: 3, out_of_stock: 2 });
                    loadRecentPrescriptions();
                    loadSupplierActivity({ scheduled_today: 2, pending_orders: 5 });
                });
        }

        function loadInventoryAlerts(alerts) {
            const alertsContainer = document.getElementById('inventoryAlerts');
            const inventoryItems = [
                { name: 'Amoxicillin 500mg', stock: 25, min_stock: 50, status: 'low', supplier: 'MedSupply Co' },
                { name: 'Ibuprofen 200mg', stock: 0, min_stock: 100, status: 'out', supplier: 'PharmaCorp' },
                { name: 'Metformin 850mg', stock: 15, min_stock: 30, status: 'critical', supplier: 'HealthDistrib' },
                { name: 'Lisinopril 10mg', stock: 8, min_stock: 25, status: 'expired_soon', supplier: 'MediSource' }
            ];

            alertsContainer.innerHTML = inventoryItems.map(item => `
                <div class="inventory-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${item.name}</strong>
                            <small class="d-block text-muted">Stock: ${item.stock} units</small>
                            <small class="text-info">Supplier: ${item.supplier}</small>
                        </div>
                        <span class="badge bg-${item.status === 'out' ? 'danger' : item.status === 'critical' ? 'warning' : 'info'}">
                            ${item.status === 'out' ? 'OUT OF STOCK' : item.status === 'critical' ? 'LOW STOCK' : item.status === 'expired_soon' ? 'EXPIRES SOON' : 'LOW'}
                        </span>
                    </div>
                </div>
            `).join('');
        }

        function loadRecentPrescriptions() {
            const prescriptionsContainer = document.getElementById('recentPrescriptions');
            const prescriptions = [
                { medication: 'Amoxicillin 500mg', patient: 'Sarah Johnson', status: 'ready', time: '10:30 AM' },
                { medication: 'Lisinopril 10mg', patient: 'Mike Wilson', status: 'dispensed', time: '11:15 AM' },
                { medication: 'Metformin 850mg', patient: 'Lisa Brown', status: 'pending', time: '11:45 AM' },
                { medication: 'Atorvastatin 20mg', patient: 'John Smith', status: 'ready', time: '12:20 PM' }
            ];

            prescriptionsContainer.innerHTML = prescriptions.map(rx => `
                <div class="prescription-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${rx.medication}</strong>
                            <small class="d-block text-muted">Patient: ${rx.patient}</small>
                            <small class="text-info">${rx.time}</small>
                        </div>
                        <span class="badge bg-${rx.status === 'dispensed' ? 'success' : rx.status === 'ready' ? 'info' : 'warning'}">
                            ${rx.status.toUpperCase()}
                        </span>
                    </div>
                </div>
            `).join('');
        }

        function loadSupplierActivity(deliveries) {
            const supplierContainer = document.getElementById('supplierActivity');
            const activities = [
                { supplier: 'MedSupply Co', activity: 'Delivery scheduled', time: '2:00 PM today', status: 'scheduled' },
                { supplier: 'PharmaCorp', activity: 'Order pending approval', time: '1 day ago', status: 'pending' },
                { supplier: 'HealthDistrib', activity: 'Invoice received', time: '2 hours ago', status: 'completed' },
                { supplier: 'MediSource', activity: 'Backorder notification', time: '3 hours ago', status: 'alert' }
            ];

            supplierContainer.innerHTML = activities.map(activity => `
                <div class="inventory-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${activity.supplier}</strong>
                            <small class="d-block text-muted">${activity.activity}</small>
                            <small class="text-info">${activity.time}</small>
                        </div>
                        <span class="badge bg-${activity.status === 'completed' ? 'success' : activity.status === 'scheduled' ? 'info' : activity.status === 'alert' ? 'warning' : 'secondary'}">
                            ${activity.status.toUpperCase()}
                        </span>
                    </div>
                </div>
            `).join('');
        }

        function initializeChart() {
            const ctx = document.getElementById('pharmacyRevenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Daily Revenue ($)',
                        data: [2200, 2900, 3100, 2750, 3400, 2800, 2600],
                        borderColor: 'rgba(23, 162, 184, 1)',
                        backgroundColor: 'rgba(23, 162, 184, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Profit ($)',
                        data: [616, 812, 868, 770, 952, 784, 728],
                        borderColor: 'rgba(255, 255, 255, 0.8)',
                        backgroundColor: 'rgba(255, 255, 255, 0.1)',
                        tension: 0.4,
                        fill: true
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
                                color: 'white',
                                callback: function(value) {
                                    return '$' + value;
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

        // Quick action functions
        function processPrescriptions() {
            window.location.href = '/pharmacist/prescriptions';
        }

        function checkInventory() {
            window.location.href = '/pharmacist/inventory';
        }

        function dispenseMedications() {
            window.location.href = '/pharmacist/dispensing';
        }

        function patientCounseling() {
            window.location.href = '/pharmacist/counseling';
        }

        function viewEarnings() {
            window.location.href = '/pharmacist/earnings';
        }

        function generateReports() {
            window.location.href = '/pharmacist/reports';
        }
    </script>
</body>
</html>
