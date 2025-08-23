<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inventory Management - Pharmacist</title>
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
        .inventory-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid #17a2b8;
            transition: all 0.3s ease;
        }
        .inventory-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        .stock-critical { border-left-color: #dc3545; }
        .stock-low { border-left-color: #ffc107; }
        .stock-good { border-left-color: #28a745; }
        .stock-out { border-left-color: #6c757d; }
        
        .navbar { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="/pharmacist-dashboard">
                <i class="fas fa-boxes me-2"></i>Inventory Management
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
                    <i class="fas fa-boxes fa-2x mb-2 text-info"></i>
                    <h4 id="totalItems">-</h4>
                    <small>Total Items</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2 text-warning"></i>
                    <h4 id="lowStockCount">-</h4>
                    <small>Low Stock Alerts</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-ban fa-2x mb-2 text-danger"></i>
                    <h4 id="outOfStockCount">-</h4>
                    <small>Out of Stock</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <i class="fas fa-dollar-sign fa-2x mb-2 text-success"></i>
                    <h4 id="inventoryValue">-</h4>
                    <small>Total Value</small>
                </div>
            </div>
        </div>

        <!-- Actions and Filters -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="glass-card p-3">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Search medications..." id="searchInput">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="good">Good Stock</option>
                                <option value="low">Low Stock</option>
                                <option value="critical">Critical</option>
                                <option value="out">Out of Stock</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="supplierFilter">
                                <option value="">All Suppliers</option>
                                <option value="MedSupply Co">MedSupply Co</option>
                                <option value="PharmaCorp">PharmaCorp</option>
                                <option value="HealthDistrib">HealthDistrib</option>
                                <option value="MediSource">MediSource</option>
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
                        <button class="btn btn-primary" onclick="addNewMedication()">
                            <i class="fas fa-plus me-2"></i>Add New Medication
                        </button>
                        <button class="btn btn-outline-light" onclick="generateInventoryReport()">
                            <i class="fas fa-file-export me-2"></i>Export Report
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory List -->
        <div class="row">
            <div class="col-12">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-list me-2"></i>Medication Inventory</h5>
                    <div id="inventoryList">
                        <div class="text-center p-4">
                            <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                            <p>Loading inventory...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Medication Modal -->
    <div class="modal fade" id="medicationModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title" id="modalTitle">Add New Medication</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="medicationForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Medication Name</label>
                                <input type="text" class="form-control" id="medicationName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Current Stock</label>
                                <input type="number" class="form-control" id="currentStock" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Minimum Stock Level</label>
                                <input type="number" class="form-control" id="minStock" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Supplier</label>
                                <select class="form-select" id="supplier" required>
                                    <option value="">Select Supplier</option>
                                    <option value="MedSupply Co">MedSupply Co</option>
                                    <option value="PharmaCorp">PharmaCorp</option>
                                    <option value="HealthDistrib">HealthDistrib</option>
                                    <option value="MediSource">MediSource</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Cost per Unit ($)</label>
                                <input type="number" step="0.01" class="form-control" id="unitCost" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Selling Price ($)</label>
                                <input type="number" step="0.01" class="form-control" id="sellingPrice" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveMedication()">Save Medication</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let allMedications = [];
        let filteredMedications = [];

        document.addEventListener('DOMContentLoaded', function() {
            loadInventory();
        });

        async function loadInventory() {
            try {
                const response = await fetch('/api/pharmacist/inventory');
                const data = await response.json();
                allMedications = data.medications || [];
                filteredMedications = [...allMedications];
                
                displayInventory();
                updateSummaryStats();
            } catch (error) {
                console.error('Error loading inventory:', error);
                document.getElementById('inventoryList').innerHTML = `
                    <div class="text-center p-4">
                        <i class="fas fa-exclamation-triangle fa-2x mb-3 text-warning"></i>
                        <p>Error loading inventory data</p>
                    </div>
                `;
            }
        }

        function displayInventory() {
            const container = document.getElementById('inventoryList');
            
            if (filteredMedications.length === 0) {
                container.innerHTML = `
                    <div class="text-center p-4">
                        <i class="fas fa-box-open fa-2x mb-3 opacity-50"></i>
                        <p>No medications found</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = filteredMedications.map(med => `
                <div class="inventory-item stock-${med.status}" data-id="${med.id}">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <h6 class="mb-1">${med.name}</h6>
                            <small class="text-info">Supplier: ${med.supplier}</small>
                        </div>
                        <div class="col-md-2">
                            <strong class="d-block">Stock: ${med.stock}</strong>
                            <small class="text-muted">Min: ${med.min_stock}</small>
                        </div>
                        <div class="col-md-2">
                            <strong class="d-block">Cost: $${med.cost}</strong>
                            <small class="text-muted">Price: $${med.price}</small>
                        </div>
                        <div class="col-md-2">
                            <span class="badge bg-${getBadgeColor(med.status)}">${getStatusText(med.status)}</span>
                        </div>
                        <div class="col-md-2">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-light" onclick="editMedication(${med.id})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-outline-light" onclick="reorderMedication(${med.id})" title="Reorder">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                                <button class="btn btn-outline-danger" onclick="deleteMedication(${med.id})" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function updateSummaryStats() {
            const totalItems = allMedications.length;
            const lowStockCount = allMedications.filter(m => m.status === 'low' || m.status === 'critical').length;
            const outOfStockCount = allMedications.filter(m => m.status === 'out').length;
            const inventoryValue = allMedications.reduce((sum, m) => sum + (m.stock * m.cost), 0);

            document.getElementById('totalItems').textContent = totalItems;
            document.getElementById('lowStockCount').textContent = lowStockCount;
            document.getElementById('outOfStockCount').textContent = outOfStockCount;
            document.getElementById('inventoryValue').textContent = '$' + inventoryValue.toLocaleString('en-US', {minimumFractionDigits: 2});
        }

        function getBadgeColor(status) {
            const colors = {
                'good': 'success',
                'low': 'warning', 
                'critical': 'danger',
                'out': 'secondary'
            };
            return colors[status] || 'info';
        }

        function getStatusText(status) {
            const texts = {
                'good': 'GOOD STOCK',
                'low': 'LOW STOCK',
                'critical': 'CRITICAL',
                'out': 'OUT OF STOCK'
            };
            return texts[status] || 'UNKNOWN';
        }

        function applyFilters() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const supplierFilter = document.getElementById('supplierFilter').value;

            filteredMedications = allMedications.filter(med => {
                const matchesSearch = med.name.toLowerCase().includes(searchTerm);
                const matchesStatus = !statusFilter || med.status === statusFilter;
                const matchesSupplier = !supplierFilter || med.supplier === supplierFilter;
                
                return matchesSearch && matchesStatus && matchesSupplier;
            });

            displayInventory();
        }

        function addNewMedication() {
            document.getElementById('modalTitle').textContent = 'Add New Medication';
            document.getElementById('medicationForm').reset();
            new bootstrap.Modal(document.getElementById('medicationModal')).show();
        }

        function editMedication(id) {
            const medication = allMedications.find(m => m.id === id);
            if (medication) {
                document.getElementById('modalTitle').textContent = 'Edit Medication';
                document.getElementById('medicationName').value = medication.name;
                document.getElementById('currentStock').value = medication.stock;
                document.getElementById('minStock').value = medication.min_stock;
                document.getElementById('supplier').value = medication.supplier;
                document.getElementById('unitCost').value = medication.cost;
                document.getElementById('sellingPrice').value = medication.price;
                
                new bootstrap.Modal(document.getElementById('medicationModal')).show();
            }
        }

        function saveMedication() {
            // Simulate save operation
            alert('Medication saved successfully!');
            bootstrap.Modal.getInstance(document.getElementById('medicationModal')).hide();
            loadInventory(); // Refresh the list
        }

        function reorderMedication(id) {
            const medication = allMedications.find(m => m.id === id);
            if (medication) {
                const quantity = prompt(`How many units of ${medication.name} would you like to reorder?`, medication.min_stock);
                if (quantity && parseInt(quantity) > 0) {
                    alert(`Reorder request sent to ${medication.supplier} for ${quantity} units of ${medication.name}`);
                }
            }
        }

        function deleteMedication(id) {
            const medication = allMedications.find(m => m.id === id);
            if (medication && confirm(`Are you sure you want to delete ${medication.name} from inventory?`)) {
                alert('Medication deleted successfully!');
                loadInventory(); // Refresh the list
            }
        }

        function generateInventoryReport() {
            const reportData = filteredMedications.map(med => 
                `${med.name},${med.stock},${med.min_stock},${med.status},${med.supplier},$${med.cost},$${med.price}`
            ).join('\\n');
            
            const csvContent = 'Medication,Current Stock,Min Stock,Status,Supplier,Cost,Price\\n' + reportData;
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'pharmacy_inventory_report.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }

        // Real-time search
        document.getElementById('searchInput').addEventListener('input', applyFilters);
    </script>
</body>
</html>
