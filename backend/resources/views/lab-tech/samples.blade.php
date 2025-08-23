<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample Processing - Lab Technician</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); min-height: 100vh; }
        .glass-card { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 15px; color: white; }
        .navbar { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); }
        .sample-item { background: rgba(255, 255, 255, 0.05); border-radius: 8px; padding: 15px; margin-bottom: 10px; border-left: 4px solid #28a745; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="/lab-tech-dashboard"><i class="fas fa-vial me-2"></i>Sample Processing</a>
            <div class="ms-auto">
                <a href="/lab-tech-dashboard" class="btn btn-outline-light btn-sm"><i class="fas fa-arrow-left me-1"></i>Back</a>
            </div>
        </div>
    </nav>
    <div class="container-fluid px-4 py-4">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-vial fa-2x mb-2 text-warning"></i>
                    <h4 id="pendingSamples">-</h4>
                    <small>Pending Samples</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-flask fa-2x mb-2 text-info"></i>
                    <h4 id="inProgress">-</h4>
                    <small>In Progress</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                    <h4 id="completedToday">-</h4>
                    <small>Completed Today</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2 text-danger"></i>
                    <h4 id="urgentSamples">-</h4>
                    <small>STAT Orders</small>
                </div>
            </div>
        </div>
        
        <div class="glass-card p-4">
            <h5 class="mb-3"><i class="fas fa-list me-2"></i>Sample Queue</h5>
            <div id="sampleQueue">
                <div class="text-center p-4">
                    <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                    <p>Loading samples...</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadSamples();
        });

        async function loadSamples() {
            try {
                const response = await fetch('/api/lab-tech/samples');
                const data = await response.json();
                const samples = data.samples || [];
                
                displaySamples(samples);
                updateStats(samples);
            } catch (error) {
                console.error('Error loading samples:', error);
                document.getElementById('sampleQueue').innerHTML = `
                    <div class="text-center p-4">
                        <i class="fas fa-exclamation-triangle fa-2x mb-3 text-warning"></i>
                        <p>Error loading sample data</p>
                    </div>
                `;
            }
        }

        function displaySamples(samples) {
            const container = document.getElementById('sampleQueue');
            
            if (samples.length === 0) {
                container.innerHTML = `<div class="text-center p-4"><i class="fas fa-vial fa-2x mb-3 opacity-50"></i><p>No samples found</p></div>`;
                return;
            }

            container.innerHTML = samples.map(sample => `
                <div class="sample-item">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <h6 class="mb-1">${sample.patient}</h6>
                            <small class="text-info">ID: ${sample.id}</small>
                        </div>
                        <div class="col-md-2">
                            <strong class="d-block">${sample.test_type}</strong>
                            <small class="text-muted">Test</small>
                        </div>
                        <div class="col-md-2">
                            <strong class="d-block">${sample.collection_time}</strong>
                            <small class="text-muted">Collected</small>
                        </div>
                        <div class="col-md-2">
                            <span class="badge bg-${sample.priority === 'stat' ? 'danger' : sample.priority === 'urgent' ? 'warning' : 'info'}">${sample.priority.toUpperCase()}</span>
                        </div>
                        <div class="col-md-2">
                            <span class="badge bg-${sample.status === 'completed' ? 'success' : sample.status === 'in_progress' ? 'info' : 'warning'}">${sample.status.replace('_', ' ').toUpperCase()}</span>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-outline-light btn-sm" onclick="processSample(${sample.id})" title="Process">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function updateStats(samples) {
            const pending = samples.filter(s => s.status === 'pending').length;
            const inProgress = samples.filter(s => s.status === 'in_progress').length;
            const completed = samples.filter(s => s.status === 'completed').length;
            const urgent = samples.filter(s => s.priority === 'stat').length;
            
            document.getElementById('pendingSamples').textContent = pending;
            document.getElementById('inProgress').textContent = inProgress;
            document.getElementById('completedToday').textContent = completed;
            document.getElementById('urgentSamples').textContent = urgent;
        }

        function processSample(id) {
            alert(`Processing sample ${id}...`);
        }
    </script>
</body>
</html>
