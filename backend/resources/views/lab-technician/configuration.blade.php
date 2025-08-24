@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">
                        <i class="bi bi-gear-fill me-2"></i>Configuration Management
                    </h2>
                    <span class="badge bg-light text-primary">Lab Technician</span>
                </div>
                <div class="card-body">
                    <!-- Configuration Navigation Tabs -->
                    <ul class="nav nav-tabs mb-4" id="configTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="lab-tests-tab" data-bs-toggle="tab" 
                                data-bs-target="#lab-tests" type="button" role="tab">
                                <i class="bi bi-clipboard2-pulse me-2"></i>Lab Tests
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="imaging-tests-tab" data-bs-toggle="tab" 
                                data-bs-target="#imaging-tests" type="button" role="tab">
                                <i class="bi bi-camera-reels me-2"></i>Imaging Tests
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="equipment-tab" data-bs-toggle="tab" 
                                data-bs-target="#equipment" type="button" role="tab">
                                <i class="bi bi-cpu me-2"></i>Equipment
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="suppliers-tab" data-bs-toggle="tab" 
                                data-bs-target="#suppliers" type="button" role="tab">
                                <i class="bi bi-truck me-2"></i>Suppliers
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="configTabsContent">
                        <!-- Lab Tests Tab -->
                        <div class="tab-pane fade show active" id="lab-tests" role="tabpanel">
                            <div class="d-flex justify-content-between mb-3">
                                <h4 class="text-primary">Lab Tests Configuration</h4>
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addLabTestModal">
                                    <i class="bi bi-plus-circle me-2"></i>Add Lab Test
                                </button>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Test Code</th>
                                            <th>Test Name</th>
                                            <th>Category</th>
                                            <th>Normal Range</th>
                                            <th>Units</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="labTestsTableBody">
                                        <!-- Lab tests will be populated here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Imaging Tests Tab -->
                        <div class="tab-pane fade" id="imaging-tests" role="tabpanel">
                            <div class="d-flex justify-content-between mb-3">
                                <h4 class="text-primary">Imaging Tests Configuration</h4>
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addImagingTestModal">
                                    <i class="bi bi-plus-circle me-2"></i>Add Imaging Test
                                </button>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-info">
                                        <tr>
                                            <th>Test Code</th>
                                            <th>Test Name</th>
                                            <th>Modality</th>
                                            <th>Body Part</th>
                                            <th>Preparation Required</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="imagingTestsTableBody">
                                        <!-- Imaging tests will be populated here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Equipment Tab -->
                        <div class="tab-pane fade" id="equipment" role="tabpanel">
                            <div class="d-flex justify-content-between mb-3">
                                <h4 class="text-primary">Equipment Management</h4>
                                <div>
                                    <button class="btn btn-info me-2" onclick="refreshEquipmentStatus()">
                                        <i class="bi bi-arrow-clockwise me-2"></i>Refresh Status
                                    </button>
                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEquipmentModal">
                                        <i class="bi bi-plus-circle me-2"></i>Add Equipment
                                    </button>
                                </div>
                            </div>
                            
                            <div class="row" id="equipmentCards">
                                <!-- Equipment cards will be populated here -->
                            </div>
                        </div>

                        <!-- Suppliers Tab -->
                        <div class="tab-pane fade" id="suppliers" role="tabpanel">
                            <div class="d-flex justify-content-between mb-3">
                                <h4 class="text-primary">Supplier Management</h4>
                                <button class="btn btn-outline-secondary" onclick="loadAvailableSuppliers()">
                                    <i class="bi bi-eye me-2"></i>View Available Suppliers
                                </button>
                            </div>
                            
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                As a Lab Technician, you can view assigned suppliers and create work orders for them.
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-warning">
                                        <tr>
                                            <th>Supplier Name</th>
                                            <th>Type</th>
                                            <th>Contact Person</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="suppliersTableBody">
                                        <!-- Suppliers will be populated here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications Section -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-bell-fill me-2"></i>Notifications
                        <span class="badge bg-light text-info ms-2" id="notificationCount">0</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div id="notificationsList">
                        <!-- Notifications will be populated here -->
                    </div>
                </div>
            </div>

            <!-- Work Orders Section -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-clipboard-check me-2"></i>Work Orders
                    </h5>
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#createWorkOrderModal">
                        <i class="bi bi-plus-circle me-2"></i>Create Work Order
                    </button>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="card text-center bg-light">
                                <div class="card-body">
                                    <h6 class="text-warning">Pending</h6>
                                    <h3 class="text-warning" id="pendingWorkOrders">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center bg-light">
                                <div class="card-body">
                                    <h6 class="text-info">In Progress</h6>
                                    <h3 class="text-info" id="inProgressWorkOrders">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center bg-light">
                                <div class="card-body">
                                    <h6 class="text-success">Completed</h6>
                                    <h3 class="text-success" id="completedWorkOrders">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center bg-light">
                                <div class="card-body">
                                    <h6 class="text-danger">Overdue</h6>
                                    <h3 class="text-danger" id="overdueWorkOrders">0</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-success">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Title</th>
                                    <th>Supplier</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="workOrdersTableBody">
                                <!-- Work orders will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Lab Test Modal -->
<div class="modal fade" id="addLabTestModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Lab Test</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addLabTestForm">
                    <div class="mb-3">
                        <label class="form-label">Test Code</label>
                        <input type="text" class="form-control" name="code" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Test Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-select" name="category" required>
                            <option value="">Select Category</option>
                            <option value="Hematology">Hematology</option>
                            <option value="Chemistry">Chemistry</option>
                            <option value="Microbiology">Microbiology</option>
                            <option value="Immunology">Immunology</option>
                            <option value="Molecular">Molecular</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Normal Range</label>
                        <input type="text" class="form-control" name="normal_range">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Units</label>
                        <input type="text" class="form-control" name="units">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Specimen Type</label>
                        <select class="form-select" name="specimen_type" required>
                            <option value="">Select Specimen Type</option>
                            <option value="Blood">Blood</option>
                            <option value="Urine">Urine</option>
                            <option value="Saliva">Saliva</option>
                            <option value="Tissue">Tissue</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitLabTest()">Add Test</button>
            </div>
        </div>
    </div>
</div>

<!-- Create Work Order Modal -->
<div class="modal fade" id="createWorkOrderModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Work Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createWorkOrderForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Supplier</label>
                                <select class="form-select" name="supplier_id" required>
                                    <option value="">Select Supplier</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Priority</label>
                                <select class="form-select" name="priority" required>
                                    <option value="low">Low</option>
                                    <option value="normal" selected>Normal</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Due Date</label>
                                <input type="datetime-local" class="form-control" name="due_date" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitWorkOrder()">Create Work Order</button>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
}

.nav-tabs .nav-link {
    border: none;
    color: #6c757d;
    font-weight: 500;
    padding: 12px 20px;
    border-radius: 8px 8px 0 0;
}

.nav-tabs .nav-link.active {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    border: none;
}

.table th {
    font-weight: 600;
    border-bottom: 2px solid #dee2e6;
    color: #495057;
}

.badge {
    font-size: 0.75em;
    padding: 4px 8px;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.alert {
    border: none;
    border-radius: 10px;
    border-left: 4px solid;
}

.alert-info {
    border-left-color: #17a2b8;
    background-color: rgba(23, 162, 184, 0.1);
}

.modal-header {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    border-radius: 12px 12px 0 0;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #ced4da;
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
</style>

<script>
// Global variables
let availableSuppliers = [];
let notifications = [];
let workOrders = [];

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    loadLabTests();
    loadImagingTests();
    loadEquipment();
    loadNotifications();
    loadWorkOrders();
    loadSuppliers();
    
    // Set minimum date for work order due date
    const dueDateInput = document.querySelector('input[name="due_date"]');
    if (dueDateInput) {
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        dueDateInput.min = now.toISOString().slice(0, 16);
    }
});

// Load lab tests
async function loadLabTests() {
    try {
        const response = await fetch('/api/configuration/lab-tests');
        const tests = await response.json();
        
        const tbody = document.getElementById('labTestsTableBody');
        tbody.innerHTML = '';
        
        tests.forEach(test => {
            const row = `
                <tr>
                    <td><code>${test.code}</code></td>
                    <td>${test.name}</td>
                    <td><span class="badge bg-secondary">${test.category || 'N/A'}</span></td>
                    <td>${test.normal_range || 'N/A'}</td>
                    <td>${test.units || 'N/A'}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary me-1" onclick="editLabTest(${test.id})">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteLabTest(${test.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    } catch (error) {
        console.error('Error loading lab tests:', error);
        showAlert('Error loading lab tests', 'danger');
    }
}

// Load imaging tests
async function loadImagingTests() {
    try {
        const response = await fetch('/api/configuration/imaging-tests');
        const tests = await response.json();
        
        const tbody = document.getElementById('imagingTestsTableBody');
        tbody.innerHTML = '';
        
        tests.forEach(test => {
            const row = `
                <tr>
                    <td><code>${test.code}</code></td>
                    <td>${test.name}</td>
                    <td><span class="badge bg-info">${test.modality}</span></td>
                    <td>${test.body_part || 'N/A'}</td>
                    <td>${test.preparation_required ? '<span class="badge bg-warning">Yes</span>' : '<span class="badge bg-success">No</span>'}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary me-1" onclick="editImagingTest(${test.id})">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteImagingTest(${test.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    } catch (error) {
        console.error('Error loading imaging tests:', error);
        showAlert('Error loading imaging tests', 'danger');
    }
}

// Load equipment
function loadEquipment() {
    // Simulate equipment data for lab technician view
    const equipment = [
        {
            id: 1,
            name: 'Hematology Analyzer',
            type: 'Automated',
            status: 'online',
            last_maintenance: '2025-01-10'
        },
        {
            id: 2,
            name: 'Chemistry Analyzer',
            type: 'Semi-Automated',
            status: 'offline',
            last_maintenance: '2025-01-08'
        },
        {
            id: 3,
            name: 'Microscope Unit A',
            type: 'Manual',
            status: 'online',
            last_maintenance: '2025-01-12'
        }
    ];
    
    const container = document.getElementById('equipmentCards');
    container.innerHTML = '';
    
    equipment.forEach(eq => {
        const statusBadge = eq.status === 'online' ? 'bg-success' : 'bg-danger';
        const statusIcon = eq.status === 'online' ? 'bi-check-circle' : 'bi-x-circle';
        
        const card = `
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-cpu-fill fs-1 text-primary mb-3"></i>
                        <h6 class="card-title">${eq.name}</h6>
                        <p class="text-muted">${eq.type}</p>
                        <span class="badge ${statusBadge}">
                            <i class="bi ${statusIcon} me-1"></i>${eq.status.toUpperCase()}
                        </span>
                        <hr>
                        <small class="text-muted">Last Maintenance: ${eq.last_maintenance}</small>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += card;
    });
}

// Load notifications
async function loadNotifications() {
    try {
        const response = await fetch('/api/notifications');
        if (response.ok) {
            notifications = await response.json();
            displayNotifications();
        }
    } catch (error) {
        console.error('Error loading notifications:', error);
        document.getElementById('notificationsList').innerHTML = '<p class="text-muted">No notifications available</p>';
    }
}

// Display notifications
function displayNotifications() {
    const container = document.getElementById('notificationsList');
    const countElement = document.getElementById('notificationCount');
    
    if (notifications.length === 0) {
        container.innerHTML = '<p class="text-muted">No notifications</p>';
        countElement.textContent = '0';
        return;
    }
    
    countElement.textContent = notifications.filter(n => !n.read_at).length;
    
    container.innerHTML = notifications.slice(0, 5).map(notification => `
        <div class="alert ${notification.read_at ? 'alert-secondary' : 'alert-primary'} alert-dismissible">
            <div class="d-flex align-items-start">
                <i class="bi ${getNotificationIcon(notification.type)} me-2 mt-1"></i>
                <div class="flex-grow-1">
                    <h6 class="mb-1">${notification.title}</h6>
                    <p class="mb-1">${notification.message}</p>
                    <small class="text-muted">${formatDate(notification.created_at)}</small>
                </div>
                ${!notification.read_at ? `
                    <button type="button" class="btn btn-sm btn-outline-primary ms-2" 
                        onclick="markNotificationAsRead(${notification.id})">
                        Mark as Read
                    </button>
                ` : ''}
            </div>
        </div>
    `).join('');
}

// Load work orders
async function loadWorkOrders() {
    try {
        const response = await fetch('/api/work-orders');
        if (response.ok) {
            const data = await response.json();
            workOrders = data.work_orders || [];
            updateWorkOrderStats(data.counts || {});
            displayWorkOrders();
        }
    } catch (error) {
        console.error('Error loading work orders:', error);
        showAlert('Error loading work orders', 'danger');
    }
}

// Display work orders
function displayWorkOrders() {
    const tbody = document.getElementById('workOrdersTableBody');
    
    if (workOrders.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No work orders found</td></tr>';
        return;
    }
    
    tbody.innerHTML = workOrders.map(order => `
        <tr>
            <td><code>#${order.id}</code></td>
            <td>${order.title}</td>
            <td>${order.supplier ? order.supplier.name : 'Unassigned'}</td>
            <td><span class="badge bg-${getPriorityColor(order.priority)}">${order.priority.toUpperCase()}</span></td>
            <td><span class="badge bg-${getStatusColor(order.status)}">${order.status.replace('_', ' ').toUpperCase()}</span></td>
            <td>
                ${formatDate(order.due_date)}
                ${order.is_overdue ? '<span class="badge bg-danger ms-1">Overdue</span>' : ''}
            </td>
            <td>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" onclick="viewWorkOrder(${order.id})" title="View Details">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="btn btn-outline-success" onclick="updateWorkOrderStatus(${order.id}, 'in_progress')" 
                        ${order.status === 'completed' || order.status === 'cancelled' ? 'disabled' : ''} title="Start">
                        <i class="bi bi-play"></i>
                    </button>
                    <button class="btn btn-outline-success" onclick="completeWorkOrder(${order.id})" 
                        ${order.status === 'completed' || order.status === 'cancelled' ? 'disabled' : ''} title="Complete">
                        <i class="bi bi-check"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Load suppliers
async function loadSuppliers() {
    try {
        const response = await fetch('/api/work-orders/suppliers');
        if (response.ok) {
            availableSuppliers = await response.json();
            displaySuppliers();
            updateSupplierDropdown();
        }
    } catch (error) {
        console.error('Error loading suppliers:', error);
        showAlert('Error loading suppliers', 'danger');
    }
}

// Display suppliers
function displaySuppliers() {
    const tbody = document.getElementById('suppliersTableBody');
    
    if (!availableSuppliers.suppliers || availableSuppliers.suppliers.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No suppliers assigned</td></tr>';
        return;
    }
    
    tbody.innerHTML = availableSuppliers.suppliers.map(supplier => `
        <tr>
            <td><strong>${supplier.name}</strong></td>
            <td><span class="badge bg-info">${supplier.type || 'General'}</span></td>
            <td>${supplier.contact_person || 'N/A'}</td>
            <td>${supplier.phone || 'N/A'}</td>
            <td>${supplier.email || 'N/A'}</td>
            <td><span class="badge bg-success">Active</span></td>
            <td>
                <button class="btn btn-sm btn-primary" onclick="createWorkOrderForSupplier(${supplier.id})">
                    <i class="bi bi-plus me-1"></i>Create Order
                </button>
            </td>
        </tr>
    `).join('');
}

// Update supplier dropdown in work order modal
function updateSupplierDropdown() {
    const select = document.querySelector('select[name="supplier_id"]');
    if (select && availableSuppliers.suppliers) {
        select.innerHTML = '<option value="">Select Supplier</option>';
        availableSuppliers.suppliers.forEach(supplier => {
            select.innerHTML += `<option value="${supplier.id}">${supplier.name}</option>`;
        });
    }
}

// Submit lab test
async function submitLabTest() {
    const form = document.getElementById('addLabTestForm');
    const formData = new FormData(form);
    
    try {
        const response = await fetch('/api/configuration/lab-tests', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(Object.fromEntries(formData))
        });
        
        if (response.ok) {
            showAlert('Lab test added successfully', 'success');
            bootstrap.Modal.getInstance(document.getElementById('addLabTestModal')).hide();
            form.reset();
            loadLabTests();
        } else {
            throw new Error('Failed to add lab test');
        }
    } catch (error) {
        console.error('Error adding lab test:', error);
        showAlert('Error adding lab test', 'danger');
    }
}

// Submit work order
async function submitWorkOrder() {
    const form = document.getElementById('createWorkOrderForm');
    const formData = new FormData(form);
    
    try {
        const response = await fetch('/api/work-orders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(Object.fromEntries(formData))
        });
        
        if (response.ok) {
            showAlert('Work order created successfully', 'success');
            bootstrap.Modal.getInstance(document.getElementById('createWorkOrderModal')).hide();
            form.reset();
            loadWorkOrders();
        } else {
            const error = await response.json();
            showAlert(error.error || 'Error creating work order', 'danger');
        }
    } catch (error) {
        console.error('Error creating work order:', error);
        showAlert('Error creating work order', 'danger');
    }
}

// Helper functions
function getNotificationIcon(type) {
    switch(type) {
        case 'lab_request': return 'bi-clipboard2-pulse-fill';
        case 'imaging_request': return 'bi-camera-reels-fill';
        case 'work_order': return 'bi-clipboard-check-fill';
        default: return 'bi-info-circle-fill';
    }
}

function getPriorityColor(priority) {
    switch(priority) {
        case 'low': return 'secondary';
        case 'normal': return 'info';
        case 'high': return 'warning';
        case 'urgent': return 'danger';
        default: return 'secondary';
    }
}

function getStatusColor(status) {
    switch(status) {
        case 'pending': return 'warning';
        case 'in_progress': return 'info';
        case 'completed': return 'success';
        case 'cancelled': return 'secondary';
        default: return 'secondary';
    }
}

function updateWorkOrderStats(counts) {
    document.getElementById('pendingWorkOrders').textContent = counts.pending || 0;
    document.getElementById('inProgressWorkOrders').textContent = counts.in_progress || 0;
    document.getElementById('completedWorkOrders').textContent = counts.completed || 0;
    document.getElementById('overdueWorkOrders').textContent = counts.overdue || 0;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
}

function showAlert(message, type) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}

// Placeholder functions for features to be implemented
function editLabTest(id) {
    showAlert('Edit functionality will be implemented', 'info');
}

function deleteLabTest(id) {
    if (confirm('Are you sure you want to delete this lab test?')) {
        showAlert('Delete functionality will be implemented', 'info');
    }
}

function editImagingTest(id) {
    showAlert('Edit functionality will be implemented', 'info');
}

function deleteImagingTest(id) {
    if (confirm('Are you sure you want to delete this imaging test?')) {
        showAlert('Delete functionality will be implemented', 'info');
    }
}

function refreshEquipmentStatus() {
    showAlert('Equipment status refreshed', 'info');
    loadEquipment();
}

function markNotificationAsRead(id) {
    // This would make an API call to mark notification as read
    showAlert('Notification marked as read', 'info');
    loadNotifications();
}

function viewWorkOrder(id) {
    showAlert('Work order details will be shown', 'info');
}

function updateWorkOrderStatus(id, status) {
    showAlert(`Work order status updated to ${status}`, 'info');
    loadWorkOrders();
}

function completeWorkOrder(id) {
    showAlert('Work order completion form will be shown', 'info');
}

function createWorkOrderForSupplier(supplierId) {
    const modal = bootstrap.Modal.getInstance(document.getElementById('createWorkOrderModal')) || 
                  new bootstrap.Modal(document.getElementById('createWorkOrderModal'));
    
    // Pre-select the supplier
    document.querySelector('select[name="supplier_id"]').value = supplierId;
    modal.show();
}

function loadAvailableSuppliers() {
    showAlert('Refreshing supplier list...', 'info');
    loadSuppliers();
}
</script>
@endsection
