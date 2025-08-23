<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lab Tech Configuration - FHIR DICOM Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
        .btn-primary {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
        }
        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        .table-dark {
            background: rgba(0, 0, 0, 0.3);
        }
        .modal-content {
            background: rgba(25, 25, 25, 0.95);
            backdrop-filter: blur(10px);
        }
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
        }
        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #667eea;
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        .form-label {
            color: white !important;
            font-weight: 500;
            margin-bottom: 8px;
        }
        .input-group-text {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.3);
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="/lab-tech-dashboard">
                <i class="fas fa-vial me-2"></i>Lab Tech Configuration
            </a>
            <div class="ms-auto">
                <a href="/lab-tech-dashboard" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="glass-card p-4">
                    <h4 class="mb-3"><i class="fas fa-bolt me-2"></i>Quick Actions</h4>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <button class="btn btn-primary w-100" onclick="showAddTestModal()">
                                <i class="fas fa-plus-circle me-2"></i>Add Lab Test
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-outline-light w-100" onclick="refreshTests()">
                                <i class="fas fa-sync-alt me-2"></i>Refresh Tests
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-outline-light w-100" onclick="exportTests()">
                                <i class="fas fa-download me-2"></i>Export Tests
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lab Tests Management -->
        <div class="row">
            <div class="col-12">
                <div class="glass-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0"><i class="fas fa-flask me-2"></i>Lab Tests Configuration</h4>
                            <button class="btn btn-primary" onclick="showAddTestModal()">
                                <i class="fas fa-plus me-2"></i>Add New Test
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-dark table-hover" id="testsTable">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Test Name</th>
                                        <th>Specimen Type</th>
                                        <th>Price</th>
                                        <th>Units</th>
                                        <th>Normal Range</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="testsTableBody">
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <i class="fas fa-spinner fa-spin me-2"></i>Loading tests...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Test Modal -->
    <div class="modal fade" id="testModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="testModalTitle">Add Lab Test</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="testForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Test Code *</label>
                            <input type="text" class="form-control" id="testCode" name="code" required placeholder="e.g., CBC">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Test Name *</label>
                            <input type="text" class="form-control" id="testName" name="name" required placeholder="e.g., Complete Blood Count">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Specimen Type *</label>
                            <select class="form-select" id="specimenType" name="specimen_type" required>
                                <option value="">Select specimen type</option>
                                <option value="blood">Blood</option>
                                <option value="urine">Urine</option>
                                <option value="stool">Stool</option>
                                <option value="serum">Serum</option>
                                <option value="plasma">Plasma</option>
                                <option value="saliva">Saliva</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Test Price (USD) *</label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background: rgba(255, 255, 255, 0.1); border-color: rgba(255, 255, 255, 0.3); color: white;">$</span>
                                    <input type="number" class="form-control" id="testPrice" name="price" step="0.01" min="0" required placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select class="form-select" id="testCategory" name="category">
                                    <option value="">Select category</option>
                                    <option value="hematology">Hematology</option>
                                    <option value="chemistry">Chemistry</option>
                                    <option value="microbiology">Microbiology</option>
                                    <option value="immunology">Immunology</option>
                                    <option value="molecular">Molecular</option>
                                    <option value="pathology">Pathology</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Units</label>
                            <input type="text" class="form-control" id="testUnits" name="units" placeholder="e.g., mg/dL, count/μL">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Normal Range</label>
                            <input type="text" class="form-control" id="normalRange" name="normal_range" placeholder="e.g., 4.5-11.0 x10³/μL">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Test
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let editingTestId = null;
        let testsData = [];

        // Load tests on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadTests();
        });

        // Load all lab tests
        function loadTests() {
            fetch('/api/lab-tests')
                .then(response => response.json())
                .then(data => {
                    testsData = data;
                    displayTests(data);
                })
                .catch(error => {
                    console.error('Error loading tests:', error);
                    // Show demo data with pricing if API fails
                    testsData = [
                        {
                            id: 1,
                            code: 'CBC',
                            name: 'Complete Blood Count',
                            specimen_type: 'blood',
                            price: 25.50,
                            category: 'hematology',
                            units: 'count/μL',
                            normal_range: '4.5-11.0 x10³/μL'
                        },
                        {
                            id: 2,
                            code: 'LFT',
                            name: 'Liver Function Test',
                            specimen_type: 'serum',
                            price: 45.00,
                            category: 'chemistry',
                            units: 'U/L',
                            normal_range: 'ALT: 7-56 U/L'
                        },
                        {
                            id: 3,
                            code: 'GLU',
                            name: 'Glucose Random',
                            specimen_type: 'serum',
                            price: 15.75,
                            category: 'chemistry',
                            units: 'mg/dL',
                            normal_range: '70-140 mg/dL'
                        },
                        {
                            id: 4,
                            code: 'UA',
                            name: 'Urinalysis',
                            specimen_type: 'urine',
                            price: 20.00,
                            category: 'chemistry',
                            units: 'various',
                            normal_range: 'See reference chart'
                        }
                    ];
                    displayTests(testsData);
                    showAlert('Loaded demo lab tests with pricing', 'info');
                });
        }

        // Display tests in table
        function displayTests(tests) {
            const tbody = document.getElementById('testsTableBody');
            
            if (tests.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center">
                            <i class="fas fa-flask me-2"></i>No lab tests configured
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = tests.map(test => `
                <tr>
                    <td><strong>${test.code}</strong></td>
                    <td>${test.name}</td>
                    <td><span class="badge bg-info">${test.specimen_type}</span></td>
                    <td><strong class="text-success">$${test.price ? parseFloat(test.price).toFixed(2) : '0.00'}</strong></td>
                    <td>${test.units || '-'}</td>
                    <td>${test.normal_range || '-'}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary" onclick="editTest(${test.id})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-danger" onclick="deleteTest(${test.id}, '${test.name}')" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        // Show add test modal
        function showAddTestModal() {
            editingTestId = null;
            document.getElementById('testModalTitle').textContent = 'Add Lab Test';
            document.getElementById('testForm').reset();
            new bootstrap.Modal(document.getElementById('testModal')).show();
        }

        // Edit test
        function editTest(testId) {
            const test = testsData.find(t => t.id === testId);
            if (!test) return;

            editingTestId = testId;
            document.getElementById('testModalTitle').textContent = 'Edit Lab Test';
            document.getElementById('testCode').value = test.code;
            document.getElementById('testName').value = test.name;
            document.getElementById('specimenType').value = test.specimen_type;
            document.getElementById('testPrice').value = test.price || '';
            document.getElementById('testCategory').value = test.category || '';
            document.getElementById('testUnits').value = test.units || '';
            document.getElementById('normalRange').value = test.normal_range || '';
            
            new bootstrap.Modal(document.getElementById('testModal')).show();
        }

        // Save test (add or edit)
        document.getElementById('testForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const testData = Object.fromEntries(formData.entries());
            
            const url = editingTestId ? `/api/lab-tests/${editingTestId}` : '/api/lab-tests';
            const method = editingTestId ? 'PUT' : 'POST';
            
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(testData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.errors) {
                    showAlert('Validation errors: ' + Object.values(data.errors).flat().join(', '), 'danger');
                    return;
                }
                
                showAlert(`Lab test ${editingTestId ? 'updated' : 'created'} successfully!`, 'success');
                bootstrap.Modal.getInstance(document.getElementById('testModal')).hide();
                loadTests();
            })
            .catch(error => {
                console.error('Error saving test:', error);
                showAlert(`Failed to ${editingTestId ? 'update' : 'create'} lab test`, 'danger');
            });
        });

        // Delete test
        function deleteTest(testId, testName) {
            if (!confirm(`Are you sure you want to delete "${testName}"?`)) return;
            
            fetch(`/api/lab-tests/${testId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    showAlert(data.error, 'warning');
                    return;
                }
                
                showAlert('Lab test deleted successfully!', 'success');
                loadTests();
            })
            .catch(error => {
                console.error('Error deleting test:', error);
                showAlert('Failed to delete lab test', 'danger');
            });
        }

        // Refresh tests
        function refreshTests() {
            loadTests();
            showAlert('Tests refreshed!', 'info');
        }

        // Export tests
        function exportTests() {
            const csvContent = "data:text/csv;charset=utf-8," +
                "Code,Name,Specimen Type,Price,Category,Units,Normal Range\n" +
                testsData.map(test => 
                    `"${test.code}","${test.name}","${test.specimen_type}","${test.price || '0.00'}","${test.category || ''}","${test.units || ''}","${test.normal_range || ''}"`
                ).join('\n');
                
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "lab_tests_with_pricing.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            showAlert('Lab tests with pricing exported successfully!', 'success');
        }

        // Show alert
        function showAlert(message, type = 'info') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
            alertDiv.style.zIndex = '9999';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 5000);
        }
    </script>
</body>
</html>
