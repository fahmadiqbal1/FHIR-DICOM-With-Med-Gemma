<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Reports - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .glass-card { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 15px; color: white; }
        .navbar { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="/dashboard"><i class="fas fa-chart-bar me-2"></i>System Reports</a>
            <div class="ms-auto"><a href="/dashboard" class="btn btn-outline-light btn-sm"><i class="fas fa-arrow-left me-1"></i>Back</a></div>
        </div>
    </nav>
    <div class="container-fluid px-4 py-4">
        <div class="glass-card p-4">
            <h3><i class="fas fa-chart-bar me-2"></i>Comprehensive System Reports</h3>
            <p>Generate detailed reports on all system activities, user performance, and financial metrics.</p>
            <div class="row mt-4">
                <div class="col-md-4 mb-3">
                    <div class="card bg-transparent border-light">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <h5>User Activity</h5>
                            <button class="btn btn-primary">Generate</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-transparent border-light">
                        <div class="card-body text-center">
                            <i class="fas fa-dollar-sign fa-2x mb-2"></i>
                            <h5>Financial Summary</h5>
                            <button class="btn btn-primary">Generate</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-transparent border-light">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-line fa-2x mb-2"></i>
                            <h5>Performance Metrics</h5>
                            <button class="btn btn-primary">Generate</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
