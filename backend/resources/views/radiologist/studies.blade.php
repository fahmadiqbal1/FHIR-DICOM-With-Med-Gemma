<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imaging Studies - Radiologist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%); min-height: 100vh; }
        .glass-card { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 15px; color: white; }
        .navbar { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); }
        .study-item { background: rgba(255, 255, 255, 0.05); border-radius: 8px; padding: 15px; margin-bottom: 10px; border-left: 4px solid #6f42c1; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="/radiologist-dashboard"><i class="fas fa-x-ray me-2"></i>Imaging Studies</a>
            <div class="ms-auto"><a href="/radiologist-dashboard" class="btn btn-outline-light btn-sm"><i class="fas fa-arrow-left me-1"></i>Back</a></div>
        </div>
    </nav>
    <div class="container-fluid px-4 py-4">
        <div class="glass-card p-4">
            <h5 class="mb-3"><i class="fas fa-list me-2"></i>Study Queue</h5>
            <div id="studyList">Loading studies...</div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            try {
                const response = await fetch('/api/radiologist/studies');
                const data = await response.json();
                const studies = data.studies || [];
                
                document.getElementById('studyList').innerHTML = studies.map(study => `
                    <div class="study-item">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <h6 class="mb-1">${study.patient}</h6>
                                <small class="text-info">ID: ${study.id}</small>
                            </div>
                            <div class="col-md-2">
                                <strong class="d-block">${study.study_type}</strong>
                                <small class="text-muted">Study</small>
                            </div>
                            <div class="col-md-2">
                                <strong class="d-block">${study.scheduled_time}</strong>
                                <small class="text-muted">Scheduled</small>
                            </div>
                            <div class="col-md-2">
                                <span class="badge bg-${study.priority === 'stat' ? 'danger' : study.priority === 'urgent' ? 'warning' : 'info'}">${study.priority.toUpperCase()}</span>
                            </div>
                            <div class="col-md-2">
                                <span class="badge bg-${study.status === 'completed' ? 'success' : study.status === 'in_progress' ? 'info' : 'warning'}">${study.status.replace('_', ' ').toUpperCase()}</span>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-outline-light btn-sm" onclick="openViewer(${study.id})" title="Open DICOM Viewer">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');
            } catch (error) {
                document.getElementById('studyList').innerHTML = '<div class="text-center p-4"><i class="fas fa-exclamation-triangle fa-2x mb-3 text-warning"></i><p>Error loading studies</p></div>';
            }
        });

        function openViewer(studyId) {
            window.location.href = `/radiologist/viewer?study=${studyId}`;
        }
    </script>
</body>
</html>
