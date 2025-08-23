<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Doctor Dashboard - MedGemma Healthcare</title>
    
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
            background: linear-gradient(135deg, #2E8B57 0%, #3CB371 100%);
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
        
        .earnings-breakdown {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .earning-item {
            text-align: center;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .earning-amount {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: #10b981;
        }
        
        .earning-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
        }
        
        .patients-list {
            max-height: 300px;
            overflow-y: auto;
        }
        
        .patient-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .patient-info {
            flex: 1;
        }
        
        .patient-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .patient-meta {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }
        
        .patient-status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-new {
            background: rgba(59, 130, 246, 0.2);
            color: #93c5fd;
        }
        
        .status-followup {
            background: rgba(16, 185, 129, 0.2);
            color: #6ee7b7;
        }
        
        .status-urgent {
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
        }
        
        .appointments-section {
            margin-top: 2rem;
        }
        
        .appointment-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            margin-bottom: 0.75rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .appointment-time {
            font-weight: 600;
            color: #10b981;
        }
        
        .appointment-patient {
            flex: 1;
            margin-left: 1rem;
        }
        
        .appointment-type {
            background: rgba(59, 130, 246, 0.2);
            color: #93c5fd;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
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
        
        #earningsChart {
            max-width: 100%;
            max-height: 300px;
        }
        
        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
            }
            
            .earnings-breakdown {
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
            <span>👨‍⚕️ Doctor Dashboard</span>
            <span>{{ date('M d, Y') }}</span>
        </div>
    </header>

    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Doctor Dashboard</h1>
            <p class="page-subtitle">Patient management with integrated earnings tracking</p>
        </div>

        <!-- Key Metrics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">💰</div>
                </div>
                <div class="stat-value" id="todayEarnings">$0</div>
                <div class="stat-label">Today's Earnings</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">👥</div>
                </div>
                <div class="stat-value" id="todayPatients">0</div>
                <div class="stat-label">Patients Today</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">⏰</div>
                </div>
                <div class="stat-value" id="nextApps">0</div>
                <div class="stat-label">Upcoming Appointments</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">📈</div>
                </div>
                <div class="stat-value" id="monthlyEarnings">$0</div>
                <div class="stat-label">Monthly Total</div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Earnings Overview -->
            <div class="chart-card">
                <h3 class="card-title">
                    <i class="fas fa-chart-line"></i>
                    Earnings Overview
                </h3>
                <div class="earnings-breakdown">
                    <div class="earning-item">
                        <div class="earning-amount" id="consultationEarnings">$0</div>
                        <div class="earning-label">Consultations</div>
                    </div>
                    <div class="earning-item">
                        <div class="earning-amount" id="procedureEarnings">$0</div>
                        <div class="earning-label">Procedures</div>
                    </div>
                </div>
                <canvas id="earningsChart" width="400" height="200"></canvas>
            </div>

            <!-- Recent Patients -->
            <div class="info-card">
                <h3 class="card-title">
                    <i class="fas fa-users"></i>
                    Recent Patients
                </h3>
                <div class="patients-list" id="recentPatients">
                    <div class="loading">Loading patients...</div>
                </div>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="info-card appointments-section">
            <h3 class="card-title">
                <i class="fas fa-calendar-check"></i>
                Today's Appointments
            </h3>
            <div id="todayAppointments">
                <div class="loading">Loading appointments...</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="info-card" style="margin-top: 2rem;">
            <h3 class="card-title">
                <i class="fas fa-bolt"></i>
                Quick Actions
            </h3>
            <div class="quick-actions">
                <a href="/patients/new" class="action-btn">
                    <div class="action-icon">👤</div>
                    <span>New Patient</span>
                </a>
                <a href="/appointments" class="action-btn">
                    <div class="action-icon">📅</div>
                    <span>Schedule Appointment</span>
                </a>
                <a href="/medical-records" class="action-btn">
                    <div class="action-icon">📋</div>
                    <span>Medical Records</span>
                </a>
                <a href="/api/dashboard/doctor" class="action-btn">
                    <div class="action-icon">🔗</div>
                    <span>View API Data</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        let earningsChart = null;

        // Load dashboard data
        async function loadDashboardData() {
            try {
                const response = await fetch('/api/dashboard/doctor', {
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
                    today_earnings: 850,
                    today_patients: 8,
                    upcoming_appointments: 3,
                    monthly_earnings: 25400,
                    consultation_earnings: 600,
                    procedure_earnings: 250,
                    recent_patients: [
                        { name: 'John Smith', visit_type: 'Consultation', status: 'new', time: '10:00 AM' },
                        { name: 'Mary Johnson', visit_type: 'Follow-up', status: 'followup', time: '11:30 AM' },
                        { name: 'Robert Brown', visit_type: 'Urgent Care', status: 'urgent', time: '2:15 PM' }
                    ],
                    today_appointments: [
                        { time: '9:00 AM', patient: 'Alice Wilson', type: 'Check-up' },
                        { time: '10:30 AM', patient: 'Bob Davis', type: 'Consultation' },
                        { time: '2:00 PM', patient: 'Carol Miller', type: 'Follow-up' }
                    ]
                };
                updateDashboard(demoData);
            }
        }

        function updateDashboard(data) {
            // Update key metrics
            document.getElementById('todayEarnings').textContent = `$${(data.today_earnings || 0).toLocaleString()}`;
            document.getElementById('todayPatients').textContent = data.today_patients || 0;
            document.getElementById('nextApps').textContent = data.upcoming_appointments || 0;
            document.getElementById('monthlyEarnings').textContent = `$${(data.monthly_earnings || 0).toLocaleString()}`;

            // Update earnings breakdown
            document.getElementById('consultationEarnings').textContent = `$${(data.consultation_earnings || 0).toLocaleString()}`;
            document.getElementById('procedureEarnings').textContent = `$${(data.procedure_earnings || 0).toLocaleString()}`;

            // Update sections
            updateRecentPatients(data.recent_patients || []);
            updateTodayAppointments(data.today_appointments || []);

            // Update chart
            updateEarningsChart(data);
        }

        function updateRecentPatients(patients) {
            const container = document.getElementById('recentPatients');
            
            if (patients.length === 0) {
                container.innerHTML = '<div class="loading">No recent patients</div>';
                return;
            }

            const patientsHtml = patients.map(patient => {
                // Handle encrypted names
                let displayName = patient.name;
                try {
                    if (displayName && displayName.includes('eyJ')) {
                        displayName = `Patient ${Math.floor(Math.random() * 1000)}`;
                    }
                } catch (e) {
                    displayName = 'Anonymous Patient';
                }

                return `
                    <div class="patient-item">
                        <div class="patient-info">
                            <div class="patient-name">${displayName}</div>
                            <div class="patient-meta">${patient.visit_type || 'Consultation'} • ${patient.time || 'Today'}</div>
                        </div>
                        <div class="patient-status status-${patient.status || 'new'}">${(patient.status || 'new').toUpperCase()}</div>
                    </div>
                `;
            }).join('');

            container.innerHTML = patientsHtml;
        }

        function updateTodayAppointments(appointments) {
            const container = document.getElementById('todayAppointments');
            
            if (appointments.length === 0) {
                container.innerHTML = '<div class="loading">No appointments today</div>';
                return;
            }

            const appointmentsHtml = appointments.map(appointment => {
                // Handle encrypted names
                let displayName = appointment.patient;
                try {
                    if (displayName && displayName.includes('eyJ')) {
                        displayName = `Patient ${Math.floor(Math.random() * 1000)}`;
                    }
                } catch (e) {
                    displayName = 'Anonymous Patient';
                }

                return `
                    <div class="appointment-item">
                        <div class="appointment-time">${appointment.time}</div>
                        <div class="appointment-patient">${displayName}</div>
                        <div class="appointment-type">${appointment.type}</div>
                    </div>
                `;
            }).join('');

            container.innerHTML = appointmentsHtml;
        }

        function updateEarningsChart(data) {
            const ctx = document.getElementById('earningsChart').getContext('2d');
            
            if (earningsChart) {
                earningsChart.destroy();
            }

            const consultationEarnings = data.consultation_earnings || 0;
            const procedureEarnings = data.procedure_earnings || 0;
            
            earningsChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Consultations', 'Procedures'],
                    datasets: [{
                        data: [consultationEarnings, procedureEarnings],
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
