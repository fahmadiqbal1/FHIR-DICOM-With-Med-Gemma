<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'MedGemma Healthcare Platform'))</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        body { background: #f8fafc; }
        .navbar-brand { font-weight: bold; letter-spacing: 1px; }
        .navbar { box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
        .card { border-radius: 0.75rem; }
        .btn-primary, .bg-primary { background: #0056b3 !important; }
        .btn-outline-primary { border-color: #0056b3; color: #0056b3; }
        .btn-outline-primary:hover { background: #0056b3; color: #fff; }
        .alert-info { background: #e3f2fd; color: #0056b3; }
        .alert-success { background: #e6f4ea; color: #256029; }
        .alert-danger { background: #fbe9e7; color: #b71c1c; }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('head')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">{{ config('app.name', 'MedGemma Healthcare Platform') }}</a>
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
