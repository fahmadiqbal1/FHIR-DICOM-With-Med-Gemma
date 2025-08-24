<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lab Configuration - MedGemma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            color: white;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .glass-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }
        
        .stats-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.1) 100%);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            transform: scale(1.05);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.25) 0%, rgba(255, 255, 255, 0.15) 100%);
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #fff;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .stats-label {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
        }
        
        .test-card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        
        .test-card:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        .test-code {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .test-price {
            background: linear-gradient(45deg, #ffc107, #fd7e14);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            border-radius: 25px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(45deg, #20c997, #28a745);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        
        .btn-outline-light {
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 20px;
            transition: all 0.3s ease;
        }
        
        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
            transform: translateY(-1px);
        }
        
        .search-box {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 25px;
            color: white;
            padding: 0.75rem 1.5rem;
        }
        
        .search-box:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #28a745;
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        
        .search-box::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        
        .modal-content {
            background: rgba(25, 25, 25, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
        }
        
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            color: white;
            padding: 0.75rem 1rem;
        }
        
        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #28a745;
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        
        .form-label {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        
        .alert {
            border: none;
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }
        
        .category-filter {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            color: white;
            padding: 0.5rem 1rem;
            margin: 0.25rem;
            transition: all 0.3s ease;
        }
        
        .category-filter:hover,
        .category-filter.active {
            background: rgba(255, 255, 255, 0.2);
            border-color: #28a745;
            color: white;
        }
        
        .floating-add-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .floating-add-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            background: linear-gradient(45deg, #20c997, #28a745);
        }
        
        .actions-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .dropdown-menu {
            background: rgba(25, 25, 25, 0.95);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        
        .dropdown-item {
            color: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
        }
        
        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .test-card {
            animation: slideIn 0.5s ease-out;
        }
        
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            margin-bottom: 1rem;
        }
        
        .navbar-brand {
            font-weight: 600;
        }
        
        .navbar .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="/lab-tech-dashboard">
                <i class="fas fa-microscope me-2"></i>Lab Configuration
            </a>
            <div class="ms-auto">
                <span class="navbar-text me-3">Welcome, {{ Auth::user()->name ?? 'Lab Tech' }}</span>
                <a href="/logout" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <!-- Quick Actions Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="glass-card p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <h4 class="mb-0"><i class="fas fa-flask me-2"></i>Laboratory Test Management</h4>
                            <button class="btn btn-outline-light btn-sm" onclick="refreshTests()">
                                <i class="fas fa-sync-alt me-1"></i>Refresh
                            </button>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="/lab-tech-dashboard" class="btn btn-outline-light">
                                <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                            </a>
                            <button class="btn btn-outline-light" onclick="exportTests()">
                                <i class="fas fa-download me-1"></i>Export
                            </button>
                            <button class="btn btn-primary" onclick="showAddTestModal()">
                                <i class="fas fa-plus me-1"></i>Add Test
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Dashboard -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stats-card glass-card">
                    <div class="stats-number" id="totalTests">0</div>
                    <div class="stats-label">Total Tests</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card glass-card">
                    <div class="stats-number" id="activeTests">0</div>
                    <div class="stats-label">Active Tests</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card glass-card">
                    <div class="stats-number" id="categoryCount">0</div>
                    <div class="stats-label">Categories</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card glass-card">
                    <div class="stats-number">$<span id="avgPrice">0</span></div>
                    <div class="stats-label">Avg. Price</div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <div class="col-12">
                <div class="glass-card p-4">
                    <!-- Search and Filters -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="position-relative">
                                <i class="fas fa-search position-absolute" style="left: 1.5rem; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,0.7);"></i>
                                <input type="text" class="form-control search-box ps-5" id="searchTests" placeholder="Search by name, code, or category...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="statusFilter" style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.3); border-radius: 25px; color: white;">
                                <option value="">All Status</option>
                                <option value="1">Active Only</option>
                                <option value="0">Inactive Only</option>
                            </select>
                        </div>
                    </div>

                    <!-- Category Filters -->
                    <div class="mb-4" id="categoryFilters">
                        <button class="category-filter active" data-category="">All</button>
                    </div>

                    <!-- Tests Display -->
                    <div id="testsContainer">
                        <div class="text-center py-5">
                            <div class="loading-spinner mb-3"></div>
                            <p class="text-white-50">Loading tests...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Add Button -->
    <button class="floating-add-btn" onclick="showAddTestModal()" title="Add New Test">
        <i class="fas fa-plus"></i>
    </button>

    <!-- Add/Edit Test Modal -->
    <div class="modal fade" id="testModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="testModalTitle">Add Lab Test</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="testForm">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Test Code *</label>
                                <input type="text" class="form-control" id="testCode" name="code" required placeholder="e.g., CBC">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Test Name *</label>
                                <input type="text" class="form-control" id="testName" name="name" required placeholder="e.g., Complete Blood Count">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
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
                            <div class="col-md-6">
                                <label class="form-label">Test Price (USD)</label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background: rgba(255, 255, 255, 0.1); border-color: rgba(255, 255, 255, 0.3); color: white;">$</span>
                                    <input type="number" class="form-control" id="testPrice" name="price" step="0.01" min="0" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Unit</label>
                                <input type="text" class="form-control" id="testUnit" name="unit" placeholder="e.g., mg/dL, count/μL">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Normal Range</label>
                                <input type="text" class="form-control" id="normalRange" name="normal_range" placeholder="e.g., 4.5-11.0 x10³/μL">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label class="form-label">Equipment/Method</label>
                                <select class="form-select" id="testEquipment" name="equipment">
                                    <option value="">Select Equipment</option>
                                    <option value="Mission HA-360">Mission HA-360 (Hematology)</option>
                                    <option value="CBS-40">CBS-40 (Electrolytes)</option>
                                    <option value="Contec BC300">Contec BC300 (Biochemistry)</option>
                                    <option value="Manual">Manual Testing</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="testActive" name="is_active" checked>
                                    <label class="form-check-label" for="testActive">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Save Test
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
            setupEventListeners();
        });

        // Setup event listeners
        function setupEventListeners() {
            // Search functionality
            document.getElementById('searchTests').addEventListener('input', filterTests);
            document.getElementById('statusFilter').addEventListener('change', filterTests);

            // Form submission
            document.getElementById('testForm').addEventListener('submit', saveTest);
        }

        // Load all lab tests
        function loadTests() {
            fetch('/api/lab-tests')
                .then(response => response.json())
                .then(data => {
                    testsData = data;
                    displayTests(data);
                    updateStats(data);
                    updateCategoryFilters(data);
                })
                .catch(error => {
                    console.error('Error loading tests, using demo data:', error);
                    
                    // Comprehensive demo data showing all available tests for the 3-machine lab setup
                    testsData = [
                        // === Mission HA-360 Hematology Analyzer - Complete Blood Count Tests ===
                        {
                            id: 1,
                            code: 'WBC',
                            name: 'White Blood Cell Count',
                            price: 15.00,
                            category: 'Hematology',
                            unit: 'K/μL',
                            normal_range: '4.0-10.0',
                            equipment: 'Mission HA-360',
                            is_active: true
                        },
                        {
                            id: 2,
                            code: 'RBC',
                            name: 'Red Blood Cell Count',
                            price: 15.00,
                            category: 'Hematology',
                            unit: 'M/μL',
                            normal_range: 'M: 4.2-5.4, F: 3.6-5.0',
                            equipment: 'Mission HA-360',
                            is_active: true
                        },
                        {
                            id: 3,
                            code: 'HGB',
                            name: 'Hemoglobin',
                            price: 15.00,
                            category: 'Hematology',
                            unit: 'g/dL',
                            normal_range: 'M: 13.2-16.6, F: 11.6-15.0',
                            equipment: 'Mission HA-360',
                            is_active: true
                        },
                        {
                            id: 4,
                            code: 'HCT',
                            name: 'Hematocrit',
                            price: 15.00,
                            category: 'Hematology',
                            unit: '%',
                            normal_range: 'M: 38.3-48.6, F: 35.5-44.9',
                            equipment: 'Mission HA-360',
                            is_active: true
                        },
                        {
                            id: 5,
                            code: 'PLT',
                            name: 'Platelet Count',
                            price: 15.00,
                            category: 'Hematology',
                            unit: 'K/μL',
                            normal_range: '150-450',
                            equipment: 'Mission HA-360',
                            is_active: true
                        },
                        {
                            id: 6,
                            code: 'MCV',
                            name: 'Mean Cell Volume',
                            price: 15.00,
                            category: 'Hematology',
                            unit: 'fL',
                            normal_range: '80.0-100.0',
                            equipment: 'Mission HA-360',
                            is_active: true
                        },
                        {
                            id: 7,
                            code: 'MCH',
                            name: 'Mean Cell Hemoglobin',
                            price: 15.00,
                            category: 'Hematology',
                            unit: 'pg',
                            normal_range: '27.0-31.0',
                            equipment: 'Mission HA-360',
                            is_active: true
                        },
                        {
                            id: 8,
                            code: 'MCHC',
                            name: 'Mean Cell Hemoglobin Concentration',
                            price: 15.00,
                            category: 'Hematology',
                            unit: 'g/dL',
                            normal_range: '33.0-37.0',
                            equipment: 'Mission HA-360',
                            is_active: true
                        },
                        {
                            id: 9,
                            code: 'RDW-CV',
                            name: 'Red Cell Distribution Width',
                            price: 15.00,
                            category: 'Hematology',
                            unit: '%',
                            normal_range: '11.5-14.5',
                            equipment: 'Mission HA-360',
                            is_active: true
                        },
                        {
                            id: 10,
                            code: 'MPV',
                            name: 'Mean Platelet Volume',
                            price: 15.00,
                            category: 'Hematology',
                            unit: 'fL',
                            normal_range: '7.4-10.4',
                            equipment: 'Mission HA-360',
                            is_active: true
                        },
                        {
                            id: 11,
                            code: 'PDW',
                            name: 'Platelet Distribution Width',
                            price: 15.00,
                            category: 'Hematology',
                            unit: 'fL',
                            normal_range: '10.0-18.0',
                            equipment: 'Mission HA-360',
                            is_active: true
                        },
                        {
                            id: 12,
                            code: 'PCT',
                            name: 'Plateletcrit',
                            price: 15.00,
                            category: 'Hematology',
                            unit: '%',
                            normal_range: '0.20-0.50',
                            equipment: 'Mission HA-360',
                            is_active: true
                        },
                        
                        // === CBS-40 Chemistry Analyzer - Basic Metabolic Panel & Electrolytes ===
                        {
                            id: 13,
                            code: 'NA',
                            name: 'Sodium',
                            price: 12.00,
                            category: 'Chemistry',
                            unit: 'mmol/L',
                            normal_range: '136-145',
                            equipment: 'CBS-40',
                            is_active: true
                        },
                        {
                            id: 14,
                            code: 'K',
                            name: 'Potassium',
                            price: 12.00,
                            category: 'Chemistry',
                            unit: 'mmol/L',
                            normal_range: '3.5-5.1',
                            equipment: 'CBS-40',
                            is_active: true
                        },
                        {
                            id: 15,
                            code: 'CL',
                            name: 'Chloride',
                            price: 12.00,
                            category: 'Chemistry',
                            unit: 'mmol/L',
                            normal_range: '98-107',
                            equipment: 'CBS-40',
                            is_active: true
                        },
                        {
                            id: 16,
                            code: 'CO2',
                            name: 'Carbon Dioxide',
                            price: 12.00,
                            category: 'Chemistry',
                            unit: 'mmol/L',
                            normal_range: '22-28',
                            equipment: 'CBS-40',
                            is_active: true
                        },
                        {
                            id: 17,
                            code: 'GLU',
                            name: 'Glucose',
                            price: 10.00,
                            category: 'Chemistry',
                            unit: 'mg/dL',
                            normal_range: '70-100 (fasting)',
                            equipment: 'CBS-40',
                            is_active: true
                        },
                        {
                            id: 18,
                            code: 'BUN',
                            name: 'Blood Urea Nitrogen',
                            price: 15.00,
                            category: 'Chemistry',
                            unit: 'mg/dL',
                            normal_range: '7-20',
                            equipment: 'CBS-40',
                            is_active: true
                        },
                        {
                            id: 19,
                            code: 'CREA',
                            name: 'Creatinine',
                            price: 15.00,
                            category: 'Chemistry',
                            unit: 'mg/dL',
                            normal_range: 'M: 0.7-1.3, F: 0.6-1.1',
                            equipment: 'CBS-40',
                            is_active: true
                        },
                        {
                            id: 20,
                            code: 'EGFR',
                            name: 'Estimated GFR',
                            price: 15.00,
                            category: 'Chemistry',
                            unit: 'mL/min/1.73m²',
                            normal_range: '>60',
                            equipment: 'CBS-40',
                            is_active: true
                        },
                        {
                            id: 21,
                            code: 'CA',
                            name: 'Calcium',
                            price: 15.00,
                            category: 'Chemistry',
                            unit: 'mg/dL',
                            normal_range: '8.5-10.5',
                            equipment: 'CBS-40',
                            is_active: true
                        },
                        
                        // === Contec BC300 Biochemistry Analyzer - Liver, Cardiac & Lipid Tests ===
                        {
                            id: 22,
                            code: 'ALT',
                            name: 'Alanine Aminotransferase (SGPT)',
                            price: 18.00,
                            category: 'Liver Function',
                            unit: 'U/L',
                            normal_range: 'M: 7-56, F: 7-56',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 23,
                            code: 'AST',
                            name: 'Aspartate Aminotransferase (SGOT)',
                            price: 18.00,
                            category: 'Liver Function',
                            unit: 'U/L',
                            normal_range: 'M: 10-40, F: 10-40',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 24,
                            code: 'ALP',
                            name: 'Alkaline Phosphatase',
                            price: 18.00,
                            category: 'Liver Function',
                            unit: 'U/L',
                            normal_range: '44-147',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 25,
                            code: 'TBIL',
                            name: 'Total Bilirubin',
                            price: 20.00,
                            category: 'Liver Function',
                            unit: 'mg/dL',
                            normal_range: '0.2-1.0',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 26,
                            code: 'DBIL',
                            name: 'Direct Bilirubin',
                            price: 20.00,
                            category: 'Liver Function',
                            unit: 'mg/dL',
                            normal_range: '0.0-0.3',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 27,
                            code: 'CHOL',
                            name: 'Total Cholesterol',
                            price: 20.00,
                            category: 'Lipid Profile',
                            unit: 'mg/dL',
                            normal_range: '<200',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 28,
                            code: 'HDL',
                            name: 'HDL Cholesterol',
                            price: 20.00,
                            category: 'Lipid Profile',
                            unit: 'mg/dL',
                            normal_range: 'M: >40, F: >50',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 29,
                            code: 'LDL',
                            name: 'LDL Cholesterol',
                            price: 25.00,
                            category: 'Lipid Profile',
                            unit: 'mg/dL',
                            normal_range: '<100',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 30,
                            code: 'TRIG',
                            name: 'Triglycerides',
                            price: 20.00,
                            category: 'Lipid Profile',
                            unit: 'mg/dL',
                            normal_range: '<150',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 31,
                            code: 'TP',
                            name: 'Total Protein',
                            price: 15.00,
                            category: 'Protein Studies',
                            unit: 'g/dL',
                            normal_range: '6.0-8.3',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 32,
                            code: 'ALB',
                            name: 'Albumin',
                            price: 15.00,
                            category: 'Protein Studies',
                            unit: 'g/dL',
                            normal_range: '3.5-5.0',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 33,
                            code: 'GLOB',
                            name: 'Globulin',
                            price: 15.00,
                            category: 'Protein Studies',
                            unit: 'g/dL',
                            normal_range: '2.0-3.5',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 34,
                            code: 'A/G',
                            name: 'Albumin/Globulin Ratio',
                            price: 15.00,
                            category: 'Protein Studies',
                            unit: 'ratio',
                            normal_range: '1.1-2.5',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 35,
                            code: 'UA',
                            name: 'Uric Acid',
                            price: 15.00,
                            category: 'Chemistry',
                            unit: 'mg/dL',
                            normal_range: 'M: 3.4-7.0, F: 2.4-6.0',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 36,
                            code: 'PHOS',
                            name: 'Phosphorus',
                            price: 15.00,
                            category: 'Chemistry',
                            unit: 'mg/dL',
                            normal_range: '2.5-4.5',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 37,
                            code: 'MG',
                            name: 'Magnesium',
                            price: 18.00,
                            category: 'Chemistry',
                            unit: 'mg/dL',
                            normal_range: '1.6-2.6',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        
                        // === Cardiac Markers (Contec BC300) ===
                        {
                            id: 38,
                            code: 'CK',
                            name: 'Creatine Kinase',
                            price: 25.00,
                            category: 'Cardiac Markers',
                            unit: 'U/L',
                            normal_range: 'M: 38-174, F: 96-140',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 39,
                            code: 'CKMB',
                            name: 'CK-MB',
                            price: 30.00,
                            category: 'Cardiac Markers',
                            unit: 'ng/mL',
                            normal_range: '<6.3',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 40,
                            code: 'LDH',
                            name: 'Lactate Dehydrogenase',
                            price: 20.00,
                            category: 'Cardiac Markers',
                            unit: 'U/L',
                            normal_range: '140-280',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        
                        // === Thyroid Function Tests (Contec BC300) ===
                        {
                            id: 41,
                            code: 'TSH',
                            name: 'Thyroid Stimulating Hormone',
                            price: 35.00,
                            category: 'Thyroid Function',
                            unit: 'mIU/L',
                            normal_range: '0.27-4.20',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 42,
                            code: 'T3',
                            name: 'Triiodothyronine',
                            price: 40.00,
                            category: 'Thyroid Function',
                            unit: 'ng/dL',
                            normal_range: '80-200',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 43,
                            code: 'T4',
                            name: 'Thyroxine',
                            price: 40.00,
                            category: 'Thyroid Function',
                            unit: 'μg/dL',
                            normal_range: '5.1-14.1',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 44,
                            code: 'FT3',
                            name: 'Free T3',
                            price: 45.00,
                            category: 'Thyroid Function',
                            unit: 'pg/mL',
                            normal_range: '2.0-4.4',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 45,
                            code: 'FT4',
                            name: 'Free T4',
                            price: 45.00,
                            category: 'Thyroid Function',
                            unit: 'ng/dL',
                            normal_range: '0.82-1.77',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        
                        // === Diabetes Monitoring (CBS-40 & Contec BC300) ===
                        {
                            id: 46,
                            code: 'HBA1C',
                            name: 'Hemoglobin A1C',
                            price: 50.00,
                            category: 'Diabetes Monitoring',
                            unit: '%',
                            normal_range: '<5.7%',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 47,
                            code: 'FRUC',
                            name: 'Fructosamine',
                            price: 35.00,
                            category: 'Diabetes Monitoring',
                            unit: 'μmol/L',
                            normal_range: '205-285',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        
                        // === Comprehensive Panel Tests ===
                        {
                            id: 48,
                            code: 'CBC',
                            name: 'Complete Blood Count',
                            price: 35.00,
                            category: 'Panel Tests',
                            unit: 'panel',
                            normal_range: 'See individual components',
                            equipment: 'Mission HA-360',
                            is_active: true
                        },
                        {
                            id: 49,
                            code: 'BMP',
                            name: 'Basic Metabolic Panel',
                            price: 30.00,
                            category: 'Panel Tests',
                            unit: 'panel',
                            normal_range: 'See individual components',
                            equipment: 'CBS-40',
                            is_active: true
                        },
                        {
                            id: 50,
                            code: 'CMP',
                            name: 'Comprehensive Metabolic Panel',
                            price: 45.00,
                            category: 'Panel Tests',
                            unit: 'panel',
                            normal_range: 'See individual components',
                            equipment: 'CBS-40 + Contec BC300',
                            is_active: true
                        },
                        {
                            id: 51,
                            code: 'LIPID',
                            name: 'Lipid Panel',
                            price: 38.00,
                            category: 'Panel Tests',
                            unit: 'panel',
                            normal_range: 'See individual components',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 52,
                            code: 'HEPATIC',
                            name: 'Hepatic Function Panel',
                            price: 55.00,
                            category: 'Panel Tests',
                            unit: 'panel',
                            normal_range: 'See individual components',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 53,
                            code: 'RENAL',
                            name: 'Renal Function Panel',
                            price: 40.00,
                            category: 'Panel Tests',
                            unit: 'panel',
                            normal_range: 'See individual components',
                            equipment: 'CBS-40',
                            is_active: true
                        },
                        {
                            id: 54,
                            code: 'THYROID',
                            name: 'Thyroid Function Panel',
                            price: 120.00,
                            category: 'Panel Tests',
                            unit: 'panel',
                            normal_range: 'See individual components',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 55,
                            code: 'CARDIAC',
                            name: 'Cardiac Risk Assessment Panel',
                            price: 85.00,
                            category: 'Panel Tests',
                            unit: 'panel',
                            normal_range: 'See individual components',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        
                        // === Additional Specialized Tests ===
                        {
                            id: 56,
                            code: 'IRON',
                            name: 'Iron',
                            price: 25.00,
                            category: 'Hematology',
                            unit: 'μg/dL',
                            normal_range: 'M: 65-175, F: 50-170',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 57,
                            code: 'TIBC',
                            name: 'Total Iron Binding Capacity',
                            price: 25.00,
                            category: 'Hematology',
                            unit: 'μg/dL',
                            normal_range: '250-450',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 58,
                            code: 'FERR',
                            name: 'Ferritin',
                            price: 40.00,
                            category: 'Hematology',
                            unit: 'ng/mL',
                            normal_range: 'M: 12-300, F: 12-150',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 59,
                            code: 'B12',
                            name: 'Vitamin B12',
                            price: 45.00,
                            category: 'Vitamins',
                            unit: 'pg/mL',
                            normal_range: '200-900',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 60,
                            code: 'FOL',
                            name: 'Folate',
                            price: 40.00,
                            category: 'Vitamins',
                            unit: 'ng/mL',
                            normal_range: '>3.0',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 61,
                            code: 'VIT D',
                            name: 'Vitamin D, 25-Hydroxy',
                            price: 60.00,
                            category: 'Vitamins',
                            unit: 'ng/mL',
                            normal_range: '30-100',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 62,
                            code: 'PSA',
                            name: 'Prostate Specific Antigen',
                            price: 55.00,
                            category: 'Tumor Markers',
                            unit: 'ng/mL',
                            normal_range: '<4.0',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 63,
                            code: 'CEA',
                            name: 'Carcinoembryonic Antigen',
                            price: 50.00,
                            category: 'Tumor Markers',
                            unit: 'ng/mL',
                            normal_range: '<2.5',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 64,
                            code: 'AFP',
                            name: 'Alpha Fetoprotein',
                            price: 45.00,
                            category: 'Tumor Markers',
                            unit: 'ng/mL',
                            normal_range: '<10.0',
                            equipment: 'Contec BC300',
                            is_active: true
                        },
                        {
                            id: 65,
                            code: 'CA125',
                            name: 'Cancer Antigen 125',
                            price: 65.00,
                            category: 'Tumor Markers',
                            unit: 'U/mL',
                            normal_range: '<35.0',
                            equipment: 'Contec BC300',
                            is_active: true
                        }
                    ];
                    
                    displayTests(testsData);
                    updateStats(testsData);
                    updateCategoryFilters(testsData);
                    showAlert('Loaded demo tests for 3-machine lab setup', 'info');
                });
        }

        // Display tests in card format
        function displayTests(tests) {
            const container = document.getElementById('testsContainer');
            
            if (!tests || tests.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-flask fa-4x mb-3" style="opacity: 0.3;"></i>
                        <h5 class="text-white-50">No tests found</h5>
                        <p class="text-white-50">Click the "Add Test" button to create your first test.</p>
                    </div>
                `;
                return;
            }

            const html = tests.map(test => `
                <div class="test-card">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="test-code">${test.code}</span>
                                <span class="test-price">$${(test.price || 0).toFixed(2)}</span>
                                ${test.is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>'}
                            </div>
                            <h6 class="text-white mb-1">${test.name}</h6>
                            <div class="row text-sm">
                                <div class="col-md-6">
                                    ${test.category ? `<p class="mb-1 text-white-50"><i class="fas fa-tag me-1"></i>${test.category}</p>` : ''}
                                    ${test.unit ? `<p class="mb-1 text-white-50"><i class="fas fa-ruler me-1"></i>${test.unit}</p>` : ''}
                                </div>
                                <div class="col-md-6">
                                    ${test.normal_range ? `<p class="mb-1 text-white-50"><i class="fas fa-chart-line me-1"></i>${test.normal_range}</p>` : ''}
                                </div>
                            </div>
                        </div>
                        <div class="actions-dropdown">
                            <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="editTest(${test.id})"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="#" onclick="toggleTestStatus(${test.id}, ${test.is_active})"><i class="fas fa-${test.is_active ? 'pause' : 'play'} me-2"></i>${test.is_active ? 'Deactivate' : 'Activate'}</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteTest(${test.id}, '${test.name}')"><i class="fas fa-trash me-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            `).join('');

            container.innerHTML = html;
        }

        // Update statistics
        function updateStats(tests) {
            const totalTests = tests.length;
            const activeTests = tests.filter(t => t.is_active).length;
            const categories = [...new Set(tests.filter(t => t.category).map(t => t.category))].length;
            const avgPrice = tests.length ? (tests.reduce((sum, t) => sum + (parseFloat(t.price) || 0), 0) / tests.length) : 0;

            document.getElementById('totalTests').textContent = totalTests;
            document.getElementById('activeTests').textContent = activeTests;
            document.getElementById('categoryCount').textContent = categories;
            document.getElementById('avgPrice').textContent = avgPrice.toFixed(2);
        }

        // Update category filters
        function updateCategoryFilters(tests) {
            const categories = [...new Set(tests.filter(t => t.category).map(t => t.category))];
            const container = document.getElementById('categoryFilters');
            
            let html = '<button class="category-filter active" data-category="">All</button>';
            categories.forEach(cat => {
                html += `<button class="category-filter" data-category="${cat}">${cat}</button>`;
            });
            
            container.innerHTML = html;
            
            // Add event listeners to category filters
            container.querySelectorAll('.category-filter').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.category-filter').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    filterTests();
                });
            });
        }

        // Filter tests
        function filterTests() {
            const searchTerm = document.getElementById('searchTests').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const activeCategory = document.querySelector('.category-filter.active').dataset.category;

            let filtered = testsData;

            // Search filter
            if (searchTerm) {
                filtered = filtered.filter(test => 
                    test.name.toLowerCase().includes(searchTerm) ||
                    test.code.toLowerCase().includes(searchTerm) ||
                    (test.category && test.category.toLowerCase().includes(searchTerm))
                );
            }

            // Status filter
            if (statusFilter !== '') {
                filtered = filtered.filter(test => test.is_active.toString() === statusFilter);
            }

            // Category filter
            if (activeCategory) {
                filtered = filtered.filter(test => test.category === activeCategory);
            }

            displayTests(filtered);
        }

        // Show add test modal
        function showAddTestModal() {
            editingTestId = null;
            document.getElementById('testModalTitle').textContent = 'Add New Lab Test';
            document.getElementById('testForm').reset();
            document.getElementById('testActive').checked = true;
            new bootstrap.Modal(document.getElementById('testModal')).show();
        }

        // Edit test
        function editTest(testId) {
            const test = testsData.find(t => t.id === testId);
            if (!test) return;

            editingTestId = testId;
            document.getElementById('testModalTitle').textContent = 'Edit Lab Test';
            
            // Populate form fields
            document.getElementById('testCode').value = test.code || '';
            document.getElementById('testName').value = test.name || '';
            document.getElementById('testCategory').value = test.category || '';
            document.getElementById('testPrice').value = test.price || '';
            document.getElementById('testUnit').value = test.unit || '';
            document.getElementById('normalRange').value = test.normal_range || '';
            document.getElementById('testActive').checked = test.is_active;
            
            new bootstrap.Modal(document.getElementById('testModal')).show();
        }

        // Save test
        function saveTest(e) {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const testData = Object.fromEntries(formData.entries());
            
            // Handle checkbox
            testData.is_active = document.getElementById('testActive').checked;
            
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
        }

        // Toggle test status
        function toggleTestStatus(testId, currentStatus) {
            const newStatus = !currentStatus;
            
            fetch(`/api/lab-tests/${testId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ is_active: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.errors) {
                    showAlert('Failed to update status', 'danger');
                    return;
                }
                showAlert(`Test ${newStatus ? 'activated' : 'deactivated'} successfully!`, 'success');
                loadTests();
            })
            .catch(error => {
                console.error('Error updating status:', error);
                showAlert('Failed to update test status', 'danger');
            });
        }

        // Delete test
        function deleteTest(testId, testName) {
            if (!confirm(`Are you sure you want to delete "${testName}"?\n\nThis action cannot be undone.`)) return;
            
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
            showAlert('Tests refreshed successfully!', 'success');
        }

        // Export tests
        function exportTests() {
            const csvContent = "data:text/csv;charset=utf-8," +
                "Code,Name,Category,Price,Unit,Normal Range,Active\n" +
                testsData.map(test => 
                    `"${test.code}","${test.name}","${test.category || ''}","${test.price || '0.00'}","${test.unit || ''}","${test.normal_range || ''}","${test.is_active ? 'Yes' : 'No'}"`
                ).join('\n');
                
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "lab_tests.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            showAlert('Tests exported successfully!', 'success');
        }

        // Show alert
        function showAlert(message, type) {
            // Create alert container if it doesn't exist
            let alertContainer = document.getElementById('alertContainer');
            if (!alertContainer) {
                alertContainer = document.createElement('div');
                alertContainer.id = 'alertContainer';
                alertContainer.style.position = 'fixed';
                alertContainer.style.top = '20px';
                alertContainer.style.right = '20px';
                alertContainer.style.zIndex = '9999';
                document.body.appendChild(alertContainer);
            }

            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            alertContainer.appendChild(alert);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 5000);
        }
    </script>
</body>
</html>
