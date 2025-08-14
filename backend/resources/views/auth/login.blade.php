<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Healthcare AI Platform</title>
    
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
        
        .login-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header .logo {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .login-header h1 {
            color: white;
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 1.8rem;
        }
        
        .login-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
            margin-bottom: 0;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: white;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            font-size: 1rem;
            color: white;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .form-control:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .remember-me input[type="checkbox"] {
            margin-right: 0.5rem;
            transform: scale(1.1);
        }
        
        .remember-me label {
            margin-bottom: 0;
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
        }
        
        .btn {
            width: 100%;
            padding: 0.875rem;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }
        
        .btn:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        .alert {
            padding: 0.875rem 1rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            font-size: 0.9rem;
            backdrop-filter: blur(5px);
        }
        
        .alert-success {
            background: rgba(40, 167, 69, 0.2);
            color: #90ee90;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }
        
        .alert-error {
            background: rgba(220, 53, 69, 0.2);
            color: #ffb3b3;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }
        
        .footer-text {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
        }
        
        .footer-text i {
            margin-right: 0.3rem;
        }
        
        @media (max-width: 480px) {
            .login-container {
                margin: 1rem;
                padding: 2rem;
            }
            
            .login-header h1 {
                font-size: 1.5rem;
            }
            
            .login-header .logo {
                font-size: 2.5rem;
            }
        }
        
        /* Loading animation */
        .btn.loading {
            pointer-events: none;
            position: relative;
        }
        
        .btn.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo">🏥</div>
            <h1>Healthcare AI Platform</h1>
            <p>FHIR DICOM with MedGemma Integration</p>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    {{ $error }}@if(!$loop->last)<br>@endif
                @endforeach
            </div>
        @endif
        
        <form method="POST" action="{{ route('login.submit') }}" id="loginForm">
            @csrf
            <input type="hidden" name="_token" id="csrf-token" value="{{ csrf_token() }}">
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       class="form-control" 
                       value="{{ old('email') }}" 
                       placeholder="Enter your email"
                       required 
                       autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       class="form-control" 
                       placeholder="Enter your password"
                       required>
            </div>
            
            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me</label>
            </div>
            
            <button type="submit" class="btn" id="loginBtn">
                Sign In
            </button>
        </form>
        
        <div class="footer-text">
            <i>🔒</i>
            Secure healthcare platform
        </div>
    </div>
    
    <script>
        // Refresh CSRF token periodically to prevent 419 errors
        function refreshCSRFToken() {
            fetch('/csrf-token', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.token) {
                    document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.token);
                    document.querySelector('input[name="_token"]').value = data.token;
                }
            })
            .catch(err => console.log('CSRF refresh failed:', err));
        }
        
        // Refresh token every 2 minutes
        setInterval(refreshCSRFToken, 120000);
        
        // Add loading state to login button
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
            btn.textContent = '';
            
            // If form submission fails due to CSRF, try refreshing token and retry
            setTimeout(() => {
                if (btn.classList.contains('loading')) {
                    refreshCSRFToken();
                }
            }, 3000);
        });
        
        // Auto logout after 5 minutes of inactivity (only show timer if already logged in)
        let inactivityTimer;
        let warningTimer;
        const TIMEOUT_DURATION = 5 * 60 * 1000; // 5 minutes
        const WARNING_DURATION = 4 * 60 * 1000; // Show warning at 4 minutes
        
        function resetTimer() {
            clearTimeout(inactivityTimer);
            clearTimeout(warningTimer);
            
            // Show warning at 4 minutes
            warningTimer = setTimeout(() => {
                if (confirm('Your session will expire in 1 minute due to inactivity. Click OK to stay logged in.')) {
                    resetTimer();
                }
            }, WARNING_DURATION);
            
            // Auto logout at 5 minutes
            inactivityTimer = setTimeout(() => {
                alert('Session expired due to inactivity. You will be logged out.');
                window.location.href = '/logout';
            }, TIMEOUT_DURATION);
        }
        
        // Only start timer if user is authenticated (check if we're not on login page after successful login)
        @auth
        document.addEventListener('click', resetTimer);
        document.addEventListener('keypress', resetTimer);
        document.addEventListener('scroll', resetTimer);
        document.addEventListener('mousemove', resetTimer);
        resetTimer();
        @endauth
        
        // Enhanced form validation
        const form = document.getElementById('loginForm');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
        
        form.addEventListener('submit', function(e) {
            if (!validateEmail(email.value)) {
                e.preventDefault();
                alert('Please enter a valid email address.');
                email.focus();
                return false;
            }
            
            if (password.value.length < 1) {
                e.preventDefault();
                alert('Please enter your password.');
                password.focus();
                return false;
            }
        });
        
        // Add visual feedback on input focus
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>
