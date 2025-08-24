<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Healthcare Platform - Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            color: white;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 15px;
            width: 100%;
            max-width: 500px;
            margin: 20px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header h1 {
            color: white;
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: white;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 16px;
        }
        
        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        
        .btn {
            width: 100%;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5a6fd8;
        }
        
        .quick-login-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .quick-login-section h3 {
            text-align: center;
            margin-bottom: 1rem;
            color: white;
        }
        
        .quick-login-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 10px;
        }
        
        .btn-quick {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 12px;
            font-size: 14px;
            text-decoration: none;
            text-align: center;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .btn-quick:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }
        
        .alert {
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .alert-error {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #f8d7da;
        }
        
        .alert-success {
            background: rgba(40, 167, 69, 0.2);
            border: 1px solid rgba(40, 167, 69, 0.3);
            color: #d4edda;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div style="font-size: 3rem; margin-bottom: 1rem;">🏥</div>
            <h1>Healthcare AI Platform</h1>
            <p style="color: rgba(255, 255, 255, 0.8); margin: 0;">Please sign in to continue</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required 
                       placeholder="Enter your email address" autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required 
                       placeholder="Enter your password">
            </div>

            <button type="submit" class="btn btn-primary">
                Sign In
            </button>
        </form>

        <div class="quick-login-section">
            <h3>Quick Login (Demo)</h3>
            <p style="text-align: center; color: rgba(255, 255, 255, 0.7); margin-bottom: 1rem; font-size: 14px;">
                Click below to instantly login as different user types
            </p>
            <div class="quick-login-grid">
                <a href="/quick-login/owner" class="btn-quick">Owner</a>
                <a href="/quick-login/admin" class="btn-quick">Admin</a>
                <a href="/quick-login/lab-tech" class="btn-quick">Lab Tech</a>
                <a href="/quick-login/doctor" class="btn-quick">Doctor</a>
                <a href="/quick-login/radiologist" class="btn-quick">Radiologist</a>
                <a href="/quick-login/pharmacist" class="btn-quick">Pharmacist</a>
            </div>
        </div>

        <div style="text-align: center; margin-top: 2rem; color: rgba(255, 255, 255, 0.6); font-size: 14px;">
            <p>Test Credentials: Any email above with password: <strong>password</strong></p>
            <p>Or use Quick Login for instant access</p>
        </div>
    </div>
</body>
</html>
