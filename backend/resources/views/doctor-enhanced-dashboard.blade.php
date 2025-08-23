<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Doctor Dashboard - FHIR DICOM Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #2E8B57 0%, #3CB371 100%);
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
            background: linear-gradient(45deg, #2E8B57, #3CB371);
            border: none;
        }
        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        .test-item {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 8px;
            cursor: grab;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        .test-item:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        .test-item.lab-test {
            border-left-color: #28a745;
        }
        .test-item.imaging-test {
            border-left-color: #007bff;
        }
        .test-item.dragging {
            opacity: 0.5;
            transform: rotate(5deg);
        }
        .drop-zone {
            min-height: 100px;
            border: 2px dashed rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }
        .drop-zone.drag-over {
            border-color: #28a745;
            background: rgba(40, 167, 69, 0.1);
        }
        .selected-test {
            background: rgba(40, 167, 69, 0.2);
            border: 1px solid #28a745;
        }
        .quick-stats {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
        }
        .badge-test-type {
            font-size: 0.7em;
            padding: 0.25em 0.5em;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="#">
                <i class="fas fa-user-md me-2"></i>Doctor Dashboard
            </a>
            <div class="ms-auto">
                <span class="navbar-text me-3">Welcome, Dr. {{ Auth::user()->name ?? 'Doctor' }}</span>
                <a href="/logout" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
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
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" onclick="showNewPatientModal()">
                                <i class="fas fa-user-plus me-2"></i>New Patient
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="showOrderTestModal()">
                                <i class="fas fa-flask me-2"></i>Order Tests
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="showPrescribeModal()">
                                <i class="fas fa-prescription-bottle me-2"></i>Prescribe
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="viewAppointments()">
                                <i class="fas fa-calendar me-2"></i>Appointments
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="viewResults()">
                                <i class="fas fa-chart-line me-2"></i>Results
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light w-100" onclick="viewEarnings()">
                                <i class="fas fa-dollar-sign me-2"></i>Earnings
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Stats -->
        <div class="row mb-4" id="dashboardStats">
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-users fa-2x mb-2 text-primary"></i>
                        <h5 class="mb-0">-</h5>
                        <small>Today's Patients</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-flask fa-2x mb-2 text-success"></i>
                        <h5 class="mb-0">-</h5>
                        <small>Tests Ordered</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-prescription-bottle fa-2x mb-2 text-info"></i>
                        <h5 class="mb-0">-</h5>
                        <small>Prescriptions</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="quick-stats">
                        <i class="fas fa-dollar-sign fa-2x mb-2 text-warning"></i>
                        <h5 class="mb-0">$-</h5>
                        <small>Today's Earnings</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Selection Area -->
        <div class="row">
            <div class="col-md-4">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-flask me-2"></i>Available Lab Tests</h5>
                    <div id="labTestsList" class="mb-4">
                        <!-- Lab tests will be loaded here -->
                    </div>
                    
                    <h5 class="mb-3"><i class="fas fa-x-ray me-2"></i>Available Imaging Tests</h5>
                    <div id="imagingTestsList">
                        <!-- Imaging tests will be loaded here -->
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-clipboard-list me-2"></i>Selected Tests for Order</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="patientSearch" placeholder="Search patient by name or ID...">
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="priorityLevel">
                                <option value="routine">Routine</option>
                                <option value="urgent">Urgent</option>
                                <option value="stat">STAT</option>
                            </select>
                        </div>
                    </div>
                    
                    <div id="selectedTestsArea" class="drop-zone mb-3">
                        <i class="fas fa-hand-pointer fa-2x mb-2 text-muted"></i>
                        <p class="mb-0 text-muted">Drag and drop tests here to create an order</p>
                        <div id="selectedTests"></div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-outline-light" onclick="clearSelectedTests()">
                            <i class="fas fa-trash me-2"></i>Clear All
                        </button>
                        <button class="btn btn-primary" onclick="submitTestOrder()" disabled id="submitOrderBtn">
                            <i class="fas fa-paper-plane me-2"></i>Submit Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Test Modal -->
    <div class="modal fade" id="orderTestModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order Laboratory Tests</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="orderTestForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Patient *</label>
                                    <select class="form-select" id="modalPatientSelect" name="patient_id" required>
                                        <option value="">Select Patient</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Priority</label>
                                    <select class="form-select" id="modalPrioritySelect" name="priority">
                                        <option value="routine">Routine</option>
                                        <option value="urgent">Urgent</option>
                                        <option value="stat">STAT</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Clinical Notes</label>
                            <textarea class="form-control" id="clinicalNotes" name="clinical_notes" rows="3" placeholder="Clinical indication for tests..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Selected Tests</label>
                            <div id="modalSelectedTests" class="border rounded p-2" style="min-height: 100px;">
                                <p class="text-muted mb-0">No tests selected</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <script>
        let selectedTests = [];
        let labTests = [];
        let imagingTests = [];
        let patients = [];

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardStats();
            loadLabTests();
            loadImagingTests();
            loadPatients();
            initializeDragAndDrop();
        });

        // Load dashboard statistics
        function loadDashboardStats() {
            fetch('/api/dashboard/doctor')
                .then(response => response.json())
                .then(data => {
                    updateDashboardStats(data);
                })
                .catch(error => {
                    console.error('Error loading dashboard stats:', error);
                });
        }

        // Update dashboard statistics display
        function updateDashboardStats(data) {
            const statsCards = document.querySelectorAll('#dashboardStats .quick-stats h5');
            if (data.patients_today !== undefined) statsCards[0].textContent = data.patients_today;
            if (data.tests_ordered !== undefined) statsCards[1].textContent = data.tests_ordered;
            if (data.prescriptions_written !== undefined) statsCards[2].textContent = data.prescriptions_written;
            if (data.total_earnings !== undefined) statsCards[3].textContent = `$${data.total_earnings.toFixed(2)}`;
        }

        // Load available lab tests
        function loadLabTests() {
            fetch('/api/configuration/lab-tests')
                .then(response => response.json())
                .then(tests => {
                    // Filter valid tests (those with actual values, not placeholder strings)
                    const validTests = tests.filter(test => 
                        test.is_active === true || test.is_active === 1 ||
                        (typeof test.is_active === 'string' && test.is_active !== 'is_active')
                    );
                    
                    if (validTests.length > 0) {
                        labTests = validTests;
                    } else {
                        // Use fallback data that matches the user's configuration
                        labTests = [
                            {id: 1, name: 'Complete Blood Count', code: 'CBC', price: 25.50, is_active: true, specimen_type: 'blood'},
                            {id: 2, name: 'Liver Function Test', code: 'LFT', price: 45.00, is_active: true, specimen_type: 'serum'},
                            {id: 3, name: 'Glucose Random', code: 'GLU', price: 15.75, is_active: true, specimen_type: 'serum'},
                            {id: 4, name: 'Urinalysis', code: 'UA', price: 20.00, is_active: true, specimen_type: 'urine'}
                        ];
                    }
                    displayLabTests();
                })
                .catch(error => {
                    console.error('Error loading lab tests:', error);
                    // Fallback with user's configured tests
                    labTests = [
                        {id: 1, name: 'Complete Blood Count', code: 'CBC', price: 25.50, is_active: true, specimen_type: 'blood'},
                        {id: 2, name: 'Liver Function Test', code: 'LFT', price: 45.00, is_active: true, specimen_type: 'serum'},
                        {id: 3, name: 'Glucose Random', code: 'GLU', price: 15.75, is_active: true, specimen_type: 'serum'},
                        {id: 4, name: 'Urinalysis', code: 'UA', price: 20.00, is_active: true, specimen_type: 'urine'}
                    ];
                    displayLabTests();
                });
        }

        // Load available imaging tests
        function loadImagingTests() {
            fetch('/api/configuration/imaging-tests')
                .then(response => response.json())
                .then(tests => {
                    imagingTests = tests.filter(test => test.is_active);
                    displayImagingTests();
                })
                .catch(error => {
                    console.error('Error loading imaging tests:', error);
                    // Fallback with demo data if API fails
                    imagingTests = [
                        {id: 1, name: 'Chest X-Ray', code: 'CXR', modality: 'X-RAY', estimated_duration: 15, is_active: true},
                        {id: 2, name: 'CT Scan Head', code: 'CT-HEAD', modality: 'CT', estimated_duration: 30, is_active: true},
                        {id: 3, name: 'MRI Brain', code: 'MRI-BRAIN', modality: 'MRI', estimated_duration: 45, is_active: true}
                    ];
                    displayImagingTests();
                });
        }

        // Display lab tests
        function displayLabTests() {
            const container = document.getElementById('labTestsList');
            container.innerHTML = labTests.map(test => `
                <div class="test-item lab-test" draggable="true" data-test-id="${test.id}" data-test-type="lab">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${test.name}</strong>
                            <small class="d-block text-muted">${test.code}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success badge-test-type">LAB</span>
                            ${test.price ? `<small class="d-block">$${test.price}</small>` : ''}
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Display imaging tests
        function displayImagingTests() {
            const container = document.getElementById('imagingTestsList');
            container.innerHTML = imagingTests.map(test => `
                <div class="test-item imaging-test" draggable="true" data-test-id="${test.id}" data-test-type="imaging">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${test.name}</strong>
                            <small class="d-block text-muted">${test.code} - ${test.modality}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary badge-test-type">IMAGING</span>
                            ${test.estimated_duration ? `<small class="d-block">${test.estimated_duration}min</small>` : ''}
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Initialize drag and drop functionality
        function initializeDragAndDrop() {
            const dropZone = document.getElementById('selectedTestsArea');
            const testItems = document.querySelectorAll('.test-item');

            // Add drag start event to all test items
            document.addEventListener('dragstart', function(e) {
                if (e.target.classList.contains('test-item')) {
                    e.target.classList.add('dragging');
                    e.dataTransfer.setData('text/plain', JSON.stringify({
                        id: e.target.dataset.testId,
                        type: e.target.dataset.testType,
                        html: e.target.outerHTML
                    }));
                }
            });

            document.addEventListener('dragend', function(e) {
                if (e.target.classList.contains('test-item')) {
                    e.target.classList.remove('dragging');
                }
            });

            // Drop zone events
            dropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                dropZone.classList.add('drag-over');
            });

            dropZone.addEventListener('dragleave', function(e) {
                if (!dropZone.contains(e.relatedTarget)) {
                    dropZone.classList.remove('drag-over');
                }
            });

            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                dropZone.classList.remove('drag-over');
                
                const data = JSON.parse(e.dataTransfer.getData('text/plain'));
                addSelectedTest(data);
            });
        }

        // Add test to selected tests
        function addSelectedTest(testData) {
            // Check if test is already selected
            if (selectedTests.find(t => t.id === testData.id && t.type === testData.type)) {
                showAlert('Test already selected!', 'warning');
                return;
            }

            selectedTests.push(testData);
            updateSelectedTestsDisplay();
            updateSubmitButton();
        }

        // Update selected tests display
        function updateSelectedTestsDisplay() {
            const container = document.getElementById('selectedTests');
            const dropZoneText = document.querySelector('#selectedTestsArea p');
            const dropZoneIcon = document.querySelector('#selectedTestsArea i');

            if (selectedTests.length === 0) {
                container.innerHTML = '';
                dropZoneText.style.display = 'block';
                dropZoneIcon.style.display = 'block';
                return;
            }

            dropZoneText.style.display = 'none';
            dropZoneIcon.style.display = 'none';

            container.innerHTML = selectedTests.map((test, index) => {
                const testInfo = test.type === 'lab' 
                    ? labTests.find(t => t.id == test.id)
                    : imagingTests.find(t => t.id == test.id);
                
                return `
                    <div class="selected-test test-item mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${testInfo.name}</strong>
                                <small class="d-block text-muted">${testInfo.code}</small>
                            </div>
                            <div>
                                <span class="badge bg-${test.type === 'lab' ? 'success' : 'primary'} badge-test-type me-2">
                                    ${test.type.toUpperCase()}
                                </span>
                                <button class="btn btn-sm btn-outline-danger" onclick="removeSelectedTest(${index})">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Remove selected test
        function removeSelectedTest(index) {
            selectedTests.splice(index, 1);
            updateSelectedTestsDisplay();
            updateSubmitButton();
        }

        // Clear all selected tests
        function clearSelectedTests() {
            selectedTests = [];
            updateSelectedTestsDisplay();
            updateSubmitButton();
        }

        // Update submit button state
        function updateSubmitButton() {
            const submitBtn = document.getElementById('submitOrderBtn');
            const patientInput = document.getElementById('patientSearch');
            
            submitBtn.disabled = selectedTests.length === 0 || !patientInput.value.trim();
        }

        // Show order test modal
        function showOrderTestModal() {
            if (selectedTests.length === 0) {
                showAlert('Please select tests first using drag and drop!', 'info');
                return;
            }
            
            updateModalSelectedTests();
            new bootstrap.Modal(document.getElementById('orderTestModal')).show();
        }

        // Update modal selected tests display
        function updateModalSelectedTests() {
            const container = document.getElementById('modalSelectedTests');
            if (selectedTests.length === 0) {
                container.innerHTML = '<p class="text-muted mb-0">No tests selected</p>';
                return;
            }

            container.innerHTML = selectedTests.map(test => {
                const testInfo = test.type === 'lab' 
                    ? labTests.find(t => t.id == test.id)
                    : imagingTests.find(t => t.id == test.id);
                
                return `
                    <span class="badge bg-${test.type === 'lab' ? 'success' : 'primary'} me-2 mb-2">
                        ${testInfo.name} (${testInfo.code})
                    </span>
                `;
            }).join('');
        }

        // Submit test order
        function submitTestOrder() {
            const patientSearch = document.getElementById('patientSearch').value.trim();
            const priority = document.getElementById('priorityLevel').value;
            
            if (selectedTests.length === 0) {
                showAlert('No tests selected!', 'danger');
                return;
            }
            
            if (!patientSearch) {
                showAlert('Please enter patient information!', 'danger');
                return;
            }

            const orderData = {
                patient_query: patientSearch,
                priority: priority,
                lab_tests: selectedTests.filter(t => t.type === 'lab').map(t => t.id),
                imaging_tests: selectedTests.filter(t => t.type === 'imaging').map(t => t.id),
                clinical_notes: 'Ordered via drag-and-drop interface'
            };

            fetch('/api/test-orders', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(orderData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    showAlert(data.error, 'danger');
                    return;
                }
                
                showAlert('Test order submitted successfully!', 'success');
                clearSelectedTests();
                loadDashboardStats(); // Refresh stats
            })
            .catch(error => {
                console.error('Error submitting order:', error);
                showAlert('Failed to submit test order', 'danger');
            });
        }

        // Load patients for search
        function loadPatients() {
            fetch('/api/patients')
                .then(response => response.json())
                .then(data => {
                    patients = data;
                })
                .catch(error => {
                    console.error('Error loading patients:', error);
                });
        }

        // Update patient search input
        document.getElementById('patientSearch').addEventListener('input', updateSubmitButton);

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

        // Quick action functions
        function showNewPatientModal() {
            showAlert('New Patient modal - To be implemented', 'info');
        }

        function showPrescribeModal() {
            showAlert('Prescription modal - To be implemented', 'info');
        }

        function viewAppointments() {
            showAlert('Appointments view - To be implemented', 'info');
        }

        function viewResults() {
            showAlert('Test results view - To be implemented', 'info');
        }

        function viewEarnings() {
            showAlert('Earnings view - To be implemented', 'info');
        }
    </script>
</body>
</html>
