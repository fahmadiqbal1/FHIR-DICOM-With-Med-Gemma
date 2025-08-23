<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DICOM Viewer - Radiologist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%); min-height: 100vh; }
        .glass-card { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 15px; color: white; }
        .navbar { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); }
        .viewer-panel { background: rgba(0, 0, 0, 0.8); border-radius: 8px; padding: 20px; min-height: 500px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="/radiologist-dashboard"><i class="fas fa-eye me-2"></i>DICOM Viewer</a>
            <div class="ms-auto">
                <button class="btn btn-outline-light btn-sm me-2" onclick="window.print()"><i class="fas fa-print me-1"></i>Print</button>
                <a href="/radiologist/studies" class="btn btn-outline-light btn-sm"><i class="fas fa-arrow-left me-1"></i>Back to Studies</a>
            </div>
        </div>
    </nav>
    <div class="container-fluid px-4 py-4">
        <div class="row">
            <div class="col-md-9">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-image me-2"></i>DICOM Image Viewer</h5>
                    <div class="viewer-panel text-center">
                        <div class="mt-5 pt-5">
                            <i class="fas fa-x-ray fa-4x mb-3 opacity-50"></i>
                            <h4>Professional DICOM Viewer</h4>
                            <p class="mb-4">Advanced medical imaging viewer with measurement tools, annotations, and AI-assisted analysis.</p>
                            <div class="btn-toolbar justify-content-center">
                                <button class="btn btn-primary me-2"><i class="fas fa-search-plus me-1"></i>Zoom In</button>
                                <button class="btn btn-primary me-2"><i class="fas fa-search-minus me-1"></i>Zoom Out</button>
                                <button class="btn btn-primary me-2"><i class="fas fa-ruler me-1"></i>Measure</button>
                                <button class="btn btn-primary me-2"><i class="fas fa-palette me-1"></i>Window/Level</button>
                                <button class="btn btn-success"><i class="fas fa-robot me-1"></i>AI Analysis</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-4">
                    <h6><i class="fas fa-info-circle me-2"></i>Study Information</h6>
                    <p><strong>Patient:</strong> John Doe<br>
                    <strong>Study Date:</strong> 2025-08-23<br>
                    <strong>Modality:</strong> CT<br>
                    <strong>Body Part:</strong> Chest</p>
                    
                    <h6 class="mt-4"><i class="fas fa-edit me-2"></i>Report</h6>
                    <textarea class="form-control mb-2" rows="8" placeholder="Enter radiology report..."></textarea>
                    <button class="btn btn-success w-100"><i class="fas fa-save me-1"></i>Save Report</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
