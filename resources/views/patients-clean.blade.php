@extends('layouts.app')
@section('title', 'Patients')
@section('content')

<style>
    /* Next-gen patient list styling */
    .patients-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding: 0.5rem 0;
    }
    .patients-header h2 {
        font-size: 2.2rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        color: #fff;
        margin-bottom: 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .patients-actions button {
        font-size: 1rem;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(102,126,234,0.08);
        margin-right: 0.5rem;
        transition: box-shadow 0.2s;
    }
    .patients-actions button:last-child { margin-right: 0; }
    .patients-actions button:hover {
        box-shadow: 0 4px 16px rgba(118,75,162,0.15);
    }
    .patients-list-container {
        background: rgba(255,255,255,0.04);
        border-radius: 18px;
        box-shadow: 0 4px 32px rgba(102,126,234,0.08);
        padding: 2rem 1rem 1rem 1rem;
        margin-bottom: 2rem;
    }
    .patients-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 1rem;
    }
    .patients-table th {
        background: rgba(102,126,234,0.12);
        color: #fff;
        font-weight: 600;
        padding: 1rem 0.75rem;
        border: none;
        font-size: 1.05rem;
        text-align: left;
    }
    .patients-table td {
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
    .patients-table tr {
        transition: box-shadow 0.2s, background 0.2s;
    }
    .patients-table tr:not(.table-header):hover td {
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        box-shadow: 0 4px 16px rgba(118,75,162,0.15);
    }
    .patients-table tr:not(.table-header) {
        border-bottom: 2px solid rgba(102,126,234,0.08);
    }
    .patients-table td.actions {
        text-align: right;
        min-width: 140px;
    }
    .patients-table td.name {
        font-weight: 600;
        letter-spacing: 0.01em;
        font-size: 1.08rem;
        padding-left: 1.2rem;
    }
    .patients-table td {
        border-right: 1px solid rgba(102,126,234,0.04);
    }
    @media (max-width: 900px) {
        .patients-list-container { padding: 1rem 0.2rem; }
        .patients-table th, .patients-table td { font-size: 0.95rem; padding: 0.7rem 0.4rem; }
    }
    @media (max-width: 600px) {
        .patients-header { flex-direction: column; align-items: flex-start; }
        .patients-actions { margin-top: 1rem; }
        .patients-table th, .patients-table td { font-size: 0.9rem; padding: 0.5rem 0.2rem; }
    }
    
    /* Next-gen modal styling */
    .modal-content {
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 18px;
        box-shadow: 0 4px 32px rgba(102,126,234,0.08);
    }
    .modal-header {
        border-bottom: 1px solid rgba(255,255,255,0.1);
        padding: 1.5rem;
    }
    .modal-title {
        color: #fff;
        font-weight: 600;
        font-size: 1.3rem;
    }
    .modal-body {
        padding: 1.5rem;
    }
    .form-label {
        color: #fff;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    .form-control, .form-select {
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.2);
        color: #fff;
        border-radius: 8px;
        padding: 0.7rem;
    }
    .form-control:focus, .form-select:focus {
        background: rgba(255,255,255,0.12);
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102,126,234,0.25);
        color: #fff;
    }
    .form-control::placeholder {
        color: rgba(255,255,255,0.6);
    }
    .btn {
        border-radius: 8px;
        font-weight: 500;
        padding: 0.6rem 1.2rem;
        transition: all 0.3s;
    }
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        transform: translateY(-1px);
    }
    .btn-secondary {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        color: #fff;
    }
    .btn-secondary:hover {
        background: rgba(255,255,255,0.2);
        color: #fff;
    }
</style>

<div class="patients-header">
    <h2>Patients</h2>
    <div class="patients-actions">
        <button class="btn btn-outline-primary me-2" onclick="showInvoiceModal()">Generate Invoice</button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPatientModal">Add Patient</button>
    </div>
</div>

<div class="patients-list-container">
    <div class="table-responsive">
        <table class="patients-table" id="patients-table">
            <thead class="table-header">
                <tr>
                    <th>MRN</th>
                    <th>Name</th>
                    <th>DOB</th>
                    <th>Sex</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading patients...</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Patient Modal -->
<div class="modal fade" id="addPatientModal" tabindex="-1" aria-labelledby="addPatientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="patient-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPatientModalLabel">Add Patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mrn" class="form-label">MRN *</label>
                                <input type="text" class="form-control" id="mrn" name="mrn" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="dob" name="dob">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
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
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Patient</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Generate Invoice Modal -->
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="invoiceForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="invoiceModalLabel">Generate Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="invoicePatient" class="form-label">Select Patient *</label>
                                <select id="invoicePatient" name="patient_id" class="form-select" required>
                                    <option value="">Choose a patient...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="invoiceDoctor" class="form-label">Assign Doctor *</label>
                                <select id="invoiceDoctor" name="doctor_id" class="form-select" required>
                                    <option value="">Select Doctor...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="invoiceCheckupFee" class="form-label">Check-up Fee (PKR) *</label>
                                <input type="number" id="invoiceCheckupFee" name="checkup_fee" class="form-control" step="0.01" min="0" required placeholder="0.00">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="invoiceServiceDescription" class="form-label">Service Description</label>
                        <textarea id="invoiceServiceDescription" name="service_description" class="form-control" rows="4" placeholder="Follow-up visit, consultation, etc."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Generate Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentEditingPatient = null;

// Fetch and display patients
function fetchPatients() {
    fetch('/api/patients')
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector('#patients-table tbody');
            const invoicePatientSelect = document.getElementById('invoicePatient');
            
            tbody.innerHTML = '';
            invoicePatientSelect.innerHTML = '<option value="">Choose a patient...</option>';
            
            if (data && data.length > 0) {
                data.forEach(p => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td style="color: #fff;">${p.mrn || ''}</td>
                        <td class="name" style="color: #fff; font-weight: 600;">${(p.first_name || '') + ' ' + (p.last_name || '')}</td>
                        <td style="color: #fff;">${p.dob || ''}</td>
                        <td style="color: #fff;">${p.sex || ''}</td>
                        <td style="color: #fff;">${p.phone || ''}</td>
                        <td style="color: #fff; word-wrap: break-word; max-width: 200px;">${p.email || ''}</td>
                        <td class="actions" style="color: #fff;">
                            <button class="btn btn-sm btn-outline-primary me-1" onclick="editPatient(${p.id})" style="margin-right: 0.5rem;">Edit</button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deletePatient(${p.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                    
                    // Add to invoice select
                    const option = document.createElement('option');
                    option.value = p.id;
                    option.textContent = `${(p.first_name || '') + ' ' + (p.last_name || '')} (${p.mrn || ''})`;
                    invoicePatientSelect.appendChild(option);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-muted">No patients found</td></tr>';
            }
        })
        .catch(err => {
            console.error('Error fetching patients:', err);
            const tbody = document.querySelector('#patients-table tbody');
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-danger">Error loading patients</td></tr>';
        });
}

// Load doctors for dropdowns
function loadDoctors() {
    fetch('/api/doctors')
        .then(res => res.json())
        .then(doctors => {
            const doctorSelect = document.getElementById('invoiceDoctor');
            doctorSelect.innerHTML = '<option value="">Select Doctor...</option>';
            
            if (doctors && doctors.length > 0) {
                doctors.forEach(doctor => {
                    const option = document.createElement('option');
                    option.value = doctor.id;
                    option.textContent = doctor.name;
                    doctorSelect.appendChild(option);
                });
            }
        })
        .catch(err => console.error('Error loading doctors:', err));
}

// Edit patient
function editPatient(patientId) {
    fetch(`/api/patients/${patientId}`)
        .then(res => res.json())
        .then(patient => {
            currentEditingPatient = patient.id;
            
            // Populate form fields
            document.getElementById('mrn').value = patient.mrn || '';
            document.getElementById('first_name').value = patient.first_name || '';
            document.getElementById('last_name').value = patient.last_name || '';
            document.getElementById('dob').value = patient.dob || '';
            document.getElementById('sex').value = patient.sex || '';
            document.getElementById('phone').value = patient.phone || '';
            document.getElementById('email').value = patient.email || '';
            document.getElementById('address').value = patient.address || '';
            
            // Update modal title
            document.getElementById('addPatientModalLabel').textContent = 'Edit Patient';
            document.querySelector('#patient-form button[type="submit"]').textContent = 'Update Patient';
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('addPatientModal'));
            modal.show();
        })
        .catch(err => {
            console.error('Error fetching patient for edit:', err);
            alert('Error loading patient data');
        });
}

// Delete patient
function deletePatient(patientId) {
    if (!confirm('Are you sure you want to delete this patient? This action cannot be undone.')) {
        return;
    }
    
    fetch(`/api/patients/${patientId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.message) {
            alert(data.message);
            fetchPatients();
        }
    })
    .catch(err => {
        console.error('Error deleting patient:', err);
        alert('Error deleting patient');
    });
}

// Show invoice modal
function showInvoiceModal() {
    const modal = new bootstrap.Modal(document.getElementById('invoiceModal'));
    modal.show();
}

// Handle patient form submission
document.getElementById('patient-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    
    const url = currentEditingPatient ? `/api/patients/${currentEditingPatient}` : '/api/patients';
    const method = currentEditingPatient ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(result => {
        if (result.patient) {
            alert(result.message);
            fetchPatients();
            
            // Reset form and modal
            this.reset();
            currentEditingPatient = null;
            document.getElementById('addPatientModalLabel').textContent = 'Add Patient';
            document.querySelector('#patient-form button[type="submit"]').textContent = 'Save Patient';
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('addPatientModal'));
            if (modal) modal.hide();
        } else {
            alert(result.message || 'Error saving patient');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Error saving patient');
    });
});

// Handle invoice form submission
document.getElementById('invoiceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        patient_id: formData.get('patient_id'),
        doctor_id: formData.get('doctor_id'),
        service_type: 'Consultation',
        amount: formData.get('checkup_fee'),
        description: formData.get('service_description') || 'Medical consultation'
    };
    
    fetch('/api/invoices', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(result => {
        if (result.invoice) {
            alert('Invoice created and sent to invoices@avivahealthcare.org');
            this.reset();
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('invoiceModal'));
            if (modal) modal.hide();
            
            if (result.view_url) {
                setTimeout(() => {
                    window.open(result.view_url, '_blank');
                }, 1000);
            }
        } else {
            alert(result.message || 'Error creating invoice');
        }
    })
    .catch(err => {
        console.error('Error creating invoice:', err);
        alert('Error creating invoice');
    });
});

// Reset form when modal is hidden
document.getElementById('addPatientModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('patient-form').reset();
    currentEditingPatient = null;
    document.getElementById('addPatientModalLabel').textContent = 'Add Patient';
    document.querySelector('#patient-form button[type="submit"]').textContent = 'Save Patient';
});

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    fetchPatients();
    loadDoctors();
});
</script>
@endsection
