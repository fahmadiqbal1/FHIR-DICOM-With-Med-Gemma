<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Financial Dashboard - Admin</title>
    
    <!-- External Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #ffffff;
            overflow-x: hidden;
        }
        
        /* Header */
        .header {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .nav {
            display: flex;
            gap: 2rem;
            align-items: center;
        }
        
        .nav a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }
        
        .nav a:hover, .nav a.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
        }
        
        /* Dashboard Layout */
        .dashboard {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .dashboard-header {
            text-align: center;
            margin-bottom: 3rem;
            animation: fadeInUp 0.8s ease;
        }
        
        .dashboard-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #fff 0%, #e0e7ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .dashboard-subtitle {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 2rem;
        }
        
        /* Period Selector */
        .period-selector {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 3rem;
        }
        
        .period-btn {
            padding: 0.75rem 1.5rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: #fff;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .period-btn:hover, .period-btn.active {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
            cursor: pointer;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #667eea);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }
        
        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }
        
        .stat-value {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #fff;
        }
        
        .stat-label {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 0.5rem;
        }
        
        .stat-change {
            font-size: 0.9rem;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            display: inline-block;
        }
        
        .stat-change.positive {
            background: rgba(34, 197, 94, 0.2);
            color: #86efac;
        }
        
        .stat-change.negative {
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
        }
        
        /* Revenue Breakdown Section */
        .revenue-breakdown {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 3rem;
        }
        
        .breakdown-title {
            font-size: 2rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
            color: #fff;
        }
        
        .breakdown-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 3rem;
            align-items: center;
        }
        
        .breakdown-chart {
            position: relative;
            height: 400px;
        }
        
        .breakdown-legend {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .legend-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(8px);
        }
        
        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        
        .legend-content {
            flex: 1;
        }
        
        .legend-label {
            font-weight: 600;
            color: #fff;
            margin-bottom: 0.25rem;
        }
        
        .legend-value {
            font-size: 1.2rem;
            font-weight: 700;
            color: #fff;
        }
        
        .legend-percentage {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }
        
        /* Charts Section */
        .charts-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .chart-container {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .chart-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
            color: #fff;
        }
        
        .chart-wrapper {
            position: relative;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Performance Metrics */
        .performance-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .performance-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2rem;
        }
        
        .performance-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #fff;
        }
        
        .doctor-performance {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .doctor-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        
        .doctor-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: scale(1.02);
        }
        
        .doctor-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .doctor-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #fff;
        }
        
        .doctor-details h4 {
            color: #fff;
            margin-bottom: 0.25rem;
        }
        
        .doctor-details p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }
        
        .doctor-earnings {
            text-align: right;
        }
        
        .earnings-amount {
            font-size: 1.3rem;
            font-weight: 700;
            color: #fff;
        }
        
        .earnings-percentage {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }
        
        /* Recent Transactions */
        .transactions-section {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2rem;
        }
        
        .transactions-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #fff;
        }
        
        .transaction-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .transaction-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .transaction-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .transaction-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        
        .transaction-icon.consultation {
            background: rgba(34, 197, 94, 0.2);
            color: #86efac;
        }
        
        .transaction-icon.lab {
            background: rgba(59, 130, 246, 0.2);
            color: #93c5fd;
        }
        
        .transaction-icon.imaging {
            background: rgba(168, 85, 247, 0.2);
            color: #c4b5fd;
        }
        
        .transaction-icon.pharmacy {
            background: rgba(245, 158, 11, 0.2);
            color: #fcd34d;
        }
        
        .transaction-details h4 {
            color: #fff;
            margin-bottom: 0.25rem;
        }
        
        .transaction-details p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }
        
        .transaction-amount {
            font-size: 1.2rem;
            font-weight: 700;
            color: #fff;
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .animate-pulse {
            animation: pulse 2s ease-in-out infinite;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .charts-section {
                grid-template-columns: 1fr;
            }
            
            .breakdown-grid {
                grid-template-columns: 1fr;
            }
            
            .performance-section {
                grid-template-columns: 1fr;
            }
            
            .dashboard {
                padding: 1rem;
            }
            
            .dashboard-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <a href="/dashboard" class="logo">
                <i class="fas fa-hospital"></i>
                MedGemma Healthcare
            </a>
            
            <nav class="nav">
                <a href="/dashboard">Dashboard</a>
                <a href="/patients">Patients</a>
                <a href="/financial/admin-dashboard" class="active">Financial</a>
                <a href="/reports">Reports</a>
                <form method="POST" action="/logout" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: rgba(255,255,255,0.8); cursor: pointer;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </nav>
        </div>
    </header>

    <!-- Dashboard -->
    <div class="dashboard">
        <!-- Header -->
        <div class="dashboard-header">
            <h1 class="dashboard-title">Financial Command Center</h1>
            <p class="dashboard-subtitle">Comprehensive revenue analytics and business intelligence</p>
            
            <!-- Period Selector -->
            <div class="period-selector">
                <a href="?period=day" class="period-btn {{ $period == 'day' ? 'active' : '' }}">Today</a>
                <a href="?period=week" class="period-btn {{ $period == 'week' ? 'active' : '' }}">This Week</a>
                <a href="?period=month" class="period-btn {{ $period == 'month' ? 'active' : '' }}">This Month</a>
                <a href="?period=year" class="period-btn {{ $period == 'year' ? 'active' : '' }}">This Year</a>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-value">${{ number_format($totalRevenue, 0) }}</div>
                <div class="stat-label">Total Revenue</div>
                <div class="stat-change positive">+12.5% from last period</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">🏥</div>
                <div class="stat-value">${{ number_format($adminEarnings, 0) }}</div>
                <div class="stat-label">Admin Share (35%)</div>
                <div class="stat-change positive">+8.3% from last period</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">👨‍⚕️</div>
                <div class="stat-value">${{ number_format($totalDoctorEarnings, 0) }}</div>
                <div class="stat-label">Doctor Earnings (65%)</div>
                <div class="stat-change positive">+15.2% from last period</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-value">${{ number_format($netProfit, 0) }}</div>
                <div class="stat-label">Net Profit</div>
                <div class="stat-change positive">+22.1% from last period</div>
            </div>
        </div>

        <!-- Revenue Breakdown -->
        <div class="revenue-breakdown">
            <h2 class="breakdown-title">💼 Revenue Stream Analysis</h2>
            <div class="breakdown-grid">
                <div class="breakdown-chart">
                    <canvas id="revenueBreakdownChart"></canvas>
                </div>
                <div class="breakdown-legend">
                    <div class="legend-item" data-segment="doctors">
                        <div class="legend-color" style="background: #667eea;"></div>
                        <div class="legend-content">
                            <div class="legend-label">👨‍⚕️ Doctor Services</div>
                            <div class="legend-value">${{ number_format($totalDoctorEarnings, 0) }}</div>
                            <div class="legend-percentage">{{ number_format(($totalDoctorEarnings / max($totalRevenue, 1)) * 100, 1) }}%</div>
                        </div>
                    </div>
                    
                    <div class="legend-item" data-segment="admin">
                        <div class="legend-color" style="background: #764ba2;"></div>
                        <div class="legend-content">
                            <div class="legend-label">🏥 Admin Setup Share</div>
                            <div class="legend-value">${{ number_format($adminEarnings, 0) }}</div>
                            <div class="legend-percentage">{{ number_format(($adminEarnings / max($totalRevenue, 1)) * 100, 1) }}%</div>
                        </div>
                    </div>
                    
                    <div class="legend-item" data-segment="lab">
                        <div class="legend-color" style="background: #3b82f6;"></div>
                        <div class="legend-content">
                            <div class="legend-label">🧪 Laboratory Services</div>
                            <div class="legend-value">${{ number_format($totalRevenue * 0.15, 0) }}</div>
                            <div class="legend-percentage">15.0%</div>
                        </div>
                    </div>
                    
                    <div class="legend-item" data-segment="imaging">
                        <div class="legend-color" style="background: #a855f7;"></div>
                        <div class="legend-content">
                            <div class="legend-label">📸 X-Ray & Ultrasound</div>
                            <div class="legend-value">${{ number_format($totalRevenue * 0.12, 0) }}</div>
                            <div class="legend-percentage">12.0%</div>
                        </div>
                    </div>
                    
                    <div class="legend-item" data-segment="pharmacy">
                        <div class="legend-color" style="background: #f59e0b;"></div>
                        <div class="legend-content">
                            <div class="legend-label">💊 Pharmacy Sales</div>
                            <div class="legend-value">${{ number_format($totalRevenue * 0.08, 0) }}</div>
                            <div class="legend-percentage">8.0%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
            <div class="chart-container">
                <h3 class="chart-title">📈 Revenue Trends</h3>
                <div class="chart-wrapper">
                    <canvas id="revenueTrendChart"></canvas>
                </div>
            </div>
            
            <div class="chart-container">
                <h3 class="chart-title">💸 Expense Distribution</h3>
                <div class="chart-wrapper">
                    <canvas id="expenseChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Performance Section -->
        <div class="performance-section">
            <div class="performance-card">
                <h3 class="performance-title">🏆 Top Performing Doctors</h3>
                <div class="doctor-performance">
                    @forelse($topDoctors as $index => $doctor)
                    <div class="doctor-item">
                        <div class="doctor-info">
                            <div class="doctor-avatar">{{ substr($doctor->doctor->name ?? 'Dr', 0, 1) }}</div>
                            <div class="doctor-details">
                                <h4>{{ $doctor->doctor->name ?? 'Unknown Doctor' }}</h4>
                                <p>{{ number_format($doctor->total / 1500) }} consultations</p>
                            </div>
                        </div>
                        <div class="doctor-earnings">
                            <div class="earnings-amount">${{ number_format($doctor->total, 0) }}</div>
                            <div class="earnings-percentage">{{ number_format(($doctor->total / max($totalDoctorEarnings, 1)) * 100, 1) }}%</div>
                        </div>
                    </div>
                    @empty
                    <p style="color: rgba(255,255,255,0.7); text-align: center;">No doctor performance data available</p>
                    @endforelse
                </div>
            </div>
            
            <div class="performance-card">
                <h3 class="performance-title">📊 Key Metrics</h3>
                <div class="doctor-performance">
                    <div class="doctor-item">
                        <div class="doctor-info">
                            <div class="doctor-avatar">💰</div>
                            <div class="doctor-details">
                                <h4>Average Transaction</h4>
                                <p>Per patient visit</p>
                            </div>
                        </div>
                        <div class="doctor-earnings">
                            <div class="earnings-amount">${{ number_format($totalRevenue / max(1, count($recentInvoices)), 0) }}</div>
                        </div>
                    </div>
                    
                    <div class="doctor-item">
                        <div class="doctor-info">
                            <div class="doctor-avatar">📈</div>
                            <div class="doctor-details">
                                <h4>Growth Rate</h4>
                                <p>Month over month</p>
                            </div>
                        </div>
                        <div class="doctor-earnings">
                            <div class="earnings-amount">+18.5%</div>
                        </div>
                    </div>
                    
                    <div class="doctor-item">
                        <div class="doctor-info">
                            <div class="doctor-avatar">⚡</div>
                            <div class="doctor-details">
                                <h4>Efficiency Score</h4>
                                <p>Revenue per hour</p>
                            </div>
                        </div>
                        <div class="doctor-earnings">
                            <div class="earnings-amount">94%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="transactions-section">
            <h3 class="transactions-title">🔄 Recent Transactions</h3>
            @forelse($recentInvoices->take(10) as $invoice)
            <div class="transaction-item">
                <div class="transaction-info">
                    <div class="transaction-icon consultation">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div class="transaction-details">
                        <h4>{{ $invoice->patient->first_name ?? 'Unknown' }} {{ $invoice->patient->last_name ?? 'Patient' }}</h4>
                        <p>{{ $invoice->service_type }} - Dr. {{ $invoice->doctor->name ?? 'Unknown' }}</p>
                    </div>
                </div>
                <div class="transaction-amount">${{ number_format($invoice->amount, 2) }}</div>
            </div>
            @empty
            <p style="color: rgba(255,255,255,0.7); text-align: center;">No recent transactions</p>
            @endforelse
        </div>
    </div>

    <script>
        // Chart.js default config
        Chart.defaults.color = '#ffffff';
        Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.1)';
        Chart.defaults.backgroundColor = 'rgba(255, 255, 255, 0.05)';

        // Revenue Breakdown Pie Chart
        const revenueBreakdownCtx = document.getElementById('revenueBreakdownChart').getContext('2d');
        const totalRevenue = {{ $totalRevenue }};
        const doctorShare = {{ $totalDoctorEarnings }};
        const adminShare = {{ $adminEarnings }};
        const labRevenue = totalRevenue * 0.15;
        const imagingRevenue = totalRevenue * 0.12;
        const pharmacyRevenue = totalRevenue * 0.08;

        new Chart(revenueBreakdownCtx, {
            type: 'doughnut',
            data: {
                labels: ['Doctor Services', 'Admin Setup', 'Laboratory', 'X-Ray & Ultrasound', 'Pharmacy'],
                datasets: [{
                    data: [doctorShare, adminShare, labRevenue, imagingRevenue, pharmacyRevenue],
                    backgroundColor: [
                        '#667eea',
                        '#764ba2', 
                        '#3b82f6',
                        '#a855f7',
                        '#f59e0b'
                    ],
                    borderWidth: 0,
                    hoverBorderWidth: 4,
                    hoverBorderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed;
                                const percentage = ((value / totalRevenue) * 100).toFixed(1);
                                return context.label + ': $' + value.toLocaleString() + ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '50%',
                animation: {
                    animateRotate: true,
                    duration: 2000,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Revenue Trend Chart
        const revenueTrendCtx = document.getElementById('revenueTrendChart').getContext('2d');
        const dailyRevenueData = @json($dailyRevenue);
        
        new Chart(revenueTrendCtx, {
            type: 'line',
            data: {
                labels: dailyRevenueData.map(item => item.date),
                datasets: [{
                    label: 'Daily Revenue',
                    data: dailyRevenueData.map(item => item.revenue),
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        },
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Expense Distribution Chart
        const expenseCtx = document.getElementById('expenseChart').getContext('2d');
        const expenseData = @json($expenseBreakdown);
        
        new Chart(expenseCtx, {
            type: 'pie',
            data: {
                labels: expenseData.map(item => item.category?.name || 'Other'),
                datasets: [{
                    data: expenseData.map(item => item.total),
                    backgroundColor: [
                        '#ef4444',
                        '#f97316', 
                        '#eab308',
                        '#84cc16',
                        '#06b6d4'
                    ],
                    borderWidth: 0,
                    hoverBorderWidth: 4,
                    hoverBorderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                return context.label + ': $' + context.parsed.toLocaleString();
                            }
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    duration: 2000,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Animate stats on load
        window.addEventListener('load', function() {
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.animation = 'fadeInUp 0.8s ease forwards';
                }, index * 100);
            });

            // Animate stat values
            const statValues = document.querySelectorAll('.stat-value');
            statValues.forEach(stat => {
                const finalValue = parseInt(stat.textContent.replace(/[^0-9]/g, ''));
                let currentValue = 0;
                const increment = finalValue / 100;
                const timer = setInterval(() => {
                    currentValue += increment;
                    if (currentValue >= finalValue) {
                        currentValue = finalValue;
                        clearInterval(timer);
                    }
                    stat.textContent = '$' + Math.floor(currentValue).toLocaleString();
                }, 20);
            });
        });

        // Interactive legend
        document.querySelectorAll('.legend-item').forEach(item => {
            item.addEventListener('click', function() {
                const segment = this.dataset.segment;
                // Add interactive functionality here
                this.style.transform = 'scale(1.05)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 200);
            });
        });

        // Auto-refresh data every 30 seconds
        setInterval(() => {
            // Add AJAX call to refresh data here
            console.log('Refreshing dashboard data...');
        }, 30000);
    </script>
</body>
</html>
