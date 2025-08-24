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
        
        /* Fix dropdown z-index issues */
        .dropdown-menu, .dropdown {
            z-index: 9999 !important;
            position: relative !important;
        }
        
        .dropdown-menu {
            background: white !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2) !important;
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
        }
        
        .dropdown-item {
            color: #333 !important;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa !important;
            color: #333 !important;
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
                <img src="data:image/png;base64,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" style="height: 40px;" alt="Healthcare Platform Logo">
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
