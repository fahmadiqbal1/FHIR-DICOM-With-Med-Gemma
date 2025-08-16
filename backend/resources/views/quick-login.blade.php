<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Login - Financial Dashboard Demo</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 40px 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 2rem;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 1.1rem;
        }
        .login-option {
            margin: 20px 0;
            padding: 20px;
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }
        .login-option:hover {
            border-color: #667eea;
            background: #f0f2ff;
            transform: translateY(-2px);
        }
        .login-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        .feature-list {
            text-align: left;
            margin: 10px 0;
            font-size: 0.9rem;
            color: #555;
        }
        .feature-list li {
            margin: 5px 0;
        }
        .note {
            background: #e8f4fd;
            border: 1px solid #b8daff;
            border-radius: 10px;
            padding: 15px;
            margin-top: 30px;
            font-size: 0.9rem;
            color: #004085;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>🏥 Financial Dashboard Demo</h1>
        <p class="subtitle">Quick access to test the financial tracking system</p>

        <div class="login-option">
            <h3>👨‍💼 Admin Dashboard</h3>
            <ul class="feature-list">
                <li>✅ Complete business overview</li>
                <li>✅ Doctor performance analytics</li>
                <li>✅ Revenue & expense tracking</li>
                <li>✅ Interactive charts & reports</li>
            </ul>
            <a href="/quick-login/admin" class="login-btn">Login as Admin</a>
        </div>

        <div class="login-option">
            <h3>👨‍⚕️ Doctor Dashboard</h3>
            <ul class="feature-list">
                <li>✅ Personal earnings tracker</li>
                <li>✅ Patient count & appointments</li>
                <li>✅ Revenue share visualization</li>
                <li>✅ Performance metrics</li>
            </ul>
            <a href="/quick-login/doctor" class="login-btn">Login as Doctor</a>
        </div>

        <div class="login-option">
            <h3>🩻 Radiologist Dashboard</h3>
            <ul class="feature-list">
                <li>✅ Imaging study analysis</li>
                <li>✅ AI-powered diagnostic assistance</li>
                <li>✅ Second opinion workflows</li>
                <li>✅ Report generation system</li>
            </ul>
            <a href="/quick-login/radiologist" class="login-btn">Login as Radiologist</a>
        </div>

        <div class="login-option">
            <h3>🧪 Lab Technician Dashboard</h3>
            <ul class="feature-list">
                <li>✅ Lab order management</li>
                <li>✅ Sample collection tracking</li>
                <li>✅ Result submission workflow</li>
                <li>✅ Priority-based task organization</li>
            </ul>
            <a href="/quick-login/lab-tech" class="login-btn">Login as Lab Tech</a>
        </div>

        <div class="note">
            <strong>Demo Features:</strong><br>
            • 30 days of sample financial data<br>
            • Revenue sharing: 60-70% doctors, 30-40% admin<br>
            • Expense tracking with categories<br>
            • Real-time charts and analytics<br><br>
            
            <strong>🔑 Manual Login Credentials:</strong><br>
            <div style="text-align: left; margin-top: 10px; font-family: monospace; background: rgba(255,255,255,0.7); padding: 10px; border-radius: 5px;">
            <strong>Admin:</strong> admin@medgemma.com / admin123<br>
            <strong>Doctor 1:</strong> doctor1@medgemma.com / doctor123<br>
            <strong>Doctor 2:</strong> doctor2@medgemma.com / doctor123<br>
            <strong>Radiologist:</strong> radiologist@medgemma.com / radiologist123<br>
            <strong>Lab Tech:</strong> labtech@medgemma.com / labtech123
            </div><br>
            <a href="/login" style="color: #667eea; text-decoration: none; font-weight: 600;">👉 Manual Login Page</a>
        </div>
    </div>
</body>
</html>
