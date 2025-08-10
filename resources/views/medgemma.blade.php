@extends('layouts.app')
@section('title', 'MedGemma AI Analysis')
@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="mb-3">MedGemma AI Imaging & Lab Analysis</h2>
        <p class="text-muted">Run AI-powered analysis on imaging studies and lab results for your patients. Select a patient and study to begin.</p>
    </div>
</div>
<div class="card mb-4">
    <div class="card-body">
        <form id="medgemma-form" class="row g-3">
            <div class="col-md-5">
                <label for="patient_id" class="form-label">Patient</label>
                <select class="form-select" id="patient_id" name="patient_id" required>
                    <option value="">Select patient...</option>
                </select>
            </div>
            <div class="col-md-5">
                <label for="study_id" class="form-label">Imaging Study</label>
                <select class="form-select" id="study_id" name="study_id" required disabled>
                    <option value="">Select study...</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100" id="analyze-btn" disabled>Analyze</button>
            </div>
        </form>
    </div>
</div>
<div class="text-center mb-4">
    <button class="btn btn-outline-secondary" id="analyze-labs-btn" disabled>Analyze Labs for Patient</button>
    <button class="btn btn-outline-info ms-2" id="second-opinion-btn" disabled>Combined Second Opinion</button>
</div>
<div id="ai-result" style="display:none">
    <div class="card border-success mb-4">
        <div class="card-header bg-success text-white">AI Analysis Result</div>
        <div class="card-body">
            <h5 class="card-title">Impression</h5>
            <p id="impression"></p>
            <h6>Findings</h6>
            <ul id="findings"></ul>
            <h6>Recommendations</h6>
            <ul id="recommendations"></ul>
            <div class="mt-3">
                <span class="badge bg-info">Confidence: <span id="confidence"></span></span>
            </div>
        </div>
    </div>
</div>
<div id="labs-result" style="display:none">
    <div class="card border-primary mb-4">
        <div class="card-header bg-primary text-white">Lab AI Comments</div>
        <div class="card-body">
            <ul id="lab-comments"></ul>
        </div>
    </div>
</div>
<div id="second-opinion-result" style="display:none">
    <div class="card border-warning mb-4">
        <div class="card-header bg-warning text-dark">Combined Second Opinion</div>
        <div class="card-body">
            <h6>Imaging</h6>
            <ul id="so-imaging"></ul>
            <h6>Labs</h6>
            <ul id="so-labs"></ul>
            <h6>Medications</h6>
            <ul id="so-meds"></ul>
        </div>
    </div>
</div>
<div id="ai-error" class="alert alert-danger" style="display:none"></div>
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
// When patient selected, fetch studies
const patientSel = document.getElementById('patient_id');
const studySel = document.getElementById('study_id');
patientSel.addEventListener('change', function() {
    studySel.innerHTML = '<option value="">Select study...</option>';
    studySel.disabled = true;
    document.getElementById('analyze-btn').disabled = true;
    if (this.value) {
        fetch(`/api/patients/${this.value}/studies`)
            .then(res => res.json())
            .then(data => {
                data.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s.id;
                    opt.textContent = s.modality + ' - ' + s.description;
                    studySel.appendChild(opt);
                });
                studySel.disabled = false;
            });
    }
});
studySel.addEventListener('change', function() {
    document.getElementById('analyze-btn').disabled = !this.value;
});
// Enable lab/second opinion buttons when patient is selected
patientSel.addEventListener('change', function() {
    document.getElementById('analyze-labs-btn').disabled = !this.value;
    document.getElementById('second-opinion-btn').disabled = !this.value;
});
// Handle form submit
const form = document.getElementById('medgemma-form');
form.addEventListener('submit', function(e) {
    e.preventDefault();
    document.getElementById('ai-result').style.display = 'none';
    document.getElementById('ai-error').style.display = 'none';
    const patientId = patientSel.value;
    const studyId = studySel.value;
    if (!patientId || !studyId) return;
    document.getElementById('analyze-btn').disabled = true;
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
            document.getElementById('ai-error').textContent = data.error;
            document.getElementById('ai-error').style.display = 'block';
        } else {
            document.getElementById('impression').textContent = data.impression || 'N/A';
            document.getElementById('findings').innerHTML = (data.findings || []).map(f => `<li>${f}</li>`).join('');
            document.getElementById('recommendations').innerHTML = (data.recommendations || []).map(r => `<li>${r}</li>`).join('');
            document.getElementById('confidence').textContent = data.confidence ? (data.confidence * 100).toFixed(1) + '%' : '--';
            document.getElementById('ai-result').style.display = 'block';
        }
    })
    .catch(() => {
        document.getElementById('ai-error').textContent = 'Failed to contact MedGemma AI service.';
        document.getElementById('ai-error').style.display = 'block';
    })
    .finally(() => {
        document.getElementById('analyze-btn').disabled = false;
    });
});
// Analyze Labs
const labsBtn = document.getElementById('analyze-labs-btn');
labsBtn.addEventListener('click', function() {
    const patientId = patientSel.value;
    if (!patientId) return;
    labsBtn.disabled = true;
    document.getElementById('labs-result').style.display = 'none';
    document.getElementById('ai-error').style.display = 'none';
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
            document.getElementById('lab-comments').innerHTML = data.lab_comments.map(c => `<li>${c}</li>`).join('');
            document.getElementById('labs-result').style.display = 'block';
        } else {
            document.getElementById('ai-error').textContent = data.error || 'No lab comments.';
            document.getElementById('ai-error').style.display = 'block';
        }
    })
    .catch(() => {
        document.getElementById('ai-error').textContent = 'Failed to contact MedGemma AI service.';
        document.getElementById('ai-error').style.display = 'block';
    })
    .finally(() => {
        labsBtn.disabled = false;
    });
});
// Combined Second Opinion
const soBtn = document.getElementById('second-opinion-btn');
soBtn.addEventListener('click', function() {
    const patientId = patientSel.value;
    if (!patientId) return;
    soBtn.disabled = true;
    document.getElementById('second-opinion-result').style.display = 'none';
    document.getElementById('ai-error').style.display = 'none';
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
            document.getElementById('so-imaging').innerHTML = (data.imaging || []).map(i => `<li>${i.impression || i}</li>`).join('');
            document.getElementById('so-labs').innerHTML = (data.labs || []).map(l => `<li>${l}</li>`).join('');
            document.getElementById('so-meds').innerHTML = (data.medications || []).map(m => `<li>${m.medication} (${m.dosage})</li>`).join('');
            document.getElementById('second-opinion-result').style.display = 'block';
        } else {
            document.getElementById('ai-error').textContent = data.error || 'No second opinion available.';
            document.getElementById('ai-error').style.display = 'block';
        }
    })
    .catch(() => {
        document.getElementById('ai-error').textContent = 'Failed to contact MedGemma AI service.';
        document.getElementById('ai-error').style.display = 'block';
    })
    .finally(() => {
        soBtn.disabled = false;
    });
});
</script>
@endpush
@endsection
