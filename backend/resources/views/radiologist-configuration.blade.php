<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Radiologist Configuration - FHIR DICOM Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
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
            background: linear-gradient(45deg, #6f42c1, #e83e8c);
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
        .badge-modality {
            font-size: 0.75em;
            padding: 0.35em 0.65em;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="/radiologist-dashboard">
                <i class="fas fa-x-ray me-2"></i>Radiology Configuration
            </a>
            <div class="ms-auto">
                <a href="/radiologist-dashboard" class="btn btn-outline-light">
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
                        <div class="col-md-3">
                            <button class="btn btn-primary w-100" onclick="showAddTestModal()">
                                <i class="fas fa-plus-circle me-2"></i>Add Imaging Test
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-light w-100" onclick="refreshTests()">
                                <i class="fas fa-sync-alt me-2"></i>Refresh Tests
                            </button>
                        </div>
                        <div class="col-md-3">
                            <a href="/radiologist-dashboard" class="btn btn-outline-light w-100">
                                <i class="fas fa-chart-bar me-2"></i>View Dashboard
                            </a>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-light w-100" onclick="exportTests()">
                                <i class="fas fa-download me-2"></i>Export Tests
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Imaging Tests Management -->
        <div class="row">
            <div class="col-12">
                <div class="glass-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0"><i class="fas fa-images me-2"></i>Imaging Tests Configuration</h4>
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
                                        <th>Modality</th>
                                        <th>Body Part</th>
                                        <th>Duration (min)</th>
                                        <th>Status</th>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="testModalTitle">Add Imaging Test</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="testForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Test Code *</label>
                                    <input type="text" class="form-control" id="testCode" name="code" required placeholder="e.g., CXR">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Test Name *</label>
                                    <input type="text" class="form-control" id="testName" name="name" required placeholder="e.g., Chest X-Ray">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Modality *</label>
                                    <select class="form-select" id="modality" name="modality" required>
                                        <option value="">Select modality</option>
                                        <option value="X-RAY">X-Ray</option>
                                        <option value="CT">Computed Tomography (CT)</option>
                                        <option value="MRI">Magnetic Resonance Imaging (MRI)</option>
                                        <option value="US">Ultrasound</option>
                                        <option value="MAMMO">Mammography</option>
                                        <option value="FLUORO">Fluoroscopy</option>
                                        <option value="NM">Nuclear Medicine</option>
                                        <option value="PET">PET Scan</option>
                                        <option value="OTHER">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Body Part</label>
                                    <input type="text" class="form-control" id="bodyPart" name="body_part" placeholder="e.g., Chest, Abdomen, Head">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Estimated Duration (minutes)</label>
                                    <input type="number" class="form-control" id="duration" name="estimated_duration" min="0" max="999" placeholder="e.g., 15">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" id="status" name="is_active">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Brief description of the imaging test"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Preparation Instructions</label>
                            <textarea class="form-control" id="instructions" name="preparation_instructions" rows="2" placeholder="Patient preparation instructions (optional)"></textarea>
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

        // Load all imaging tests
        function loadTests() {
            fetch('/api/configuration/imaging-tests')
                .then(response => response.json())
                .then(data => {
                    testsData = data;
                    displayTests(data);
                })
                .catch(error => {
                    console.error('Error loading tests:', error);
                    showAlert('Failed to load imaging tests', 'danger');
                });
        }

        // Display tests in table
        function displayTests(tests) {
            const tbody = document.getElementById('testsTableBody');
            
            if (tests.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center">
                            <i class="fas fa-images me-2"></i>No imaging tests configured
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = tests.map(test => `
                <tr>
                    <td><strong>${test.code}</strong></td>
                    <td>${test.name}</td>
                    <td><span class="badge bg-primary badge-modality">${test.modality}</span></td>
                    <td>${test.body_part || '-'}</td>
                    <td>${test.estimated_duration ? test.estimated_duration + ' min' : '-'}</td>
                    <td>
                        <span class="badge ${test.is_active ? 'bg-success' : 'bg-secondary'}">
                            ${test.is_active ? 'Active' : 'Inactive'}
                        </span>
                    </td>
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
            document.getElementById('testModalTitle').textContent = 'Add Imaging Test';
            document.getElementById('testForm').reset();
            document.getElementById('status').value = '1'; // Default to active
            new bootstrap.Modal(document.getElementById('testModal')).show();
        }

        // Edit test
        function editTest(testId) {
            const test = testsData.find(t => t.id === testId);
            if (!test) return;

            editingTestId = testId;
            document.getElementById('testModalTitle').textContent = 'Edit Imaging Test';
            document.getElementById('testCode').value = test.code;
            document.getElementById('testName').value = test.name;
            document.getElementById('modality').value = test.modality;
            document.getElementById('bodyPart').value = test.body_part || '';
            document.getElementById('duration').value = test.estimated_duration || '';
            document.getElementById('status').value = test.is_active ? '1' : '0';
            document.getElementById('description').value = test.description || '';
            document.getElementById('instructions').value = Array.isArray(test.preparation_instructions) 
                ? test.preparation_instructions.join('\n') 
                : (test.preparation_instructions || '');
            
            new bootstrap.Modal(document.getElementById('testModal')).show();
        }

        // Save test (add or edit)
        document.getElementById('testForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const testData = Object.fromEntries(formData.entries());
            
            // Convert preparation instructions to array
            if (testData.preparation_instructions) {
                testData.preparation_instructions = testData.preparation_instructions.split('\n').filter(line => line.trim());
            }
            
            // Convert is_active to boolean
            testData.is_active = testData.is_active === '1';
            
            const url = editingTestId ? `/api/configuration/imaging-tests/${editingTestId}` : '/api/configuration/imaging-tests';
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
                
                showAlert(`Imaging test ${editingTestId ? 'updated' : 'created'} successfully!`, 'success');
                bootstrap.Modal.getInstance(document.getElementById('testModal')).hide();
                loadTests();
            })
            .catch(error => {
                console.error('Error saving test:', error);
                showAlert(`Failed to ${editingTestId ? 'update' : 'create'} imaging test`, 'danger');
            });
        });

        // Delete test
        function deleteTest(testId, testName) {
            if (!confirm(`Are you sure you want to delete "${testName}"?`)) return;
            
            fetch(`/api/configuration/imaging-tests/${testId}`, {
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
                
                showAlert('Imaging test deleted successfully!', 'success');
                loadTests();
            })
            .catch(error => {
                console.error('Error deleting test:', error);
                showAlert('Failed to delete imaging test', 'danger');
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
                "Code,Name,Modality,Body Part,Duration,Status,Description\n" +
                testsData.map(test => 
                    `"${test.code}","${test.name}","${test.modality}","${test.body_part || ''}","${test.estimated_duration || ''}","${test.is_active ? 'Active' : 'Inactive'}","${test.description || ''}"`
                ).join('\n');
                
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "imaging_tests.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            showAlert('Imaging tests exported successfully!', 'success');
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
