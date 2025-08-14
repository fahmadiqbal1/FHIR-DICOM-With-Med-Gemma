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
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .nav {
            display: flex;
            gap: 1.2rem;
        }
        
        .nav a {
            color: #fff;
            text-decoration: none;
            padding: 0.7rem 1.2rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .nav a.active, .nav a:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(102,126,234,0.15);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        .dashboard-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .dashboard-header h1 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .dashboard-header p {
            font-size: 1.2rem;
            color: rgba(255,255,255,0.8);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .stat-card {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 18px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s;
            box-shadow: 0 4px 32px rgba(102,126,234,0.08);
        }
        
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 40px rgba(118,75,162,0.15);
            background: rgba(255,255,255,0.12);
        }
        
        .stat-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.8);
            font-weight: 500;
        }
        
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        
        .action-card {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 1.8rem;
            text-decoration: none;
            color: #fff;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 2px 16px rgba(102,126,234,0.06);
        }
        
        .action-card:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transform: translateY(-4px);
            box-shadow: 0 6px 24px rgba(118,75,162,0.2);
            color: #fff;
            text-decoration: none;
        }
        
        .action-icon {
            font-size: 2.2rem;
            flex-shrink: 0;
        }
        
        .action-content h3 {
            margin: 0 0 0.5rem 0;
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .action-content p {
            margin: 0;
            color: rgba(255,255,255,0.8);
            font-size: 0.95rem;
        }
        
        .recent-activity {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 18px;
            padding: 2rem;
            box-shadow: 0 4px 32px rgba(102,126,234,0.08);
        }
        
        .recent-activity h2 {
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            font-weight: 600;
        }
        
        .activity-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            background: rgba(255,255,255,0.04);
            transition: background 0.2s;
        }
        
        .activity-item:hover {
            background: rgba(255,255,255,0.08);
        }
        
        .activity-icon {
            font-size: 1.5rem;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(102,126,234,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .activity-time {
            color: rgba(255,255,255,0.6);
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .dashboard-header h1 {
                font-size: 2.2rem;
            }
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            .quick-actions {
                grid-template-columns: 1fr;
            }
        }
    </style>
            font-weight: bold;
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
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
        
        .text-end {
            text-align: right;
        }
        
        .mb-4 {
            margin-bottom: 1.5rem;
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
        
        .text-center {
            text-align: center;
        }
        
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: white;
        }
        
        .display-6 {
            font-size: 2.5rem;
            font-weight: 600;
            color: white;
            margin-bottom: 1rem;
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
        
        @media (max-width: 768px) {
            .col-md-8, .col-md-4, .col-md-3 {
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
                <a href="/reports">Reports</a>
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
                        <a href="/reports" class="btn btn-link">View Reports</a>
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
        
        <div class="alert mt-5" role="alert">
            <strong>Tip:</strong> Use the navigation bar to access all features. For help, click the Help link above.
        </div>
    </main>

    <script>
        // Example: Fetch dashboard stats via AJAX (replace with real endpoints)
        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>
</body>
</html>

