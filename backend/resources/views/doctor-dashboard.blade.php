<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Doctor Dashboard - Healthcare AI Platform</title>
    
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
        }
        
        .header {
            background: rgba(255, 255, 255, 0.10);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.18);
            padding: 1.5rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(102,126,234,0.08);
        }
        
        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .logo::before {
            content: "🏥";
            margin-right: 0.5rem;
        }
        
        .nav {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
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
        }
        
        .logout-btn {
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
        
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -0.5rem;
        }
        
        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
            padding: 0 0.5rem;
        }
        
        .col-md-12 {
            flex: 0 0 100%;
            max-width: 100%;
            padding: 0 0.5rem;
        }
        
        .mb-4 {
            margin-bottom: 1.5rem;
        }
        
        .mt-4 {
            margin-top: 1.5rem;
        }
        
        .mt-5 {
            margin-top: 3rem;
        }
        
        .g-4 > * {
            margin-bottom: 1.5rem;
        }
        
        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .card-header {
            padding: 1rem 1.5rem;
            background: rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px 15px 0 0;
        }
        
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0;
            color: white;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            backdrop-filter: blur(5px);
            background: rgba(23, 162, 184, 0.2);
            color: #87ceeb;
            border: 1px solid rgba(23, 162, 184, 0.3);
        }
        
        /* Doctor Dashboard Specific Styles */
        .doctor-greeting {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 2rem;
        }
        
        .doctor-greeting h2 {
            color: white;
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }
        
        .doctor-greeting p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            margin-bottom: 0;
        }
        
        .stats-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            background: rgba(255, 255, 255, 0.15);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            font-weight: 500;
        }
        
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .action-card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            text-decoration: none;
            color: white;
            transition: all 0.3s ease;
            display: block;
        }
        
        .action-card:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-5px);
            color: white;
            text-decoration: none;
        }
        
        .action-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: block;
        }
        
        .action-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .action-desc {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }
        
        .activity-feed {
            max-height: 300px;
            overflow-y: auto;
        }
        
        .activity-item {
            padding: 1rem;
            margin-bottom: 0.5rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .activity-icon {
            font-size: 1.5rem;
            width: 40px;
            text-align: center;
        }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-title {
            font-weight: 600;
            color: white;
            margin-bottom: 0.25rem;
        }
        
        .activity-time {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .schedule-item {
            padding: 1rem;
            margin-bottom: 0.5rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .schedule-time {
            font-weight: 600;
            color: white;
            min-width: 80px;
        }
        
        .schedule-patient {
            color: rgba(255, 255, 255, 0.9);
        }
        
        .schedule-type {
            background: rgba(255, 255, 255, 0.1);
            padding: 0.25rem 0.5rem;
            border-radius: 15px;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.8);
        }
        
        @media (max-width: 768px) {
            .col-md-6, .col-md-12 {
                flex: 0 0 100%;
                max-width: 100%;
            }
            
            .header-container {
                flex-direction: column;
                gap: 1rem;
            }
            
            .nav {
                flex-wrap: wrap;
            }
            
            .quick-actions {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
            
            .stats-overview {
                grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <a href="/dashboard" class="logo">Healthcare AI Platform</a>
            
            <nav class="nav">
                <a href="/dashboard" class="active">Dashboard</a>
                <a href="/patients">Patients</a>
                <a href="/medgemma">AI Analysis</a>
                <a href="/financial/doctor-dashboard">💰 Financial</a>
                <a href="/dicom-upload">DICOM Upload</a>
                <a href="/reports">Reports</a>
            </nav>
            
            <div class="user-info">
                <span>👨‍⚕️ Dr. {{ Auth::user()->name }}</span>
                <form method="POST" action="/logout" style="display: inline;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="logout-btn">🚪 Sign Out</button>
                </form>
            </div>
        </div>
    </header>
    
    <main class="container">
        <!-- Doctor Greeting -->
        <div class="doctor-greeting">
            <h2>👨‍⚕️ Welcome back, Dr. {{ Auth::user()->name }}</h2>
            <p>Here's your practice overview for {{ date('l, F j, Y') }}</p>
        </div>

        <!-- Quick Statistics Overview -->
        <div class="stats-overview">
            <div class="stat-card">
                <div class="stat-number" id="totalPatients">--</div>
                <div class="stat-label">Total Patients</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="todayAppointments">--</div>
                <div class="stat-label">Today's Appointments</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="pendingTests">--</div>
                <div class="stat-label">Pending Lab Results</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="prescriptionsWeek">--</div>
                <div class="stat-label">Prescriptions This Week</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="imagingStudies">--</div>
                <div class="stat-label">Imaging Studies</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="/patients" class="action-card">
                <span class="action-icon">👥</span>
                <div class="action-title">Manage Patients</div>
                <div class="action-desc">View and manage patient records</div>
            </a>
            <a href="/medgemma" class="action-card">
                <span class="action-icon">🧠</span>
                <div class="action-title">AI Analysis</div>
                <div class="action-desc">Run MedGemma AI diagnostics</div>
            </a>
            <a href="/dicom-upload" class="action-card">
                <span class="action-icon">📸</span>
                <div class="action-title">DICOM Upload</div>
                <div class="action-desc">Upload medical imaging studies</div>
            </a>
            <a href="/reports" class="action-card">
                <span class="action-icon">📊</span>
                <div class="action-title">View Reports</div>
                <div class="action-desc">Generate and view clinical reports</div>
            </a>
        </div>

        <!-- Main Content Grid -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">🕒 Recent Patient Activity</h5>
                    </div>
                    <div class="card-body">
                        <div id="recentActivity" class="activity-feed">
                            <div class="activity-item">
                                <div class="activity-icon">⏳</div>
                                <div class="activity-content">
                                    <div class="activity-title">Loading recent activity...</div>
                                    <div class="activity-time">Please wait</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">📅 Today's Schedule</h5>
                    </div>
                    <div class="card-body">
                        <div id="todaySchedule">
                            <div class="schedule-item">
                                <div class="schedule-time">⏳</div>
                                <div class="schedule-patient">Loading schedule...</div>
                                <div class="schedule-type">Please wait</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts and Notifications -->
        <div class="row g-4 mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">🔔 Alerts & Notifications</h5>
                    </div>
                    <div class="card-body">
                        <div id="alertsContainer">
                            <div style="background: rgba(255, 193, 7, 0.1); border: 1px solid rgba(255, 193, 7, 0.3); color: #ffc107; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                                <strong>⚠️ Lab Results Ready:</strong> 3 patients have new lab results waiting for review.
                            </div>
                            <div style="background: rgba(40, 167, 69, 0.1); border: 1px solid rgba(40, 167, 69, 0.3); color: #28a745; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                                <strong>✅ System Status:</strong> All systems operational. Last backup completed successfully.
                            </div>
                            <div style="background: rgba(23, 162, 184, 0.1); border: 1px solid rgba(23, 162, 184, 0.3); color: #17a2b8; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                                <strong>ℹ️ MedGemma AI:</strong> AI analysis server is running optimally. Ready for new diagnostic requests.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert mt-5" role="alert">
            <strong>💡 Tip:</strong> Use the quick actions above to access frequently used features. For comprehensive reporting, visit the Reports section.
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardStats();
            loadRecentActivity();
            loadTodaySchedule();
        });

        function loadDashboardStats() {
            // Simulate loading dashboard statistics with realistic medical data
            setTimeout(() => {
                document.getElementById('totalPatients').textContent = '247';
                document.getElementById('todayAppointments').textContent = '12';
                document.getElementById('pendingTests').textContent = '8';
                document.getElementById('prescriptionsWeek').textContent = '34';
                document.getElementById('imagingStudies').textContent = '15';
            }, 500);
        }

        function loadRecentActivity() {
            // Simulate loading recent patient activity
            setTimeout(() => {
                const activities = [
                    { icon: '🧪', title: 'Lab results received for John Doe', time: '10 minutes ago' },
                    { icon: '✅', title: 'Appointment completed with Jane Smith', time: '1 hour ago' },
                    { icon: '💊', title: 'Prescription filled - Michael Johnson', time: '2 hours ago' },
                    { icon: '📸', title: 'DICOM imaging uploaded - Sarah Williams', time: '3 hours ago' },
                    { icon: '🧠', title: 'AI analysis completed - Robert Miller', time: '4 hours ago' },
                    { icon: '📋', title: 'Medical report generated - Emily Davis', time: '5 hours ago' }
                ];

                const container = document.getElementById('recentActivity');
                container.innerHTML = activities.map(activity => `
                    <div class="activity-item">
                        <div class="activity-icon">${activity.icon}</div>
                        <div class="activity-content">
                            <div class="activity-title">${activity.title}</div>
                            <div class="activity-time">${activity.time}</div>
                        </div>
                    </div>
                `).join('');
            }, 750);
        }

        function loadTodaySchedule() {
            // Simulate loading today's schedule
            setTimeout(() => {
                const schedule = [
                    { time: '09:00 AM', patient: 'Robert Miller', type: 'Consultation' },
                    { time: '10:30 AM', patient: 'Emily Davis', type: 'Follow-up' },
                    { time: '11:45 AM', patient: 'David Brown', type: 'Check-up' },
                    { time: '02:00 PM', patient: 'Jessica Wilson', type: 'Consultation' },
                    { time: '03:30 PM', patient: 'Mark Thompson', type: 'Lab Review' },
                    { time: '04:45 PM', patient: 'Lisa Anderson', type: 'Imaging Review' }
                ];

                const container = document.getElementById('todaySchedule');
                container.innerHTML = schedule.map(appointment => `
                    <div class="schedule-item">
                        <div class="schedule-time">${appointment.time}</div>
                        <div class="schedule-patient">${appointment.patient}</div>
                        <div class="schedule-type">${appointment.type}</div>
                    </div>
                `).join('');
            }, 1000);
        }
    </script>
</body>
</html>
        
        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .logo::before {
            content: "🏥";
            margin-right: 0.5rem;
        }
        
        .nav {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
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
        }
        
        .logout-btn {
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
        
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -0.5rem;
        }
        
        .col-md-8 {
            flex: 0 0 66.666667%;
            max-width: 66.666667%;
            padding: 0 0.5rem;
        }
        
        .col-md-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
            padding: 0 0.5rem;
        }
        
        .col-md-3 {
            flex: 0 0 25%;
            max-width: 25%;
            padding: 0 0.5rem;
        }
        
        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
            padding: 0 0.5rem;
        }
        
        .col-md-12 {
            flex: 0 0 100%;
            max-width: 100%;
            padding: 0 0.5rem;
        }
        
        .text-end {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .mb-4 {
            margin-bottom: 1.5rem;
        }
        
        .mb-3 {
            margin-bottom: 1rem;
        }
        
        .mt-5 {
            margin-top: 3rem;
        }
        
        .g-4 > * {
            margin-bottom: 1.5rem;
        }
        
        .display-5 {
            font-size: 3rem;
            font-weight: 300;
            line-height: 1.2;
            margin-bottom: 1rem;
        }
        
        .display-6 {
            font-size: 2.5rem;
            font-weight: 600;
            color: white;
            margin-bottom: 1rem;
        }
        
        .lead {
            font-size: 1.25rem;
            font-weight: 300;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            font-size: 0.95rem;
            margin: 0.25rem;
        }
        
        .btn-primary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(5px);
        }
        
        .btn-primary:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .btn-outline-primary {
            background: transparent;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        
        .btn-outline-primary:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
        
        .btn-lg {
            padding: 1rem 2rem;
            font-size: 1.1rem;
        }
        
        .btn-link {
            background: transparent;
            color: rgba(255, 255, 255, 0.8);
            border: none;
            text-decoration: underline;
            padding: 0.5rem 1rem;
        }
        
        .btn-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            text-decoration: none;
        }
        
        .ms-2 {
            margin-left: 0.5rem;
        }
        
        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .card-header {
            padding: 1rem 1.5rem;
            background: rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px 15px 0 0;
        }
        
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: white;
        }
        
        .fw-bold {
            font-weight: bold;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            backdrop-filter: blur(5px);
            background: rgba(23, 162, 184, 0.2);
            color: #87ceeb;
            border: 1px solid rgba(23, 162, 184, 0.3);
        }
        
        /* Doctor Dashboard Specific Styles */
        .doctor-greeting {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 2rem;
        }
        
        .doctor-greeting h2 {
            color: white;
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }
        
        .doctor-greeting p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            margin-bottom: 0;
        }
        
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .action-card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            text-decoration: none;
            color: white;
            transition: all 0.3s ease;
            display: block;
        }
        
        .action-card:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-5px);
            color: white;
            text-decoration: none;
        }
        
        .action-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: block;
        }
        
        .action-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .action-desc {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }
        
        .stats-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            background: rgba(255, 255, 255, 0.15);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            font-weight: 500;
        }
        
        .activity-feed {
            max-height: 300px;
            overflow-y: auto;
        }
        
        .activity-item {
            padding: 1rem;
            margin-bottom: 0.5rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .activity-icon {
            font-size: 1.5rem;
            width: 40px;
            text-align: center;
        }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-title {
            font-weight: 600;
            color: white;
            margin-bottom: 0.25rem;
        }
        
        .activity-time {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .schedule-item {
            padding: 1rem;
            margin-bottom: 0.5rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .schedule-time {
            font-weight: 600;
            color: white;
            min-width: 80px;
        }
        
        .schedule-patient {
            color: rgba(255, 255, 255, 0.9);
        }
        
        .schedule-type {
            background: rgba(255, 255, 255, 0.1);
            padding: 0.25rem 0.5rem;
            border-radius: 15px;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.8);
        }
        
        @media (max-width: 768px) {
            .col-md-8, .col-md-4, .col-md-3, .col-md-6, .col-md-12 {
                flex: 0 0 100%;
                max-width: 100%;
            }
            
            .header-container {
                flex-direction: column;
                gap: 1rem;
            }
            
            .nav {
                flex-wrap: wrap;
            }
            
            .display-5 {
                font-size: 2rem;
            }
            
            .text-end {
                text-align: center;
            }
            
            .quick-actions {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
            
            .stats-overview {
                grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <a href="/dashboard" class="logo">Healthcare AI Platform</a>
            
            <nav class="nav">
                <a href="/dashboard" class="active">Dashboard</a>
                <a href="/patients">Patients</a>
                <a href="/medgemma">AI Analysis</a>
                <a href="/financial/doctor-dashboard">💰 Financial</a>
                <a href="/dicom-upload">DICOM Upload</a>
                <a href="/reports">Reports</a>
            </nav>
            
            <div class="user-info">
                <span>👨‍⚕️ Dr. {{ Auth::user()->name }}</span>
                <form method="POST" action="/logout" style="display: inline;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="logout-btn">🚪 Sign Out</button>
                </form>
            </div>
        </div>
    </header>
    
    <main class="container">
        <!-- Doctor Greeting -->
        <div class="doctor-greeting">
            <h2>👨‍⚕️ Welcome back, Dr. {{ Auth::user()->name }}</h2>
            <p>Here's your practice overview for {{ date('l, F j, Y') }}</p>
        </div>

        <!-- Quick Statistics Overview -->
        <div class="stats-overview">
            <div class="stat-card">
                <div class="stat-number" id="totalPatients">--</div>
                <div class="stat-label">Total Patients</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="todayAppointments">--</div>
                <div class="stat-label">Today's Appointments</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="pendingTests">--</div>
                <div class="stat-label">Pending Lab Results</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="prescriptionsWeek">--</div>
                <div class="stat-label">Prescriptions This Week</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="imagingStudies">--</div>
                <div class="stat-label">Imaging Studies</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="/patients" class="action-card">
                <span class="action-icon">👥</span>
                <div class="action-title">Manage Patients</div>
                <div class="action-desc">View and manage patient records</div>
            </a>
            <a href="/medgemma" class="action-card">
                <span class="action-icon">🧠</span>
                <div class="action-title">AI Analysis</div>
                <div class="action-desc">Run MedGemma AI diagnostics</div>
            </a>
            <a href="/dicom-upload" class="action-card">
                <span class="action-icon">📸</span>
                <div class="action-title">DICOM Upload</div>
                <div class="action-desc">Upload medical imaging studies</div>
            </a>
            <a href="/reports" class="action-card">
                <span class="action-icon">📊</span>
                <div class="action-title">View Reports</div>
                <div class="action-desc">Generate and view clinical reports</div>
            </a>
        </div>

        <!-- Main Content Grid -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">🕒 Recent Patient Activity</h5>
                    </div>
                    <div class="card-body">
                        <div id="recentActivity" class="activity-feed">
                            <div class="activity-item">
                                <div class="activity-icon">⏳</div>
                                <div class="activity-content">
                                    <div class="activity-title">Loading recent activity...</div>
                                    <div class="activity-time">Please wait</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">📅 Today's Schedule</h5>
                    </div>
                    <div class="card-body">
                        <div id="todaySchedule">
                            <div class="schedule-item">
                                <div class="schedule-time">⏳</div>
                                <div class="schedule-patient">Loading schedule...</div>
                                <div class="schedule-type">Please wait</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts and Notifications -->
        <div class="row g-4 mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">🔔 Alerts & Notifications</h5>
                    </div>
                    <div class="card-body">
                        <div id="alertsContainer">
                            <div style="background: rgba(255, 193, 7, 0.1); border: 1px solid rgba(255, 193, 7, 0.3); color: #ffc107; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                                <strong>⚠️ Lab Results Ready:</strong> 3 patients have new lab results waiting for review.
                            </div>
                            <div style="background: rgba(40, 167, 69, 0.1); border: 1px solid rgba(40, 167, 69, 0.3); color: #28a745; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                                <strong>✅ System Status:</strong> All systems operational. Last backup completed successfully.
                            </div>
                            <div style="background: rgba(23, 162, 184, 0.1); border: 1px solid rgba(23, 162, 184, 0.3); color: #17a2b8; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                                <strong>ℹ️ MedGemma AI:</strong> AI analysis server is running optimally. Ready for new diagnostic requests.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert mt-5" role="alert">
            <strong>💡 Tip:</strong> Use the quick actions above to access frequently used features. For comprehensive reporting, visit the Reports section.
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardStats();
            loadRecentActivity();
            loadTodaySchedule();
        });

        function loadDashboardStats() {
            // Simulate loading dashboard statistics with realistic medical data
            setTimeout(() => {
                document.getElementById('totalPatients').textContent = '247';
                document.getElementById('todayAppointments').textContent = '12';
                document.getElementById('pendingTests').textContent = '8';
                document.getElementById('prescriptionsWeek').textContent = '34';
                document.getElementById('imagingStudies').textContent = '15';
            }, 500);
        }

        function loadRecentActivity() {
            // Simulate loading recent patient activity
            setTimeout(() => {
                const activities = [
                    { icon: '🧪', title: 'Lab results received for John Doe', time: '10 minutes ago' },
                    { icon: '✅', title: 'Appointment completed with Jane Smith', time: '1 hour ago' },
                    { icon: '💊', title: 'Prescription filled - Michael Johnson', time: '2 hours ago' },
                    { icon: '📸', title: 'DICOM imaging uploaded - Sarah Williams', time: '3 hours ago' },
                    { icon: '🧠', title: 'AI analysis completed - Robert Miller', time: '4 hours ago' },
                    { icon: '📋', title: 'Medical report generated - Emily Davis', time: '5 hours ago' }
                ];

                const container = document.getElementById('recentActivity');
                container.innerHTML = activities.map(activity => `
                    <div class="activity-item">
                        <div class="activity-icon">${activity.icon}</div>
                        <div class="activity-content">
                            <div class="activity-title">${activity.title}</div>
                            <div class="activity-time">${activity.time}</div>
                        </div>
                    </div>
                `).join('');
            }, 750);
        }

        function loadTodaySchedule() {
            // Simulate loading today's schedule
            setTimeout(() => {
                const schedule = [
                    { time: '09:00 AM', patient: 'Robert Miller', type: 'Consultation' },
                    { time: '10:30 AM', patient: 'Emily Davis', type: 'Follow-up' },
                    { time: '11:45 AM', patient: 'David Brown', type: 'Check-up' },
                    { time: '02:00 PM', patient: 'Jessica Wilson', type: 'Consultation' },
                    { time: '03:30 PM', patient: 'Mark Thompson', type: 'Lab Review' },
                    { time: '04:45 PM', patient: 'Lisa Anderson', type: 'Imaging Review' }
                ];

                const container = document.getElementById('todaySchedule');
                container.innerHTML = schedule.map(appointment => `
                    <div class="schedule-item">
                        <div class="schedule-time">${appointment.time}</div>
                        <div class="schedule-patient">${appointment.patient}</div>
                        <div class="schedule-type">${appointment.type}</div>
                    </div>
                `).join('');
            }, 1000);
        }
    </script>
</body>
</html>

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        color: white;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        border-color: rgba(255, 255, 255, 0.3);
    }

    .stat-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1rem;
        font-weight: 500;
    }

    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .content-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        padding: 1.5rem;
        color: white;
    }

    .content-card-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .action-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: center;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        color: white;
    }

    .recent-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .recent-item {
        padding: 0.75rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        justify-content: between;
        align-items: center;
    }

    .recent-item:last-child {
        border-bottom: none;
    }

    .patient-name {
        font-weight: 600;
        color: white;
    }

    .visit-time {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
        
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
        
        .dashboard-title {
            font-size: 2rem;
        }
    }
</style>

<div class="dashboard-container">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h1 class="dashboard-title">👨‍⚕️ Doctor Dashboard</h1>
        <p class="dashboard-subtitle">Welcome back, Dr. {{ Auth::user()->name }}. Here's your practice overview for today.</p>
    </div>

    <!-- Statistics Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-number" id="totalPatients">--</div>
            <div class="stat-label">Total Patients</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-number" id="todayAppointments">--</div>
            <div class="stat-label">Today's Appointments</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🔬</div>
            <div class="stat-number" id="pendingTests">--</div>
            <div class="stat-label">Pending Lab Results</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💊</div>
            <div class="stat-number" id="prescriptionsIssued">--</div>
            <div class="stat-label">Prescriptions This Week</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="content-card">
        <h3 class="content-card-title">
            <i class="fas fa-bolt"></i>Quick Actions
        </h3>
        <div class="quick-actions">
            <a href="/patients" class="action-btn">
                <i class="fas fa-users"></i>Manage Patients
            </a>
            <a href="/medgemma" class="action-btn">
                <i class="fas fa-brain"></i>AI Analysis
            </a>
            <a href="/reports" class="action-btn">
                <i class="fas fa-chart-bar"></i>View Reports
            </a>
            <a href="/financial/doctor-dashboard" class="action-btn">
                <i class="fas fa-dollar-sign"></i>Financial Dashboard
            </a>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="content-grid">
        <!-- Recent Patient Activity -->
        <div class="content-card">
            <h3 class="content-card-title">
                <i class="fas fa-clock"></i>Recent Patient Activity
            </h3>
            <ul class="recent-list" id="recentActivity">
                <li class="recent-item">
                    <div>
                        <div class="patient-name">Loading recent activity...</div>
                        <div class="visit-time">Please wait</div>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Today's Schedule -->
        <div class="content-card">
            <h3 class="content-card-title">
                <i class="fas fa-calendar"></i>Today's Schedule
            </h3>
            <ul class="recent-list" id="todaySchedule">
                <li class="recent-item">
                    <div>
                        <div class="patient-name">Loading schedule...</div>
                        <div class="visit-time">Please wait</div>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <!-- Alerts & Notifications -->
    <div class="content-card">
        <h3 class="content-card-title">
            <i class="fas fa-bell"></i>Alerts & Notifications
        </h3>
        <div id="alertsContainer">
            <div class="alert" style="background: rgba(255, 193, 7, 0.1); border: 1px solid rgba(255, 193, 7, 0.3); color: #ffc107; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                <strong>Lab Results Ready:</strong> 3 patients have new lab results waiting for review.
            </div>
            <div class="alert" style="background: rgba(40, 167, 69, 0.1); border: 1px solid rgba(40, 167, 69, 0.3); color: #28a745; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                <strong>System Status:</strong> All systems operational. Last backup completed successfully.
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardStats();
    loadRecentActivity();
    loadTodaySchedule();
});

function loadDashboardStats() {
    // Simulate loading dashboard statistics
    setTimeout(() => {
        document.getElementById('totalPatients').textContent = '247';
        document.getElementById('todayAppointments').textContent = '12';
        document.getElementById('pendingTests').textContent = '8';
        document.getElementById('prescriptionsIssued').textContent = '34';
    }, 500);
}

function loadRecentActivity() {
    // Simulate loading recent patient activity
    setTimeout(() => {
        const recentActivity = [
            { name: 'John Doe', action: 'Lab results received', time: '10 minutes ago' },
            { name: 'Jane Smith', action: 'Appointment completed', time: '1 hour ago' },
            { name: 'Michael Johnson', action: 'Prescription filled', time: '2 hours ago' },
            { name: 'Sarah Williams', action: 'Test ordered', time: '3 hours ago' }
        ];

        const container = document.getElementById('recentActivity');
        container.innerHTML = recentActivity.map(activity => `
            <li class="recent-item">
                <div>
                    <div class="patient-name">${activity.name}</div>
                    <div class="visit-time">${activity.action} - ${activity.time}</div>
                </div>
            </li>
        `).join('');
    }, 750);
}

function loadTodaySchedule() {
    // Simulate loading today's schedule
    setTimeout(() => {
        const schedule = [
            { time: '09:00 AM', patient: 'Robert Miller', type: 'Consultation' },
            { time: '10:30 AM', patient: 'Emily Davis', type: 'Follow-up' },
            { time: '02:00 PM', patient: 'David Brown', type: 'Check-up' },
            { time: '03:30 PM', patient: 'Jessica Wilson', type: 'Consultation' }
        ];

        const container = document.getElementById('todaySchedule');
        container.innerHTML = schedule.map(appointment => `
            <li class="recent-item">
                <div>
                    <div class="patient-name">${appointment.time} - ${appointment.patient}</div>
                    <div class="visit-time">${appointment.type}</div>
                </div>
            </li>
        `).join('');
    }, 1000);
}
</script>
@endsection
