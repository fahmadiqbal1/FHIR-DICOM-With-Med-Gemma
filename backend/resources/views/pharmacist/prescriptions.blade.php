<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Prescription Processing - Pharmacist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
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
        .prescription-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid #17a2b8;
            transition: all 0.3s ease;
        }
        .prescription-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        .prescription-pending { border-left-color: #ffc107; }
        .prescription-ready { border-left-color: #17a2b8; }
        .prescription-dispensed { border-left-color: #28a745; }
        .prescription-rejected { border-left-color: #dc3545; }
        
        .navbar { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="/pharmacist-dashboard">
                <i class="fas fa-prescription me-2"></i>Prescription Processing
            </a>
            <div class="ms-auto">
                <a href="/pharmacist-dashboard" class="btn btn-outline-light btn-sm me-2">
                    <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                </a>
                <a href="/logout" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <!-- Summary Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-clock fa-2x mb-2 text-warning"></i>
                    <h4 id="pendingCount">-</h4>
                    <small>Pending Review</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-check-circle fa-2x mb-2 text-info"></i>
                    <h4 id="readyCount">-</h4>
                    <small>Ready for Pickup</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-hand-holding-medical fa-2x mb-2 text-success"></i>
                    <h4 id="dispensedCount">-</h4>
                    <small>Dispensed Today</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-dollar-sign fa-2x mb-2 text-success"></i>
                    <h4 id="revenueToday">-</h4>
                    <small>Today's Revenue</small>
                </div>
            </div>
        </div>

        <!-- Filters and Actions -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="glass-card p-3">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Search patient or medication..." id="searchInput">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="ready">Ready</option>
                                <option value="dispensed">Dispensed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="priorityFilter">
                                <option value="">All Priorities</option>
                                <option value="urgent">Urgent</option>
                                <option value="routine">Routine</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="applyFilters()">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card p-3">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" onclick="processNextPrescription()">
                            <i class="fas fa-forward me-2"></i>Process Next
                        </button>
                        <button class="btn btn-outline-light" onclick="generateReport()">
                            <i class="fas fa-file-export me-2"></i>Daily Report
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prescription Queue -->
        <div class="row">
            <div class="col-12">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-list me-2"></i>Prescription Queue</h5>
                    <div id="prescriptionQueue">
                        <div class="text-center p-4">
                            <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                            <p>Loading prescriptions...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Prescription Detail Modal -->
    <div class="modal fade" id="prescriptionModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Prescription Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="prescriptionDetails">
                    <!-- Content will be populated by JavaScript -->
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-warning" onclick="flagPrescription()">Flag Issue</button>
                    <button type="button" class="btn btn-primary" onclick="processPrescription()">Process</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let allPrescriptions = [];
        let filteredPrescriptions = [];
        let selectedPrescription = null;

        document.addEventListener('DOMContentLoaded', function() {
            loadPrescriptions();
        });

        async function loadPrescriptions() {
            try {
                const response = await fetch('/api/pharmacist/prescriptions');
                const data = await response.json();
                allPrescriptions = data.prescriptions || [];
                filteredPrescriptions = [...allPrescriptions];
                
                displayPrescriptions();
                updateSummaryStats();
            } catch (error) {
                console.error('Error loading prescriptions:', error);
                document.getElementById('prescriptionQueue').innerHTML = `
                    <div class="text-center p-4">
                        <i class="fas fa-exclamation-triangle fa-2x mb-3 text-warning"></i>
                        <p>Error loading prescription data</p>
                    </div>
                `;
            }
        }

        function displayPrescriptions() {
            const container = document.getElementById('prescriptionQueue');
            
            if (filteredPrescriptions.length === 0) {
                container.innerHTML = `
                    <div class="text-center p-4">
                        <i class="fas fa-prescription fa-2x mb-3 opacity-50"></i>
                        <p>No prescriptions found</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = filteredPrescriptions.map(rx => `
                <div class="prescription-item prescription-${rx.status}" data-id="${rx.id}">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <h6 class="mb-1">${rx.patient}</h6>
                            <small class="text-info">Dr. ${rx.doctor}</small>
                        </div>
                        <div class="col-md-3">
                            <strong class="d-block">${rx.medication}</strong>
                            <small class="text-muted">Qty: ${rx.quantity}</small>
                        </div>
                        <div class="col-md-2">
                            <strong class="d-block">${rx.time}</strong>
                            <small class="text-muted">Received</small>
                        </div>
                        <div class="col-md-2">
                            <span class="badge bg-${getBadgeColor(rx.status)}">${getStatusText(rx.status)}</span>
                        </div>
                        <div class="col-md-2">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-light" onclick="viewPrescription(${rx.id})" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-light" onclick="printLabel(${rx.id})" title="Print Label">
                                    <i class="fas fa-print"></i>
                                </button>
                                ${rx.status === 'pending' ? 
                                    `<button class="btn btn-primary btn-sm" onclick="markReady(${rx.id})" title="Mark Ready">
                                        <i class="fas fa-check"></i>
                                    </button>` : 
                                    rx.status === 'ready' ?
                                    `<button class="btn btn-success btn-sm" onclick="dispense(${rx.id})" title="Dispense">
                                        <i class="fas fa-hand-holding-medical"></i>
                                    </button>` : ''
                                }
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function updateSummaryStats() {
            const pendingCount = allPrescriptions.filter(rx => rx.status === 'pending').length;
            const readyCount = allPrescriptions.filter(rx => rx.status === 'ready').length;
            const dispensedCount = allPrescriptions.filter(rx => rx.status === 'dispensed').length;
            
            document.getElementById('pendingCount').textContent = pendingCount;
            document.getElementById('readyCount').textContent = readyCount;
            document.getElementById('dispensedCount').textContent = dispensedCount;
            document.getElementById('revenueToday').textContent = '$1,240'; // Mock revenue
        }

        function getBadgeColor(status) {
            const colors = {
                'pending': 'warning',
                'ready': 'info',
                'dispensed': 'success',
                'rejected': 'danger'
            };
            return colors[status] || 'secondary';
        }

        function getStatusText(status) {
            const texts = {
                'pending': 'PENDING',
                'ready': 'READY',
                'dispensed': 'DISPENSED',
                'rejected': 'REJECTED'
            };
            return texts[status] || 'UNKNOWN';
        }

        function applyFilters() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const priorityFilter = document.getElementById('priorityFilter').value;

            filteredPrescriptions = allPrescriptions.filter(rx => {
                const matchesSearch = rx.patient.toLowerCase().includes(searchTerm) || 
                                    rx.medication.toLowerCase().includes(searchTerm);
                const matchesStatus = !statusFilter || rx.status === statusFilter;
                const matchesPriority = !priorityFilter || (rx.priority || 'routine') === priorityFilter;
                
                return matchesSearch && matchesStatus && matchesPriority;
            });

            displayPrescriptions();
        }

        function viewPrescription(id) {
            const prescription = allPrescriptions.find(rx => rx.id === id);
            if (prescription) {
                selectedPrescription = prescription;
                
                const details = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Patient Information</h6>
                            <p><strong>Name:</strong> ${prescription.patient}<br>
                            <strong>DOB:</strong> 08/15/1985<br>
                            <strong>Phone:</strong> (555) 123-4567</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Prescriber Information</h6>
                            <p><strong>Doctor:</strong> ${prescription.doctor}<br>
                            <strong>DEA:</strong> AB1234567<br>
                            <strong>Date:</strong> ${new Date().toLocaleDateString()}</p>
                        </div>
                    </div>
                    <hr>
                    <h6>Prescription Details</h6>
                    <div class="bg-secondary p-3 rounded">
                        <strong>${prescription.medication}</strong><br>
                        Quantity: ${prescription.quantity}<br>
                        Instructions: Take 1 tablet by mouth twice daily with food<br>
                        Refills: 3 remaining<br>
                        Generic Substitution: Yes
                    </div>
                    <hr>
                    <h6>Insurance & Pricing</h6>
                    <p>Insurance: Blue Cross Blue Shield<br>
                    Copay: $10.00<br>
                    Cash Price: $24.50</p>
                `;
                
                document.getElementById('prescriptionDetails').innerHTML = details;
                new bootstrap.Modal(document.getElementById('prescriptionModal')).show();
            }
        }

        function processNextPrescription() {
            const nextPending = allPrescriptions.find(rx => rx.status === 'pending');
            if (nextPending) {
                viewPrescription(nextPending.id);
            } else {
                alert('No pending prescriptions to process');
            }
        }

        function markReady(id) {
            const prescription = allPrescriptions.find(rx => rx.id === id);
            if (prescription && confirm(`Mark ${prescription.medication} for ${prescription.patient} as ready for pickup?`)) {
                prescription.status = 'ready';
                displayPrescriptions();
                updateSummaryStats();
                alert('Prescription marked as ready for pickup');
            }
        }

        function dispense(id) {
            const prescription = allPrescriptions.find(rx => rx.id === id);
            if (prescription && confirm(`Dispense ${prescription.medication} to ${prescription.patient}?`)) {
                prescription.status = 'dispensed';
                displayPrescriptions();
                updateSummaryStats();
                alert('Prescription dispensed successfully');
            }
        }

        function printLabel(id) {
            const prescription = allPrescriptions.find(rx => rx.id === id);
            if (prescription) {
                alert(`Printing prescription label for ${prescription.medication} - ${prescription.patient}`);
            }
        }

        function processPrescription() {
            if (selectedPrescription) {
                selectedPrescription.status = 'ready';
                bootstrap.Modal.getInstance(document.getElementById('prescriptionModal')).hide();
                displayPrescriptions();
                updateSummaryStats();
                alert('Prescription processed and ready for pickup');
            }
        }

        function flagPrescription() {
            if (selectedPrescription) {
                const issue = prompt('Describe the issue with this prescription:');
                if (issue) {
                    alert('Prescription flagged for review. Doctor will be notified.');
                    bootstrap.Modal.getInstance(document.getElementById('prescriptionModal')).hide();
                }
            }
        }

        function generateReport() {
            const today = new Date().toDateString();
            alert(`Daily prescription report for ${today} generated successfully`);
        }

        // Real-time search
        document.getElementById('searchInput').addEventListener('input', applyFilters);
    </script>
</body>
</html>
