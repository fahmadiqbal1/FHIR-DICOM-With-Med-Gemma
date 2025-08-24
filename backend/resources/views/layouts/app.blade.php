<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aviva Healthcare Platform')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #f8fafc 0%, #e9ecef 100%); min-height: 100vh; }
        .navbar-brand { font-weight: bold; letter-spacing: 1px; }
        .navbar { 
            box-shadow: 0 4px 15px rgba(102,126,234,0.15); 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: none;
            padding: 1rem 0;
        }
        .navbar-brand:hover {
            transform: translateY(-1px);
            transition: all 0.3s ease;
        }
        .nav-link {
            font-weight: 500;
            letter-spacing: 0.5px;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-1px);
        }
        .card { 
            border-radius: 1rem; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: none;
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.95);
        }
        .btn-primary, .bg-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; border: none; }
        .btn-outline-primary { border-color: #667eea; color: #667eea; }
        .btn-outline-primary:hover { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border-color: #667eea; }
        .alert-info { background: linear-gradient(135deg, #e3f2fd 0%, #f0f4ff 100%); color: #667eea; border: none; }
        .alert-success { background: linear-gradient(135deg, #e6f4ea 0%, #f0f9f2 100%); color: #256029; border: none; }
        .alert-danger { background: linear-gradient(135deg, #fbe9e7 0%, #ffebe8 100%); color: #b71c1c; border: none; }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('head')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="/">
            <img src="{{ asset('images/viva-healthcare-logo.png') }}" alt="Aviva Healthcare Logo" 
                 style="height: 40px; margin-right: 12px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <div>
                <div style="font-size: 1.2rem; font-weight: 700; letter-spacing: 1px;">Aviva Healthcare</div>
                <div style="font-size: 0.75rem; opacity: 0.9; letter-spacing: 0.5px; margin-top: -2px;">Medical Platform</div>
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="/dashboard">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="/patients">Patients</a></li>
                <li class="nav-item"><a class="nav-link" href="/medgemma">MedGemma AI</a></li>
                <li class="nav-item"><a class="nav-link" href="/dicom-upload">DICOM Upload</a></li>
                <li class="nav-item"><a class="nav-link" href="/reports">Reports</a></li>
                <li class="nav-item"><a class="nav-link" href="/help">Help</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
