<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pharmacist Dashboard - MedGemma Healthcare</title>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
            min-height: 100vh;
            color: #ffffff;
        }
        
        .header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .page-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        
        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        
        .stat-icon {
            font-size: 2rem;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
        }
        
        .main-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .chart-card, .info-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
        }
        
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .revenue-breakdown {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .revenue-item {
            text-align: center;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .revenue-amount {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: #10b981;
        }
        
        .revenue-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
        }
        
        .prescriptions-list {
            max-height: 350px;
            overflow-y: auto;
        }
        
        .prescription-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            margin-bottom: 0.75rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .prescription-info {
            flex: 1;
        }
        
        .prescription-drug {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .prescription-meta {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }
        
        .prescription-status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-pending {
            background: rgba(251, 191, 36, 0.2);
            color: #fcd34d;
        }
        
        .status-filled {
            background: rgba(16, 185, 129, 0.2);
            color: #6ee7b7;
        }
        
        .status-ready {
            background: rgba(59, 130, 246, 0.2);
            color: #93c5fd;
        }
        
        .status-urgent {
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
        }
        
        .inventory-alerts {
            margin-top: 2rem;
        }
        
        .alert-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            margin-bottom: 0.75rem;
            background: rgba(239, 68, 68, 0.1);
            border-radius: 12px;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        
        .alert-icon {
            font-size: 1.5rem;
            margin-right: 1rem;
            color: #ef4444;
        }
        
        .alert-text {
            flex: 1;
            line-height: 1.4;
        }
        
        .alert-critical {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.4);
        }
        
        .alert-low {
            background: rgba(251, 191, 36, 0.1);
            border-color: rgba(251, 191, 36, 0.2);
        }
        
        .alert-low .alert-icon {
            color: #f59e0b;
        }
        
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .action-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 1rem;
            color: white;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }
        
        .action-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }
        
        .action-icon {
            font-size: 1.5rem;
        }
        
        .loading {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            color: rgba(255, 255, 255, 0.7);
        }
        
        #revenueChart {
            max-width: 100%;
            max-height: 300px;
        }
        
        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
            }
            
            .revenue-breakdown {
                grid-template-columns: 1fr;
            }
            
            .container {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">🏥 MedGemma Healthcare</div>
        <div class="user-info">
            <span>💊 Pharmacist Dashboard</span>
            <span>{{ date('M d, Y') }}</span>
        </div>
    </header>

    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Pharmacist Dashboard</h1>
            <p class="page-subtitle">Prescription management with integrated inventory and revenue tracking</p>
        </div>

        <!-- Key Metrics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">💊</div>
                </div>
                <div class="stat-value" id="prescriptionsFilled">0</div>
                <div class="stat-label">Prescriptions Filled Today</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">💰</div>
                </div>
                <div class="stat-value" id="todayRevenue">$0</div>
                <div class="stat-label">Today's Revenue</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">📋</div>
                </div>
                <div class="stat-value" id="pendingOrders">0</div>
                <div class="stat-label">Pending Orders</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">⚠️</div>
                </div>
                <div class="stat-value" id="lowStock">0</div>
                <div class="stat-label">Low Stock Items</div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Revenue Breakdown -->
            <div class="chart-card">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar"></i>
                    Revenue Breakdown
                </h3>
                <div class="revenue-breakdown">
                    <div class="revenue-item">
                        <div class="revenue-amount" id="prescriptionRevenue">$0</div>
                        <div class="revenue-label">Prescription Sales</div>
                    </div>
                    <div class="revenue-item">
                        <div class="revenue-amount" id="otcRevenue">$0</div>
                        <div class="revenue-label">OTC Products</div>
                    </div>
                </div>
                <canvas id="revenueChart" width="400" height="200"></canvas>
            </div>

            <!-- Pending Prescriptions -->
            <div class="info-card">
                <h3 class="card-title">
                    <i class="fas fa-clipboard-list"></i>
                    Pending Prescriptions
                </h3>
                <div class="prescriptions-list" id="pendingPrescriptions">
                    <div class="loading">Loading prescriptions...</div>
                </div>
            </div>
        </div>

        <!-- Inventory Alerts -->
        <div class="info-card inventory-alerts">
            <h3 class="card-title">
                <i class="fas fa-exclamation-triangle"></i>
                Inventory Alerts
            </h3>
            <div id="inventoryAlerts">
                <div class="alert-item alert-critical">
                    <div class="alert-icon">🚨</div>
                    <div class="alert-text"><strong>Critical:</strong> Amoxicillin 500mg - Only 12 units remaining (Reorder immediately)</div>
                </div>
                <div class="alert-item alert-low">
                    <div class="alert-icon">⚠️</div>
                    <div class="alert-text"><strong>Low Stock:</strong> Ibuprofen 200mg - 45 units remaining (Reorder soon)</div>
                </div>
                <div class="alert-item alert-low">
                    <div class="alert-icon">⚠️</div>
                    <div class="alert-text"><strong>Low Stock:</strong> Metformin 500mg - 38 units remaining (Reorder soon)</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="info-card" style="margin-top: 2rem;">
            <h3 class="card-title">
                <i class="fas fa-bolt"></i>
                Quick Actions
            </h3>
            <div class="quick-actions">
                <a href="/prescriptions/new" class="action-btn">
                    <div class="action-icon">💊</div>
                    <span>New Prescription</span>
                </a>
                <a href="/inventory/manage" class="action-btn">
                    <div class="action-icon">📦</div>
                    <span>Manage Inventory</span>
                </a>
                <a href="/drug-interactions" class="action-btn">
                    <div class="action-icon">⚕️</div>
                    <span>Check Interactions</span>
                </a>
                <a href="/controlled-substances" class="action-btn">
                    <div class="action-icon">🔒</div>
                    <span>Controlled Substances</span>
                </a>
                <a href="/api/dashboard/pharmacist" class="action-btn">
                    <div class="action-icon">🔗</div>
                    <span>View API Data</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        let revenueChart = null;

        // Load dashboard data
        async function loadDashboardData() {
            try {
                const response = await fetch('/api/dashboard/pharmacist', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                
                const data = await response.json();
                updateDashboard(data);
            } catch (error) {
                console.error('Error loading dashboard data:', error);
                // Use demo data if API fails
                const demoData = {
                    prescriptions_filled: 34,
                    today_revenue: 2850,
                    pending_orders: 12,
                    low_stock_items: 8,
                    prescription_revenue: 2400,
                    otc_revenue: 450,
                    pending_prescriptions: [
                        { drug: 'Amoxicillin 500mg', patient: 'Patient #1234', status: 'urgent', time: '30 min ago' },
                        { drug: 'Metformin 500mg', patient: 'Patient #1245', status: 'pending', time: '1 hour ago' },
                        { drug: 'Lisinopril 10mg', patient: 'Patient #1256', status: 'ready', time: '2 hours ago' },
                        { drug: 'Atorvastatin 20mg', patient: 'Patient #1267', status: 'pending', time: '3 hours ago' }
                    ],
                    revenue_breakdown: {
                        'Prescription': 2400,
                        'OTC': 450
                    }
                };
                updateDashboard(demoData);
            }
        }

        function updateDashboard(data) {
            // Update key metrics
            document.getElementById('prescriptionsFilled').textContent = data.prescriptions_filled || 0;
            document.getElementById('todayRevenue').textContent = `$${(data.today_revenue || 0).toLocaleString()}`;
            document.getElementById('pendingOrders').textContent = data.pending_orders || 0;
            document.getElementById('lowStock').textContent = data.low_stock_items || 0;

            // Update revenue breakdown
            document.getElementById('prescriptionRevenue').textContent = `$${(data.prescription_revenue || 0).toLocaleString()}`;
            document.getElementById('otcRevenue').textContent = `$${(data.otc_revenue || 0).toLocaleString()}`;

            // Update sections
            updatePendingPrescriptions(data.pending_prescriptions || []);

            // Update chart
            updateRevenueChart(data);
        }

        function updatePendingPrescriptions(prescriptions) {
            const container = document.getElementById('pendingPrescriptions');
            
            if (prescriptions.length === 0) {
                container.innerHTML = '<div class="loading">No pending prescriptions</div>';
                return;
            }

            const prescriptionsHtml = prescriptions.map(prescription => {
                // Handle encrypted patient names
                let displayName = prescription.patient;
                try {
                    if (displayName && displayName.includes('eyJ')) {
                        displayName = `Patient #${Math.floor(Math.random() * 9000) + 1000}`;
                    }
                } catch (e) {
                    displayName = 'Anonymous Patient';
                }

                return `
                    <div class="prescription-item">
                        <div class="prescription-info">
                            <div class="prescription-drug">${prescription.drug}</div>
                            <div class="prescription-meta">${displayName} • ${prescription.time || 'Recently'}</div>
                        </div>
                        <div class="prescription-status status-${prescription.status || 'pending'}">${(prescription.status || 'pending').toUpperCase()}</div>
                    </div>
                `;
            }).join('');

            container.innerHTML = prescriptionsHtml;
        }

        function updateRevenueChart(data) {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            
            if (revenueChart) {
                revenueChart.destroy();
            }

            const prescriptionRevenue = data.prescription_revenue || 0;
            const otcRevenue = data.otc_revenue || 0;
            
            revenueChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Prescription Sales', 'OTC Products'],
                    datasets: [{
                        data: [prescriptionRevenue, otcRevenue],
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(59, 130, 246, 0.8)'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: 'rgba(255, 255, 255, 0.8)',
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        }

        // Load data when page loads
        document.addEventListener('DOMContentLoaded', loadDashboardData);
    </script>
</body>
</html>
