<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MedGemma Healthcare Platform') }} • Dashboard</title>
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
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
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
        
        .muted {
            color: rgba(255, 255, 255, 0.6);
        }
        
        /* Clinical Notes Styling */
        #patientNotes .card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem;
            margin: 8px 0;
            border-radius: 8px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        #patientNotes pre {
            white-space: pre-wrap;
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-width: 100%;
            overflow-x: auto;
            background: rgba(0, 0, 0, 0.2);
            padding: 0.75rem;
            border-radius: 4px;
            margin: 0.5rem 0;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            line-height: 1.4;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        #patientNotes h3 {
            color: #fff;
            margin-bottom: 1rem;
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        #patientNotes .card > div {
            margin-bottom: 0.5rem;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        #patientNotes .card b {
            color: rgba(255, 255, 255, 0.9);
        }
        
        /* Imaging Studies AI Results Styling */
        .ai-analysis-container {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-width: 100%;
            width: 100%;
            box-sizing: border-box;
            overflow: hidden;
        }
        
        .ai-analysis-header {
            color: #fff;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .ai-analysis-content {
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
            white-space: pre-wrap;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        .ai-confidence {
            display: inline-block;
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }
        
        .ai-recommendations {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .ai-recommendations h4 {
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }
        
        .ai-recommendations ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .ai-recommendations li {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            position: relative;
            padding-left: 1.5rem;
        }
        
        .ai-recommendations li:before {
            content: "•";
            color: #22c55e;
            font-weight: bold;
            position: absolute;
            left: 0;
        }
        
        /* Table Styling */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .table th {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-weight: 600;
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .table td {
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, 0.9);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-width: 0;
            overflow: hidden;
        }
        
        .table td[colspan] {
            max-width: none;
            width: 100%;
        }
        
        .table tr:hover {
            background: rgba(255, 255, 255, 0.02);
        }
        
        .table tr:last-child td {
            border-bottom: none;
        }
        
        .row {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .input {
            padding: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 0.9rem;
            flex: 1;
        }
        
        .input:focus {
            outline: none;
            border-color: #4ecdc4;
            box-shadow: 0 0 0 3px rgba(78, 205, 196, 0.1);
        }
        
        .input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .list {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .list-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            margin-bottom: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .list-item:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(4px);
        }
        
        .list-item.selected {
            background: rgba(78, 205, 196, 0.2);
            border-color: #4ecdc4;
        }
        
        .patient-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4ecdc4, #44a08d);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
        }
        
        .patient-info {
            flex: 1;
        }
        
        .patient-name {
            font-weight: 600;
            color: #fff;
            margin-bottom: 0.25rem;
        }
        
        .patient-meta {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.8rem;
        }
        
        .tag {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
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
        
        .tag.clickable {
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .tag.clickable:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
            transform: translateY(-1px);
        }
        
        /* Modal/Popup Styling */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }
        
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .modal-content {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 2rem;
            max-width: 90vw;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            transform: scale(0.9);
            transition: transform 0.3s;
        }
        
        .modal-overlay.active .modal-content {
            transform: scale(1);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .modal-title {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }
        
        .modal-close {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.2s;
        }
        
        .modal-close:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        
        .modal-body {
            color: rgba(255, 255, 255, 0.9);
        }
        
        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }
        
        .image-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .image-item:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-2px);
        }
        
        .image-placeholder {
            width: 100%;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.5rem;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .tag.error {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        .loading {
            text-align: center;
            padding: 2rem;
            color: rgba(255, 255, 255, 0.6);
        }
        
        ul {
            padding-left: 1.5rem;
        }
        
        li {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 0.5rem;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1.5rem 0 1rem 0;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #fff;
        }
        
        .study-item, .lab-item, .rx-item, .note-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.5rem;
            transition: all 0.2s;
        }
        
        .study-item:hover, .lab-item:hover, .rx-item:hover, .note-item:hover {
            background: rgba(255, 255, 255, 0.08);
        }
        
        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .item-title {
            font-weight: 600;
            color: #fff;
        }
        
        .item-meta {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.8rem;
        }
        
        .item-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn.small {
            padding: 0.375rem 0.75rem;
            font-size: 0.8rem;
        }
        
        .btn.outline {
            background: transparent;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn.outline:hover {
            background: rgba(255, 255, 255, 0.1);
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
            
            .grid {
                grid-template-columns: 1fr;
            }
            
            .row {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
<header class="app-header"><div class="inner"><div class="logo"><div class="mark"></div><span>FHIR • DICOM • MedGemma</span></div><nav class="nav"><a class="btn ghost active" href="/app" title="Dashboard">Dashboard</a><a class="btn ghost" href="/patients" title="Patients">Patients</a><a class="btn ghost" href="/medgemma" title="MedGemma AI">MedGemma AI</a><a class="btn ghost" href="/reports" title="Reports">Reports</a><a class="btn ghost" href="/dicom-upload" title="DICOM Upload">DICOM Upload</a>@auth @if(Auth::user()->hasRole('Admin') || Auth::user()->email === 'admin@medgemma.com')<a class="btn ghost" href="/user-management" title="User Management">User Management</a>@endif @endauth<a class="btn ghost" href="/help" title="Help">Help</a></nav></div></header>
<div class="container">
    <div class="grid">
        <div class="card">
            <h2>MedGemma Integration</h2>
            <div id="medgemmaStatus" class="muted">Loading...</div>
        </div>
        <div class="card">
            <h2>Quick Tips</h2>
            <ul>
                <li>Use the left list to select a patient. Demo data is seeded.</li>
                <li>Trigger AI analyses from the patient details panel.</li>
                <li>Admin panel: <span class="tag">/admin/users</span> (Basic Auth via .env)</li>
            </ul>
        </div>
    </div>

    <div class="grid" style="margin-top:16px">
        <div class="card">
            <h2>Patients</h2>
            <div class="row" style="margin-bottom:8px">
                <input id="search" type="search" placeholder="Search by name or MRN" class="input" style="flex:1">
                <button class="btn primary" onclick="loadPatients()">Reload</button>
            </div>
            <div id="patients" class="list" role="listbox" aria-label="Patients list"></div>
        </div>
        <div class="card">
            <h2 id="patientTitle">Patient Details</h2>
            <div id="patientMeta" class="muted">Select a patient to view details.</div>
            <div id="patientActions" class="row" style="margin:10px 0; display:none"></div>

            <div id="patientImaging"></div>
            <div id="patientLabs" style="margin-top:16px"></div>
            <div id="patientRx" style="margin-top:16px"></div>
            <div id="patientNotes" style="margin-top:16px"></div>
        </div>
    </div>
</div>

<!-- Modal for Imaging Studies -->
<div id="imagingModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Imaging Studies</h3>
            <button class="modal-close" onclick="closeModal('imagingModal')">&times;</button>
        </div>
        <div class="modal-body" id="imagingModalBody">
            Loading...
        </div>
    </div>
</div>

<!-- Modal for Lab Results -->
<div id="labModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Laboratory Results</h3>
            <button class="modal-close" onclick="closeModal('labModal')">&times;</button>
        </div>
        <div class="modal-body" id="labModalBody">
            Loading...
        </div>
    </div>
</div>

<!-- Modal for Prescriptions -->
<div id="rxModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Prescriptions</h3>
            <button class="modal-close" onclick="closeModal('rxModal')">&times;</button>
        </div>
        <div class="modal-body" id="rxModalBody">
            Loading...
        </div>
    </div>
</div>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let patientsCache = [];
let currentPatientId = null;

// Modal Functions
function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
}

// Close modal when clicking overlay
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal-overlay')) {
        e.target.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
});

// Escape key to close modal
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        const activeModal = document.querySelector('.modal-overlay.active');
        if (activeModal) {
            activeModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    }
});

async function showImagingModal(patientId) {
    try {
        showModal('imagingModal');
        const modalBody = document.getElementById('imagingModalBody');
        modalBody.innerHTML = 'Loading imaging studies...';
        
        const response = await fetch(`/reports/patients/${patientId}`, {
            headers: {'Accept': 'application/json'}
        });
        const data = await response.json();
        const patient = data.data;
        
        if (!patient.imaging_studies || patient.imaging_studies.length === 0) {
            modalBody.innerHTML = '<div class="muted">No imaging studies found for this patient.</div>';
            return;
        }
        
        let html = '<div class="image-grid">';
        patient.imaging_studies.forEach(study => {
            const studyDate = new Date(study.started_at).toLocaleDateString();
            html += `
                <div class="image-item" onclick="viewImageDetails(${study.id})">
                    <div class="image-placeholder">
                        <i class="fas fa-x-ray" style="font-size: 2rem;"></i>
                    </div>
                    <div><strong>${study.modality}</strong></div>
                    <div class="muted">${study.description}</div>
                    <div class="muted">${studyDate}</div>
                    <div class="muted">Status: ${study.status}</div>
                </div>
            `;
        });
        html += '</div>';
        
        modalBody.innerHTML = html;
    } catch (error) {
        console.error('Error loading imaging studies:', error);
        document.getElementById('imagingModalBody').innerHTML = '<div class="muted">Error loading imaging studies.</div>';
    }
}

async function showLabModal(patientId) {
    try {
        showModal('labModal');
        const modalBody = document.getElementById('labModalBody');
        modalBody.innerHTML = 'Loading lab results...';
        
        const response = await fetch(`/reports/patients/${patientId}`, {
            headers: {'Accept': 'application/json'}
        });
        const data = await response.json();
        const patient = data.data;
        
        if (!patient.lab_orders || patient.lab_orders.length === 0) {
            modalBody.innerHTML = '<div class="muted">No lab results found for this patient.</div>';
            return;
        }
        
        let html = '<table class="table"><thead><tr><th>Test</th><th>Result</th><th>Flag</th><th>Reference Range</th><th>Notes</th></tr></thead><tbody>';
        patient.lab_orders.forEach(lab => {
            const flagClass = lab.result_flag === 'critical' ? 'err' : (lab.result_flag === 'normal' ? 'ok' : 'warn');
            html += `
                <tr>
                    <td><strong>${htmlesc(lab.code || '')} ${htmlesc(lab.name || '')}</strong></td>
                    <td>${htmlesc(lab.result_value || '')}</td>
                    <td>${lab.result_flag ? tag(htmlesc(lab.result_flag), flagClass) : '-'}</td>
                    <td>${htmlesc(lab.reference_range || '-')}</td>
                    <td>${htmlesc(lab.result_notes || '-')}</td>
                </tr>
            `;
        });
        html += '</tbody></table>';
        
        modalBody.innerHTML = html;
    } catch (error) {
        console.error('Error loading lab results:', error);
        document.getElementById('labModalBody').innerHTML = '<div class="muted">Error loading lab results.</div>';
    }
}

async function showRxModal(patientId) {
    try {
        showModal('rxModal');
        const modalBody = document.getElementById('rxModalBody');
        modalBody.innerHTML = 'Loading prescriptions...';
        
        const response = await fetch(`/reports/patients/${patientId}`, {
            headers: {'Accept': 'application/json'}
        });
        const data = await response.json();
        const patient = data.data;
        
        if (!patient.prescriptions || patient.prescriptions.length === 0) {
            modalBody.innerHTML = '<div class="muted">No prescriptions found for this patient.</div>';
            return;
        }
        
        let html = '<table class="table"><thead><tr><th>Medication</th><th>Strength</th><th>Dosage</th><th>Frequency</th><th>Status</th><th>Prescriber</th></tr></thead><tbody>';
        patient.prescriptions.forEach(rx => {
            html += `
                <tr>
                    <td><strong>${htmlesc(rx.medication_name || '')}</strong></td>
                    <td>${htmlesc(rx.strength || '-')}</td>
                    <td>${htmlesc(rx.dosage_instruction || '-')}</td>
                    <td>${htmlesc(rx.frequency || '-')}</td>
                    <td>${tag(htmlesc(rx.status || 'Unknown'))}</td>
                    <td>${htmlesc(rx.prescriber_name || '-')}</td>
                </tr>
            `;
        });
        html += '</tbody></table>';
        
        modalBody.innerHTML = html;
    } catch (error) {
        console.error('Error loading prescriptions:', error);
        document.getElementById('rxModalBody').innerHTML = '<div class="muted">Error loading prescriptions.</div>';
    }
}

async function viewImageDetails(studyId) {
    // This could be expanded to show actual DICOM images
    // For now, we'll show a placeholder
    alert(`Image details for study ${studyId}. In a full implementation, this would display DICOM images.`);
}

function tag(text, cls='') { return `<span class="tag ${cls}">${text}</span>`; }

function clickableTag(text, cls='', onclick='') { 
    return `<span class="tag clickable ${cls}" onclick="${onclick}">${text}</span>`; }

function formatAIAnalysis(aiResult) {
    if (!aiResult || !aiResult.result) return '';
    
    const result = aiResult.result;
    let html = '<div class="ai-analysis-container">';
    
    // Header with model and confidence
    html += '<div class="ai-analysis-header">';
    html += `AI Analysis - ${htmlesc(aiResult.model || 'MedGemma')}`;
    if (result.confidence) {
        const confidencePercent = Math.round(result.confidence * 100);
        html += `<span class="ai-confidence">${confidencePercent}% Confidence</span>`;
    }
    html += '</div>';
    
    // Main analysis content
    if (result.analysis) {
        html += `<div class="ai-analysis-content">${htmlesc(result.analysis)}</div>`;
    }
    
    // Recommendations section
    if (result.recommendations && result.recommendations.length > 0) {
        html += '<div class="ai-recommendations">';
        html += '<h4>Recommendations:</h4>';
        html += '<ul>';
        result.recommendations.forEach(rec => {
            html += `<li>${htmlesc(rec)}</li>`;
        });
        html += '</ul>';
        html += '</div>';
    }
    
    html += '</div>';
    return html;
}

async function loadMedGemma() {
    const el = document.getElementById('medgemmaStatus');
    try {
        const r = await fetch('/integrations/medgemma');
        const d = await r.json();
        const parts = [
            `Model: <b>${d.model || 'medgemma'}</b>`,
            d.enabled ? tag('enabled','ok') : tag('disabled','warn'),
            d.configured ? tag('configured','ok') : tag('not configured','warn')
        ];
        el.innerHTML = parts.join(' ');
    } catch (e) {
        el.innerHTML = 'Failed to load MedGemma status.';
    }
}

function renderPatients(list) {
    const box = document.getElementById('patients');
    if (!list || list.length === 0) {
        box.innerHTML = '<div class="muted" style="padding:10px">No patients</div>';
        return;
    }
    box.innerHTML = list.map(p => {
        const name = p.name || `${p.first_name || ''} ${p.last_name || ''}`.trim();
        return `<div class="item" role="option" onclick="selectPatient(${p.id})">
            <div>
                <div><b>${name}</b></div>
                <div class="muted">MRN: ${p.mrn || '-'} • DOB: ${p.dob || '-'} • ${p.sex || '-'}</div>
            </div>
            <div>
                ${clickableTag(`Img ${p.counts.imaging_studies}`, '', `event.stopPropagation(); showImagingModal(${p.id})`)}
                ${clickableTag(`Lab ${p.counts.lab_orders}`, '', `event.stopPropagation(); showLabModal(${p.id})`)}
                ${clickableTag(`Rx ${p.counts.prescriptions}`, '', `event.stopPropagation(); showRxModal(${p.id})`)}
            </div>
        </div>`
    }).join('');
}

async function loadPatients() {
    const q = document.getElementById('search').value.trim().toLowerCase();
    try {
        const r = await fetch('/reports/patients', {headers: {'Accept':'application/json'}});
        const d = await r.json();
        patientsCache = d.data || [];
        const filtered = q ? patientsCache.filter(p => (p.name||'').toLowerCase().includes(q) || (p.mrn||'').toLowerCase().includes(q)) : patientsCache;
        renderPatients(filtered);
    } catch (e) {
        document.getElementById('patients').innerHTML = '<div class="muted" style="padding:10px">Failed to load patients</div>';
    }
}

function htmlesc(str){return (str||'').toString().replace(/[&<>\"]/g, s=>({"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;"}[s]));}

async function selectPatient(id) {
    currentPatientId = id;
    document.getElementById('patientTitle').innerText = 'Patient Details';
    document.getElementById('patientMeta').innerText = 'Loading...';
    document.getElementById('patientActions').style.display = 'none';
    document.getElementById('patientImaging').innerHTML = '';
    document.getElementById('patientLabs').innerHTML = '';
    document.getElementById('patientRx').innerHTML = '';
    document.getElementById('patientNotes').innerHTML = '';

    try {
        const r = await fetch(`/reports/patients/${id}`, {headers: {'Accept':'application/json'}});
        const p = await r.json();
        const title = `${p.name || (p.first_name||'')+' '+(p.last_name||'')}`.trim();
        document.getElementById('patientTitle').innerText = title;
        document.getElementById('patientMeta').innerHTML = `MRN: <b>${htmlesc(p.mrn||'-')}</b> • DOB: ${htmlesc(p.dob||'-')} • ${htmlesc(p.sex||'-')}`;

        // Actions
        const actions = document.getElementById('patientActions');
        actions.style.display = 'flex';
        actions.innerHTML = `
            <button class="btn primary" onclick="analyzeLabs(${p.id})">Analyze Labs</button>
            <button class="btn ghost" onclick="secondOpinion(${p.id})">Combined Second Opinion</button>
        `;

        // Imaging
        const im = p.imaging_studies || [];
        let imHtml = `<h3>Imaging Studies</h3>`;
        if (im.length === 0) imHtml += '<div class="muted">No imaging studies.</div>';
        else {
            imHtml += '<table class="table"><thead><tr><th>Modality</th><th>Description</th><th>Date</th><th>AI</th><th></th></tr></thead><tbody>';
            im.forEach(s => {
                const lastAI = (s.ai_results||[])[0];
                const aiCell = lastAI ? `${htmlesc(lastAI.model)} ${tag((lastAI.confidence_score||'').toString(),'ok')}` : '<span class="muted">None</span>';
                imHtml += `<tr>
                    <td>${htmlesc(s.modality||'-')}</td>
                    <td>${htmlesc(s.description||'-')}</td>
                    <td>${htmlesc(s.started_at||'-')}</td>
                    <td>${aiCell}</td>
                    <td><button class="btn small primary" onclick="analyzeImaging(${s.id})">Analyze</button></td>
                </tr>`;
                if (lastAI && lastAI.result) {
                    imHtml += `<tr><td colspan="5">${formatAIAnalysis(lastAI)}</td></tr>`;
                }
            });
            imHtml += '</tbody></table>';
        }
        document.getElementById('patientImaging').innerHTML = imHtml;

        // Labs
        const labs = p.lab_orders || [];
        let labsHtml = `<h3>Lab Orders</h3>`;
        if (labs.length === 0) labsHtml += '<div class="muted">No labs.</div>';
        else {
            labsHtml += '<table class="table"><thead><tr><th>Test</th><th>Status</th><th>Priority</th><th>Result</th><th>Notes</th></tr></thead><tbody>';
            labs.forEach(o => {
                labsHtml += `<tr>
                    <td>${htmlesc(o.code || '')} ${htmlesc(o.name||'')}</td>
                    <td>${htmlesc(o.status||'')}</td>
                    <td>${htmlesc(o.priority||'')}</td>
                    <td>${htmlesc(o.result_value||'')} ${o.result_flag?tag(htmlesc(o.result_flag),o.result_flag==='critical'?'err':(o.result_flag==='normal'?'ok':'warn')):''}</td>
                    <td>${htmlesc(o.result_notes||'')}</td>
                </tr>`;
            });
            labsHtml += '</tbody></table>';
        }
        document.getElementById('patientLabs').innerHTML = labsHtml;

        // Prescriptions
        const rx = p.prescriptions || [];
        let rxHtml = `<h3>Prescriptions</h3>`;
        if (rx.length === 0) rxHtml += '<div class="muted">No prescriptions.</div>';
        else {
            rxHtml += '<table class="table"><thead><tr><th>Medication</th><th>Strength</th><th>Dosage</th><th>Frequency</th><th>Status</th></tr></thead><tbody>';
            rx.forEach(r => {
                rxHtml += `<tr>
                    <td>${htmlesc(r.medication||'')}</td>
                    <td>${htmlesc(r.strength||'')}</td>
                    <td>${htmlesc(r.dosage||'')}</td>
                    <td>${htmlesc(r.frequency||'')}</td>
                    <td>${htmlesc(r.status||'')}</td>
                </tr>`;
            });
            rxHtml += '</tbody></table>';
        }
        document.getElementById('patientRx').innerHTML = rxHtml;

        // Notes
        const notes = p.clinical_notes || [];
        let noteHtml = `<h3>Clinical Notes</h3>`;
        if (notes.length === 0) noteHtml += '<div class="muted">No notes.</div>';
        else {
            notes.forEach(n => {
                noteHtml += `<div class="card" style="margin:8px 0">
                    <div class="muted">${htmlesc(n.created_at||'')}</div>
                    <div><b>Assessment:</b> ${htmlesc(n.soap_assessment||'')}</div>
                    <div><b>Plan:</b><br><pre>${htmlesc(n.soap_plan||'')}</pre></div>
                </div>`;
            });
        }
        document.getElementById('patientNotes').innerHTML = noteHtml;
    } catch (e) {
        document.getElementById('patientMeta').innerText = 'Failed to load patient.';
    }
}

async function postJson(url) {
    const r = await fetch(url, {method:'POST', headers:{'X-CSRF-TOKEN': csrf, 'Accept':'application/json'}});
    if (!r.ok) throw new Error('Request failed');
    return r.json().catch(()=>({ok:true}))
}

async function analyzeImaging(studyId){
    try { await postJson(`/medgemma/analyze/imaging/${studyId}`); if (currentPatientId) await selectPatient(currentPatientId); }
    catch(e){ alert('Failed to analyze imaging'); }
}
async function analyzeLabs(patientId){
    try { await postJson(`/medgemma/analyze/labs/${patientId}`); if (currentPatientId) await selectPatient(currentPatientId); }
    catch(e){ alert('Failed to analyze labs'); }
}
async function secondOpinion(patientId){
    try { await postJson(`/medgemma/second-opinion/${patientId}`); if (currentPatientId) await selectPatient(currentPatientId); }
    catch(e){ alert('Failed to get second opinion'); }
}

// Init
loadMedGemma();
loadPatients();
document.getElementById('search').addEventListener('input', () => {
    const q = document.getElementById('search').value.trim().toLowerCase();
    const filtered = q ? patientsCache.filter(p => (p.name||'').toLowerCase().includes(q) || (p.mrn||'').toLowerCase().includes(q)) : patientsCache;
    renderPatients(filtered);
});
</script>
</body>
</html>
