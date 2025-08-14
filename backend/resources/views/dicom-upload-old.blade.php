@extends('layouts.app')
@section('title', 'DICOM Upload')
@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="mb-3">DICOM Upload & FHIR Integration</h2>
        <p class="text-muted">Upload DICOM files for your patients and convert them to FHIR format.</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        Upload DICOM File
    </div>
    <div class="card-body">
        <form id="dicom-upload-form" class="row g-3" enctype="multipart/form-data">
            <div class="col-md-6">
                <label for="patient_id" class="form-label">Patient</label>
                <select class="form-select" id="patient_id" name="patient_id" required>
                    <option value="">Select patient...</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="modality" class="form-label">Modality</label>
                <select class="form-select" id="modality" name="modality">
                    <option value="CT">CT</option>
                    <option value="MR">MR</option>
                    <option value="US">Ultrasound</option>
                    <option value="XR">X-Ray</option>
                    <option value="OT">Other</option>
                </select>
            </div>
            <div class="col-md-12">
                <label for="description" class="form-label">Description</label>
                <input type="text" class="form-control" id="description" name="description" placeholder="Study description">
            </div>
            <div class="col-md-12">
                <label for="file" class="form-label">DICOM File</label>
                <input type="file" class="form-control" id="file" name="file" required>
                <div class="form-text">Select a DICOM file to upload (max 50MB).</div>
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary" id="upload-btn">Upload DICOM</button>
            </div>
        </form>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-success text-white">
        FHIR Integration
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="study_id" class="form-label">Imaging Study</label>
                <select class="form-select" id="study_id" name="study_id">
                    <option value="">Select study...</option>
                </select>
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <button class="btn btn-success" id="export-fhir-btn" disabled>Export to FHIR</button>
            </div>
        </div>
        <div class="mt-4">
            <h5>FHIR Resource</h5>
            <pre id="fhir-output" class="bg-light p-3 rounded" style="max-height: 400px; overflow-y: auto; display: none;"></pre>
        </div>
    </div>
</div>

<div id="upload-result" class="alert alert-success" style="display:none"></div>
<div id="upload-error" class="alert alert-danger" style="display:none"></div>

@push('scripts')
<script>
// Load patients from API
function loadPatients() {
    const sel = document.getElementById('patient_id');
    sel.innerHTML = '<option value="">Select patient...</option>';
    
    // Fetch patients from API
    fetch('/api/patients', {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to load patients');
        }
        return response.json();
    })
    .then(patients => {
        if (patients.length === 0) {
            // If no patients found, use demo data as fallback
            const demoPatients = [
                { id: 1, first_name: 'John', last_name: 'Doe', mrn: 'MRN12345' },
                { id: 2, first_name: 'Jane', last_name: 'Smith', mrn: 'MRN67890' },
                { id: 3, first_name: 'Robert', last_name: 'Johnson', mrn: 'MRN54321' }
            ];
            
            demoPatients.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = p.first_name + ' ' + p.last_name + ' (MRN: ' + p.mrn + ')';
                sel.appendChild(opt);
            });
        } else {
            // Add patients from API
            patients.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = p.first_name + ' ' + p.last_name + ' (MRN: ' + p.mrn + ')';
                sel.appendChild(opt);
            });
        }
    })
    .catch(error => {
        console.error('Error loading patients:', error);
        // Use demo data as fallback
        const demoPatients = [
            { id: 1, first_name: 'John', last_name: 'Doe', mrn: 'MRN12345' },
            { id: 2, first_name: 'Jane', last_name: 'Smith', mrn: 'MRN67890' },
            { id: 3, first_name: 'Robert', last_name: 'Johnson', mrn: 'MRN54321' }
        ];
        
        demoPatients.forEach(p => {
            const opt = document.createElement('option');
            opt.value = p.id;
            opt.textContent = p.first_name + ' ' + p.last_name + ' (MRN: ' + p.mrn + ')';
            sel.appendChild(opt);
        });
    });
}

// Load patients on page load
loadPatients();

// Load studies from API
function loadStudies() {
    const sel = document.getElementById('study_id');
    sel.innerHTML = '<option value="">Select study...</option>';
    
    // Fetch studies from API
    fetch('/api/imaging-studies', {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to load studies');
        }
        return response.json();
    })
    .then(studies => {
        if (studies.length === 0) {
            // If no studies found, use demo data as fallback
            const demoStudies = [
                { id: 1, modality: 'CT', description: 'Chest CT', patient: 'John Doe' },
                { id: 2, modality: 'MR', description: 'Brain MRI', patient: 'Jane Smith' },
                { id: 3, modality: 'XR', description: 'Chest X-Ray', patient: 'Robert Johnson' }
            ];
            
            demoStudies.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id;
                opt.textContent = s.modality + ' - ' + s.description + ' (' + s.patient + ')';
                sel.appendChild(opt);
            });
        } else {
            // Add studies from API
            studies.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id;
                opt.textContent = s.modality + ' - ' + s.description + ' (' + s.patient + ')';
                sel.appendChild(opt);
            });
        }
        
        document.getElementById('export-fhir-btn').disabled = sel.options.length <= 1;
    })
    .catch(error => {
        console.error('Error loading studies:', error);
        // Use demo data as fallback
        const demoStudies = [
            { id: 1, modality: 'CT', description: 'Chest CT', patient: 'John Doe' },
            { id: 2, modality: 'MR', description: 'Brain MRI', patient: 'Jane Smith' },
            { id: 3, modality: 'XR', description: 'Chest X-Ray', patient: 'Robert Johnson' }
        ];
        
        demoStudies.forEach(s => {
            const opt = document.createElement('option');
            opt.value = s.id;
            opt.textContent = s.modality + ' - ' + s.description + ' (' + s.patient + ')';
            sel.appendChild(opt);
        });
        
        document.getElementById('export-fhir-btn').disabled = false;
    });
}

// Initial load
loadStudies();

// Handle DICOM upload form submission (real API integration)
const uploadForm = document.getElementById('dicom-upload-form');
uploadForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(uploadForm);
    const uploadBtn = document.getElementById('upload-btn');
    const resultDiv = document.getElementById('upload-result');
    const errorDiv = document.getElementById('upload-error');
    
    // Check if file is selected
    if (!formData.get('file') || formData.get('file').size === 0) {
        errorDiv.textContent = 'Please select a file.';
        errorDiv.style.display = 'block';
        return;
    }
    
    resultDiv.style.display = 'none';
    errorDiv.style.display = 'none';
    uploadBtn.disabled = true;
    uploadBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading...';
    
    // Send to backend API
    fetch('/dicom/upload', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            if (response.headers.get('content-type')?.includes('application/json')) {
                return response.json().then(data => {
                    throw new Error(data.error || data.errors ? JSON.stringify(data.errors) : 'Upload failed');
                });
            } else {
                throw new Error('Server error: ' + response.status);
            }
        }
        return response.json();
    })
    .then(data => {
        resultDiv.textContent = data.message;
        resultDiv.style.display = 'block';
        uploadForm.reset();
        
        // Add the new study to the dropdown
        const patientId = formData.get('patient_id');
        const patientName = document.querySelector(`#patient_id option[value="${patientId}"]`).textContent.split(' (')[0];
        const modality = formData.get('modality');
        const description = formData.get('description') || 'Uploaded DICOM study';
        
        const sel = document.getElementById('study_id');
        const opt = document.createElement('option');
        opt.value = data.study_id;
        opt.textContent = modality + ' - ' + description + ' (' + patientName + ')';
        sel.appendChild(opt);
        
        // Enable export button
        document.getElementById('export-fhir-btn').disabled = false;
        
        // Refresh studies list
        loadStudies();
    })
    .catch(error => {
        errorDiv.textContent = 'Error: ' + error.message;
        errorDiv.style.display = 'block';
    })
    .finally(() => {
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = 'Upload DICOM';
    });
});

// Handle FHIR export (real API integration)
const exportBtn = document.getElementById('export-fhir-btn');
exportBtn.addEventListener('click', function() {
    const studyId = document.getElementById('study_id').value;
    if (!studyId) return;
    
    const fhirOutput = document.getElementById('fhir-output');
    fhirOutput.style.display = 'none';
    exportBtn.disabled = true;
    exportBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Exporting...';
    
    // Send to backend API
    fetch(`/dicom/export-fhir/${studyId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            if (response.headers.get('content-type')?.includes('application/json')) {
                return response.json().then(data => {
                    throw new Error(data.error || 'Export failed');
                });
            } else {
                throw new Error('Server error: ' + response.status);
            }
        }
        return response.json();
    })
    .then(data => {
        fhirOutput.textContent = JSON.stringify(data, null, 2);
        fhirOutput.style.display = 'block';
    })
    .catch(error => {
        const errorDiv = document.getElementById('upload-error');
        errorDiv.textContent = 'Error: ' + error.message;
        errorDiv.style.display = 'block';
    })
    .finally(() => {
        exportBtn.disabled = false;
        exportBtn.innerHTML = 'Export to FHIR';
    });
});

// Enable/disable export button based on study selection
document.getElementById('study_id').addEventListener('change', function() {
    exportBtn.disabled = !this.value;
});
</script>
@endpush
@endsection