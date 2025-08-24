<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Patient Management - Doctor Portal</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #2E8B57 0%, #3CB371 100%);
            min-height: 100vh;
            color: white;
            overflow-x: hidden;
        }
        
        /* Header Styles */
        .header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .logo::before {
            content: "👥";
            margin-right: 0.5rem;
        }
        
        .nav {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
            white-space: nowrap;
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
            flex-wrap: wrap;
        }
        
        .back-btn {
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
        
        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
        }
        
        /* Main Content */
        .main-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .page-header h1 {
            font-size: 3rem;
            font-weight: 600;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #fff, #e1e8ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .page-header .subtitle {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.8);
        }
        
        /* Grid Layout */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        @media (max-width: 1024px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* Card Styles */
        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .card h2, .card h3 {
            margin-bottom: 1.5rem;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            font-size: 1.25rem;
        }
        
        /* Patient Search */
        .search-section {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(5px);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .search-row {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .search-input {
            flex: 1;
            min-width: 250px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            color: white;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            backdrop-filter: blur(5px);
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
        }
        
        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        
        /* Patient List */
        .patient-list {
            max-height: 500px;
            overflow-y: auto;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(5px);
            padding: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .patient-list::-webkit-scrollbar {
            width: 8px;
        }
        
        .patient-list::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }
        
        .patient-list::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
        }
        
        .patient-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .patient-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
            border-color: rgba(255, 255, 255, 0.2);
        }
        
        .patient-item.selected {
            background: rgba(102, 126, 234, 0.3);
            border: 1px solid rgba(102, 126, 234, 0.5);
        }
        
        .patient-info h4 {
            color: white;
            margin-bottom: 0.25rem;
            font-size: 1rem;
        }
        
        .patient-info p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            margin: 0;
        }
        
        .patient-meta {
            text-align: right;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.6);
        }
        
        /* Patient Details Panel */
        .patient-details {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 1.5rem;
            min-height: 400px;
        }
        
        .patient-details.empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .patient-details .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        
        /* Clinical Tabs */
        .clinical-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 0.5rem;
        }
        
        .tab-button {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .tab-button:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .tab-button.active {
            background: rgba(102, 126, 234, 0.5);
            border-color: rgba(102, 126, 234, 0.7);
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        /* Forms */
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: white;
            font-size: 0.95rem;
        }
        
        .form-input, .form-textarea, .form-select {
            width: 100%;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            color: white;
            font-size: 0.95rem;
            backdrop-filter: blur(5px);
            transition: all 0.3s ease;
        }
        
        .form-input, .form-select {
            padding: 0.75rem;
        }
        
        .form-textarea {
            padding: 0.75rem;
            min-height: 120px;
            resize: vertical;
        }
        
        .form-input:focus, .form-textarea:focus, .form-select:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
        }
        
        .form-input::placeholder, .form-textarea::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .form-select option {
            background: #4a5568;
            color: white;
        }
        
        /* Buttons */
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
            text-align: center;
            white-space: nowrap;
        }
        
        .btn.primary {
            background: linear-gradient(45deg, #2E8B57, #3CB371);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .btn.primary:hover {
            background: linear-gradient(45deg, #228B22, #3CB371);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn.secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn.secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        
        .btn.success {
            background: linear-gradient(45deg, #4ade80, #22c55e);
            color: white;
        }
        
        .btn.success:hover {
            background: linear-gradient(45deg, #22c55e, #16a34a);
            transform: translateY(-2px);
        }
        
        .btn.small {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }
        
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }
        
        /* Clinical Notes */
        .notes-list {
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 1rem;
        }
        
        .note-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid #667eea;
        }
        
        .note-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .note-date {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.7);
        }
        
        .note-content {
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.5;
        }
        
        /* Orders and Results */
        .order-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid;
        }
        
        .order-item.lab { border-left-color: #fbbf24; }
        .order-item.imaging { border-left-color: #60a5fa; }
        .order-item.prescription { border-left-color: #a78bfa; }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .order-title {
            font-weight: 600;
            color: white;
        }
        
        .order-status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .order-status.pending { background: rgba(251, 191, 36, 0.2); color: #fbbf24; }
        .order-status.completed { background: rgba(34, 197, 94, 0.2); color: #22c55e; }
        .order-status.in-progress { background: rgba(96, 165, 250, 0.2); color: #60a5fa; }
        
        /* Image Viewer */
        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .image-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .image-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: scale(1.05);
        }
        
        .image-preview {
            width: 100%;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }
        
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 2000;
            backdrop-filter: blur(5px);
        }
        
        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .modal-header h3 {
            color: white;
            margin: 0;
        }
        
        .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 4px;
            transition: background 0.3s ease;
        }
        
        .close-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .header-content {
                padding: 0 1rem;
                flex-direction: column;
                gap: 1rem;
            }
            
            .nav {
                order: -1;
                width: 100%;
                justify-content: center;
            }
            
            .main-content {
                padding: 1rem;
            }
            
            .page-header h1 {
                font-size: 2rem;
            }
            
            .card {
                padding: 1.5rem;
            }
            
            .search-row {
                flex-direction: column;
            }
            
            .search-input {
                min-width: unset;
            }
            
            .clinical-tabs {
                flex-wrap: wrap;
            }
        }
        
        /* Lab Tests Drag & Drop Styles */
        .test-item {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            padding: 8px 12px;
            margin: 5px 0;
            cursor: grab;
            transition: all 0.3s ease;
            user-select: none;
            font-size: 14px;
            position: relative;
        }
        
        .test-item:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(5px);
        }
        
        .test-item:active {
            cursor: grabbing;
        }
        
        .test-item.dragging {
            opacity: 0.5;
        }
        
        .test-item.selected {
            background: rgba(76, 175, 80, 0.3);
            border-color: #4CAF50;
        }
        
        #availableTests.drag-over,
        #selectedTests.drag-over {
            background: rgba(76, 175, 80, 0.2);
            border-color: #4CAF50;
        }
        
        .remove-test {
            float: right;
            color: #ff6b6b;
            cursor: pointer;
            font-weight: bold;
            margin-left: 10px;
            font-size: 16px;
        }
        
        .remove-test:hover {
            color: #ff5252;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <a href="/dashboard" class="logo">Patient Management</a>
            <nav class="nav">
                <a href="/dashboard">Dashboard</a>
                <a href="/patients" class="active">Patients</a>
                <a href="/financial/doctor-dashboard">Financial</a>
                <a href="/medgemma">AI Analysis</a>
            </nav>
            <div class="user-info">
                <span>Dr. {{ 
                    (Auth::user()->name && strlen(Auth::user()->name) < 50 && !str_contains(Auth::user()->name, 'eyJ')) 
                        ? Auth::user()->name 
                        : ucfirst(str_replace(['.', '_', '-'], ' ', explode('@', Auth::user()->email ?? 'Doctor')[0])) 
                }}</span>
                <a href="/dashboard" class="back-btn">← Back</a>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="page-header">
            <h1>👥 Patient Management</h1>
            <p class="subtitle">View patient records and manage clinical data</p>
        </div>

        <div class="content-grid">
            <!-- Patient Search and List -->
            <div class="card">
                <h2>🔍 Patient Directory</h2>
                
                <div class="search-section">
                    <div class="search-row">
                        <input type="text" id="patientSearch" class="search-input" placeholder="Search by name, MRN, or email...">
                        <select id="genderFilter" class="form-select" style="min-width: 120px;">
                            <option value="">All Genders</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <div id="patientList" class="patient-list">
                    <div style="text-align: center; padding: 2rem; color: rgba(255, 255, 255, 0.6);">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">👥</div>
                        <p>Loading patients...</p>
                    </div>
                </div>
            </div>

            <!-- Patient Details and Clinical Data -->
            <div class="card">
                <h2>📋 Patient Clinical Data</h2>
                
                <div id="patientDetails" class="patient-details empty">
                    <div class="empty-icon">🩺</div>
                    <h3>Select a Patient</h3>
                    <p>Choose a patient from the directory to view their clinical information and manage their care.</p>
                </div>

                <div id="clinicalPanel" style="display: none;">
                    <div class="clinical-tabs">
                        <button class="tab-button active" onclick="switchTab('overview')">Overview</button>
                        <button class="tab-button" onclick="switchTab('notes')">Clinical Notes</button>
                        <button class="tab-button" onclick="switchTab('orders')">Orders & Results</button>
                        <button class="tab-button" onclick="switchTab('imaging')">Imaging</button>
                    </div>

                    <!-- Overview Tab -->
                    <div id="overview-tab" class="tab-content active">
                        <div id="patientOverview"></div>
                    </div>

                    <!-- Clinical Notes Tab -->
                    <div id="notes-tab" class="tab-content">
                        <div class="form-group">
                            <label class="form-label">Add Clinical Note:</label>
                            <textarea id="newNote" class="form-textarea" placeholder="Enter clinical observations, diagnosis, treatment plan..."></textarea>
                        </div>
                        <button class="btn primary small" onclick="addClinicalNote()">💾 Save Note</button>
                        
                        <div id="notesList" class="notes-list" style="margin-top: 1.5rem;">
                            <p style="color: rgba(255, 255, 255, 0.6); text-align: center; padding: 2rem;">No clinical notes yet</p>
                        </div>
                    </div>

                    <!-- Orders & Results Tab -->
                    <div id="orders-tab" class="tab-content">
                        <div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
                            <button class="btn secondary small" onclick="showOrderModal('lab')">🧪 Lab Order</button>
                            <button class="btn secondary small" onclick="showOrderModal('imaging')">📷 Imaging Request</button>
                            <button class="btn secondary small" onclick="showOrderModal('prescription')">💊 Prescription</button>
                        </div>
                        
                        <div id="ordersList">
                            <p style="color: rgba(255, 255, 255, 0.6); text-align: center; padding: 2rem;">No orders placed yet</p>
                        </div>
                    </div>

                    <!-- Imaging Tab -->
                    <div id="imaging-tab" class="tab-content">
                        <div id="imagingResults">
                            <p style="color: rgba(255, 255, 255, 0.6); text-align: center; padding: 2rem;">No imaging studies available</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Order Modal -->
    <div id="orderModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="orderModalTitle">Place Order</h3>
                <button class="close-btn" onclick="closeOrderModal()">&times;</button>
            </div>
            
            <form id="orderForm">
                <!-- Lab Tests Selection with Drag & Drop -->
                <div class="form-group">
                    <label class="form-label">Available Lab Tests:</label>
                    <div style="display: flex; gap: 20px; margin-top: 10px;">
                        <div id="availableTests" style="
                            flex: 1;
                            min-height: 150px;
                            border: 2px dashed #ccc;
                            border-radius: 8px;
                            padding: 15px;
                            background: rgba(255, 255, 255, 0.05);
                            color: white;
                        ">
                            <h5 style="margin: 0 0 10px 0; color: #ffffff;">Available Tests</h5>
                            <div id="loadingTests" style="text-align: center; padding: 20px; color: #888;">
                                <i class="fas fa-spinner fa-spin"></i> Loading available tests...
                            </div>
                        </div>
                        
                        <div id="selectedTests" style="
                            flex: 1;
                            min-height: 150px;
                            border: 2px dashed #4CAF50;
                            border-radius: 8px;
                            padding: 15px;
                            background: rgba(76, 175, 80, 0.1);
                            color: white;
                        ">
                            <h5 style="margin: 0 0 10px 0; color: #4CAF50;">Selected Tests</h5>
                            <p style="color: #888; font-style: italic; margin: 0;">Drag tests here from the left panel</p>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Priority:</label>
                    <select id="orderPriority" class="form-select" required>
                        <option value="">Select priority</option>
                        <option value="routine">Routine</option>
                        <option value="urgent">Urgent</option>
                        <option value="stat">STAT</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Clinical Notes:</label>
                    <textarea id="orderNotes" class="form-textarea" placeholder="Additional instructions or clinical context..."></textarea>
                </div>
                
                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="button" class="btn secondary" onclick="closeOrderModal()">Cancel</button>
                    <button type="submit" class="btn primary">Place Order</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Image Viewer Modal -->
    <div id="imageModal" class="modal">
        <div class="modal-content" style="max-width: 90%; max-height: 90%;">
            <div class="modal-header">
                <h3 id="imageModalTitle">Medical Image</h3>
                <button class="close-btn" onclick="closeImageModal()">&times;</button>
            </div>
            <div id="imageViewer" style="text-align: center;">
                <!-- Image will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        let selectedPatientId = null;
        let patients = [];
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Utility functions
        function htmlesc(str) {
            return (str || '').toString().replace(/[&<>\"]/g, s => ({"&": "&amp;", "<": "&lt;", ">": "&gt;", "\"": "&quot;"}[s]));
        }

        // Load patients
        async function loadPatients() {
            try {
                const response = await fetch('/api/patients', {
                    headers: { 'X-CSRF-TOKEN': csrf }
                });

                if (response.ok) {
                    patients = await response.json();
                    displayPatients(patients);
                } else {
                    throw new Error('Failed to load patients');
                }
            } catch (error) {
                document.getElementById('patientList').innerHTML = `
                    <div style="text-align: center; padding: 2rem; color: rgba(255, 255, 255, 0.6);">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">⚠️</div>
                        <p>Error loading patients</p>
                    </div>
                `;
                console.error('Error loading patients:', error);
            }
        }

        // Display patients
        function displayPatients(patientList) {
            const container = document.getElementById('patientList');
            
            if (patientList.length === 0) {
                container.innerHTML = `
                    <div style="text-align: center; padding: 2rem; color: rgba(255, 255, 255, 0.6);">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">👥</div>
                        <p>No patients found</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = patientList.map(patient => {
                const name = patient.name || `${patient.first_name || ''} ${patient.last_name || ''}`.trim();
                const age = patient.dob ? calculateAge(patient.dob) : 'Unknown';
                return `
                    <div class="patient-item" onclick="selectPatient(${patient.id})" data-patient-id="${patient.id}">
                        <div class="patient-info">
                            <h4>${htmlesc(name)}</h4>
                            <p>MRN: ${htmlesc(patient.mrn || 'N/A')} • Age: ${age} • ${htmlesc(patient.sex || 'N/A')}</p>
                            <p>📧 ${htmlesc(patient.email || 'No email')}</p>
                        </div>
                        <div class="patient-meta">
                            <div style="color: #4ade80;">▶ Select</div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Calculate age from DOB
        function calculateAge(dob) {
            const birthDate = new Date(dob);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            return age;
        }

        // Select patient
        function selectPatient(patientId) {
            // Remove previous selection
            document.querySelectorAll('.patient-item').forEach(item => {
                item.classList.remove('selected');
            });
            
            // Add selection to clicked item
            const selectedItem = document.querySelector(`[data-patient-id="${patientId}"]`);
            if (selectedItem) {
                selectedItem.classList.add('selected');
            }
            
            selectedPatientId = patientId;
            const patient = patients.find(p => p.id === patientId);
            
            if (patient) {
                showPatientDetails(patient);
            }
        }

        // Show patient details
        function showPatientDetails(patient) {
            document.getElementById('patientDetails').style.display = 'none';
            document.getElementById('clinicalPanel').style.display = 'block';
            
            // Load patient overview
            loadPatientOverview(patient);
            loadClinicalNotes(patient.id);
            loadOrders(patient.id);
            loadImagingStudies(patient.id);
        }

        // Load patient overview
        function loadPatientOverview(patient) {
            const name = patient.name || `${patient.first_name || ''} ${patient.last_name || ''}`.trim();
            const age = patient.dob ? calculateAge(patient.dob) : 'Unknown';
            
            document.getElementById('patientOverview').innerHTML = `
                <div style="display: grid; gap: 1rem;">
                    <div style="background: rgba(255, 255, 255, 0.05); padding: 1rem; border-radius: 10px;">
                        <h4 style="color: white; margin-bottom: 0.5rem;">Patient Information</h4>
                        <p><strong>Name:</strong> ${htmlesc(name)}</p>
                        <p><strong>MRN:</strong> ${htmlesc(patient.mrn || 'N/A')}</p>
                        <p><strong>Age:</strong> ${age} years</p>
                        <p><strong>Gender:</strong> ${htmlesc(patient.sex || 'N/A')}</p>
                        <p><strong>DOB:</strong> ${patient.dob ? new Date(patient.dob).toLocaleDateString() : 'N/A'}</p>
                        <p><strong>Email:</strong> ${htmlesc(patient.email || 'N/A')}</p>
                        <p><strong>Phone:</strong> ${htmlesc(patient.phone || 'N/A')}</p>
                    </div>
                    
                    <div style="background: rgba(255, 255, 255, 0.05); padding: 1rem; border-radius: 10px;">
                        <h4 style="color: white; margin-bottom: 0.5rem;">Quick Actions</h4>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            <button class="btn secondary small" onclick="switchTab('notes')">📝 Add Note</button>
                            <button class="btn secondary small" onclick="switchTab('orders')">🧪 New Order</button>
                            <button class="btn secondary small" onclick="switchTab('imaging')">📷 View Images</button>
                        </div>
                    </div>
                </div>
            `;
        }

        // Tab switching
        function switchTab(tabName) {
            // Remove active class from all tabs and contents
            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            // Add active class to selected tab
            event.target.classList.add('active');
            document.getElementById(`${tabName}-tab`).classList.add('active');
        }

        // Clinical Notes functions
        async function loadClinicalNotes(patientId) {
            try {
                console.log('Loading clinical notes for patient:', patientId);
                const response = await fetch(`/api/patients/${patientId}/notes`, {
                    headers: { 
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    }
                });
                
                console.log('Notes API response status:', response.status);
                
                if (response.ok) {
                    const notes = await response.json();
                    console.log('Notes loaded:', notes);
                    displayClinicalNotes(notes);
                } else {
                    const errorText = await response.text();
                    console.error('Error loading notes:', response.status, errorText);
                    displayClinicalNotes([]);
                }
            } catch (error) {
                console.error('Error loading notes:', error);
                displayClinicalNotes([]);
            }
        }

        function displayClinicalNotes(notes) {
            const container = document.getElementById('notesList');
            
            if (notes.length === 0) {
                container.innerHTML = '<p style="color: rgba(255, 255, 255, 0.6); text-align: center; padding: 2rem;">No clinical notes yet</p>';
                return;
            }
            
            container.innerHTML = notes.map(note => `
                <div class="note-item">
                    <div class="note-header">
                        <strong style="color: white;">${htmlesc(note.provider ? note.provider.name : 'Unknown Provider')}</strong>
                        <span class="note-date">${note.formatted_date || new Date(note.created_at).toLocaleString()}</span>
                    </div>
                    <div class="note-content">${htmlesc(note.content)}</div>
                </div>
            `).join('');
        }

        async function addClinicalNote() {
            const noteContent = document.getElementById('newNote').value.trim();
            
            if (!noteContent) {
                alert('Please enter a clinical note');
                return;
            }
            
            if (!selectedPatientId) {
                alert('No patient selected');
                return;
            }
            
            try {
                const response = await fetch(`/api/patients/${selectedPatientId}/notes`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ content: noteContent })
                });
                
                if (response.ok) {
                    alert('Clinical note saved successfully!');
                    document.getElementById('newNote').value = '';
                    loadClinicalNotes(selectedPatientId);
                } else {
                    const error = await response.json();
                    alert('Error saving note: ' + (error.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error saving note:', error);
                alert('Error saving note. Please try again.');
            }
        }

        // Orders functions
        async function loadOrders(patientId) {
            try {
                console.log('Loading orders for patient:', patientId);
                const response = await fetch(`/api/patients/${patientId}/orders`, {
                    headers: { 
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    }
                });
                
                console.log('Orders API response status:', response.status);
                
                if (response.ok) {
                    const orders = await response.json();
                    console.log('Orders loaded:', orders);
                    displayOrders(orders);
                } else {
                    const errorText = await response.text();
                    console.error('Error loading orders:', response.status, errorText);
                    displayOrders([]);
                }
            } catch (error) {
                console.error('Error loading orders:', error);
                displayOrders([]);
            }
        }

        function displayOrders(orders) {
            const container = document.getElementById('ordersList');
            
            if (orders.length === 0) {
                container.innerHTML = '<p style="color: rgba(255, 255, 255, 0.6); text-align: center; padding: 2rem;">No orders placed yet</p>';
                return;
            }
            
            container.innerHTML = orders.map(order => `
                <div class="order-item ${order.type}">
                    <div class="order-header">
                        <span class="order-title">${htmlesc(order.title)}</span>
                        <span class="order-status ${order.status}">${order.status.toUpperCase()}</span>
                    </div>
                    <p style="color: rgba(255, 255, 255, 0.8); margin-bottom: 0.5rem;">${htmlesc(order.description)}</p>
                    <p style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem;">Ordered: ${order.date}</p>
                    ${order.results ? `<div style="background: rgba(255, 255, 255, 0.05); padding: 0.75rem; border-radius: 8px; margin-top: 0.5rem;"><strong>Results:</strong> ${htmlesc(order.results)}</div>` : ''}
                </div>
            `).join('');
        }

        // Order modal functions
        let selectedTests = [];
        
        function showOrderModal(orderType) {
            const modal = document.getElementById('orderModal');
            const titleMap = {
                'lab': '🧪 Laboratory Order',
                'imaging': '📷 Imaging Request',
                'prescription': '💊 Prescription Order'
            };
            
            document.getElementById('orderModalTitle').textContent = titleMap[orderType] || 'Place Order';
            
            // Clear form and selected tests
            document.getElementById('orderForm').reset();
            selectedTests = [];
            updateSelectedTestsDisplay();
            
            // Initialize drag and drop if it's a lab order
            if (orderType === 'lab') {
                loadAvailableTests();
            }
            
            modal.classList.add('active');
        }
        
        // Load available tests from lab configuration
        async function loadAvailableTests() {
            try {
                const response = await fetch('/api/lab-tests', {
                    headers: { 'X-CSRF-TOKEN': csrf }
                });
                
                if (response.ok) {
                    const tests = await response.json();
                    displayAvailableTests(tests.filter(test => test.is_active));
                } else {
                    // Fallback to hardcoded tests if API fails
                    displayAvailableTests([
                        {code: 'CBC', name: 'Complete Blood Count', category: 'hematology', price: 35.00, equipment: 'Mission HA-360'},
                        {code: 'CMP', name: 'Comprehensive Metabolic Panel', category: 'chemistry', price: 45.00, equipment: 'CBS-40'},
                        {code: 'LFT', name: 'Liver Function Tests', category: 'chemistry', price: 40.00, equipment: 'Contec BC300'},
                        {code: 'LIPID', name: 'Lipid Panel', category: 'chemistry', price: 38.00, equipment: 'Contec BC300'},
                        {code: 'TSH', name: 'Thyroid Stimulating Hormone', category: 'immunology', price: 35.00, equipment: 'Contec BC300'},
                        {code: 'GLU', name: 'Glucose', category: 'chemistry', price: 10.00, equipment: 'CBS-40'},
                        {code: 'HGB', name: 'Hemoglobin', category: 'hematology', price: 15.00, equipment: 'Mission HA-360'},
                        {code: 'CREA', name: 'Creatinine', category: 'chemistry', price: 15.00, equipment: 'CBS-40'}
                    ]);
                }
            } catch (error) {
                console.error('Error loading available tests:', error);
                displayAvailableTests([]);
            }
        }
        
        function displayAvailableTests(tests) {
            const container = document.getElementById('availableTests');
            const loadingElement = document.getElementById('loadingTests');
            
            if (loadingElement) {
                loadingElement.remove();
            }
            
            if (tests.length === 0) {
                container.innerHTML = `
                    <h5 style="margin: 0 0 10px 0; color: #ffffff;">Available Tests</h5>
                    <p style="color: #888; font-style: italic;">No tests configured</p>
                `;
                return;
            }
            
            // Group tests by category
            const categories = {
                hematology: {name: 'Hematology', icon: '🩸', tests: []},
                chemistry: {name: 'Chemistry', icon: '🧪', tests: []},
                immunology: {name: 'Immunology', icon: '🛡️', tests: []},
                microbiology: {name: 'Microbiology', icon: '🦠', tests: []},
                molecular: {name: 'Molecular', icon: '🧬', tests: []}
            };
            
            tests.forEach(test => {
                const category = test.category || 'chemistry';
                if (categories[category]) {
                    categories[category].tests.push(test);
                }
            });
            
            let testsHTML = '<h5 style="margin: 0 0 10px 0; color: #ffffff;">Available Tests</h5>';
            
            Object.entries(categories).forEach(([key, category]) => {
                if (category.tests.length > 0) {
                    testsHTML += `<div style="margin-bottom: 15px;">`;
                    testsHTML += `<h6 style="color: #4CAF50; margin: 10px 0 5px 0; font-size: 12px;">${category.icon} ${category.name.toUpperCase()}</h6>`;
                    
                    category.tests.forEach(test => {
                        testsHTML += `
                            <div class="test-item" draggable="true" data-test="${test.code}" data-price="${test.price || 0}" data-equipment="${test.equipment || 'Manual'}">
                                ${category.icon} ${test.name} 
                                <span style="font-size: 11px; color: #888;">($${(test.price || 0).toFixed(2)})</span>
                            </div>
                        `;
                    });
                    testsHTML += `</div>`;
                }
            });
            
            container.innerHTML = testsHTML;
            
            // Initialize drag and drop after tests are loaded
            initializeDragAndDrop();
        }
        
        function initializeDragAndDrop() {
            const availableTests = document.getElementById('availableTests');
            const selectedTestsContainer = document.getElementById('selectedTests');
            
            // Add drag event listeners to available tests
            const testItems = availableTests.querySelectorAll('.test-item');
            testItems.forEach(item => {
                item.addEventListener('dragstart', handleDragStart);
                item.addEventListener('dragend', handleDragEnd);
            });
            
            // Add drop event listeners to containers
            [availableTests, selectedTestsContainer].forEach(container => {
                container.addEventListener('dragover', handleDragOver);
                container.addEventListener('drop', handleDrop);
                container.addEventListener('dragenter', handleDragEnter);
                container.addEventListener('dragleave', handleDragLeave);
            });
        }
        
        function handleDragStart(e) {
            e.dataTransfer.setData('text/plain', JSON.stringify({
                test: e.target.dataset.test,
                text: e.target.textContent
            }));
            e.target.classList.add('dragging');
        }
        
        function handleDragEnd(e) {
            e.target.classList.remove('dragging');
        }
        
        function handleDragOver(e) {
            e.preventDefault();
        }
        
        function handleDragEnter(e) {
            e.preventDefault();
            e.target.classList.add('drag-over');
        }
        
        function handleDragLeave(e) {
            e.target.classList.remove('drag-over');
        }
        
        function handleDrop(e) {
            e.preventDefault();
            e.target.classList.remove('drag-over');
            
            const data = JSON.parse(e.dataTransfer.getData('text/plain'));
            const targetContainer = e.target.id || e.target.closest('#availableTests, #selectedTests').id;
            
            if (targetContainer === 'selectedTests') {
                // Add to selected tests if not already added
                if (!selectedTests.some(test => test.test === data.test)) {
                    selectedTests.push(data);
                    updateSelectedTestsDisplay();
                }
            } else if (targetContainer === 'availableTests') {
                // Remove from selected tests
                selectedTests = selectedTests.filter(test => test.test !== data.test);
                updateSelectedTestsDisplay();
            }
        }
        
        function updateSelectedTestsDisplay() {
            const container = document.getElementById('selectedTests');
            const header = container.querySelector('h5');
            
            if (selectedTests.length === 0) {
                container.innerHTML = `
                    <h5 style="margin: 0 0 10px 0; color: #4CAF50;">Selected Tests</h5>
                    <p style="color: #888; font-style: italic; margin: 0;">Drag tests here from the left panel</p>
                `;
            } else {
                container.innerHTML = `
                    <h5 style="margin: 0 0 10px 0; color: #4CAF50;">Selected Tests (${selectedTests.length})</h5>
                    ${selectedTests.map(test => `
                        <div class="test-item selected" data-test="${test.test}">
                            ${test.text}
                            <span class="remove-test" onclick="removeSelectedTest('${test.test}')">&times;</span>
                        </div>
                    `).join('')}
                `;
            }
        }
        
        function removeSelectedTest(testCode) {
            selectedTests = selectedTests.filter(test => test.test !== testCode);
            updateSelectedTestsDisplay();
        }

        function closeOrderModal() {
            document.getElementById('orderModal').classList.remove('active');
        }

        // Handle order form submission
        document.getElementById('orderForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!selectedPatientId) {
                alert('No patient selected');
                return;
            }
            
            // Check if lab tests are selected for lab orders
            if (selectedTests.length === 0) {
                alert('Please select at least one lab test');
                return;
            }
            
            const orderData = {
                type: 'lab',
                description: selectedTests.map(test => test.text.replace(/^[^\s]+\s/, '')).join(', '), // Remove emoji and format
                tests: selectedTests.map(test => test.test), // Send test codes
                priority: document.getElementById('orderPriority').value,
                notes: document.getElementById('orderNotes').value
            };
            
            try {
                const response = await fetch(`/api/patients/${selectedPatientId}/orders`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(orderData)
                });
                
                if (response.ok) {
                    alert(`Order placed successfully!\nTests ordered: ${selectedTests.length}\nPriority: ${orderData.priority}`);
                    closeOrderModal();
                    loadOrders(selectedPatientId);
                } else {
                    const error = await response.json();
                    alert('Error placing order: ' + (error.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error placing order:', error);
                alert('Error placing order. Please try again.');
            }
        });

        // Imaging functions
        async function loadImagingStudies(patientId) {
            try {
                const response = await fetch(`/api/patients/${patientId}/imaging`, {
                    headers: { 'X-CSRF-TOKEN': csrf }
                });
                
                if (response.ok) {
                    const images = await response.json();
                    displayImagingStudies(images);
                } else {
                    displayImagingStudies([]);
                }
            } catch (error) {
                console.error('Error loading imaging:', error);
                displayImagingStudies([]);
            }
        }

        function displayImagingStudies(images) {
            const container = document.getElementById('imagingResults');
            
            if (images.length === 0) {
                container.innerHTML = '<p style="color: rgba(255, 255, 255, 0.6); text-align: center; padding: 2rem;">No imaging studies available</p>';
                return;
            }
            
            container.innerHTML = `
                <div class="image-grid">
                    ${images.map(image => `
                        <div class="image-item" onclick="viewImage(${image.id}, '${htmlesc(image.title)}')">
                            <div class="image-preview">${image.thumbnail}</div>
                            <div style="color: white; font-size: 0.9rem; font-weight: 500;">${htmlesc(image.title)}</div>
                            <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.8rem;">${image.date}</div>
                        </div>
                    `).join('')}
                </div>
            `;
        }

        function viewImage(imageId, title) {
            document.getElementById('imageModalTitle').textContent = title;
            document.getElementById('imageViewer').innerHTML = `
                <div style="background: rgba(255, 255, 255, 0.1); padding: 2rem; border-radius: 10px; text-align: center;">
                    <div style="font-size: 4rem; margin-bottom: 1rem;">🫁</div>
                    <p>Medical Image Viewer</p>
                    <p style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;">In a real implementation, the actual DICOM image would be displayed here</p>
                </div>
            `;
            document.getElementById('imageModal').classList.add('active');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.remove('active');
        }

        // Search functionality
        document.getElementById('patientSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const genderFilter = document.getElementById('genderFilter').value;
            
            const filteredPatients = patients.filter(patient => {
                const name = (patient.name || `${patient.first_name || ''} ${patient.last_name || ''}`).toLowerCase();
                const mrn = (patient.mrn || '').toLowerCase();
                const email = (patient.email || '').toLowerCase();
                const matchesSearch = name.includes(searchTerm) || mrn.includes(searchTerm) || email.includes(searchTerm);
                const matchesGender = !genderFilter || patient.sex === genderFilter;
                
                return matchesSearch && matchesGender;
            });
            
            displayPatients(filteredPatients);
        });

        document.getElementById('genderFilter').addEventListener('change', function() {
            document.getElementById('patientSearch').dispatchEvent(new Event('input'));
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadPatients();
        });
    </script>
</body>
</html>
