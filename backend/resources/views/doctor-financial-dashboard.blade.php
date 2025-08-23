<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Dashboard - Doctor Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #2E8B57 0%, #3CB371 100%);
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
            background: linear-gradient(45deg, #2E8B57, #3CB371);
            border: none;
        }
        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        .financial-highlight {
            background: linear-gradient(45deg, #90EE90, #228B22);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
        }
        .navbar {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        .earning-card {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .earning-badge {
            background: rgba(50, 205, 50, 0.8);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
        }
        .metric-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            height: 100%;
        }
        .metric-value {
            font-size: 2rem;
            font-weight: bold;
            color: #90EE90;
        }
        .metric-label {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        .consultation-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid #2E8B57;
        }
        .chart-container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="/dashboard">
                <i class="fas fa-chart-line me-2"></i>
                <span class="financial-highlight">Doctor Financial Dashboard</span>
            </a>
            <div class="ms-auto">
                <a href="/dashboard" class="btn btn-outline-light btn-sm me-2">
                    <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                </a>
                <a href="/patients" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-users me-1"></i>Patients
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <!-- Financial Overview Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="metric-card">
                    <div class="metric-value" id="todayEarnings">$0</div>
                    <div class="metric-label">Today's Earnings</div>
                    <small class="text-info"><span id="todayConsultations">0</span> consultations</small>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="metric-card">
                    <div class="metric-value" id="weeklyEarnings">$0</div>
                    <div class="metric-label">This Week</div>
                    <small class="text-info"><span id="weeklyConsultations">0</span> consultations</small>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="metric-card">
                    <div class="metric-value" id="monthlyEarnings">$0</div>
                    <div class="metric-label">This Month</div>
                    <small class="text-info"><span id="monthlyConsultations">0</span> consultations</small>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="metric-card">
                    <div class="metric-value" id="avgPerPatient">$0</div>
                    <div class="metric-label">Avg per Patient</div>
                    <small class="text-info"><span id="revenuePercentage">70</span>% share</small>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Earnings Chart -->
            <div class="col-md-8 mb-4">
                <div class="chart-container">
                    <h5><i class="fas fa-chart-line me-2"></i>Weekly Earnings Trend</h5>
                    <canvas id="earningsChart" height="300"></canvas>
                </div>
            </div>

            <!-- Performance Metrics -->
            <div class="col-md-4 mb-4">
                <div class="glass-card p-4">
                    <h5><i class="fas fa-trophy me-2"></i>Performance Metrics</h5>
                    <div class="metric-card mb-3">
                        <div class="metric-value" id="patientSatisfaction">94%</div>
                        <div class="metric-label">Patient Satisfaction</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-value" id="efficiency">88%</div>
                        <div class="metric-label">Efficiency Score</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Consultations -->
        <div class="glass-card p-4">
            <h5><i class="fas fa-history me-2"></i>Today's Consultations</h5>
            <div id="recentConsultations">
                <p class="text-center opacity-75">Loading consultation data...</p>
            </div>
        </div>
    </div>

    <script>
        // Load financial data
        document.addEventListener('DOMContentLoaded', async function() {
            try {
                const response = await fetch('/api/dashboard/doctor');
                const data = await response.json();
                
                if (data.success && data.financial_data) {
                    const financial = data.financial_data;
                    
                    // Update earnings display
                    document.getElementById('todayEarnings').textContent = `$${financial.todayEarnings || 0}`;
                    document.getElementById('todayConsultations').textContent = financial.todayConsultations || 0;
                    document.getElementById('weeklyEarnings').textContent = `$${financial.weeklyEarnings || 0}`;
                    document.getElementById('weeklyConsultations').textContent = financial.weeklyConsultations || 0;
                    document.getElementById('monthlyEarnings').textContent = `$${financial.monthlyEarnings || 0}`;
                    document.getElementById('monthlyConsultations').textContent = financial.monthlyConsultations || 0;
                    document.getElementById('avgPerPatient').textContent = `$${Math.round(financial.avgPerPatient || 0)}`;
                    document.getElementById('revenuePercentage').textContent = financial.revenuePercentage || 70;
                    document.getElementById('patientSatisfaction').textContent = `${financial.patientSatisfaction || 94}%`;
                    document.getElementById('efficiency').textContent = `${financial.efficiency || 88}%`;
                    
                    // Create earnings chart
                    if (financial.chartData) {
                        createEarningsChart(financial.chartData);
                    }
                    
                    // Display recent consultations
                    if (financial.recentConsultations) {
                        displayRecentConsultations(financial.recentConsultations);
                    }
                } else {
                    // Use demo data if API fails
                    loadDemoData();
                }
            } catch (error) {
                console.error('Error loading financial data:', error);
                loadDemoData();
            }
        });
        
        function loadDemoData() {
            // Demo financial data
            document.getElementById('todayEarnings').textContent = '$840';
            document.getElementById('todayConsultations').textContent = '12';
            document.getElementById('weeklyEarnings').textContent = '$4,200';
            document.getElementById('weeklyConsultations').textContent = '58';
            document.getElementById('monthlyEarnings').textContent = '$18,500';
            document.getElementById('monthlyConsultations').textContent = '247';
            document.getElementById('avgPerPatient').textContent = '$70';
            
            // Demo chart data
            const demoChartData = {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                values: [560, 672, 504, 840, 728, 420, 476]
            };
            createEarningsChart(demoChartData);
            
            // Demo consultations
            const demoConsultations = [
                { patient_name: 'Sarah Johnson', service: 'General Check-up', time: '9:30 AM', duration: '25 min', doctor_fee: 84, total_fee: 120 },
                { patient_name: 'Michael Chen', service: 'General Check-up', time: '10:15 AM', duration: '20 min', doctor_fee: 84, total_fee: 120 },
                { patient_name: 'Emily Davis', service: 'General Check-up', time: '11:00 AM', duration: '30 min', doctor_fee: 84, total_fee: 120 },
                { patient_name: 'James Wilson', service: 'General Check-up', time: '2:30 PM', duration: '22 min', doctor_fee: 84, total_fee: 120 }
            ];
            displayRecentConsultations(demoConsultations);
        }
        
        function createEarningsChart(chartData) {
            const ctx = document.getElementById('earningsChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Daily Earnings',
                        data: chartData.values,
                        borderColor: '#90EE90',
                        backgroundColor: 'rgba(144, 238, 144, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: { color: 'white' }
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
                                    return '$' + value;
                                }
                            },
                            grid: { color: 'rgba(255,255,255,0.1)' }
                        }
                    }
                }
            });
        }
        
        function displayRecentConsultations(consultations) {
            const container = document.getElementById('recentConsultations');
            
            if (!consultations || consultations.length === 0) {
                container.innerHTML = '<p class="text-center opacity-75">No consultations today.</p>';
                return;
            }
            
            container.innerHTML = consultations.map(consultation => `
                <div class="consultation-item">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <h6 class="mb-1">${consultation.patient_name}</h6>
                            <small class="text-info">${consultation.service}</small>
                        </div>
                        <div class="col-md-2 text-center">
                            <strong>${consultation.time}</strong><br>
                            <small>${consultation.duration}</small>
                        </div>
                        <div class="col-md-3 text-center">
                            <span class="earning-badge">Your Earnings: $${consultation.doctor_fee}</span>
                        </div>
                        <div class="col-md-3 text-end">
                            <strong>Total Fee: $${consultation.total_fee}</strong><br>
                            <small class="text-success">✓ Completed</small>
                        </div>
                    </div>
                </div>
            `).join('');
        }
    </script>
</body>
</html>
