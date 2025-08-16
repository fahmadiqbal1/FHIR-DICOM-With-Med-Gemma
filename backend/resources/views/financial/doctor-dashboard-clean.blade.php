<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Doctor Financial Dashboard - Healthcare AI Platform</title>
    
    @php
        // Pre-calculate percentages to avoid linter issues
        $avgPerPatientPercent = min(100, round(($avgPerPatient / 150) * 100, 2));
        $dailyTargetPercent = min(100, round(($todayEarnings / 400) * 100, 2));
        $satisfactionPercent = $patientSatisfaction;
    @endphp
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: white;
            overflow-x: hidden;
        }
        
        /* Header Styles */
        .header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .logo::before {
            content: "💰";
            margin-right: 0.5rem;
        }
        
        .nav {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
            white-space: nowrap;
        }
        
        .nav a:hover, .nav a.active {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            flex-wrap: wrap;
        }
        
        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 15px;
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
        }
        
        /* Main Content */
        .main-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .page-header h1 {
            font-size: 3rem;
            font-weight: 600;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #fff, #e1e8ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .page-header .subtitle {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 1rem;
        }
        
        .revenue-badge {
            display: inline-block;
            background: linear-gradient(45deg, #ffd700, #ffed4e);
            color: #333;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            font-weight: bold;
            font-size: 1rem;
        }
        
        /* Grid Layout */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        @media (max-width: 1024px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* Card Styles */
        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card h2, .card h3 {
            margin-bottom: 1rem;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        /* Stat Cards */
        .stat-card {
            text-align: center;
            position: relative;
        }
        
        .stat-card::before {
            content: "";
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            border-radius: 25px;
            z-index: -1;
        }
        
        .stat-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
        }
        
        .stat-icon.today { background: linear-gradient(45deg, #4ade80, #22c55e); }
        .stat-icon.week { background: linear-gradient(45deg, #60a5fa, #3b82f6); }
        .stat-icon.month { background: linear-gradient(45deg, #a78bfa, #8b5cf6); }
        .stat-icon.patients { background: linear-gradient(45deg, #f59e0b, #d97706); }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: white;
        }
        
        .stat-label {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 0.5rem;
        }
        
        .stat-detail {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.6);
        }
        
        /* Chart Container */
        .chart-container {
            position: relative;
            height: 400px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 1rem;
            margin-top: 1rem;
        }
        
        /* Performance Metrics */
        .performance-grid {
            display: grid;
            gap: 1.5rem;
        }
        
        .metric-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 1.5rem;
            border-left: 4px solid;
        }
        
        .metric-item.primary { border-left-color: #667eea; }
        .metric-item.success { border-left-color: #4ade80; }
        .metric-item.warning { border-left-color: #fbbf24; }
        .metric-item.info { border-left-color: #60a5fa; }
        
        .metric-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .metric-label {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .metric-value {
            font-size: 1.2rem;
            font-weight: bold;
            color: white;
        }
        
        .progress-bar {
            height: 8px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            overflow: hidden;
            position: relative;
        }
        
        .progress-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.3s ease;
        }
        
        .progress-fill.primary { background: linear-gradient(45deg, #667eea, #764ba2); }
        .progress-fill.success { background: linear-gradient(45deg, #4ade80, #22c55e); }
        .progress-fill.warning { background: linear-gradient(45deg, #fbbf24, #f59e0b); }
        
        /* Recent Activity */
        .activity-list {
            max-height: 400px;
            overflow-y: auto;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.05);
            padding: 1rem;
        }
        
        .activity-list::-webkit-scrollbar {
            width: 8px;
        }
        
        .activity-list::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }
        
        .activity-list::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
        }
        
        .activity-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        
        .activity-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        
        .activity-info h4 {
            color: white;
            margin-bottom: 0.25rem;
            font-size: 1rem;
        }
        
        .activity-info p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            margin: 0;
        }
        
        .activity-amount {
            text-align: right;
        }
        
        .activity-amount .amount {
            font-size: 1.1rem;
            font-weight: bold;
            color: #4ade80;
            display: block;
        }
        
        .activity-amount .time {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
        }
        
        /* Date Filter */
        .date-filter {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        
        .date-input {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            color: white;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            backdrop-filter: blur(5px);
        }
        
        .date-input:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.15);
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .empty-state .icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        
        .empty-state h3 {
            color: white;
            margin-bottom: 1rem;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .header-content {
                padding: 0 1rem;
                flex-direction: column;
                gap: 1rem;
            }
            
            .nav {
                order: -1;
                width: 100%;
                justify-content: center;
            }
            
            .main-content {
                padding: 1rem;
            }
            
            .page-header h1 {
                font-size: 2rem;
            }
            
            .card {
                padding: 1.5rem;
            }
            
            .stat-value {
                font-size: 2rem;
            }
            
            .date-filter {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <a href="/dashboard" class="logo">Financial Dashboard</a>
            <nav class="nav">
                <a href="/dashboard">Dashboard</a>
                <a href="/patients">Patients</a>
                <a href="/financial/doctor-dashboard" class="active">Financial</a>
                <a href="/medgemma">AI Analysis</a>
            </nav>
            <div class="user-info">
                <span>Dr. {{ Auth::user()->name ?? 'John Smith' }}</span>
                <a href="/dashboard" class="back-btn">← Back</a>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="page-header">
            <h1>💰 Financial Dashboard</h1>
            <p class="subtitle">Track your consultation earnings and performance</p>
            <div class="revenue-badge">{{ $revenuePercentage ?? 70 }}% Revenue Share on Check-ups</div>
        </div>

        <div class="date-filter">
            <label style="color: rgba(255, 255, 255, 0.8); font-weight: 500;">Filter by Date:</label>
            <input type="date" id="dateFilter" class="date-input" value="{{ request('date', date('Y-m-d')) }}">
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="card stat-card">
                <div class="stat-icon today">💵</div>
                <div class="stat-value">${{ number_format($todayEarnings, 2) }}</div>
                <div class="stat-label">Today's Earnings</div>
                <div class="stat-detail">From {{ $todayConsultations }} consultations</div>
            </div>

            <div class="card stat-card">
                <div class="stat-icon week">📊</div>
                <div class="stat-value">${{ number_format($weeklyEarnings, 2) }}</div>
                <div class="stat-label">This Week</div>
                <div class="stat-detail">{{ $weeklyConsultations }} total consultations</div>
            </div>

            <div class="card stat-card">
                <div class="stat-icon month">📈</div>
                <div class="stat-value">${{ number_format($monthlyEarnings, 2) }}</div>
                <div class="stat-label">This Month</div>
                <div class="stat-detail">{{ $monthlyConsultations }} consultations</div>
            </div>

            <div class="card stat-card">
                <div class="stat-icon patients">👥</div>
                <div class="stat-value">{{ $todayPatients }}</div>
                <div class="stat-label">Patients Today</div>
                <div class="stat-detail">Check-up consultations only</div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Earnings Chart -->
            <div class="card">
                <h2>📊 Earnings Trend (Last 7 Days)</h2>
                <div class="chart-container">
                    <canvas id="earningsChart"></canvas>
                </div>
            </div>

            <!-- Performance Metrics -->
            <div class="card">
                <h3>🎯 Performance Metrics</h3>
                <div class="performance-grid">
                    <div class="metric-item primary">
                        <div class="metric-header">
                            <span class="metric-label">Average per Patient</span>
                            <span class="metric-value">${{ number_format($avgPerPatient, 2) }}</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill primary" data-width="{{ $avgPerPatientPercent }}"></div>
                        </div>
                    </div>

                    <div class="metric-item success">
                        <div class="metric-header">
                            <span class="metric-label">Daily Target</span>
                            <span class="metric-value">${{ number_format($todayEarnings, 0) }}/400</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill success" data-width="{{ $dailyTargetPercent }}"></div>
                        </div>
                    </div>

                    <div class="metric-item warning">
                        <div class="metric-header">
                            <span class="metric-label">Patient Satisfaction</span>
                            <span class="metric-value">{{ $patientSatisfaction }}%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill warning" data-width="{{ $satisfactionPercent }}"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card">
            <h2>🕒 Recent Check-up Consultations</h2>
            @if(count($recentConsultations) > 0)
                <div class="activity-list">
                    @foreach($recentConsultations as $consultation)
                    <div class="activity-item">
                        <div class="activity-info">
                            <h4>{{ $consultation['patient_name'] }}</h4>
                            <p>{{ $consultation['service'] }} • {{ $consultation['time'] }}</p>
                        </div>
                        <div class="activity-amount">
                            <span class="amount">${{ number_format($consultation['doctor_fee'], 2) }}</span>
                            <span class="time">{{ $consultation['duration'] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="icon">🩺</div>
                    <h3>No Consultations Today</h3>
                    <p>Your consultation earnings will appear here once you start seeing patients.</p>
                </div>
            @endif
        </div>

        <!-- Summary Stats -->
        <div class="card">
            <h2>📋 Summary Statistics</h2>
            <div class="stats-grid">
                <div class="metric-item info">
                    <div class="metric-header">
                        <span class="metric-label">Total Revenue Generated</span>
                        <span class="metric-value">${{ number_format($totalRevenue, 2) }}</span>
                    </div>
                    <p style="color: rgba(255, 255, 255, 0.7); margin-top: 0.5rem; font-size: 0.9rem;">
                        Your share: ${{ number_format($todayEarnings, 2) }} ({{ $revenuePercentage ?? 70 }}%)
                    </p>
                </div>
                
                <div class="metric-item success">
                    <div class="metric-header">
                        <span class="metric-label">Consultation Efficiency</span>
                        <span class="metric-value">{{ $efficiency }}%</span>
                    </div>
                    <p style="color: rgba(255, 255, 255, 0.7); margin-top: 0.5rem; font-size: 0.9rem;">
                        Based on time per patient ratio
                    </p>
                </div>
            </div>
        </div>
    </main>

    <!-- Chart Data -->
    <script type="application/json" id="chartData">
        @json($chartData)
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Set progress bar widths using data attributes
        document.addEventListener('DOMContentLoaded', function() {
            const progressBars = document.querySelectorAll('.progress-fill[data-width]');
            progressBars.forEach(bar => {
                const width = bar.getAttribute('data-width');
                bar.style.width = width + '%';
            });
        });

        // Date filter functionality
        document.getElementById('dateFilter').addEventListener('change', function() {
            const url = new URL(window.location);
            url.searchParams.set('date', this.value);
            window.location.href = url.toString();
        });

        // Earnings Chart
        const ctx = document.getElementById('earningsChart').getContext('2d');
        const earningsData = JSON.parse(document.getElementById('chartData').textContent);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: earningsData.labels,
                datasets: [{
                    label: 'Daily Earnings ($)',
                    data: earningsData.values,
                    borderColor: '#4ade80',
                    backgroundColor: 'rgba(74, 222, 128, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#4ade80',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#ffffff',
                            font: {
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#4ade80',
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
                            color: '#ffffff',
                            callback: function(value) {
                                return '$' + value.toFixed(0);
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        },
                        ticks: {
                            color: '#ffffff'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
