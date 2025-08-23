<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lab Tech Dashboard - MedGemma Healthcare</title>
    
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
        
        .orders-list {
            max-height: 350px;
            overflow-y: auto;
        }
        
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            margin-bottom: 0.75rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .order-info {
            flex: 1;
        }
        
        .order-test {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .order-meta {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }
        
        .order-status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-pending {
            background: rgba(251, 191, 36, 0.2);
            color: #fcd34d;
        }
        
        .status-processing {
            background: rgba(59, 130, 246, 0.2);
            color: #93c5fd;
        }
        
        .status-completed {
            background: rgba(16, 185, 129, 0.2);
            color: #6ee7b7;
        }
        
        .status-urgent {
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
        }
        
        .equipment-status {
            margin-top: 2rem;
        }
        
        .equipment-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            margin-bottom: 0.75rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .equipment-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .equipment-icon {
            font-size: 1.5rem;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(16, 185, 129, 0.2);
            border-radius: 8px;
            color: #10b981;
        }
        
        .equipment-details h4 {
            margin-bottom: 0.25rem;
        }
        
        .equipment-details p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }
        
        .equipment-status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-online {
            background: rgba(16, 185, 129, 0.2);
            color: #6ee7b7;
        }
        
        .status-maintenance {
            background: rgba(251, 191, 36, 0.2);
            color: #fcd34d;
        }
        
        .status-offline {
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
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
        
        #testChart {
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
            <span>🧪 Lab Tech Dashboard</span>
            <span>{{ date('M d, Y') }}</span>
        </div>
    </header>

    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Lab Tech Dashboard</h1>
            <p class="page-subtitle">Laboratory operations with integrated test revenue and equipment tracking</p>
        </div>

        <!-- Key Metrics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">🧪</div>
                </div>
                <div class="stat-value" id="testsCompleted">0</div>
                <div class="stat-label">Tests Completed Today</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">💰</div>
                </div>
                <div class="stat-value" id="todayRevenue">$0</div>
                <div class="stat-label">Today's Test Revenue</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">⏳</div>
                </div>
                <div class="stat-value" id="pendingTests">0</div>
                <div class="stat-label">Pending Tests</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">⚡</div>
                </div>
                <div class="stat-value" id="avgTurnaround">0</div>
                <div class="stat-label">Avg Turnaround (hrs)</div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Test Revenue Analysis -->
            <div class="chart-card">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar"></i>
                    Test Revenue Analysis
                </h3>
                <div class="revenue-breakdown">
                    <div class="revenue-item">
                        <div class="revenue-amount" id="bloodTestRevenue">$0</div>
                        <div class="revenue-label">Blood Tests</div>
                    </div>
                    <div class="revenue-item">
                        <div class="revenue-amount" id="urineTestRevenue">$0</div>
                        <div class="revenue-label">Urine Tests</div>
                    </div>
                </div>
                <canvas id="testChart" width="400" height="200"></canvas>
            </div>

            <!-- Pending Lab Orders -->
            <div class="info-card">
                <h3 class="card-title">
                    <i class="fas fa-flask"></i>
                    Pending Lab Orders
                </h3>
                <div class="orders-list" id="pendingOrders">
                    <div class="loading">Loading lab orders...</div>
                </div>
            </div>
        </div>

        <!-- Equipment Status -->
        <div class="info-card equipment-status">
            <h3 class="card-title">
                <i class="fas fa-cogs"></i>
                Equipment Status & Utilization
            </h3>
            <div id="equipmentStatus">
                <div class="equipment-item">
                    <div class="equipment-info">
                        <div class="equipment-icon">🔬</div>
                        <div class="equipment-details">
                            <h4>Hematology Analyzer</h4>
                            <p>CBC, Differential • 89% utilization</p>
                        </div>
                    </div>
                    <div class="equipment-status-badge status-online">ONLINE</div>
                </div>
                
                <div class="equipment-item">
                    <div class="equipment-info">
                        <div class="equipment-icon">⚗️</div>
                        <div class="equipment-details">
                            <h4>Chemistry Analyzer</h4>
                            <p>Metabolic Panel • 76% utilization</p>
                        </div>
                    </div>
                    <div class="equipment-status-badge status-online">ONLINE</div>
                </div>
                
                <div class="equipment-item">
                    <div class="equipment-info">
                        <div class="equipment-icon">🦠</div>
                        <div class="equipment-details">
                            <h4>Microbiology Incubator</h4>
                            <p>Culture Studies • Scheduled maintenance</p>
                        </div>
                    </div>
                    <div class="equipment-status-badge status-maintenance">MAINTENANCE</div>
                </div>
                
                <div class="equipment-item">
                    <div class="equipment-info">
                        <div class="equipment-icon">💉</div>
                        <div class="equipment-details">
                            <h4>Immunoassay Analyzer</h4>
                            <p>Hormone Tests • 92% utilization</p>
                        </div>
                    </div>
                    <div class="equipment-status-badge status-online">ONLINE</div>
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
                <a href="/lab-orders/new" class="action-btn">
                    <div class="action-icon">🧪</div>
                    <span>New Test Order</span>
                </a>
                <a href="/sample-tracking" class="action-btn">
                    <div class="action-icon">📦</div>
                    <span>Track Samples</span>
                </a>
                <a href="/quality-control" class="action-btn">
                    <div class="action-icon">✅</div>
                    <span>Quality Control</span>
                </a>
                <a href="/lab-reports" class="action-btn">
                    <div class="action-icon">📊</div>
                    <span>Generate Reports</span>
                </a>
                <a href="/equipment-maintenance" class="action-btn">
                    <div class="action-icon">🔧</div>
                    <span>Equipment Maintenance</span>
                </a>
                <a href="/api/dashboard/lab" class="action-btn">
                    <div class="action-icon">🔗</div>
                    <span>View API Data</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        let testChart = null;

        // Load dashboard data
        async function loadDashboardData() {
            try {
                const response = await fetch('/api/dashboard/lab', {
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
                    tests_completed: 67,
                    today_revenue: 3240,
                    pending_tests: 18,
                    avg_turnaround: 4.2,
                    blood_test_revenue: 2100,
                    urine_test_revenue: 1140,
                    pending_orders: [
                        { test: 'Complete Blood Count', patient: 'Patient #1423', status: 'urgent', time: '20 min ago', cost: 45 },
                        { test: 'Basic Metabolic Panel', patient: 'Patient #1456', status: 'processing', time: '45 min ago', cost: 65 },
                        { test: 'Lipid Panel', patient: 'Patient #1478', status: 'pending', time: '1 hour ago', cost: 55 },
                        { test: 'Thyroid Function', patient: 'Patient #1489', status: 'pending', time: '1.5 hours ago', cost: 85 }
                    ],
                    test_distribution: {
                        'Blood Tests': 45,
                        'Urine Tests': 25,
                        'Microbiology': 20,
                        'Chemistry': 10
                    }
                };
                updateDashboard(demoData);
            }
        }

        function updateDashboard(data) {
            // Update key metrics
            document.getElementById('testsCompleted').textContent = data.tests_completed || 0;
            document.getElementById('todayRevenue').textContent = `$${(data.today_revenue || 0).toLocaleString()}`;
            document.getElementById('pendingTests').textContent = data.pending_tests || 0;
            document.getElementById('avgTurnaround').textContent = (data.avg_turnaround || 0).toFixed(1);

            // Update revenue breakdown
            document.getElementById('bloodTestRevenue').textContent = `$${(data.blood_test_revenue || 0).toLocaleString()}`;
            document.getElementById('urineTestRevenue').textContent = `$${(data.urine_test_revenue || 0).toLocaleString()}`;

            // Update sections
            updatePendingOrders(data.pending_orders || []);

            // Update chart
            updateTestChart(data);
        }

        function updatePendingOrders(orders) {
            const container = document.getElementById('pendingOrders');
            
            if (orders.length === 0) {
                container.innerHTML = '<div class="loading">No pending orders</div>';
                return;
            }

            const ordersHtml = orders.map(order => {
                // Handle encrypted patient names
                let displayName = order.patient;
                try {
                    if (displayName && displayName.includes('eyJ')) {
                        displayName = `Patient #${Math.floor(Math.random() * 9000) + 1000}`;
                    }
                } catch (e) {
                    displayName = 'Anonymous Patient';
                }

                return `
                    <div class="order-item">
                        <div class="order-info">
                            <div class="order-test">${order.test}</div>
                            <div class="order-meta">${displayName} • ${order.time || 'Recently'} • $${order.cost || 0}</div>
                        </div>
                        <div class="order-status status-${order.status || 'pending'}">${(order.status || 'pending').toUpperCase()}</div>
                    </div>
                `;
            }).join('');

            container.innerHTML = ordersHtml;
        }

        function updateTestChart(data) {
            const ctx = document.getElementById('testChart').getContext('2d');
            
            if (testChart) {
                testChart.destroy();
            }

            const distribution = data.test_distribution || {
                'Blood Tests': 45,
                'Urine Tests': 25,
                'Microbiology': 20,
                'Chemistry': 10
            };
            
            testChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(distribution),
                    datasets: [{
                        data: Object.values(distribution),
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(168, 85, 247, 0.8)',
                            'rgba(251, 191, 36, 0.8)'
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
