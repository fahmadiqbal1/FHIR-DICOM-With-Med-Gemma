<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Results Entry - Lab Technician</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%); 
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
        .sample-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #28a745;
        }
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
        }
        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #28a745;
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        .btn-primary {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
        }
        .result-entry {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
        }
        .modal-content {
            background: rgba(25, 25, 25, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="/lab-tech-dashboard">
                <i class="fas fa-keyboard me-2"></i>Results Entry
            </a>
            <div class="ms-auto">
                <button class="btn btn-outline-light btn-sm me-2" onclick="refreshSamples()">
                    <i class="fas fa-sync-alt me-1"></i>Refresh
                </button>
                <a href="/lab-tech-dashboard" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-hourglass-half fa-2x mb-2 text-warning"></i>
                    <h4 id="pendingResults">0</h4>
                    <small>Pending Results</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                    <h4 id="completedToday">0</h4>
                    <small>Completed Today</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2 text-danger"></i>
                    <h4 id="criticalResults">0</h4>
                    <small>Critical Results</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-clipboard-check fa-2x mb-2 text-info"></i>
                    <h4 id="qcPassed">0</h4>
                    <small>QC Passed</small>
                </div>
            </div>
        </div>

        <!-- Sample List for Results Entry -->
        <div class="row">
            <div class="col-lg-8">
                <div class="glass-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5><i class="fas fa-vial me-2"></i>Samples Awaiting Results</h5>
                        <div>
                            <select class="form-select form-select-sm" id="filterStatus" onchange="filterSamples()">
                                <option value="">All Samples</option>
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="completed">Completed</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                    </div>
                    <div id="samplesList">
                        <!-- Dynamic content loaded here -->
                        <div class="text-center p-4">
                            <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                            <p>Loading samples...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="col-lg-4">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-history me-2"></i>Recent Results</h5>
                    <div id="recentActivity">
                        <!-- Dynamic content -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Entry Modal -->
    <div class="modal fade" id="resultsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-keyboard me-2"></i>Enter Test Results
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="resultsForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Sample ID</label>
                                <input type="text" class="form-control" id="sampleId" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Test Name</label>
                                <input type="text" class="form-control" id="testName" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Patient Name</label>
                                <input type="text" class="form-control" id="patientName" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Normal Range</label>
                                <input type="text" class="form-control" id="normalRange" readonly>
                            </div>
                        </div>
                        
                        <div id="resultFields">
                            <!-- Dynamic result fields will be loaded here -->
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label">Additional Comments</label>
                                <textarea class="form-control" id="comments" rows="3" placeholder="Enter any additional observations or comments..."></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Quality Control</label>
                                <select class="form-select" id="qcStatus" required>
                                    <option value="">Select QC Status</option>
                                    <option value="passed">QC Passed</option>
                                    <option value="failed">QC Failed</option>
                                    <option value="repeated">Repeated</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Result Status</label>
                                <select class="form-select" id="resultStatus" required>
                                    <option value="">Select Status</option>
                                    <option value="normal">Normal</option>
                                    <option value="abnormal">Abnormal</option>
                                    <option value="critical">Critical</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveResults()">
                        <i class="fas fa-save me-2"></i>Save Results
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentSample = null;
        let samplesData = [];

        document.addEventListener('DOMContentLoaded', function() {
            loadSamples();
            loadStats();
            loadRecentActivity();
        });

        function loadSamples() {
            // Mock data - in production this would come from the API
            samplesData = [
                {
                    id: 'S001',
                    test: 'Complete Blood Count',
                    patient: 'John Smith',
                    mrn: 'MRN001',
                    status: 'pending',
                    priority: 'stat',
                    collected_at: '2025-08-23 08:30:00',
                    normal_range: '4.5-11.0 x10³/μL'
                },
                {
                    id: 'S002',
                    test: 'Liver Function Panel',
                    patient: 'Sarah Johnson',
                    mrn: 'MRN002',
                    status: 'processing',
                    priority: 'routine',
                    collected_at: '2025-08-23 09:15:00',
                    normal_range: 'ALT: 7-56 U/L, AST: 10-40 U/L'
                },
                {
                    id: 'S003',
                    test: 'Glucose Random',
                    patient: 'Mike Davis',
                    mrn: 'MRN003',
                    status: 'completed',
                    priority: 'routine',
                    collected_at: '2025-08-23 07:45:00',
                    normal_range: '70-140 mg/dL'
                }
            ];
            displaySamples(samplesData);
        }

        function displaySamples(samples) {
            const container = document.getElementById('samplesList');
            
            if (samples.length === 0) {
                container.innerHTML = `
                    <div class="text-center p-4">
                        <i class="fas fa-check-circle fa-2x mb-3 text-success"></i>
                        <p>No samples requiring results entry</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = samples.map(sample => `
                <div class="sample-card">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <h6 class="mb-1">
                                ${sample.id} - ${sample.test}
                                ${sample.priority === 'stat' ? '<span class="badge bg-danger ms-2">STAT</span>' : ''}
                            </h6>
                            <small class="text-info">Patient: ${sample.patient} (${sample.mrn})</small>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Collected: ${new Date(sample.collected_at).toLocaleString()}</small>
                        </div>
                        <div class="col-md-2">
                            <span class="badge bg-${getStatusColor(sample.status)}">${sample.status.toUpperCase()}</span>
                        </div>
                        <div class="col-md-3 text-end">
                            <button class="btn btn-primary btn-sm" onclick="enterResults('${sample.id}')" 
                                    ${sample.status === 'completed' ? 'disabled' : ''}>
                                <i class="fas fa-keyboard me-1"></i>
                                ${sample.status === 'completed' ? 'Completed' : 'Enter Results'}
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function getStatusColor(status) {
            const colors = {
                'pending': 'warning',
                'processing': 'info',
                'completed': 'success',
                'critical': 'danger'
            };
            return colors[status] || 'secondary';
        }

        function filterSamples() {
            const filter = document.getElementById('filterStatus').value;
            const filtered = filter ? samplesData.filter(s => s.status === filter) : samplesData;
            displaySamples(filtered);
        }

        function refreshSamples() {
            loadSamples();
            loadStats();
            showAlert('Samples refreshed!', 'info');
        }

        function enterResults(sampleId) {
            const sample = samplesData.find(s => s.id === sampleId);
            if (!sample) return;

            currentSample = sample;
            
            // Populate modal fields
            document.getElementById('sampleId').value = sample.id;
            document.getElementById('testName').value = sample.test;
            document.getElementById('patientName').value = sample.patient;
            document.getElementById('normalRange').value = sample.normal_range;
            
            // Generate result fields based on test type
            generateResultFields(sample.test);
            
            new bootstrap.Modal(document.getElementById('resultsModal')).show();
        }

        function generateResultFields(testName) {
            const container = document.getElementById('resultFields');
            
            // Different fields based on test type
            if (testName.includes('Blood Count')) {
                container.innerHTML = `
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">WBC Count (x10³/μL) *</label>
                            <input type="number" class="form-control" step="0.1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">RBC Count (x10⁶/μL) *</label>
                            <input type="number" class="form-control" step="0.01" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Hemoglobin (g/dL) *</label>
                            <input type="number" class="form-control" step="0.1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Platelet Count (x10³/μL) *</label>
                            <input type="number" class="form-control" step="1" required>
                        </div>
                    </div>
                `;
            } else if (testName.includes('Liver')) {
                container.innerHTML = `
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">ALT (U/L) *</label>
                            <input type="number" class="form-control" step="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">AST (U/L) *</label>
                            <input type="number" class="form-control" step="1" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Total Bilirubin (mg/dL) *</label>
                            <input type="number" class="form-control" step="0.1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Albumin (g/dL) *</label>
                            <input type="number" class="form-control" step="0.1" required>
                        </div>
                    </div>
                `;
            } else {
                // Generic single result field
                container.innerHTML = `
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label">Result Value *</label>
                            <input type="text" class="form-control" placeholder="Enter result value" required>
                        </div>
                    </div>
                `;
            }
        }

        function saveResults() {
            const form = document.getElementById('resultsForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // In production, this would send data to the API
            const resultData = {
                sample_id: currentSample.id,
                results: {},
                comments: document.getElementById('comments').value,
                qc_status: document.getElementById('qcStatus').value,
                result_status: document.getElementById('resultStatus').value,
                technician: 'Current User',
                entered_at: new Date().toISOString()
            };

            // Update sample status
            const sample = samplesData.find(s => s.id === currentSample.id);
            if (sample) {
                sample.status = 'completed';
            }

            showAlert('Results saved successfully!', 'success');
            bootstrap.Modal.getInstance(document.getElementById('resultsModal')).hide();
            displaySamples(samplesData);
            loadStats();
            loadRecentActivity();
        }

        function loadStats() {
            // Mock statistics
            document.getElementById('pendingResults').textContent = samplesData.filter(s => s.status === 'pending').length;
            document.getElementById('completedToday').textContent = samplesData.filter(s => s.status === 'completed').length;
            document.getElementById('criticalResults').textContent = '0';
            document.getElementById('qcPassed').textContent = samplesData.filter(s => s.status === 'completed').length;
        }

        function loadRecentActivity() {
            const recentContainer = document.getElementById('recentActivity');
            const recent = samplesData.filter(s => s.status === 'completed').slice(0, 5);
            
            recentContainer.innerHTML = recent.length ? recent.map(sample => `
                <div class="result-entry">
                    <small class="d-block"><strong>${sample.id}</strong> - ${sample.test}</small>
                    <small class="text-muted">${sample.patient}</small>
                    <small class="text-success d-block">
                        <i class="fas fa-check-circle me-1"></i>Completed
                    </small>
                </div>
            `).join('') : '<p class="text-muted">No recent results</p>';
        }

        function showAlert(message, type = 'info') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
            alertDiv.style.zIndex = '9999';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 5000);
        }
    </script>
</body>
</html>
