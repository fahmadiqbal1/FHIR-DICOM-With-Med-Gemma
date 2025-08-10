@extends('layouts.app')
@section('title', 'Patients')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Patients</h2>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPatientModal">Add Patient</button>
</div>
<div class="card mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="patients-table" aria-label="Patient list">
                <thead class="table-light">
                    <tr>
                        <th scope="col">MRN</th>
                        <th scope="col">Name</th>
                        <th scope="col">DOB</th>
                        <th scope="col">Sex</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Email</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
<!-- Add/Edit Patient Modal -->
<div class="modal fade" id="addPatientModal" tabindex="-1" aria-labelledby="addPatientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="patient-form" aria-label="Add or edit patient" tabindex="0">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPatientModalLabel">Add Patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="mrn" class="form-label">MRN</label>
                        <input type="text" class="form-control" id="mrn" name="mrn" required>
                    </div>
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="dob">
                    </div>
                    <div class="mb-3">
                        <label for="sex" class="form-label">Sex</label>
                        <select class="form-select" id="sex" name="sex">
                            <option value="">Select...</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                            <option value="unknown">Unknown</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" aria-label="Save patient">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="patient-alert" class="alert d-none mt-3"></div>
@push('scripts')
<script>
function fetchPatients() {
    fetch('/api/patients')
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector('#patients-table tbody');
            tbody.innerHTML = '';
            data.forEach(p => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${p.mrn}</td>
                    <td>${p.first_name} ${p.last_name}</td>
                    <td>${p.dob || ''}</td>
                    <td>${p.sex || ''}</td>
                    <td>${p.phone || ''}</td>
                    <td>${p.email || ''}</td>
                    <td>
                        <button class='btn btn-sm btn-outline-primary me-1' onclick='editPatient(${JSON.stringify(p)})' aria-label='Edit patient ${p.first_name} ${p.last_name}'>Edit</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        });
}
fetchPatients();
// Add/Edit Patient
const form = document.getElementById('patient-form');
form.addEventListener('submit', function(e) {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(form));
    fetch('/api/patients', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(resp => {
        if (resp.error) {
            showAlert(resp.error, 'danger');
        } else {
            showAlert('Patient saved successfully!', 'success');
            fetchPatients();
            var modal = bootstrap.Modal.getInstance(document.getElementById('addPatientModal'));
            modal.hide();
            form.reset();
        }
    })
    .catch(() => showAlert('Failed to save patient.', 'danger'));
});
function editPatient(p) {
    document.getElementById('mrn').value = p.mrn;
    document.getElementById('first_name').value = p.first_name;
    document.getElementById('last_name').value = p.last_name;
    document.getElementById('dob').value = p.dob || '';
    document.getElementById('sex').value = p.sex || '';
    document.getElementById('phone').value = p.phone || '';
    document.getElementById('email').value = p.email || '';
    document.getElementById('address').value = p.address || '';
    var modal = new bootstrap.Modal(document.getElementById('addPatientModal'));
    modal.show();
}
function showAlert(msg, type) {
    const alert = document.getElementById('patient-alert');
    alert.textContent = msg;
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.classList.remove('d-none');
    alert.innerHTML += '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    setTimeout(() => {
        if (alert) alert.classList.add('d-none');
    }, 4000);
}
</script>
@endpush
@endsection
