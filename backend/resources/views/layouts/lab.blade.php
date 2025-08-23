<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Lab Technician - FHIR DICOM MedGemma')</title>
    @include('partials.global-styles')
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
            max-width: 1400px;
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .logo::before {
            content: "🧪";
            margin-right: 0.7rem;
            font-size: 2.2rem;
        }
        
        /* Lab-Specific Navigation */
        .lab-nav {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.15);
            padding: 0.8rem;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }
        .lab-nav-tab {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid transparent;
            cursor: pointer;
            background: none;
        }
        .lab-nav-tab:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateY(-1px);
        }
        .lab-nav-tab.active {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }
        .lab-nav-tab i {
            font-size: 1.1rem;
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            color: #fff;
            padding: 0.5rem 1.1rem;
            border-radius: 15px;
            text-decoration: none;
            font-size: 0.98rem;
            font-weight: 600;
            transition: all 0.3s;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.10);
        }
        .logout-btn:hover {
            background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2);
        }
        
        .main-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 1rem;
                padding: 0 1rem;
            }
            .lab-nav {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }
            .lab-nav-tab {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
            }
            .logo {
                font-size: 1.5rem;
            }
            .main-content {
                padding: 0 1rem;
            }
        }
        
        /* Tab Content Styling */
        .tab-content {
            margin-top: 2rem;
        }
        .tab-pane {
            display: none;
        }
        .tab-pane.active {
            display: block;
            animation: fadeIn 0.3s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Quick Actions Section */
        .quick-actions-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .quick-actions-section h2 {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .quick-action-card {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .quick-action-card:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .quick-action-card .action-icon {
            font-size: 2rem;
            min-width: 50px;
            text-align: center;
        }

        .quick-action-card .action-content h3 {
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .quick-action-card .action-content p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            margin: 0;
        }
    </style>
    @stack('head')
</head>
<body>
    <header class="header">
        <div class="header-container">
            <div class="logo">
                🧪 Lab Technician Portal
            </div>
            
            @auth
            <div class="user-info">
                <span>{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        🚪 Logout
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

    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert" style="background: rgba(76, 175, 80, 0.1); border: 1px solid rgba(76, 175, 80, 0.3); color: #4caf50;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert" style="background: rgba(244, 67, 54, 0.1); border: 1px solid rgba(244, 67, 54, 0.3); color: #f44336;">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show mt-3" role="alert" style="background: rgba(33, 150, 243, 0.1); border: 1px solid rgba(33, 150, 243, 0.3); color: #2196f3;">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    
    <script>
        // Lab Tab Functionality
        function initializeLabTabs() {
            const tabs = document.querySelectorAll('.lab-nav-tab');
            const tabPanes = document.querySelectorAll('.tab-pane');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs and panes
                    tabs.forEach(t => t.classList.remove('active'));
                    tabPanes.forEach(pane => {
                        pane.classList.remove('show', 'active');
                        pane.style.display = 'none';
                    });
                    
                    // Add active class to clicked tab
                    this.classList.add('active');
                    
                    // Show corresponding pane
                    const targetId = this.getAttribute('data-bs-target');
                    const targetPane = document.querySelector(targetId);
                    if (targetPane) {
                        targetPane.style.display = 'block';
                        targetPane.classList.add('show', 'active');
                        
                        // Trigger any load functions for the tab
                        const tabId = targetId.replace('#', '');
                        if (tabId === 'equipment') {
                            if (typeof loadEquipmentData === 'function') loadEquipmentData();
                        } else if (tabId === 'invoices') {
                            if (typeof loadLabInvoices === 'function') loadLabInvoices();
                        } else if (tabId === 'analytics') {
                            if (typeof loadAnalytics === 'function') loadAnalytics();
                        }
                    }
                });
            });
        }

        // Initialize tabs when document is ready
        document.addEventListener('DOMContentLoaded', function() {
            initializeLabTabs();
            if (typeof initializeDragAndDrop === 'function') {
                initializeDragAndDrop();
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
