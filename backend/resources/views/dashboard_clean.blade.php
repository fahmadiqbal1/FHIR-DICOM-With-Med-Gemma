<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Healthcare AI Platform</title>
    
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
        
        .g-2 > * {
            margin-bottom: 0.5rem;
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
        
        .btn-outline-danger {
            background: transparent;
            color: #ff6b6b;
            border: 1px solid #ff6b6b;
        }
        
        .btn-outline-danger:hover {
            background: rgba(255, 107, 107, 0.1);
        }
        
        .btn-lg {
            padding: 1rem 2rem;
            font-size: 1.1rem;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
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
        
        /* Analytics Section Styles */
        .analytics-section {
            margin-top: 3rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1rem;
        }
        
        .stat-item {
            text-align: center;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            font-weight: 500;
        }
        
        .filters-panel {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .form-label {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            display: block;
        }
        
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            color: white;
            padding: 0.5rem;
            font-size: 0.9rem;
            width: 100%;
        }
        
        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.1);
            color: white;
            outline: none;
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .chart-container {
            color: white;
        }
        
        .demographics-grid {
            color: white;
        }
        
        .demographics-section {
            margin-bottom: 2rem;
        }
        
        .demographics-section h4 {
            color: white;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }
        
        .demo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.5rem;
        }
        
        .demo-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 5px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .activity-panel {
            max-height: 300px;
            overflow-y: auto;
        }
        
        .activity-item {
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .activity-item:last-child {
            margin-bottom: 0;
        }
        
        .activity-title {
            font-weight: 600;
            color: white;
            margin-bottom: 0.25rem;
        }
        
        .activity-meta {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
        }
        
        .reports-panel {
            min-height: 200px;
        }
        
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .empty-state p {
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }
        
        .empty-state small {
            font-size: 0.8rem;
        }
        
        .report-item {
            padding: 1rem;
            margin-bottom: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .report-title {
            font-weight: 600;
            color: white;
        }
        
        .report-meta {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
        }
        
        .report-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .w-100 {
            width: 100%;
        }
        
        .d-flex {
            display: flex;
        }
        
        .justify-content-between {
            justify-content: space-between;
        }
        
        .align-items-center {
            align-items: center;
        }
        
        .mb-0 {
            margin-bottom: 0;
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
            </nav>
            
            <div class="user-info">
                <span>👤 Welcome</span>
                <form method="POST" action="/logout" style="display: inline;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="logout-btn">🚪 Sign Out</button>
                </form>
            </div>
        </div>
    </header>
    
    <main class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <h1 class="display-5">Welcome to MedGemma Healthcare</h1>
                <p class="lead">Your secure, AI-powered healthcare management platform.</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="/patients" class="btn btn-outline-primary btn-lg">Manage Patients</a>
                <a href="/medgemma" class="btn btn-primary btn-lg ms-2">Run MedGemma AI</a>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Patients</h5>
                        <p class="display-6 fw-bold" id="patients-count">--</p>
                        <a href="/patients" class="btn btn-link">View All</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Imaging Studies</h5>
                        <p class="display-6 fw-bold" id="studies-count">--</p>
                        <a href="/medgemma" class="btn btn-link">Analyze</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Lab Orders</h5>
                        <p class="display-6 fw-bold" id="labs-count">--</p>
                        <a href="#analytics-section" class="btn btn-link">View Analytics</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">AI Results</h5>
                        <p class="display-6 fw-bold" id="ai-count">--</p>
                        <a href="/medgemma" class="btn btn-link">See Results</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Clinical Analytics & Reporting Section -->
        <div class="analytics-section" id="analytics-section">
            <h2 style="text-align: center; margin: 3rem 0 2rem 0; font-size: 2.2rem; color: white;">📊 Clinical Analytics & Reporting</h2>
            
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">🔍 Report Generator</h5>
                            <div class="filters-panel">
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6">
                                        <label for="dateFrom" class="form-label">From Date:</label>
                                        <input type="date" id="dateFrom" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="dateTo" class="form-label">To Date:</label>
                                        <input type="date" id="dateTo" class="form-control">
                                    </div>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-md-8">
                                        <label for="reportType" class="form-label">Report Type:</label>
                                        <select id="reportType" class="form-select">
                                            <option value="patients">Patient Summary</option>
                                            <option value="imaging">Imaging Studies</option>
                                            <option value="labs">Laboratory Results</option>
                                            <option value="prescriptions">Prescriptions</option>
                                            <option value="ai-analysis">AI Analysis Results</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">&nbsp;</label>
                                        <button id="generateReport" class="btn btn-primary w-100">📋 Generate</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">📈 Quick Analytics</h5>
                            <div id="quickStats" class="stats-grid">
                                <div class="stat-item">
                                    <div class="stat-number" id="totalPatients">--</div>
                                    <div class="stat-label">Total Patients</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number" id="totalImaging">--</div>
                                    <div class="stat-label">Imaging Studies</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number" id="totalLabs">--</div>
                                    <div class="stat-label">Lab Orders</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number" id="totalPrescriptions">--</div>
                                    <div class="stat-label">Prescriptions</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">👥 Patient Demographics</h5>
                            <button class="btn btn-outline-primary btn-sm" onclick="exportReport('demographics')">📤 Export</button>
                        </div>
                        <div class="card-body">
                            <div id="demographicsChart" class="chart-container">
                                <div class="demographics-grid" id="demographicsData">
                                    Loading demographics data...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">🕒 Recent Activity</h5>
                            <button class="btn btn-outline-primary btn-sm" onclick="refreshActivity()">🔄 Refresh</button>
                        </div>
                        <div class="card-body">
                            <div id="recentActivity" class="activity-panel">
                                Loading recent activity...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">📄 Generated Reports</h5>
                            <button class="btn btn-outline-danger btn-sm" onclick="clearReports()">🗑️ Clear All</button>
                        </div>
                        <div class="card-body">
                            <div id="generatedReports" class="reports-panel">
                                <div class="empty-state">
                                    <p>No reports generated yet</p>
                                    <small>Use the report generator above to create reports</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="alert mt-5" role="alert">
            <strong>Tip:</strong> Use the navigation bar to access all features. For help, click the Help link above.
        </div>
    </main>

    <script>
        // Dashboard stats functionality
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardStats();
            loadAnalyticsData();
        });
        
        function loadDashboardStats() {
            fetch('/api/dashboard-stats')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('patients-count').textContent = data.patients ?? '--';
                    document.getElementById('studies-count').textContent = data.studies ?? '--';
                    document.getElementById('labs-count').textContent = data.labs ?? '--';
                    document.getElementById('ai-count').textContent = data.ai ?? '--';
                })
                .catch(err => {
                    console.error('Error loading dashboard stats:', err);
                });
        }
        
        // Analytics and Reporting functionality
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let patientsData = [];

        function tag(text, cls='') { return `<span class="tag ${cls}">${text}</span>`; }
        function htmlesc(str) { return (str||'').toString().replace(/[&<>\"]/g, s=>({"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;"}[s])); }

        async function loadAnalyticsData() {
            await loadQuickStats();
            updateDemographics();
            updateRecentActivity();
            setupReportGenerator();
        }

        async function loadQuickStats() {
            try {
                const r = await fetch('/api/patients', {headers: {'Accept':'application/json'}});
                const d = await r.json();
                patientsData = d || [];
                
                let totalImaging = 0;
                let totalLabs = 0;
                let totalPrescriptions = 0;
                
                patientsData.forEach(patient => {
                    if (patient.imaging_studies) totalImaging += patient.imaging_studies.length;
                    if (patient.lab_orders) totalLabs += patient.lab_orders.length;
                    if (patient.prescriptions) totalPrescriptions += patient.prescriptions.length;
                });

                document.getElementById('totalPatients').textContent = patientsData.length;
                document.getElementById('totalImaging').textContent = totalImaging;
                document.getElementById('totalLabs').textContent = totalLabs;
                document.getElementById('totalPrescriptions').textContent = totalPrescriptions;
            } catch (e) {
                console.error('Error loading quick stats:', e);
            }
        }

        function updateDemographics() {
            if (!patientsData.length) {
                document.getElementById('demographicsData').innerHTML = '<p>No patient data available</p>';
                return;
            }

            const demographics = {
                gender: {},
                ageGroups: {'0-18': 0, '19-30': 0, '31-50': 0, '51-70': 0, '70+': 0},
                total: patientsData.length
            };

            patientsData.forEach(patient => {
                // Gender distribution
                const gender = patient.sex || 'Unknown';
                demographics.gender[gender] = (demographics.gender[gender] || 0) + 1;

                // Age distribution
                if (patient.dob) {
                    const age = calculateAge(patient.dob);
                    if (age <= 18) demographics.ageGroups['0-18']++;
                    else if (age <= 30) demographics.ageGroups['19-30']++;
                    else if (age <= 50) demographics.ageGroups['31-50']++;
                    else if (age <= 70) demographics.ageGroups['51-70']++;
                    else demographics.ageGroups['70+']++;
                }
            });

            let html = '';
            
            // Gender section
            html += '<div class="demographics-section">';
            html += '<h4>Gender Distribution</h4>';
            html += '<div class="demo-grid">';
            Object.entries(demographics.gender).forEach(([gender, count]) => {
                const percentage = getPercentage(count, demographics.total);
                html += `<div class="demo-item">
                    <span>${gender}</span>
                    <span>${count} (${percentage}%)</span>
                </div>`;
            });
            html += '</div></div>';

            // Age section
            html += '<div class="demographics-section">';
            html += '<h4>Age Distribution</h4>';
            html += '<div class="demo-grid">';
            Object.entries(demographics.ageGroups).forEach(([range, count]) => {
                const percentage = getPercentage(count, demographics.total);
                html += `<div class="demo-item">
                    <span>${range} years</span>
                    <span>${count} (${percentage}%)</span>
                </div>`;
            });
            html += '</div></div>';

            document.getElementById('demographicsData').innerHTML = html;
        }

        function updateRecentActivity() {
            if (!patientsData.length) {
                document.getElementById('recentActivity').innerHTML = '<p>No recent activity</p>';
                return;
            }

            let activities = [];

            patientsData.forEach(patient => {
                if (patient.imaging_studies) {
                    patient.imaging_studies.forEach(study => {
                        activities.push({
                            type: 'imaging',
                            title: `${study.modality} Study - ${patient.name || patient.first_name + ' ' + patient.last_name}`,
                            date: study.started_at || study.created_at,
                            icon: '📸'
                        });
                    });
                }

                if (patient.lab_orders) {
                    patient.lab_orders.forEach(lab => {
                        activities.push({
                            type: 'lab',
                            title: `Lab Order: ${lab.name} - ${patient.name || patient.first_name + ' ' + patient.last_name}`,
                            date: lab.created_at,
                            icon: '🧪'
                        });
                    });
                }

                if (patient.prescriptions) {
                    patient.prescriptions.forEach(rx => {
                        activities.push({
                            type: 'prescription',
                            title: `Prescription: ${rx.medication} - ${patient.name || patient.first_name + ' ' + patient.last_name}`,
                            date: rx.created_at,
                            icon: '💊'
                        });
                    });
                }
            });

            // Sort by date (most recent first)
            activities.sort((a, b) => new Date(b.date) - new Date(a.date));
            activities = activities.slice(0, 10); // Show only last 10

            let html = '';
            if (activities.length === 0) {
                html = '<p>No recent activity</p>';
            } else {
                activities.forEach(activity => {
                    html += `<div class="activity-item">
                        <div class="activity-title">${activity.icon} ${htmlesc(activity.title)}</div>
                        <div class="activity-meta">${formatDate(activity.date)}</div>
                    </div>`;
                });
            }

            document.getElementById('recentActivity').innerHTML = html;
        }

        function calculateAge(dob) {
            if (!dob) return 0;
            const birthDate = new Date(dob);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            return age;
        }

        function getPercentage(value, total) {
            return total > 0 ? Math.round((value / total) * 100) : 0;
        }

        function formatDate(dateString) {
            if (!dateString) return 'Unknown';
            try {
                return new Date(dateString).toLocaleDateString('en-US', {
                    year: 'numeric', month: 'short', day: 'numeric'
                });
            } catch (e) {
                return 'Invalid Date';
            }
        }

        function setupReportGenerator() {
            document.getElementById('generateReport').addEventListener('click', generateReport);
        }

        async function generateReport() {
            const reportType = document.getElementById('reportType').value;
            const dateFrom = document.getElementById('dateFrom').value;
            const dateTo = document.getElementById('dateTo').value;

            if (!reportType) {
                alert('Please select a report type');
                return;
            }

            const button = document.getElementById('generateReport');
            const originalText = button.textContent;
            button.textContent = '⏳ Generating...';
            button.disabled = true;

            try {
                const report = await createReport(reportType, dateFrom, dateTo);
                displayGeneratedReport(report);
            } catch (error) {
                console.error('Error generating report:', error);
                alert('Failed to generate report');
            } finally {
                button.textContent = originalText;
                button.disabled = false;
            }
        }

        async function createReport(type, dateFrom, dateTo) {
            const data = patientsData;
            let filteredData = data;

            if (dateFrom || dateTo) {
                filteredData = data.filter(patient => {
                    const createdDate = new Date(patient.created_at);
                    if (dateFrom && createdDate < new Date(dateFrom)) return false;
                    if (dateTo && createdDate > new Date(dateTo)) return false;
                    return true;
                });
            }

            let report;
            switch (type) {
                case 'patients':
                    report = generatePatientSummaryReport(filteredData);
                    break;
                case 'imaging':
                    report = generateImagingReport(filteredData);
                    break;
                case 'labs':
                    report = generateLabsReport(filteredData);
                    break;
                case 'prescriptions':
                    report = generatePrescriptionsReport(filteredData);
                    break;
                case 'ai-analysis':
                    report = generateAIAnalysisReport(filteredData);
                    break;
                default:
                    throw new Error('Unknown report type');
            }

            return {
                ...report,
                id: Date.now(),
                createdAt: new Date().toISOString(),
                dateRange: { from: dateFrom, to: dateTo }
            };
        }

        function generatePatientSummaryReport(data) {
            return {
                title: 'Patient Summary Report',
                type: 'patients',
                summary: `Total patients: ${data.length}`,
                data: data.map(p => ({
                    name: p.name || `${p.first_name || ''} ${p.last_name || ''}`.trim(),
                    mrn: p.mrn,
                    age: p.dob ? calculateAge(p.dob) : 'Unknown',
                    gender: p.sex || 'Unknown'
                }))
            };
        }

        function generateImagingReport(data) {
            const studies = [];
            data.forEach(p => {
                if (p.imaging_studies) {
                    p.imaging_studies.forEach(study => {
                        studies.push({
                            patient: p.name || `${p.first_name || ''} ${p.last_name || ''}`.trim(),
                            mrn: p.mrn || 'N/A',
                            modality: study.modality || 'Unknown',
                            description: study.description || 'N/A',
                            date: study.started_at || 'N/A',
                            status: study.status || 'Unknown'
                        });
                    });
                }
            });
            return {
                title: 'Imaging Studies Report',
                type: 'imaging',
                summary: `Total imaging studies: ${studies.length}`,
                data: studies
            };
        }

        function generateLabsReport(data) {
            const labs = [];
            data.forEach(p => {
                if (p.lab_orders) {
                    p.lab_orders.forEach(lab => {
                        labs.push({
                            patient: p.name || `${p.first_name || ''} ${p.last_name || ''}`.trim(),
                            mrn: p.mrn || 'N/A',
                            test: lab.name || lab.code || 'Unknown',
                            status: lab.status || 'Unknown',
                            result: lab.result_value || 'Pending',
                            flag: lab.result_flag || 'Normal'
                        });
                    });
                }
            });
            return {
                title: 'Laboratory Results Report',
                type: 'labs',
                summary: `Total lab orders: ${labs.length}`,
                data: labs
            };
        }

        function generatePrescriptionsReport(data) {
            const prescriptions = [];
            data.forEach(p => {
                if (p.prescriptions) {
                    p.prescriptions.forEach(rx => {
                        prescriptions.push({
                            patient: p.name || `${p.first_name || ''} ${p.last_name || ''}`.trim(),
                            mrn: p.mrn || 'N/A',
                            medication: rx.medication || 'Unknown',
                            strength: rx.strength || 'N/A',
                            dosage: rx.dosage || 'N/A',
                            frequency: rx.frequency || 'N/A',
                            status: rx.status || 'Unknown'
                        });
                    });
                }
            });
            return {
                title: 'Prescriptions Report',
                type: 'prescriptions',
                summary: `Total prescriptions: ${prescriptions.length}`,
                data: prescriptions
            };
        }

        function generateAIAnalysisReport(data) {
            const analyses = [];
            data.forEach(p => {
                if (p.ai_results) {
                    p.ai_results.forEach(result => {
                        analyses.push({
                            patient: p.name || `${p.first_name || ''} ${p.last_name || ''}`.trim(),
                            mrn: p.mrn || 'N/A',
                            type: result.analysis_type || 'Unknown',
                            model: result.model || 'MedGemma',
                            confidence: result.confidence_score || 'N/A',
                            date: result.created_at || 'N/A'
                        });
                    });
                }
            });
            return {
                title: 'AI Analysis Results Report',
                type: 'ai-analysis',
                summary: `Total AI analyses: ${analyses.length}`,
                data: analyses
            };
        }

        function displayGeneratedReport(report) {
            const reportsPanel = document.getElementById('generatedReports');
            
            // Remove empty state if it exists
            const emptyState = reportsPanel.querySelector('.empty-state');
            if (emptyState) {
                emptyState.remove();
            }

            const reportHtml = `
                <div class="report-item" data-report-id="${report.id}">
                    <div class="report-header">
                        <div>
                            <div class="report-title">${htmlesc(report.title)}</div>
                            <div class="report-meta">Generated on ${formatDate(report.createdAt)} • ${htmlesc(report.summary)}</div>
                        </div>
                        <div class="report-actions">
                            <button class="btn btn-outline-primary btn-sm" onclick="exportReport('${report.type}', ${report.id})">📤 Export</button>
                            <button class="btn btn-outline-danger btn-sm" onclick="removeReport(${report.id})">🗑️ Remove</button>
                        </div>
                    </div>
                    <div class="report-content" style="display: none;">
                        ${formatReportData(report)}
                    </div>
                    <button class="btn btn-link btn-sm" onclick="toggleReportContent(${report.id})">👁️ View Details</button>
                </div>
            `;

            reportsPanel.insertAdjacentHTML('afterbegin', reportHtml);
        }

        function formatReportData(report) {
            if (!report.data || !report.data.length) {
                return '<p>No data available for this report.</p>';
            }

            let html = '<div class="table-responsive"><table class="table table-sm" style="color: white;">';
            
            // Generate table headers based on first data item
            const headers = Object.keys(report.data[0]);
            html += '<thead><tr>';
            headers.forEach(header => {
                html += `<th>${htmlesc(header.charAt(0).toUpperCase() + header.slice(1))}</th>`;
            });
            html += '</tr></thead>';

            // Generate table rows
            html += '<tbody>';
            report.data.slice(0, 50).forEach(row => { // Limit to 50 rows for performance
                html += '<tr>';
                headers.forEach(header => {
                    html += `<td>${htmlesc(row[header] || 'N/A')}</td>`;
                });
                html += '</tr>';
            });
            html += '</tbody></table></div>';

            if (report.data.length > 50) {
                html += `<p><small>Showing first 50 of ${report.data.length} records</small></p>`;
            }

            return html;
        }

        function toggleReportContent(reportId) {
            const reportItem = document.querySelector(`[data-report-id="${reportId}"]`);
            const content = reportItem.querySelector('.report-content');
            const button = reportItem.querySelector('button[onclick*="toggleReportContent"]');
            
            if (content.style.display === 'none') {
                content.style.display = 'block';
                button.textContent = '👁️ Hide Details';
            } else {
                content.style.display = 'none';
                button.textContent = '👁️ View Details';
            }
        }

        function removeReport(reportId) {
            const reportItem = document.querySelector(`[data-report-id="${reportId}"]`);
            if (reportItem) {
                reportItem.remove();
                
                // Add empty state if no reports left
                const reportsPanel = document.getElementById('generatedReports');
                if (!reportsPanel.querySelector('.report-item')) {
                    reportsPanel.innerHTML = `
                        <div class="empty-state">
                            <p>No reports generated yet</p>
                            <small>Use the report generator above to create reports</small>
                        </div>
                    `;
                }
            }
        }

        function exportReport(type, reportId) {
            // Basic export functionality - could be enhanced with actual file generation
            if (reportId) {
                const reportItem = document.querySelector(`[data-report-id="${reportId}"]`);
                const title = reportItem.querySelector('.report-title').textContent;
                alert(`Export functionality for "${title}" would be implemented here`);
            } else {
                alert(`Export functionality for ${type} would be implemented here`);
            }
        }

        function refreshActivity() {
            updateRecentActivity();
        }

        function clearReports() {
            if (confirm('Are you sure you want to clear all generated reports?')) {
                const reportsPanel = document.getElementById('generatedReports');
                reportsPanel.innerHTML = `
                    <div class="empty-state">
                        <p>No reports generated yet</p>
                        <small>Use the report generator above to create reports</small>
                    </div>
                `;
            }
        }
    </script>
</body>
</html>
