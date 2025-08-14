@extends('layouts.main')
@section('title', 'MedGemma AI Analysis')
@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-3">
                <i class="fas fa-brain text-primary me-2"></i>
                MedGemma AI Analysis
            </h2>
            <p class="text-muted">Advanced medical AI powered by Google Health's MedGemma models for imaging, lab analysis, and second opinions.</p>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-outline-primary" id="refresh-status" title="Refresh Status">
                <i class="fas fa-sync-alt"></i> Status
            </button>
        </div>
    </div>

    <!-- Server Status Card -->
    <div class="card mb-4" id="status-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-server me-2"></i>
                MedGemma Server Status
            </h5>
            <div id="status-indicator" class="badge bg-warning">
                <i class="fas fa-circle me-1"></i>
                <span id="status-text">Checking...</span>
            </div>
        </div>
        <div class="card-body" id="status-details" style="display:none;">
            <div class="row">
                <div class="col-md-3">
                    <strong>Model Status:</strong><br>
                    <span id="model-status" class="text-muted">Unknown</span>
                </div>
                <div class="col-md-3">
                    <strong>Capabilities:</strong><br>
                    <span id="capabilities" class="text-muted">Loading...</span>
                </div>
                <div class="col-md-3">
                    <strong>Model:</strong><br>
                    <span id="model-info" class="text-muted">--</span>
                </div>
                <div class="col-md-3">
                    <strong>Endpoint:</strong><br>
                    <span id="endpoint-info" class="text-muted">--</span>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Capabilities Overview -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card h-100 border-primary">
                <div class="card-body text-center">
                    <i class="fas fa-images fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Medical Imaging</h5>
                    <p class="card-text">CT, MRI, X-ray analysis with multimodal AI capabilities</p>
                    <button class="btn btn-primary demo-btn" data-demo="imaging">Demo Analysis</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-success">
                <div class="card-body text-center">
                    <i class="fas fa-flask fa-3x text-success mb-3"></i>
                    <h5 class="card-title">Laboratory Analysis</h5>
                    <p class="card-text">Clinical lab interpretation with intelligent recommendations</p>
                    <button class="btn btn-success demo-btn" data-demo="labs">Demo Analysis</button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-warning">
                <div class="card-body text-center">
                    <i class="fas fa-user-md fa-3x text-warning mb-3"></i>
                    <h5 class="card-title">Second Opinion</h5>
                    <p class="card-text">Comprehensive case review combining all available data</p>
                    <button class="btn btn-warning demo-btn" data-demo="second-opinion">Demo Analysis</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Patient Selection and Analysis -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-user-circle me-2"></i>
                Patient Analysis
            </h5>
        </div>
        <div class="card-body">
            <form id="medgemma-form" class="row g-3">
                <div class="col-md-4">
                    <label for="patient_id" class="form-label">
                        <i class="fas fa-user me-1"></i>
                        Patient
                    </label>
                    <select class="form-select" id="patient_id" name="patient_id" required>
                        <option value="">Select patient...</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="study_id" class="form-label">
                        <i class="fas fa-x-ray me-1"></i>
                        Imaging Study
                    </label>
                    <select class="form-select" id="study_id" name="study_id" required disabled>
                        <option value="">Select study...</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100" id="analyze-btn" disabled>
                        <i class="fas fa-search me-1"></i>
                        Analyze Imaging
                    </button>
                </div>
            </form>
            
            <div class="row mt-3">
                <div class="col-md-6">
                    <button class="btn btn-outline-success w-100" id="analyze-labs-btn" disabled>
                        <i class="fas fa-vial me-1"></i>
                        Analyze Patient Labs
                    </button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-outline-info w-100" id="second-opinion-btn" disabled>
                        <i class="fas fa-stethoscope me-1"></i>
                        Comprehensive Second Opinion
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Text Analysis -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-comments me-2"></i>
                Quick Text Analysis
            </h5>
        </div>
        <div class="card-body">
            <form id="text-analysis-form">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="analysis-text" class="form-label">Medical Text or Clinical Notes</label>
                        <textarea id="analysis-text" name="text" rows="3" class="form-control" 
                            placeholder="Enter clinical notes, symptoms, or medical text for AI analysis..."></textarea>
                    </div>
                    <div class="col-md-2">
                        <label for="analysis-context" class="form-label">Context</label>
                        <select id="analysis-context" name="context" class="form-select">
                            <option value="medical">Medical</option>
                            <option value="clinical">Clinical</option>
                            <option value="diagnostic">Diagnostic</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-brain me-1"></i>
                            Analyze
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Area -->
    <div id="ai-result" style="display:none">
        <div class="card border-success mb-4">
            <div class="card-header bg-success text-white">
                <i class="fas fa-check-circle me-2"></i>
                AI Imaging Analysis Result
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="card-title">Clinical Impression</h5>
                        <p id="impression" class="alert alert-light"></p>
                        
                        <h6><i class="fas fa-eye me-1"></i> Key Findings</h6>
                        <ul id="findings" class="list-group list-group-flush mb-3"></ul>
                        
                        <h6><i class="fas fa-lightbulb me-1"></i> Recommendations</h6>
                        <ul id="recommendations" class="list-group list-group-flush"></ul>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="mb-3">
                                <span class="badge bg-info fs-6">
                                    <i class="fas fa-percentage me-1"></i>
                                    Confidence: <span id="confidence">--</span>
                                </span>
                            </div>
                            <div class="alert alert-info">
                                <small><i class="fas fa-info-circle me-1"></i>
                                This analysis is provided for educational purposes and should not replace professional medical judgment.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="labs-result" style="display:none">
        <div class="card border-primary mb-4">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-flask me-2"></i>
                Laboratory Analysis Result
            </div>
            <div class="card-body">
                <h6><i class="fas fa-comments me-1"></i> AI Lab Comments</h6>
                <ul id="lab-comments" class="list-group list-group-flush"></ul>
            </div>
        </div>
    </div>

    <div id="second-opinion-result" style="display:none">
        <div class="card border-warning mb-4">
            <div class="card-header bg-warning text-dark">
                <i class="fas fa-user-md me-2"></i>
                Comprehensive Second Opinion
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <h6><i class="fas fa-images me-1"></i> Imaging Analysis</h6>
                        <ul id="so-imaging" class="list-group list-group-flush mb-3"></ul>
                    </div>
                    <div class="col-md-4">
                        <h6><i class="fas fa-flask me-1"></i> Laboratory Findings</h6>
                        <ul id="so-labs" class="list-group list-group-flush mb-3"></ul>
                    </div>
                    <div class="col-md-4">
                        <h6><i class="fas fa-pills me-1"></i> Medications</h6>
                        <ul id="so-meds" class="list-group list-group-flush mb-3"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="text-analysis-result" style="display:none">
        <div class="card border-info mb-4">
            <div class="card-header bg-info text-white">
                <i class="fas fa-brain me-2"></i>
                Text Analysis Result
            </div>
            <div class="card-body">
                <div id="text-analysis-content"></div>
            </div>
        </div>
    </div>

    <!-- Demo Modal -->
    <div class="modal fade" id="demo-modal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="demo-modal-title">Demo Analysis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="demo-modal-content">
                    <!-- Demo content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <div id="ai-error" class="alert alert-danger" style="display:none">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <span id="error-message"></span>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize on page load
    checkServerStatus();
    loadPatients();
    
    // Event Listeners
    document.getElementById('refresh-status').addEventListener('click', checkServerStatus);
    document.getElementById('patient_id').addEventListener('change', handlePatientChange);
    document.getElementById('study_id').addEventListener('change', handleStudyChange);
    document.getElementById('medgemma-form').addEventListener('submit', handleImagingAnalysis);
    document.getElementById('analyze-labs-btn').addEventListener('click', handleLabsAnalysis);
    document.getElementById('second-opinion-btn').addEventListener('click', handleSecondOpinion);
    document.getElementById('text-analysis-form').addEventListener('submit', handleTextAnalysis);
    
    // Demo buttons
    document.querySelectorAll('.demo-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            showDemo(this.dataset.demo);
        });
    });

    function checkServerStatus() {
        const indicator = document.getElementById('status-indicator');
        const statusText = document.getElementById('status-text');
        const details = document.getElementById('status-details');
        
        // Show loading state
        indicator.className = 'badge bg-warning';
        indicator.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i><span>Checking...</span>';
        
        fetch('/medgemma/status')
            .then(response => response.json())
            .then(data => {
                if (data.enabled && data.server_status.status === 'online') {
                    indicator.className = 'badge bg-success';
                    indicator.innerHTML = '<i class="fas fa-check-circle me-1"></i><span>Online</span>';
                    
                    document.getElementById('model-status').textContent = 
                        data.server_status.data?.model_loaded ? 'Loaded' : 'Not Loaded';
                    document.getElementById('capabilities').textContent = 
                        Object.keys(data.capabilities).filter(k => data.capabilities[k]).join(', ');
                    document.getElementById('model-info').textContent = data.model || 'medgemma-4b-it';
                    document.getElementById('endpoint-info').textContent = data.endpoint || 'Local Server';
                    
                    details.style.display = 'block';
                } else {
                    indicator.className = 'badge bg-danger';
                    indicator.innerHTML = '<i class="fas fa-times-circle me-1"></i><span>Offline</span>';
                    details.style.display = 'none';
                }
            })
            .catch(error => {
                indicator.className = 'badge bg-danger';
                indicator.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i><span>Error</span>';
                details.style.display = 'none';
                console.error('Status check failed:', error);
            });
    }

    function loadPatients() {
        fetch('/api/patients')
            .then(res => res.json())
            .then(data => {
                const sel = document.getElementById('patient_id');
                sel.innerHTML = '<option value="">Select patient...</option>';
                data.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.id;
                    opt.textContent = `${p.first_name} ${p.last_name} (MRN: ${p.mrn})`;
                    sel.appendChild(opt);
                });
            })
            .catch(error => {
                console.error('Failed to load patients:', error);
                showError('Failed to load patients');
            });
    }

    function handlePatientChange() {
        const patientId = this.value;
        const studySel = document.getElementById('study_id');
        
        // Reset study selection
        studySel.innerHTML = '<option value="">Select study...</option>';
        studySel.disabled = true;
        document.getElementById('analyze-btn').disabled = true;
        
        // Enable/disable patient-level buttons
        document.getElementById('analyze-labs-btn').disabled = !patientId;
        document.getElementById('second-opinion-btn').disabled = !patientId;
        
        if (patientId) {
            fetch(`/api/patients/${patientId}/studies`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(s => {
                        const opt = document.createElement('option');
                        opt.value = s.id;
                        opt.textContent = `${s.modality} - ${s.description}`;
                        studySel.appendChild(opt);
                    });
                    studySel.disabled = false;
                })
                .catch(error => {
                    console.error('Failed to load studies:', error);
                    showError('Failed to load imaging studies');
                });
        }
    }

    function handleStudyChange() {
        document.getElementById('analyze-btn').disabled = !this.value;
    }

    function handleImagingAnalysis(e) {
        e.preventDefault();
        hideAllResults();
        
        const patientId = document.getElementById('patient_id').value;
        const studyId = document.getElementById('study_id').value;
        
        if (!patientId || !studyId) return;
        
        const btn = document.getElementById('analyze-btn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Analyzing...';
        
        fetch(`/medgemma/analyze/imaging/${studyId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                showError(data.error);
            } else {
                document.getElementById('impression').textContent = data.impression || 'No specific impression provided';
                
                const findingsList = document.getElementById('findings');
                findingsList.innerHTML = '';
                (data.findings || []).forEach(f => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item';
                    li.textContent = f;
                    findingsList.appendChild(li);
                });
                
                const recsList = document.getElementById('recommendations');
                recsList.innerHTML = '';
                (data.recommendations || []).forEach(r => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item';
                    li.textContent = r;
                    recsList.appendChild(li);
                });
                
                document.getElementById('confidence').textContent = 
                    data.confidence ? (data.confidence * 100).toFixed(1) + '%' : '--';
                
                document.getElementById('ai-result').style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Analysis failed:', error);
            showError('Failed to contact MedGemma AI service');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-search me-1"></i>Analyze Imaging';
        });
    }

    function handleLabsAnalysis() {
        const patientId = document.getElementById('patient_id').value;
        if (!patientId) return;
        
        hideAllResults();
        const btn = document.getElementById('analyze-labs-btn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Analyzing...';
        
        fetch(`/medgemma/analyze/labs/${patientId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.lab_comments) {
                const commentsList = document.getElementById('lab-comments');
                commentsList.innerHTML = '';
                data.lab_comments.forEach(c => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item';
                    li.textContent = c;
                    commentsList.appendChild(li);
                });
                document.getElementById('labs-result').style.display = 'block';
            } else {
                showError(data.error || 'No lab comments available');
            }
        })
        .catch(error => {
            console.error('Lab analysis failed:', error);
            showError('Failed to contact MedGemma AI service');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-vial me-1"></i>Analyze Patient Labs';
        });
    }

    function handleSecondOpinion() {
        const patientId = document.getElementById('patient_id').value;
        if (!patientId) return;
        
        hideAllResults();
        const btn = document.getElementById('second-opinion-btn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Analyzing...';
        
        fetch(`/medgemma/second-opinion/${patientId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.imaging || data.labs || data.medications) {
                const imagingList = document.getElementById('so-imaging');
                imagingList.innerHTML = '';
                (data.imaging || []).forEach(i => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item';
                    li.textContent = i.impression || i;
                    imagingList.appendChild(li);
                });
                
                const labsList = document.getElementById('so-labs');
                labsList.innerHTML = '';
                (data.labs || []).forEach(l => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item';
                    li.textContent = l;
                    labsList.appendChild(li);
                });
                
                const medsList = document.getElementById('so-meds');
                medsList.innerHTML = '';
                (data.medications || []).forEach(m => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item';
                    li.textContent = `${m.medication || m} ${m.dosage ? `(${m.dosage})` : ''}`;
                    medsList.appendChild(li);
                });
                
                document.getElementById('second-opinion-result').style.display = 'block';
            } else {
                showError(data.error || 'No second opinion data available');
            }
        })
        .catch(error => {
            console.error('Second opinion failed:', error);
            showError('Failed to contact MedGemma AI service');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-stethoscope me-1"></i>Comprehensive Second Opinion';
        });
    }

    function handleTextAnalysis(e) {
        e.preventDefault();
        
        const form = e.target;
        const formData = new FormData(form);
        const text = formData.get('text');
        const context = formData.get('context');
        
        if (!text.trim()) {
            alert('Please enter some text to analyze');
            return;
        }
        
        hideAllResults();
        const btn = form.querySelector('button[type="submit"]');
        const originalContent = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Analyzing...';
        
        fetch('/medgemma/analyze/text', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                text: text,
                context: context,
                task: 'analysis'
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const content = document.getElementById('text-analysis-content');
                content.innerHTML = `
                    <div class="mb-3">
                        <h6><i class="fas fa-brain me-1"></i> AI Analysis:</h6>
                        <div class="alert alert-light">${data.analysis}</div>
                    </div>
                    ${data.recommendations && data.recommendations.length > 0 ? `
                        <div class="mb-3">
                            <h6><i class="fas fa-lightbulb me-1"></i> Recommendations:</h6>
                            <ul class="list-group list-group-flush">
                                ${data.recommendations.map(rec => `<li class="list-group-item">${rec}</li>`).join('')}
                            </ul>
                        </div>
                    ` : ''}
                    ${data.note ? `<small class="text-muted"><i class="fas fa-info-circle me-1"></i>${data.note}</small>` : ''}
                `;
                document.getElementById('text-analysis-result').style.display = 'block';
            } else {
                showError(data.error || 'Text analysis failed');
            }
        })
        .catch(error => {
            console.error('Text analysis failed:', error);
            showError('Failed to analyze text');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalContent;
        });
    }

    function showDemo(type) {
        const demos = {
            imaging: {
                title: 'Medical Imaging Analysis Demo',
                content: `
                    <div class="alert alert-primary">
                        <h6><i class="fas fa-images me-1"></i> Sample: Chest X-ray Analysis</h6>
                        <p><strong>Clinical Impression:</strong> Normal chest X-ray without acute cardiopulmonary abnormalities.</p>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Key Findings:</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Normal heart size and contour</li>
                                <li class="list-group-item">Lungs clear bilaterally</li>
                                <li class="list-group-item">No acute bony abnormalities</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Recommendations:</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Clinical correlation recommended</li>
                                <li class="list-group-item">Follow-up if symptoms persist</li>
                            </ul>
                        </div>
                    </div>
                `
            },
            labs: {
                title: 'Laboratory Analysis Demo',
                content: `
                    <div class="alert alert-success">
                        <h6><i class="fas fa-flask me-1"></i> Sample: Complete Blood Count</h6>
                        <p><strong>Summary:</strong> Most values within normal limits, elevated glucose noted.</p>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Lab Values:</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Hemoglobin: 14.2 g/dL (Normal)</li>
                                <li class="list-group-item">WBC: 7,500/μL (Normal)</li>
                                <li class="list-group-item text-warning">Glucose: 180 mg/dL (High)</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>AI Comments:</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Consider HbA1c testing</li>
                                <li class="list-group-item">Dietary counseling recommended</li>
                                <li class="list-group-item">3-month follow-up suggested</li>
                            </ul>
                        </div>
                    </div>
                `
            },
            'second-opinion': {
                title: 'Second Opinion Demo',
                content: `
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-user-md me-1"></i> Comprehensive Case Review</h6>
                        <p><strong>Patient:</strong> 45-year-old male with chest pain</p>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <h6>Imaging:</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Normal chest X-ray</li>
                                <li class="list-group-item">Mild coronary calcification</li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h6>Labs:</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Elevated cholesterol</li>
                                <li class="list-group-item">Normal troponins</li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h6>Recommendations:</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Consider stress testing</li>
                                <li class="list-group-item">Lipid management</li>
                            </ul>
                        </div>
                    </div>
                `
            }
        };
        
        const modalTitle = document.getElementById('demo-modal-title');
        const modalContent = document.getElementById('demo-modal-content');
        
        modalTitle.textContent = demos[type].title;
        modalContent.innerHTML = demos[type].content;
        
        new bootstrap.Modal(document.getElementById('demo-modal')).show();
    }

    function hideAllResults() {
        document.getElementById('ai-result').style.display = 'none';
        document.getElementById('labs-result').style.display = 'none';
        document.getElementById('second-opinion-result').style.display = 'none';
        document.getElementById('text-analysis-result').style.display = 'none';
        document.getElementById('ai-error').style.display = 'none';
    }

    function showError(message) {
        document.getElementById('error-message').textContent = message;
        document.getElementById('ai-error').style.display = 'block';
    }
});
</script>
@endpush
@endsection
