<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MedGemma Healthcare Platform') }} • DICOM Upload</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            min-height: 100vh;
            line-height: 1.6;
        }
        
        /* Next-gen DICOM upload styling */
        .dicom-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 0.5rem 0;
        }
        .dicom-header h2 {
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            color: #fff;
            margin-bottom: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .upload-container {
            background: rgba(255,255,255,0.04);
            border-radius: 18px;
            box-shadow: 0 4px 32px rgba(102,126,234,0.08);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .upload-zone {
            border: 2px dashed rgba(255,255,255,0.3);
            border-radius: 12px;
            padding: 3rem;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
        }
        .upload-zone:hover {
            border-color: rgba(102,126,234,0.5);
            background: rgba(255,255,255,0.02);
        }
        .upload-zone.dragover {
            border-color: #667eea;
            background: rgba(102,126,234,0.1);
        }
        .studies-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 1rem;
        }
        .studies-table th {
            background: rgba(102,126,234,0.12);
            color: #fff;
            font-weight: 600;
            padding: 1rem 0.75rem;
            border: none;
            font-size: 1.05rem;
            text-align: left;
        }
        .studies-table td {
            background: rgba(255,255,255,0.08);
            color: #fff;
            font-size: 1rem;
            padding: 1.1rem 0.75rem;
            border-radius: 12px;
            vertical-align: middle;
            box-shadow: 0 2px 8px rgba(102,126,234,0.04);
            word-break: break-word;
            border: none;
        }
        .studies-table tr:not(.table-header):hover td {
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            box-shadow: 0 4px 16px rgba(118,75,162,0.15);
        }
        
        .app-header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .mark {
            width: 32px;
            height: 32px;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            border-radius: 8px;
        }
        
        .nav {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }
        
        .btn.ghost {
            background: transparent;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .btn.ghost:hover, .btn.ghost.active {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        
        .btn.primary {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn.primary:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
        }
        
        .btn.outline {
            background: transparent;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn.outline:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .btn.small {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #fff 0%, rgba(255,255,255,0.8) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .page-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
        }
        
        .muted {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .grid {
            display: grid;
            gap: 2rem;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            margin-bottom: 2rem;
        }
        
        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 2rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .card h2 {
            margin-bottom: 1.5rem;
            color: #fff;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .card-header h2 {
            margin-bottom: 0;
        }
        
        .card-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .upload-section {
            margin-bottom: 1rem;
        }
        
        .upload-area {
            border: 2px dashed rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            padding: 3rem 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.05);
        }
        
        .upload-area:hover, .upload-area.dragover {
            border-color: #4ecdc4;
            background: rgba(78, 205, 196, 0.1);
            transform: translateY(-2px);
        }
        
        .upload-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }
        
        .upload-area h3 {
            margin: 0 0 0.5rem 0;
            font-size: 1.25rem;
            font-weight: 600;
            color: #fff;
        }
        
        .upload-area p {
            margin-bottom: 1.5rem;
            color: rgba(255, 255, 255, 0.7);
        }
        
        .upload-progress {
            margin-top: 1rem;
        }
        
        .progress-bar {
            width: 100%;
            height: 8px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #4ecdc4, #44a08d);
            transition: width 0.3s ease;
            border-radius: 4px;
        }
        
        .progress-text {
            text-align: center;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .instructions {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .instruction-item {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4ecdc4, #44a08d);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            flex-shrink: 0;
            font-size: 1.1rem;
        }
        
        .step-content h4 {
            margin: 0 0 0.25rem 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: #fff;
        }
        
        .step-content p {
            margin: 0;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .patient-selection {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 1.5rem;
        }
        
        .row {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
        
        .col {
            display: flex;
            flex-direction: column;
        }
        
        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #fff;
        }
        
        .form-select, .form-control {
            padding: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 0.9rem;
        }
        
        .form-select:focus, .form-control:focus {
            outline: none;
            border-color: #4ecdc4;
            box-shadow: 0 0 0 3px rgba(78, 205, 196, 0.1);
        }
        
        .form-select option {
            background: #2d3748;
            color: #fff;
        }
        
        .uploads-list {
            max-height: 300px;
            overflow-y: auto;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
        }
        
        .empty-state p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        
        .empty-state small {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .upload-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: all 0.2s;
        }
        
        .upload-item:hover {
            background: rgba(255, 255, 255, 0.08);
        }
        
        .upload-item .upload-icon {
            font-size: 1.5rem;
            opacity: 0.8;
        }
        
        .upload-details {
            flex: 1;
        }
        
        .upload-details h4 {
            margin: 0 0 0.25rem 0;
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
        }
        
        .upload-meta {
            display: flex;
            gap: 1rem;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
            flex-wrap: wrap;
        }
        
        .upload-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .integration-status {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 1.5rem;
        }
        
        .status-grid {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
        
        .status-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .status-item label {
            font-weight: 500;
            color: #fff;
        }
        
        .tag {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        
        .tag.success {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }
        
        .tag.warning {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }
        
        .tag.error {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        @media (max-width: 768px) {
            .inner {
                padding: 0 1rem;
                flex-direction: column;
                gap: 1rem;
            }
            
            .nav {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .container {
                padding: 1rem;
            }
            
            .page-header h1 {
                font-size: 2rem;
            }
            
            .grid {
                grid-template-columns: 1fr;
            }
            
            .upload-area {
                padding: 2rem 1rem;
            }
            
            .upload-icon {
                font-size: 2rem;
            }
            
            .row {
                grid-template-columns: 1fr;
            }
            
            .card-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }
            
            .upload-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
<header class="app-header">
    <div class="inner">
        <div class="logo">
            <div class="mark"></div>
            <span>FHIR • DICOM • MedGemma</span>
        </div>
        <nav class="nav">
            <a class="btn ghost" href="/app" title="Dashboard">Dashboard</a>
            <a class="btn ghost" href="/patients" title="Patients">Patients</a>
            <a class="btn ghost" href="/medgemma" title="MedGemma AI">MedGemma AI</a>
            <a class="btn ghost" href="/reports" title="Reports">Reports</a>
            <a class="btn ghost active" href="/dicom-upload" title="DICOM Upload">DICOM Upload</a>
            <a class="btn ghost" href="/help" title="Help">Help</a>
        </nav>
    </div>
</header>

<div class="container">
    <div class="page-header">
        <h1>DICOM Upload & FHIR Integration</h1>
        <p class="muted">Upload medical imaging files and convert them to FHIR-compliant resources</p>
    </div>

    <div class="grid">
        <div class="card">
            <h2>Upload DICOM Files</h2>
            <div class="upload-section">
                <div class="upload-area" id="uploadArea">
                    <div class="upload-icon">📁</div>
                    <h3>Drag & Drop DICOM Files</h3>
                    <p class="muted">Or click to select files</p>
                    <input type="file" id="fileInput" multiple accept=".dcm,.dicom" style="display:none">
                    <button class="btn primary" onclick="document.getElementById('fileInput').click()">
                        Select Files
                    </button>
                </div>
                
                <div id="uploadProgress" class="upload-progress" style="display:none">
                    <div class="progress-bar">
                        <div class="progress-fill" id="progressFill"></div>
                    </div>
                    <div class="progress-text" id="progressText">Uploading...</div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <h2>Upload Instructions</h2>
            <div class="instructions">
                <div class="instruction-item">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h4>Select Patient</h4>
                        <p>Choose the patient for these DICOM files</p>
                    </div>
                </div>
                <div class="instruction-item">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h4>Upload Files</h4>
                        <p>Drag and drop or select DICOM files (.dcm, .dicom)</p>
                    </div>
                </div>
                <div class="instruction-item">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h4>Automatic Processing</h4>
                        <p>Files are automatically converted to FHIR format</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>Patient Selection</h2>
        <div class="patient-selection">
            <div class="row">
                <div class="col">
                    <label for="patientSelect" class="form-label">Select Patient:</label>
                    <select id="patientSelect" class="form-select">
                        <option value="">Choose a patient...</option>
                    </select>
                </div>
                <div class="col">
                    <label for="studyDescription" class="form-label">Study Description:</label>
                    <input type="text" id="studyDescription" class="form-control" placeholder="Enter study description">
                </div>
                <div class="col">
                    <label for="modality" class="form-label">Modality:</label>
                    <select id="modality" class="form-select">
                        <option value="">Select modality...</option>
                        <option value="CT">CT Scan</option>
                        <option value="MR">MRI</option>
                        <option value="US">Ultrasound</option>
                        <option value="XR">X-Ray</option>
                        <option value="NM">Nuclear Medicine</option>
                        <option value="PT">PET</option>
                        <option value="CR">Computed Radiography</option>
                        <option value="DX">Digital Radiography</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Recent Uploads</h2>
            <div class="card-actions">
                <button class="btn outline small" onclick="refreshUploads()">Refresh</button>
            </div>
        </div>
        <div id="recentUploads" class="uploads-list">
            <div class="empty-state">
                <p>No recent uploads</p>
                <small class="muted">Upload DICOM files to see them here</small>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>FHIR Integration Status</h2>
        <div class="integration-status">
            <div class="status-grid">
                <div class="status-item status-ok">
                    <label>FHIR Compliance:</label>
                    <span class="tag success">R4 Compatible</span>
                </div>
                <div class="status-item status-ok">
                    <label>ImagingStudy Resource:</label>
                    <span class="tag success">Supported</span>
                </div>
                <div class="status-item status-ok">
                    <label>Patient Linking:</label>
                    <span class="tag success">Automatic</span>
                </div>
                <div class="status-item status-ok">
                    <label>Metadata Extraction:</label>
                    <span class="tag success">Enabled</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let patients = [];

function tag(text, cls='') { return `<span class="tag ${cls}">${text}</span>`; }
function htmlesc(str) { return (str||'').toString().replace(/[&<>\"]/g, s=>({"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;"}[s])); }

// Load patients for selection
async function loadPatients() {
    try {
        const r = await fetch('/reports/patients', {headers: {'Accept':'application/json'}});
        const d = await r.json();
        patients = d.data || [];
        
        const select = document.getElementById('patientSelect');
        select.innerHTML = '<option value="">Choose a patient...</option>';
        
        patients.forEach(p => {
            const name = p.name || `${p.first_name || ''} ${p.last_name || ''}`.trim();
            const option = document.createElement('option');
            option.value = p.id;
            option.textContent = `${name} (MRN: ${p.mrn || 'N/A'})`;
            select.appendChild(option);
        });
    } catch (e) {
        console.error('Failed to load patients:', e);
    }
}

// File upload handling
const uploadArea = document.getElementById('uploadArea');
const fileInput = document.getElementById('fileInput');

uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('dragover');
});

uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('dragover');
});

uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('dragover');
    const files = e.dataTransfer.files;
    handleFiles(files);
});

fileInput.addEventListener('change', (e) => {
    handleFiles(e.target.files);
});

async function handleFiles(files) {
    const patientId = document.getElementById('patientSelect').value;
    const studyDescription = document.getElementById('studyDescription').value;
    const modality = document.getElementById('modality').value;
    
    if (!patientId) {
        alert('Please select a patient first');
        return;
    }
    
    if (files.length === 0) return;
    
    const progressContainer = document.getElementById('uploadProgress');
    const progressFill = document.getElementById('progressFill');
    const progressText = document.getElementById('progressText');
    
    progressContainer.style.display = 'block';
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const progress = ((i + 1) / files.length) * 100;
        
        progressFill.style.width = progress + '%';
        progressText.textContent = `Uploading ${i + 1} of ${files.length} files...`;
        
        try {
            await uploadFile(file, patientId, studyDescription, modality);
        } catch (e) {
            console.error('Upload failed:', e);
            alert(`Failed to upload ${file.name}: ${e.message}`);
        }
    }
    
    progressText.textContent = 'Upload complete!';
    setTimeout(() => {
        progressContainer.style.display = 'none';
        refreshUploads();
    }, 2000);
}

async function uploadFile(file, patientId, studyDescription, modality) {
    const formData = new FormData();
    formData.append('dicom_file', file);
    formData.append('patient_id', patientId);
    if (studyDescription) formData.append('study_description', studyDescription);
    if (modality) formData.append('modality', modality);
    
    const response = await fetch('/dicom/upload', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json'
        },
        body: formData
    });
    
    if (!response.ok) {
        const error = await response.text();
        throw new Error(error || 'Upload failed');
    }
    
    return await response.json();
}

async function refreshUploads() {
    // This would fetch recent uploads from the server
    // For now, we'll show a placeholder
    const uploadsContainer = document.getElementById('recentUploads');
    uploadsContainer.innerHTML = `
        <div class="upload-item">
            <div class="upload-icon">📄</div>
            <div class="upload-details">
                <h4>Sample_CT_Study.dcm</h4>
                <div class="upload-meta">
                    <span>Patient: John Doe</span>
                    <span>Modality: CT</span>
                    <span>Size: 2.5 MB</span>
                    <span class="tag success">Processed</span>
                </div>
            </div>
            <div class="upload-actions">
                <button class="btn small outline">View FHIR</button>
                <button class="btn small primary">Analyze</button>
            </div>
        </div>
    `;
}

// Initialize
loadPatients();
</script>
</body>
</html>
