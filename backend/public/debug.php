<!DOCTYPE html>
<html>
<head>
    <title>Debug Dashboard - Healthcare Platform</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px; }
        .section { background: #f8f9fa; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .success { background: #d4edda; border-left: 4px solid #28a745; }
        .error { background: #f8d7da; border-left: 4px solid #dc3545; }
        .info { background: #d1ecf1; border-left: 4px solid #17a2b8; }
        pre { background: white; padding: 10px; border-radius: 3px; overflow-x: auto; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h2>🔧 Healthcare Platform Debug Dashboard</h2>
    
    <div class="section info">
        <h3>🏥 Application Status</h3>
        <p><strong>Server:</strong> Running on http://127.0.0.1:8000</p>
        <p><strong>Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
        <p><strong>Platform:</strong> Healthcare AI Management System</p>
    </div>
    
    <div class="section">
        <h3>🔑 Quick Links</h3>
        <p><a href="http://127.0.0.1:8000/login">→ Login Page</a></p>
        <p><a href="http://127.0.0.1:8000/dashboard">→ Main Dashboard (requires login)</a></p>
        <p><a href="http://127.0.0.1:8000/dashboard/owner">→ Owner Dashboard (requires login)</a></p>
        <p><a href="http://127.0.0.1:8000/logout">→ Logout</a></p>
    </div>
    
    <div class="section success">
        <h3>✅ Confirmed Working</h3>
        <p>✓ Laravel server running on port 8000</p>
        <p>✓ All user passwords set to 'password'</p>
        <p>✓ Database contains 741 invoices, 65 patients</p>
        <p>✓ Owner dashboard has server-side data integration</p>
        <p>✓ Role detection logic in place</p>
    </div>
    
    <div class="section error">
        <h3>⚠️ Known Issues</h3>
        <p>• Login redirects may not work for some profiles</p>
        <p>• Dashboard data loading intermittent</p>
        <p>• Session timeout set to 5 minutes (may cause issues)</p>
    </div>
    
    <div class="section">
        <h3>🧪 Test Credentials</h3>
        <pre>
Owner:       owner@medgemma.com      / password
Admin:       admin@medgemma.com      / password  
Doctor:      doctor1@medgemma.com    / password
Lab Tech:    labtech@medgemma.com    / password
Radiologist: radiologist@medgemma.com / password
Pharmacist:  pharmacist@medgemma.com  / password
        </pre>
    </div>
    
    <div class="section">
        <h3>📊 Expected Dashboard Routes</h3>
        <pre>
/dashboard           → Role-based redirect
/dashboard/owner     → Owner business dashboard  
/admin-dashboard-direct → Admin control panel
/doctor-dashboard-direct → Doctor interface
/lab-tech-dashboard  → Laboratory management
/radiologist-dashboard-direct → Radiology interface
/pharmacist-dashboard → Pharmacy management
        </pre>
    </div>
    
    <div class="section info">
        <h3>🔄 How to Test Login</h3>
        <p>1. Go to <a href="http://127.0.0.1:8000/login">Login Page</a></p>
        <p>2. Use any of the test credentials above</p>
        <p>3. You should be redirected to /dashboard</p>
        <p>4. /dashboard should detect your role and redirect to appropriate dashboard</p>
        <p>5. If redirect fails, try direct dashboard URLs above</p>
    </div>
    
    <hr>
    <p><em>Last updated: <?php echo date('Y-m-d H:i:s'); ?></em></p>
</body>
</html>
