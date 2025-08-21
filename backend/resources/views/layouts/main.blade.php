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
            position: relative;
        }
        .nav a.active, .nav a:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            transform: translateY(-2px) scale(1.04);
            box-shadow: 0 2px 8px rgba(102,126,234,0.10);
        }
        
        /* Lab Technician Navigation Styles */
        .custom-nav-tab {
            background: transparent !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            color: rgba(255, 255, 255, 0.8) !important;
            padding: 0.7rem 1.2rem !important;
            border-radius: 8px !important;
            text-decoration: none !important;
            font-weight: 500 !important;
            font-size: 0.95rem !important;
            transition: all 0.3s ease !important;
            display: flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            cursor: pointer !important;
        }
        
        .custom-nav-tab:hover {
            background: rgba(255, 255, 255, 0.15) !important;
            border-color: rgba(255, 255, 255, 0.4) !important;
            color: white !important;
            transform: translateY(-2px) !important;
        }
        
        .custom-nav-tab.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border-color: transparent !important;
            color: white !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3) !important;
            transform: translateY(-2px) scale(1.04) !important;
        }
        
        .custom-nav-tab i {
            font-size: 0.9rem;
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
                @auth
                    @if(\App\Helpers\RoleHelper::isLabTechnician(Auth::user()))
                        {{-- Lab Technician specific navigation --}}
                        <a href="/lab-tech" class="custom-nav-tab {{ request()->is('lab-tech') && !request()->has('hash') ? 'active' : '' }}" id="orders-tab" data-bs-toggle="pill" data-bs-target="#orders" data-target="orders">
                            <i class="fas fa-vials"></i>Dashboard
                        </a>
                        <a href="/lab-tech#equipment" class="custom-nav-tab lab-nav-link" id="equipment-tab" data-bs-toggle="pill" data-bs-target="#equipment" data-target="equipment">
                            <i class="fas fa-microscope"></i>Sampling & Results
                        </a>
                        <a href="/lab-tech#invoices" class="custom-nav-tab lab-nav-link" id="invoices-tab" data-bs-toggle="pill" data-bs-target="#invoices" data-target="invoices">
                            <i class="fas fa-flask"></i>Lab Financials
                        </a>
                        <a href="/lab-tech#analytics" class="custom-nav-tab lab-nav-link" id="analytics-tab" data-bs-toggle="pill" data-bs-target="#analytics" data-target="analytics">
                            <i class="fas fa-chart-line"></i>Configuration
                        </a>
                    @else
                        {{-- Standard navigation for other roles --}}
                        <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">Dashboard</a>
                        <a href="/patients" class="{{ request()->is('patients') ? 'active' : '' }}">Patients</a>
                        <a href="/medgemma" class="{{ request()->is('medgemma') ? 'active' : '' }}">AI Analysis</a>
                        <a href="/reports" class="{{ request()->is('reports') ? 'active' : '' }}">Reports</a>
                        <a href="/dicom-upload" class="{{ request()->is('dicom-upload') ? 'active' : '' }}">DICOM Upload</a>
                        <a href="{{ route('financial.doctor-dashboard') }}" class="{{ request()->is('financial/*') ? 'active' : '' }}">Financial</a>
                    @endif
                @else
                    {{-- Guest navigation --}}
                    <a href="/dashboard">Dashboard</a>
                    <a href="/patients">Patients</a>
                    <a href="/medgemma">AI Analysis</a>
                    <a href="/reports">Reports</a>
                    <a href="/dicom-upload">DICOM Upload</a>
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
        
        // Handle lab tech navigation hash links
        @auth
            @if(\App\Helpers\RoleHelper::isLabTechnician(Auth::user()))
                // Handle navigation for lab tech dashboard tabs
                document.addEventListener('DOMContentLoaded', function() {
                    // Enhanced navigation for main nav bar lab links
                    const labNavLinks = document.querySelectorAll('.custom-nav-tab');
                    
                    // Function to update active states in main navigation
                    function updateMainNavActiveStates(activeTarget) {
                        labNavLinks.forEach(link => {
                            const target = link.getAttribute('data-target') || link.getAttribute('data-bs-target')?.replace('#', '');
                            if (target === activeTarget) {
                                link.classList.add('active');
                            } else {
                                link.classList.remove('active');
                            }
                        });
                    }
                    
                    // Function to show/hide tab content
                    function showTabContent(targetId) {
                        // Hide all tab panes first
                        document.querySelectorAll('.tab-pane').forEach(pane => {
                            pane.classList.remove('show', 'active');
                            pane.style.display = 'none';
                        });
                        
                        // Show target pane
                        const targetPane = document.getElementById(targetId);
                        if (targetPane) {
                            targetPane.style.display = 'block';
                            targetPane.classList.add('show', 'active', 'fade-in');
                            
                            // Trigger any load functions for the tab
                            if (targetId === 'equipment') {
                                if (typeof loadEquipmentData === 'function') loadEquipmentData();
                            } else if (targetId === 'invoices') {
                                if (typeof loadLabInvoices === 'function') loadLabInvoices();
                            } else if (targetId === 'analytics') {
                                if (typeof loadAnalytics === 'function') loadAnalytics();
                            } else if (targetId === 'orders') {
                                if (typeof loadLabOrders === 'function') loadLabOrders();
                            }
                        }
                    }
                    
                    // Handle main navigation clicks
                    labNavLinks.forEach(link => {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            const target = this.getAttribute('data-target') || this.getAttribute('data-bs-target')?.replace('#', '');
                            
                            // Update URL if needed
                            if (target && target !== 'orders') {
                                history.pushState(null, '', '/lab-tech#' + target);
                            } else {
                                history.pushState(null, '', '/lab-tech');
                            }
                            
                            // Update active states and show content
                            updateMainNavActiveStates(target);
                            showTabContent(target);
                        });
                    });
                    
                    // Handle hash navigation on page load and hash change
                    function handleHashNavigation() {
                        const hash = window.location.hash.replace('#', '') || 'orders';
                        updateMainNavActiveStates(hash);
                        showTabContent(hash);
                    }
                    
                    // Check if we're on the lab tech dashboard and handle hash navigation
                    if (window.location.pathname === '/lab-tech') {
                        // Force proper initialization - hide all tabs first
                        document.querySelectorAll('.tab-pane').forEach(pane => {
                            pane.classList.remove('show', 'active');
                            pane.style.display = 'none';
                        });
                        
                        // Then show the correct tab
                        handleHashNavigation();
                        
                        // Listen for hash changes
                        window.addEventListener('hashchange', handleHashNavigation);
                    } else if (window.location.pathname === '/dashboard') {
                        // If on regular dashboard, make sure orders tab is active for lab techs
                        updateMainNavActiveStates('orders');
                    }
                });
            @endif
        @endauth
    </script>
    
    @yield('scripts')
</body>
</html>
