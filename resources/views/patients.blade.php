<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Patients - Healthcare AI Platform</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: white;
        }
        
        .header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1rem 0;
            margin-bottom: 2rem;
        }
        
        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
        }
        
        .logo::before {
            content: "🏥";
            margin-right: 0.5rem;
        }
        
        .nav {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .nav a:hover, .nav a.active {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        
        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 15px;
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .page-header h2 {
            font-size: 2rem;
            font-weight: 600;
            color: white;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            font-size: 0.95rem;
        }
        
        .btn-primary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(5px);
        }
        
        .btn-primary:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .btn-outline-primary {
            background: transparent;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        
        .btn-outline-primary:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
        
        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }
        
        .btn-outline-info {
            background: transparent;
            color: #87ceeb;
            border: 1px solid rgba(135, 206, 235, 0.5);
        }
        
        .btn-outline-info:hover {
            background: rgba(135, 206, 235, 0.2);
            color: white;
        }
        
        .btn-outline-danger {
            background: transparent;
            color: #ff6b6b;
            border: 1px solid rgba(255, 107, 107, 0.5);
        }
        
        .btn-outline-danger:hover {
            background: rgba(255, 107, 107, 0.2);
            color: white;
        }
        
        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }
        
        .table-responsive {
            border-radius: 15px;
            overflow: hidden;
        }
        
        .table {
            color: white;
            margin-bottom: 0;
        }
        
        .table th {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-weight: 600;
            padding: 1rem;
        }
        
        .table td {
            border: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem;
        }
        
        .table tbody tr:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .modal {
            backdrop-filter: blur(5px);
        }
        
        .modal-content {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            color: white;
        }
        
        .modal-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1.5rem;
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .modal-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1.5rem;
        }
        
        .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }
        
        /* Patient History Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            backdrop-filter: blur(5px);
        }
        
        .large-modal {
            width: 90%;
            max-width: 1000px;
            max-height: 90vh;
            overflow-y: auto;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            color: white;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .modal-close {
            background: none;
            border: none;
            font-size: 2rem;
            color: white;
            cursor: pointer;
            opacity: 0.8;
            transition: opacity 0.2s;
        }
        
        .modal-close:hover {
            opacity: 1;
        }
        
        .patient-history-tabs {
            display: flex;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 1.5rem;
        }
        
        .tab-btn {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.7);
            padding: 1rem 1.5rem;
            cursor: pointer;
            transition: all 0.2s;
            border-bottom: 2px solid transparent;
        }
        
        .tab-btn:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .tab-btn.active {
            color: white;
            border-bottom-color: #4CAF50;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .history-tab-content {
            display: none;
            padding: 0 1.5rem 1.5rem;
        }
        
        .history-tab-content.active {
            display: block;
        }
        
        .patient-summary {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .patient-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }
        
        .summary-stats {
            display: flex;
            gap: 2rem;
            margin-top: 1.5rem;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            display: block;
            font-size: 2rem;
            font-weight: bold;
            color: #4CAF50;
        }
        
        .stat-label {
            display: block;
            font-size: 0.9rem;
            opacity: 0.8;
            margin-top: 0.5rem;
        }
        
        .history-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid #4CAF50;
        }
        
        .history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .history-date {
            font-size: 0.9rem;
            opacity: 0.7;
        }
        
        .history-content p {
            margin: 0.5rem 0;
            line-height: 1.5;
        }
        
        .no-data {
            text-align: center;
            opacity: 0.7;
            padding: 2rem;
            font-style: italic;
        }
        
        .loading {
            text-align: center;
            padding: 2rem;
            opacity: 0.7;
        }
        
        .error-message {
            text-align: center;
            padding: 2rem;
            background: rgba(255, 0, 0, 0.1);
            border-radius: 8px;
            border: 1px solid rgba(255, 0, 0, 0.3);
        }
        
        .form-label {
            color: white;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            color: white;
            padding: 0.75rem;
            backdrop-filter: blur(5px);
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.6);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .form-select option {
            background: #4a5568;
            color: white;
        }
        
        .spinner-border {
            color: white;
        }
        
        .text-muted {
            color: rgba(255, 255, 255, 0.7) !important;
        }
        
        .alert {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: white;
            backdrop-filter: blur(5px);
        }
        
        .me-1 { margin-right: 0.25rem; }
        .me-2 { margin-right: 0.5rem; }
        .mb-3 { margin-bottom: 1rem; }
        .mb-4 { margin-bottom: 1.5rem; }
        .py-4 { padding-top: 1.5rem; padding-bottom: 1.5rem; }
        .text-center { text-align: center; }
        .d-flex { display: flex; }
        .justify-content-between { justify-content: space-between; }
        .align-items-center { align-items: center; }
        .visually-hidden { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0; }
        
        .row { display: flex; flex-wrap: wrap; margin: 0 -0.5rem; }
        .col-md-6 { flex: 0 0 50%; max-width: 50%; padding: 0 0.5rem; }
        .col-md-12 { flex: 0 0 100%; max-width: 100%; padding: 0 0.5rem; }
        
        @media (max-width: 768px) {
            .col-md-6 { flex: 0 0 100%; max-width: 100%; }
            .header-container { flex-direction: column; gap: 1rem; }
            .nav { flex-wrap: wrap; }
            .page-header { flex-direction: column; align-items: stretch; gap: 1rem; }
            .table-responsive { font-size: 0.85rem; }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <a href="/dashboard" class="logo">Healthcare AI Platform</a>
            
            <nav class="nav">
                <a href="/dashboard">Dashboard</a>
                <a href="/patients" class="active">Patients</a>
                <a href="/medgemma">AI Analysis</a>
                <a href="/reports">Reports</a>
                <a href="/dicom-upload">DICOM Upload</a>
            </nav>
            
            <div class="user-info">
                <span>👤 Welcome</span>
                <form method="POST" action="/logout" style="display: inline;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="logout-btn">🚪 Sign Out</button>
                </form>
            </div>
        </div>
    </header>
    
    <main class="container">
        <div class="page-header">
            <h2>Patients</h2>
            <div>
                <button class="btn btn-outline-primary me-2" onclick="showInvoiceModal()">Generate Invoice</button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPatientModal">Add Patient</button>
            </div>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover" id="patients-table">
                    <thead>
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
                                <div class="spinner-border" role="status">
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
                            <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
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
                            <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Generate Invoice</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS for modal functionality -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
                                <td>${p.mrn || ''}</td>
                                <td>${(p.first_name || '') + ' ' + (p.last_name || '')}</td>
                                <td>${p.dob || ''}</td>
                                <td>${p.sex || ''}</td>
                                <td>${p.phone || ''}</td>
                                <td>${p.email || ''}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info me-1" onclick="viewPatient(${p.id})">View</button>
                                    <button class="btn btn-sm btn-outline-primary me-1" onclick="editPatient(${p.id})">Edit</button>
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
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4" style="color: #ff6b6b;">Error loading patients</td></tr>';
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

        function viewPatient(patientId) {
            // Show patient history modal with comprehensive data
            showPatientHistoryModal(patientId);
        }
        
        function showPatientHistoryModal(patientId) {
            // Create and show modal for patient history
            const modal = document.createElement('div');
            modal.className = 'modal-overlay';
            modal.innerHTML = `
                <div class="modal-content large-modal">
                    <div class="modal-header">
                        <h3>Patient History</h3>
                        <button class="modal-close" onclick="closePatientHistoryModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div id="patientHistoryContent">
                            <div class="loading">Loading patient history...</div>
                        </div>
                    </div>
                </div>
            `;
            modal.id = 'patientHistoryModal';
            document.body.appendChild(modal);
            
            // Load patient data
            loadPatientHistory(patientId);
        }
        
        function closePatientHistoryModal() {
            const modal = document.getElementById('patientHistoryModal');
            if (modal) {
                modal.remove();
            }
        }
        
        async function loadPatientHistory(patientId) {
            try {
                // Use authenticated API endpoints - these require login
                const patient = await fetch(`/api/patients/${patientId}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                }).then(r => {
                    if (!r.ok) throw new Error('Patient not found');
                    return r.json();
                });
                
                // Try to load notes, orders, and imaging - fail gracefully if endpoints don't exist or require auth
                const [notes, orders, imaging] = await Promise.allSettled([
                    fetch(`/api/patients/${patientId}/notes`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    }).then(r => r.ok ? r.json() : []),
                    fetch(`/api/patients/${patientId}/orders`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    }).then(r => r.ok ? r.json() : []),
                    fetch(`/api/patients/${patientId}/imaging`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    }).then(r => r.ok ? r.json() : [])
                ]);
                
                const patientNotes = notes.status === 'fulfilled' ? notes.value : [];
                const patientOrders = orders.status === 'fulfilled' ? orders.value : [];
                const patientImaging = imaging.status === 'fulfilled' ? imaging.value : [];
                
                // Display comprehensive patient history
                displayPatientHistory(patient, patientNotes, patientOrders, patientImaging);
                
            } catch (error) {
                console.error('Error loading patient history:', error);
                document.getElementById('patientHistoryContent').innerHTML = `
                    <div class="error-message">
                        <p>Error loading patient history: ${error.message}</p>
                        <p>Please ensure you are logged in and have permission to view patient data.</p>
                        <button class="btn primary" onclick="loadPatientHistory(${patientId})">Retry</button>
                    </div>
                `;
            }
        }
        
        function displayPatientHistory(patient, notes, orders, imaging) {
            const content = document.getElementById('patientHistoryContent');
            
            content.innerHTML = `
                <div class="patient-history-tabs">
                    <button class="tab-btn active" onclick="switchHistoryTab('overview')">Overview</button>
                    <button class="tab-btn" onclick="switchHistoryTab('notes')">Clinical Notes (${notes.length})</button>
                    <button class="tab-btn" onclick="switchHistoryTab('orders')">Lab Orders (${orders.length})</button>
                    <button class="tab-btn" onclick="switchHistoryTab('imaging')">Imaging (${imaging.length})</button>
                </div>
                
                <div id="overview-history" class="history-tab-content active">
                    <div class="patient-summary">
                        <h4>${patient.first_name} ${patient.last_name}</h4>
                        <div class="patient-details">
                            <p><strong>MRN:</strong> ${patient.mrn || 'N/A'}</p>
                            <p><strong>DOB:</strong> ${patient.dob || 'N/A'}</p>
                            <p><strong>Sex:</strong> ${patient.sex || 'N/A'}</p>
                            <p><strong>Phone:</strong> ${patient.phone || 'N/A'}</p>
                            <p><strong>Email:</strong> ${patient.email || 'N/A'}</p>
                        </div>
                        <div class="summary-stats">
                            <div class="stat-item">
                                <span class="stat-number">${notes.length}</span>
                                <span class="stat-label">Clinical Notes</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">${orders.length}</span>
                                <span class="stat-label">Lab Orders</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">${imaging.length}</span>
                                <span class="stat-label">Imaging Studies</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="notes-history" class="history-tab-content">
                    ${notes.length > 0 ? notes.map(note => `
                        <div class="history-item">
                            <div class="history-header">
                                <strong>Clinical Note</strong>
                                <span class="history-date">${new Date(note.created_at).toLocaleDateString()}</span>
                            </div>
                            <div class="history-content">
                                ${note.soap_subjective ? `<p><strong>Subjective:</strong> ${note.soap_subjective}</p>` : ''}
                                ${note.soap_objective ? `<p><strong>Objective:</strong> ${note.soap_objective}</p>` : ''}
                                ${note.soap_assessment ? `<p><strong>Assessment:</strong> ${note.soap_assessment}</p>` : ''}
                                ${note.soap_plan ? `<p><strong>Plan:</strong> ${note.soap_plan}</p>` : ''}
                            </div>
                        </div>
                    `).join('') : '<p class="no-data">No clinical notes found.</p>'}
                </div>
                
                <div id="orders-history" class="history-tab-content">
                    ${orders.length > 0 ? orders.map(order => `
                        <div class="history-item">
                            <div class="history-header">
                                <strong>Lab Order</strong>
                                <span class="history-date">${new Date(order.created_at).toLocaleDateString()}</span>
                            </div>
                            <div class="history-content">
                                <p><strong>Test:</strong> ${order.test_name || 'N/A'}</p>
                                <p><strong>Status:</strong> ${order.status || 'N/A'}</p>
                                ${order.results ? `<p><strong>Results:</strong> ${order.results}</p>` : ''}
                                ${order.notes ? `<p><strong>Notes:</strong> ${order.notes}</p>` : ''}
                            </div>
                        </div>
                    `).join('') : '<p class="no-data">No lab orders found.</p>'}
                </div>
                
                <div id="imaging-history" class="history-tab-content">
                    ${imaging.length > 0 ? imaging.map(study => `
                        <div class="history-item">
                            <div class="history-header">
                                <strong>Imaging Study</strong>
                                <span class="history-date">${new Date(study.created_at).toLocaleDateString()}</span>
                            </div>
                            <div class="history-content">
                                <p><strong>Description:</strong> ${study.description || 'N/A'}</p>
                                <p><strong>Modality:</strong> ${study.modality || 'N/A'}</p>
                                <p><strong>Status:</strong> ${study.status || 'N/A'}</p>
                                ${study.notes ? `<p><strong>Notes:</strong> ${study.notes}</p>` : ''}
                            </div>
                        </div>
                    `).join('') : '<p class="no-data">No imaging studies found.</p>'}
                </div>
            `;
        }
        
        function switchHistoryTab(tabName) {
            // Remove active class from all tabs and content
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.history-tab-content').forEach(content => content.classList.remove('active'));
            
            // Add active class to selected tab and content
            event.target.classList.add('active');
            document.getElementById(`${tabName}-history`).classList.add('active');
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
</body>
</html>
