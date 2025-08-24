<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Invoice Management - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
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
        .navbar {
            background: rgba(22, 33, 62, 0.2);
            backdrop-filter: blur(10px);
        }
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }
        .form-control::placeholder { color: rgba(255, 255, 255, 0.7); }
        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #0d6efd;
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        .table-dark {
            --bs-table-bg: rgba(255, 255, 255, 0.05);
            --bs-table-border-color: rgba(255, 255, 255, 0.1);
        }
        .invoice-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #28a745;
        }
        .invoice-pending { border-left-color: #ffc107; }
        .invoice-paid { border-left-color: #28a745; }
        .invoice-overdue { border-left-color: #dc3545; }
        .amount-highlight {
            font-size: 1.2rem;
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="/dashboard">
                <i class="fas fa-file-invoice-dollar me-2"></i>Invoice Management - Admin
            </a>
            <div class="ms-auto">
                <a href="/dashboard" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Invoice Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-file-invoice fa-2x mb-2 text-primary"></i>
                    <h5 id="totalInvoices">0</h5>
                    <small>Total Invoices</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                    <h5 id="paidInvoices">0</h5>
                    <small>Paid Invoices</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-clock fa-2x mb-2 text-warning"></i>
                    <h5 id="pendingInvoices">0</h5>
                    <small>Pending Invoices</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-dollar-sign fa-2x mb-2 text-success"></i>
                    <h5 id="totalRevenue">$0</h5>
                    <small>Total Revenue</small>
                </div>
            </div>
        </div>

        <!-- Invoice Management Controls -->
        <div class="glass-card p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5><i class="fas fa-file-invoice me-2"></i>Invoice Management</h5>
                <div>
                    <button class="btn btn-outline-light me-2" data-bs-toggle="modal" data-bs-target="#bulkActionsModal">
                        <i class="fas fa-layer-group me-2"></i>Bulk Actions
                    </button>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createInvoiceModal">
                        <i class="fas fa-plus me-2"></i>Create Invoice
                    </button>
                </div>
            </div>
            
            <!-- Invoice Filter -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <select class="form-select" id="statusFilter" onchange="filterInvoices()">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="overdue">Overdue</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" id="dateFilter" onchange="filterInvoices()">
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="searchInvoices" placeholder="Search invoices..." onkeyup="searchInvoices()">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-light w-100" onclick="exportInvoices()">
                        <i class="fas fa-download me-2"></i>Export Invoices
                    </button>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" class="form-check-input" id="selectAll" onchange="toggleSelectAll()">
                            </th>
                            <th>Invoice #</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Services</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="invoicesTable">
                        <!-- Invoices will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="glass-card p-4">
            <h5 class="mb-3"><i class="fas fa-history me-2"></i>Recent Invoice Activity</h5>
            <div id="recentActivity">
                <!-- Recent activity will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Create Invoice Modal -->
    <div class="modal fade" id="createInvoiceModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background: rgba(22, 33, 62, 0.95); color: white;">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Create New Invoice</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="createInvoiceForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="patient_id" class="form-label">Patient</label>
                                    <select class="form-select" id="patient_id" name="patient_id" required>
                                        <option value="">Select a patient...</option>
                                        <!-- Patients will be loaded via JavaScript -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="doctor_id" class="form-label">Doctor</label>
                                    <select class="form-select" id="doctor_id" name="doctor_id" required>
                                        <option value="">Select a doctor...</option>
                                        <!-- Doctors will be loaded via JavaScript -->
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="service_type" class="form-label">Service Type</label>
                                    <select class="form-select" id="service_type" name="service_type" required>
                                        <option value="">Select service type...</option>
                                        <option value="consultation">Consultation</option>
                                        <option value="laboratory">Laboratory Tests</option>
                                        <option value="radiology">Radiology</option>
                                        <option value="pharmacy">Pharmacy</option>
                                        <option value="procedure">Medical Procedure</option>
                                        <option value="emergency">Emergency Care</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount ($)</label>
                                    <input type="number" class="form-control" id="amount" name="amount" min="0" step="0.01" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description/Notes</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Detailed description of services..."></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">Due Date</label>
                                    <input type="date" class="form-control" id="due_date" name="due_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" name="status" id="status">
                                        <option value="pending">Pending</option>
                                        <option value="paid">Paid</option>
                                        <option value="overdue">Overdue</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Create Invoice
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Modal -->
    <div class="modal fade" id="bulkActionsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="background: rgba(22, 33, 62, 0.95); color: white;">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-layer-group me-2"></i>Bulk Actions</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Action</label>
                        <select class="form-select" id="bulkAction">
                            <option value="mark-paid">Mark as Paid</option>
                            <option value="mark-pending">Mark as Pending</option>
                            <option value="send-reminder">Send Payment Reminder</option>
                            <option value="export-selected">Export Selected</option>
                            <option value="delete">Delete Selected</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="executeBulkAction()">
                        <i class="fas fa-check me-2"></i>Execute Action
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadInvoices();
            loadPatients();
            loadDoctors();
            loadRecentActivity();
            
            // Set default due date to 30 days from today
            const today = new Date();
            const dueDate = new Date(today);
            dueDate.setDate(today.getDate() + 30);
            document.getElementById('due_date').value = dueDate.toISOString().split('T')[0];
            
            // Handle create invoice form submission
            document.getElementById('createInvoiceForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const invoiceData = Object.fromEntries(formData.entries());
                
                try {
                    const response = await fetch('/admin/api/invoices', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(invoiceData)
                    });
                    
                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Failed to create invoice');
                    }
                    
                    const invoiceResult = await response.json();
                    
                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('createInvoiceModal')).hide();
                    
                    // Redirect to invoice preview page
                    if (invoiceResult.view_url) {
                        window.open(invoiceResult.view_url, '_blank');
                    }
                    
                    // Refresh the invoices list
                    loadInvoices();
                    
                    // Reset form
                    this.reset();
                    
                } catch (error) {
                    console.error('Error creating invoice:', error);
                    alert('Failed to create invoice: ' + error.message);
                }
            });
        });

        function loadInvoices() {
            fetch('/api/invoices')
                .then(response => response.json())
                .then(data => {
                    updateInvoiceStats(data);
                    renderInvoicesTable(data);
                })
                .catch(error => {
                    console.error('Error loading invoices:', error);
                    loadDemoInvoices();
                });
        }

        function loadDemoInvoices() {
            const demoData = [
                {
                    id: 1,
                    invoice_number: 'INV-2023-001',
                    patient_name: 'John Doe',
                    doctor_name: 'Dr. Smith',
                    service_type: 'Consultation',
                    amount: 150.00,
                    status: 'paid',
                    due_date: '2023-12-25',
                    created_at: '2023-12-01'
                },
                {
                    id: 2,
                    invoice_number: 'INV-2023-002',
                    patient_name: 'Jane Smith',
                    doctor_name: 'Dr. Johnson',
                    service_type: 'Laboratory Tests',
                    amount: 250.00,
                    status: 'pending',
                    due_date: '2024-01-15',
                    created_at: '2023-12-15'
                },
                {
                    id: 3,
                    invoice_number: 'INV-2023-003',
                    patient_name: 'Mike Wilson',
                    doctor_name: 'Dr. Brown',
                    service_type: 'Radiology',
                    amount: 450.00,
                    status: 'overdue',
                    due_date: '2023-11-30',
                    created_at: '2023-11-01'
                },
                {
                    id: 4,
                    invoice_number: 'INV-2023-004',
                    patient_name: 'Sarah Davis',
                    doctor_name: 'Dr. Wilson',
                    service_type: 'Emergency Care',
                    amount: 800.00,
                    status: 'paid',
                    due_date: '2023-12-20',
                    created_at: '2023-11-20'
                }
            ];
            
            updateInvoiceStats(demoData);
            renderInvoicesTable(demoData);
        }

        function updateInvoiceStats(invoices) {
            const totalInvoices = invoices.length;
            const paidInvoices = invoices.filter(inv => inv.status === 'paid').length;
            const pendingInvoices = invoices.filter(inv => inv.status === 'pending').length;
            const totalRevenue = invoices
                .filter(inv => inv.status === 'paid')
                .reduce((sum, inv) => sum + parseFloat(inv.amount), 0);

            document.getElementById('totalInvoices').textContent = totalInvoices;
            document.getElementById('paidInvoices').textContent = paidInvoices;
            document.getElementById('pendingInvoices').textContent = pendingInvoices;
            document.getElementById('totalRevenue').textContent = '$' + totalRevenue.toLocaleString('en-US', {minimumFractionDigits: 2});
        }

        function renderInvoicesTable(invoices) {
            const tbody = document.getElementById('invoicesTable');
            tbody.innerHTML = '';

            invoices.forEach(invoice => {
                const statusClass = invoice.status === 'paid' ? 'success' : 
                                   invoice.status === 'overdue' ? 'danger' : 'warning';
                
                const row = `
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input invoice-checkbox" value="${invoice.id}">
                        </td>
                        <td><strong>${invoice.invoice_number || 'INV-' + invoice.id}</strong></td>
                        <td>${invoice.patient_name || 'Unknown Patient'}</td>
                        <td>${invoice.doctor_name || 'Unknown Doctor'}</td>
                        <td>${invoice.service_type || 'General Service'}</td>
                        <td class="amount-highlight">$${parseFloat(invoice.amount).toFixed(2)}</td>
                        <td><span class="badge bg-${statusClass}">${invoice.status.charAt(0).toUpperCase() + invoice.status.slice(1)}</span></td>
                        <td>${new Date(invoice.created_at).toLocaleDateString()}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-info" onclick="viewInvoice(${invoice.id})" title="View PDF">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-outline-success" onclick="downloadInvoice(${invoice.id})" title="Download PDF">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button class="btn btn-outline-primary" onclick="sendInvoice(${invoice.id})" title="Send Email">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        function loadPatients() {
            fetch('/api/patients', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'same-origin'
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch patients');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Patients loaded:', data);
                    const select = document.getElementById('patient_id');
                    select.innerHTML = '<option value="">Select a patient...</option>';
                    
                    // Handle both paginated and direct array responses
                    const patients = data.data || data;
                    if (Array.isArray(patients)) {
                        patients.forEach(patient => {
                            const name = patient.name || (patient.first_name + ' ' + patient.last_name) || 'Unknown Patient';
                            select.innerHTML += `<option value="${patient.id}">${name} (MRN: ${patient.mrn || 'N/A'})</option>`;
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading patients:', error);
                    // Load demo patients as fallback
                    const select = document.getElementById('patient_id');
                    select.innerHTML = '<option value="">Select a patient...</option>' +
                                     '<option value="1">John Smith (MRN: P001)</option>' +
                                     '<option value="2">Jane Johnson (MRN: P002)</option>' +
                                     '<option value="3">Michael Brown (MRN: P003)</option>' +
                                     '<option value="4">Sarah Davis (MRN: P004)</option>';
                });
        }

        function loadDoctors() {
            fetch('/admin/api/doctors', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'same-origin'
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch doctors');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Doctors loaded:', data);
                    const select = document.getElementById('doctor_id');
                    select.innerHTML = '<option value="">Select a doctor...</option>';
                    
                    if (Array.isArray(data)) {
                        data.forEach(doctor => {
                            select.innerHTML += `<option value="${doctor.id}">${doctor.name} (${doctor.email})</option>`;
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading doctors:', error);
                    // Load demo doctors as fallback
                    const select = document.getElementById('doctor_id');
                    select.innerHTML = '<option value="">Select a doctor...</option>' +
                                     '<option value="10">Dr. Sarah Johnson (doctor1@medgemma.com)</option>' +
                                     '<option value="11">Dr. Michael Chen (doctor2@medgemma.com)</option>' +
                                     '<option value="12">Dr. MedGemma Doctor (doctor@medgemma.com)</option>' +
                                     '<option value="29">Dr. Amna Iqbal (amnaiqbal10396@gmail.com)</option>';
                });
        }

        function loadRecentActivity() {
            const activities = [
                { action: 'Invoice INV-2023-004 marked as paid', time: '2 hours ago', icon: 'check-circle', color: 'success' },
                { action: 'New invoice INV-2023-005 created for Jane Smith', time: '5 hours ago', icon: 'plus-circle', color: 'primary' },
                { action: 'Payment reminder sent for INV-2023-002', time: '1 day ago', icon: 'envelope', color: 'warning' },
                { action: 'Invoice INV-2023-001 downloaded', time: '2 days ago', icon: 'download', color: 'info' }
            ];

            const container = document.getElementById('recentActivity');
            container.innerHTML = '';
            
            activities.forEach(activity => {
                container.innerHTML += `
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <i class="fas fa-${activity.icon} text-${activity.color}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div>${activity.action}</div>
                            <small class="text-muted">${activity.time}</small>
                        </div>
                    </div>
                `;
            });
        }

        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.invoice-checkbox');
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
        }

        function filterInvoices() {
            showToast('Filtering invoices...', 'info');
            loadInvoices();
        }

        function searchInvoices() {
            const searchTerm = document.getElementById('searchInvoices').value.toLowerCase();
            // Implement search logic here
        }

        function exportInvoices() {
            showToast('Exporting invoices...', 'success');
        }

        function viewInvoice(id) {
            // Open invoice in a new tab for viewing
            window.open(`/invoices/${id}`, '_blank');
            showToast(`Opening invoice ${id} for viewing...`, 'info');
        }

        function downloadInvoice(id) {
            // Download invoice as PDF
            const link = document.createElement('a');
            link.href = `/invoices/${id}?download=1`;
            link.download = `invoice-${id}.pdf`;
            link.target = '_blank';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            showToast(`Invoice ${id} download started...`, 'success');
        }

        function sendInvoice(id) {
            // Show email modal for sending invoice
            const email = prompt('Enter email address to send invoice:');
            if (email && email.trim()) {
                fetch(`/api/invoices/${id}/email`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ 
                        email: email.trim(),
                        message: 'Please find attached your invoice. Thank you for your business.'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        showToast(data.message, 'success');
                    } else {
                        showToast('Invoice sent successfully!', 'success');
                    }
                })
                .catch(error => {
                    console.error('Error sending invoice:', error);
                    showToast('Failed to send invoice. Please try again.', 'error');
                });
            }
        }

        function executeBulkAction() {
            const selected = document.querySelectorAll('.invoice-checkbox:checked').length;
            if (selected === 0) {
                alert('Please select at least one invoice.');
                return;
            }
            
            const action = document.getElementById('bulkAction').value;
            showToast(`Executing ${action} on ${selected} invoices...`, 'success');
            bootstrap.Modal.getInstance(document.getElementById('bulkActionsModal')).hide();
        }

        function showToast(message, type = 'info') {
            const toastColor = type === 'success' ? 'success' : 'info';
            const toast = document.createElement('div');
            toast.className = `alert alert-${toastColor} position-fixed`;
            toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            toast.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
            `;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 5000);
        }
    </script>
</body>
</html>
