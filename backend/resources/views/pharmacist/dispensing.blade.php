<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medication Dispensing - Pharmacist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%); min-height: 100vh; }
        .glass-card { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 15px; color: white; }
        .navbar { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="/pharmacist-dashboard"><i class="fas fa-hand-holding-medical me-2"></i>Medication Dispensing</a>
            <div class="ms-auto">
                <a href="/pharmacist-dashboard" class="btn btn-outline-light btn-sm"><i class="fas fa-arrow-left me-1"></i>Back</a>
            </div>
        </div>
    </nav>
    <div class="container-fluid px-4 py-4">
        <div class="glass-card p-4">
            <h3><i class="fas fa-hand-holding-medical me-2"></i>Medication Dispensing Interface</h3>
            <p>This comprehensive dispensing interface allows pharmacists to:</p>
            <ul>
                <li>Scan prescription barcodes for quick processing</li>
                <li>Verify patient identity and insurance information</li>
                <li>Check drug interactions and allergies</li>
                <li>Print medication labels with proper instructions</li>
                <li>Process payments and insurance claims</li>
                <li>Generate patient medication guides</li>
            </ul>
            <div class="text-center mt-4">
                <button class="btn btn-primary me-2" onclick="window.location.href='/pharmacist/prescriptions'">
                    <i class="fas fa-prescription me-1"></i>Go to Prescription Queue
                </button>
            </div>
        </div>
    </div>
</body>
</html>
