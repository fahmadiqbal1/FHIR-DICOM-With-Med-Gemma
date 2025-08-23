<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🧪 Test Access - All Dashboards</title>
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
            padding: 2rem;
            color: white;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        
        .card-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
        }
        
        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .card-desc {
            opacity: 0.9;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }
        
        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
            display: block;
        }
        
        .btn:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }
        
        .btn-primary {
            background: rgba(102, 126, 234, 0.3);
            border-color: rgba(102, 126, 234, 0.5);
        }
        
        .btn-primary:hover {
            background: rgba(102, 126, 234, 0.5);
        }
        
        .status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }
        
        .status-new {
            background: rgba(16, 185, 129, 0.2);
            border: 1px solid rgba(16, 185, 129, 0.5);
            color: #10b981;
        }
        
        .status-api {
            background: rgba(59, 130, 246, 0.2);
            border: 1px solid rgba(59, 130, 246, 0.5);
            color: #3b82f6;
        }
        
        .info-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
        }
        
        .info-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .credentials {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }
        
        .credential-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 1rem;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .credential-role {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #60a5fa;
        }
        
        .credential-details {
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="title">🧪 Test Access Portal</h1>
            <p class="subtitle">Direct access to all dashboards and APIs</p>
        </div>
        
        <div class="grid">
            <!-- Admin Dashboard -->
            <div class="card">
                <span class="card-icon">👨‍💼</span>
                <h3 class="card-title">Admin Dashboard</h3>
                <p class="card-desc">Complete business overview with financial analytics and user management capabilities.</p>
                <div class="btn-group">
                    <a href="/financial/admin-dashboard" class="btn btn-primary">📊 Financial Dashboard</a>
                    <a href="/api/dashboard/admin" class="btn">API Endpoint <span class="status status-api">JSON</span></a>
                </div>
            </div>
            
            <!-- Doctor Dashboard -->
            <div class="card">
                <span class="card-icon">👩‍⚕️</span>
                <h3 class="card-title">Doctor Dashboard</h3>
                <p class="card-desc">Patient management, clinical notes, AI analysis, and personal earnings tracking.</p>
                <div class="btn-group">
                    <a href="/financial/doctor-dashboard" class="btn btn-primary">💰 Financial Dashboard</a>
                    <a href="/api/dashboard/doctor" class="btn">API Endpoint <span class="status status-api">JSON</span></a>
                </div>
            </div>
            
            <!-- Lab Tech Dashboard -->
            <div class="card">
                <span class="card-icon">🧪</span>
                <h3 class="card-title">Lab Technician</h3>
                <p class="card-desc">Sample processing, equipment monitoring, test results management.</p>
                <div class="btn-group">
                    <a href="/lab-tech" class="btn btn-primary">🔬 Lab Dashboard</a>
                    <a href="/api/dashboard/lab" class="btn">API Endpoint <span class="status status-api">JSON</span></a>
                </div>
            </div>
            
            <!-- Radiologist Dashboard -->
            <div class="card">
                <span class="card-icon">📡</span>
                <h3 class="card-title">Radiologist</h3>
                <p class="card-desc">DICOM imaging, radiology reports, and study analysis capabilities.</p>
                <div class="btn-group">
                    <a href="/radiologist" class="btn btn-primary">📸 Radiology Dashboard</a>
                    <a href="/api/dashboard/radiology" class="btn">API Endpoint <span class="status status-api">JSON</span></a>
                </div>
            </div>
            
            <!-- Pharmacist Dashboard -->
            <div class="card">
                <span class="card-icon">💊</span>
                <h3 class="card-title">Pharmacist <span class="status status-new">NEW!</span></h3>
                <p class="card-desc">Prescription processing, inventory management, and revenue tracking system.</p>
                <div class="btn-group">
                    <a href="/pharmacist-dashboard" class="btn btn-primary">💊 Pharmacy Dashboard</a>
                    <a href="/api/dashboard/pharmacist" class="btn">API Endpoint <span class="status status-api">JSON</span></a>
                </div>
            </div>
            
            <!-- Owner Portal -->
            <div class="card">
                <span class="card-icon">🏢</span>
                <h3 class="card-title">Owner Portal</h3>
                <p class="card-desc">Complete business analytics, profit/loss analysis, multi-role revenue tracking.</p>
                <div class="btn-group">
                    <a href="/dashboard" class="btn btn-primary">📈 Owner Dashboard</a>
                    <a href="/api/dashboard/owner" class="btn">API Endpoint <span class="status status-api">JSON</span></a>
                </div>
            </div>
        </div>
        
        <div class="info-section">
            <h3 class="info-title">🔑 Test Login Credentials</h3>
            <div class="credentials">
                <div class="credential-item">
                    <div class="credential-role">Admin</div>
                    <div class="credential-details">
                        admin@medgemma.com<br>
                        admin123
                    </div>
                </div>
                <div class="credential-item">
                    <div class="credential-role">Doctor</div>
                    <div class="credential-details">
                        doctor1@medgemma.com<br>
                        doctor123
                    </div>
                </div>
                <div class="credential-item">
                    <div class="credential-role">Lab Tech</div>
                    <div class="credential-details">
                        labtech@medgemma.com<br>
                        lab123
                    </div>
                </div>
                <div class="credential-item">
                    <div class="credential-role">Radiologist</div>
                    <div class="credential-details">
                        radiologist@medgemma.com<br>
                        radio123
                    </div>
                </div>
                <div class="credential-item">
                    <div class="credential-role">Pharmacist</div>
                    <div class="credential-details">
                        pharmacist@medgemma.com<br>
                        pharma123
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 2rem; text-align: center;">
                <a href="/login" class="btn btn-primary" style="display: inline-block; margin-right: 1rem;">🔐 Manual Login</a>
                <a href="/patients" class="btn">👥 Patient Management</a>
            </div>
        </div>
    </div>
</body>
</html>
