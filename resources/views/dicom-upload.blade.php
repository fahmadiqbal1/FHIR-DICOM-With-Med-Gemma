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
// Fetch patients for dropdown
fetch('/api/patients')
    .then(res => res.json())
    .then(data => {
        const sel = document.getElementById('patient_id');
        data.forEach(p => {
            const opt = document.createElement('option');
            opt.value = p.id;
            opt.textContent = p.first_name + ' ' + p.last_name + ' (MRN: ' + p.mrn + ')';
            sel.appendChild(opt);
        });
    });

// Fetch studies for FHIR export
function loadStudies() {
    fetch('/api/imaging-studies')
        .then(res => res.json())
        .then(data => {
            const sel = document.getElementById('study_id');
            sel.innerHTML = '<option value="">Select study...</option>';
            data.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id;
                opt.textContent = s.modality + ' - ' + s.description;
                sel.appendChild(opt);
            });
            document.getElementById('export-fhir-btn').disabled = data.length === 0;
        });
}

// Initial load
loadStudies();

// Handle DICOM upload form submission
const uploadForm = document.getElementById('dicom-upload-form');
uploadForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(uploadForm);
    const uploadBtn = document.getElementById('upload-btn');
    const resultDiv = document.getElementById('upload-result');
    const errorDiv = document.getElementById('upload-error');
    
    resultDiv.style.display = 'none';
    errorDiv.style.display = 'none';
    uploadBtn.disabled = true;
    uploadBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading...';
    
    fetch('/dicom/upload', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            errorDiv.textContent = data.error;
            errorDiv.style.display = 'block';
        } else {
            resultDiv.textContent = data.message;
            resultDiv.style.display = 'block';
            uploadForm.reset();
            
            // Reload studies list
            loadStudies();
        }
    })
    .catch(err => {
        errorDiv.textContent = 'Upload failed: ' + err.message;
        errorDiv.style.display = 'block';
    })
    .finally(() => {
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = 'Upload DICOM';
    });
});

// Handle FHIR export
const exportBtn = document.getElementById('export-fhir-btn');
exportBtn.addEventListener('click', function() {
    const studyId = document.getElementById('study_id').value;
    if (!studyId) return;
    
    const fhirOutput = document.getElementById('fhir-output');
    fhirOutput.style.display = 'none';
    exportBtn.disabled = true;
    exportBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Exporting...';
    
    fetch(`/dicom/export-fhir/${studyId}`)
        .then(res => res.json())
        .then(data => {
            fhirOutput.textContent = JSON.stringify(data, null, 2);
            fhirOutput.style.display = 'block';
        })
        .catch(err => {
            document.getElementById('upload-error').textContent = 'FHIR export failed: ' + err.message;
            document.getElementById('upload-error').style.display = 'block';
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