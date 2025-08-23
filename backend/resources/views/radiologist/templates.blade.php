<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Report Templates' }} - Radiologist Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            color: white;
        }
        .navbar {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="/radiologist-dashboard">
                <i class="fas fa-file-alt me-2"></i>{{ $title ?? 'Report Templates' }}
            </a>
            <div class="ms-auto">
                <a href="/radiologist-dashboard" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <div class="row">
            <div class="col-12">
                <div class="glass-card p-5 text-center">
                    <i class="fas fa-file-alt fa-3x mb-4 text-info"></i>
                    <h2 class="mb-3">{{ $title ?? 'Report Templates' }}</h2>
                    <p class="lead mb-4">{{ $message ?? 'Standard radiology report templates and structured reporting' }}</p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Template management system is under development. Structured reporting templates will be available soon.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
