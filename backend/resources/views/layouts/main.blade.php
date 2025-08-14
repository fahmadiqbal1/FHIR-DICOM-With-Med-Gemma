<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FHIR DICOM MedGemma')</title>
    @include('partials.global-styles')
    
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
            line-height: 1.6;
            color: white;
        }
        .header {
            background: rgba(255, 255, 255, 0.10);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.18);
            color: white;
            padding: 1.5rem 0 1rem 0;
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
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .logo::before {
            content: "🏥";
            margin-right: 0.7rem;
            font-size: 2.2rem;
        }
        .nav {
            display: flex;
            align-items: center;
            gap: 1.2rem;
        }
        .nav a {
            color: #fff;
            text-decoration: none;
            padding: 0.7rem 1.2rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 1.08rem;
            transition: background 0.3s, transform 0.2s;
        }
        .nav a.active, .nav a:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            transform: translateY(-2px) scale(1.04);
            box-shadow: 0 2px 8px rgba(102,126,234,0.10);
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            background: rgba(255, 255, 255, 0.12);
            padding: 0.7rem 1.2rem;
            border-radius: 22px;
            font-size: 1rem;
            font-weight: 500;
            backdrop-filter: blur(6px);
            box-shadow: 0 2px 8px rgba(102,126,234,0.08);
        }
        .logout-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: #fff;
            padding: 0.5rem 1.1rem;
            border-radius: 15px;
            text-decoration: none;
            font-size: 0.98rem;
            font-weight: 600;
            transition: all 0.3s;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(102,126,234,0.10);
        }
        .logout-btn:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: translateY(-1px) scale(1.04);
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem;
        }
        .alert {
            padding: 1.2rem;
            margin-bottom: 1.2rem;
            border-radius: 10px;
            font-size: 1.05rem;
            font-weight: 500;
            backdrop-filter: blur(6px);
            box-shadow: 0 2px 8px rgba(102,126,234,0.08);
        }
        .alert-success {
            background: rgba(40, 167, 69, 0.18);
            color: #90ee90;
            border: 1px solid rgba(40, 167, 69, 0.22);
        }
        .alert-error {
            background: rgba(220, 53, 69, 0.18);
            color: #ffb3b3;
            border: 1px solid rgba(220, 53, 69, 0.22);
        }
        .alert-info {
            background: rgba(23, 162, 184, 0.18);
            color: #87ceeb;
            border: 1px solid rgba(23, 162, 184, 0.22);
        }
        .session-timer {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(0,0,0,0.85);
            color: white;
            padding: 0.7rem 1.2rem;
            border-radius: 22px;
            font-size: 1rem;
            font-weight: 600;
            z-index: 1000;
            display: none;
            box-shadow: 0 2px 8px rgba(102,126,234,0.10);
        }
        .session-timer.warning {
            background: #dc3545;
            animation: pulse 1s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        
        /* Removed duplicate and conflicting styles for header, logo, container, nav, user-info, logout-btn, alert, session-timer */
    </style>
    
    @yield('styles')
</head>
<body>
    <header class="header">
        <div class="header-container">
            <div class="logo">
                Healthcare AI Platform
            </div>
            
            <nav class="nav">
                <a href="/dashboard">Dashboard</a>
                <a href="/patients">Patients</a>
                <a href="/medgemma">AI Analysis</a>
                <a href="/reports">Reports</a>
                <a href="/dicom-upload">DICOM Upload</a>
                @auth
                    <a href="{{ route('financial.doctor-dashboard') }}">Financial</a>
                @endauth
            </nav>
            
            @auth
            <div class="user-info">
                <span>👤 {{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        🚪 Sign Out
                    </button>
                </form>
            </div>
            @else
            <div class="user-info">
                <a href="/login" class="logout-btn">Sign In</a>
            </div>
            @endauth
        </div>
    </header>
    
    <div class="session-timer" id="sessionTimer">
        Session expires in: <span id="timerDisplay">5:00</span>
    </div>
    
    <main class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif
        
        @if(session('info'))
            <div class="alert alert-info">
                {{ session('info') }}
            </div>
        @endif
        
        @yield('content')
    </main>
    
    <script>
        // Session timeout management
        let sessionTimer;
        let timeLeft = 5 * 60; // 5 minutes in seconds
        let warningShown = false;
        
        function updateSessionTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            const display = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            const timerElement = document.getElementById('timerDisplay');
            const sessionTimerElement = document.getElementById('sessionTimer');
            
            if (timerElement) {
                timerElement.textContent = display;
            }
            
            // Show timer when less than 2 minutes left
            if (timeLeft <= 120 && sessionTimerElement) {
                sessionTimerElement.style.display = 'block';
                
                // Add warning class when less than 1 minute
                if (timeLeft <= 60) {
                    sessionTimerElement.classList.add('warning');
                }
            }
            
            // Show warning at 1 minute
            if (timeLeft === 60 && !warningShown) {
                warningShown = true;
                if (confirm('Your session will expire in 1 minute due to inactivity. Click OK to extend your session.')) {
                    resetSessionTimer();
                }
            }
            
            // Auto logout at 0
            if (timeLeft <= 0) {
                alert('Your session has expired due to inactivity. You will be logged out.');
                window.location.href = '/logout';
                return;
            }
            
            timeLeft--;
        }
        
        function resetSessionTimer() {
            timeLeft = 5 * 60; // Reset to 5 minutes
            warningShown = false;
            
            const sessionTimerElement = document.getElementById('sessionTimer');
            if (sessionTimerElement) {
                sessionTimerElement.style.display = 'none';
                sessionTimerElement.classList.remove('warning');
            }
            
            // Send heartbeat to server to update session
            fetch('/heartbeat', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ heartbeat: true })
            }).catch(() => {
                // Ignore errors for heartbeat
            });
        }
        
        // Start session timer only if user is authenticated
        @auth
        sessionTimer = setInterval(updateSessionTimer, 1000);
        
        // Reset timer on user activity
        const resetEvents = ['click', 'keypress', 'scroll', 'mousemove', 'touchstart'];
        resetEvents.forEach(event => {
            document.addEventListener(event, resetSessionTimer);
        });
        @endauth
        
        // Handle CSRF token refresh
        function refreshCSRFToken() {
            fetch('/csrf-token')
                .then(response => response.json())
                .then(data => {
                    const token = data.token;
                    document.querySelector('meta[name="csrf-token"]').setAttribute('content', token);
                    
                    // Update all CSRF token inputs
                    document.querySelectorAll('input[name="_token"]').forEach(input => {
                        input.value = token;
                    });
                })
                .catch(() => {
                    // Ignore errors
                });
        }
        
        // Refresh CSRF token every 4 minutes
        setInterval(refreshCSRFToken, 4 * 60 * 1000);
    </script>
    
    @yield('scripts')
</body>
</html>
