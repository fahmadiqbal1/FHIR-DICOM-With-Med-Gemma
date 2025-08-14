<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to MedGemma Healthcare Platform</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8fafc;
        }
        
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .header p {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .logo {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .welcome-message {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .welcome-message h2 {
            color: #2d3748;
            font-size: 24px;
            margin-bottom: 12px;
        }
        
        .welcome-message p {
            color: #718096;
            font-size: 16px;
        }
        
        .credentials-box {
            background: #f7fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            text-align: center;
        }
        
        .credentials-box h3 {
            color: #2d3748;
            font-size: 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .credentials-box h3::before {
            content: "🔐";
            font-size: 20px;
        }
        
        .credential-item {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .credential-label {
            font-weight: 600;
            color: #4a5568;
        }
        
        .credential-value {
            font-family: 'Courier New', monospace;
            background: #edf2f7;
            padding: 6px 12px;
            border-radius: 6px;
            color: #2d3748;
            font-weight: 600;
        }
        
        .security-notice {
            background: #fef5e7;
            border: 1px solid #f6e05e;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        
        .security-notice h4 {
            color: #744210;
            font-size: 16px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .security-notice h4::before {
            content: "⚠️";
        }
        
        .security-notice p {
            color: #975a16;
            font-size: 14px;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        
        .feature-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
        }
        
        .feature-icon {
            font-size: 32px;
            margin-bottom: 12px;
        }
        
        .feature-card h4 {
            color: #2d3748;
            font-size: 16px;
            margin-bottom: 8px;
        }
        
        .feature-card p {
            color: #718096;
            font-size: 14px;
        }
        
        .cta-section {
            text-align: center;
            margin: 30px 0;
        }
        
        .btn-primary {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }
        
        .footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 25px 30px;
            text-align: center;
        }
        
        .footer p {
            color: #718096;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .footer .company-info {
            color: #4a5568;
            font-weight: 600;
        }
        
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e2e8f0, transparent);
            margin: 25px 0;
        }
        
        @media (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 8px;
            }
            
            .header, .content {
                padding: 25px 20px;
            }
            
            .credentials-box {
                padding: 20px;
            }
            
            .credential-item {
                flex-direction: column;
                gap: 8px;
                text-align: center;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header Section -->
        <div class="header">
            <div class="logo">M</div>
            <h1>MedGemma Healthcare Platform</h1>
            <p>Advanced AI-Powered Medical Analytics</p>
        </div>
        
        <!-- Main Content -->
        <div class="content">
            <div class="welcome-message">
                <h2>Welcome, {{ $user->name }}! 🎉</h2>
                <p>Your admin account has been successfully created for the MedGemma Healthcare Platform.</p>
            </div>
            
            <div class="divider"></div>
            
            <!-- Login Credentials -->
            <div class="credentials-box">
                <h3>Your Login Credentials</h3>
                <div class="credential-item">
                    <span class="credential-label">Email Address:</span>
                    <span class="credential-value">{{ $user->email }}</span>
                </div>
                <div class="credential-item">
                    <span class="credential-label">Password:</span>
                    <span class="credential-value">{{ $password }}</span>
                </div>
            </div>
            
            <!-- Security Notice -->
            <div class="security-notice">
                <h4>Important Security Notice</h4>
                <p>For security reasons, please change your password immediately after your first login. We recommend using a strong password with at least 8 characters, including uppercase letters, lowercase letters, numbers, and special characters.</p>
            </div>
            
            <!-- Platform Features -->
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🧠</div>
                    <h4>AI-Powered Analysis</h4>
                    <p>Advanced medical AI using Google's MedGemma models for clinical insights</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h4>FHIR Compliance</h4>
                    <p>Full FHIR R4 support for interoperable healthcare data exchange</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🏥</div>
                    <h4>DICOM Integration</h4>
                    <p>Comprehensive medical imaging support with DICOM standards</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h4>Secure Platform</h4>
                    <p>Enterprise-grade security with audit logs and encrypted data</p>
                </div>
            </div>
            
            <!-- Call to Action -->
            <div class="cta-section">
                <a href="{{ config('app.url') }}/login" class="btn-primary">
                    Access Your Dashboard
                </a>
            </div>
            
            <div class="divider"></div>
            
            <!-- Additional Information -->
            <div style="background: #f8fafc; padding: 20px; border-radius: 8px; margin: 25px 0;">
                <h4 style="color: #2d3748; margin-bottom: 15px;">What's Next?</h4>
                <ul style="color: #4a5568; padding-left: 20px;">
                    <li style="margin-bottom: 8px;">Log in to your admin dashboard using the credentials above</li>
                    <li style="margin-bottom: 8px;">Change your password in the account settings</li>
                    <li style="margin-bottom: 8px;">Explore the patient management system and AI analytics</li>
                    <li style="margin-bottom: 8px;">Configure DICOM upload settings as needed</li>
                    <li>Review system audit logs and user management features</li>
                </ul>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p class="company-info">MedGemma Healthcare Platform</p>
            <p>Advanced Healthcare AI • FHIR Compliant • DICOM Enabled</p>
            <p style="margin-top: 15px; font-size: 12px;">
                This email was sent to {{ $user->email }} because an admin account was created for you.
            </p>
        </div>
    </div>
</body>
</html>
