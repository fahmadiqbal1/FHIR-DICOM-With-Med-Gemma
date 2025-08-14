<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Expired - Healthcare AI Platform</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        
        .error-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
            margin: 2rem;
        }
        
        .error-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            opacity: 0.8;
        }
        
        .error-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #fff;
        }
        
        .error-message {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 0.875rem 2rem;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
            text-decoration: none;
            display: inline-block;
        }
        
        .btn:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            color: white;
            text-decoration: none;
        }
        
        .btn-primary {
            background: rgba(102, 126, 234, 0.3);
            border-color: rgba(102, 126, 234, 0.5);
        }
        
        .btn-primary:hover {
            background: rgba(102, 126, 234, 0.5);
            border-color: rgba(102, 126, 234, 0.7);
        }
        
        .info-box {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 2rem;
            text-align: left;
        }
        
        .info-box h4 {
            color: #fff;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }
        
        .info-box p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            margin: 0;
        }
        
        @media (max-width: 600px) {
            .error-container {
                padding: 2rem;
            }
            
            .error-title {
                font-size: 1.5rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">⏰</div>
        <h1 class="error-title">Session Expired</h1>
        <p class="error-message">
            Your session has expired for security reasons. This usually happens when:
            <br>• You've been inactive for too long
            <br>• Your browser was closed and reopened
            <br>• There was a server restart
        </p>
        
        <div class="action-buttons">
            <a href="/login" class="btn btn-primary">🔐 Login Again</a>
            <a href="/quick-login" class="btn">⚡ Quick Login</a>
        </div>
        
        <div class="info-box">
            <h4>🔑 Demo Credentials</h4>
            <p>
                <strong>Admin:</strong> admin@medgemma.com / admin123<br>
                <strong>Doctor:</strong> doctor1@medgemma.com / doctor123
            </p>
        </div>
    </div>

    <script>
        // Auto-redirect after 10 seconds if no action taken
        let countdown = 10;
        const originalTitle = document.title;
        
        const countdownInterval = setInterval(() => {
            countdown--;
            document.title = `${originalTitle} (${countdown}s)`;
            
            if (countdown <= 0) {
                clearInterval(countdownInterval);
                window.location.href = '/quick-login';
            }
        }, 1000);
        
        // Stop countdown if user interacts with page
        document.addEventListener('click', () => {
            clearInterval(countdownInterval);
            document.title = originalTitle;
        });
        
        document.addEventListener('keydown', () => {
            clearInterval(countdownInterval);
            document.title = originalTitle;
        });
    </script>
</body>
</html>
