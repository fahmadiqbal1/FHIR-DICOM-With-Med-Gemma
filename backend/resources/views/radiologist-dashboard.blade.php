<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Radiologist Dashboard - MedGemma</title>
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
            padding: 1rem 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 1.8rem;
            font-weight: 600;
        }

        .header .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .main-container {
            padding: 2rem;
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 1.5rem;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .card h3 {
            margin-bottom: 1rem;
            font-size: 1.2rem;
            color: #ffffff;
        }

        .notification-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 0.5rem;
            border-left: 4px solid #4ade80;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .notification-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .notification-item.urgent {
            border-left-color: #ef4444;
        }

        .notification-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .notification-details {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .main-content {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .tab-button {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .tab-button.active {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .imaging-request {
            background: rgba(255, 255, 255, 0.05);
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .imaging-request-header {
            display: flex;
            justify-content: between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .patient-info {
            flex: 1;
        }

        .patient-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .patient-details {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .request-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn.primary {
            background: #4ade80;
            color: white;
        }

        .btn.secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.1);
        }

        .upload-area {
            border: 2px dashed rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            margin: 1rem 0;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .upload-area:hover {
            border-color: rgba(255, 255, 255, 0.5);
            background: rgba(255, 255, 255, 0.05);
        }

        .upload-area.dragover {
            border-color: #4ade80;
            background: rgba(74, 222, 128, 0.1);
        }

        .modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
            border-radius: 15px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
        }

        .close-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 0.9rem;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-pending {
            background: #fbbf24;
            color: #92400e;
        }

        .status-in-progress {
            background: #60a5fa;
            color: #1e40af;
        }

        .status-completed {
            background: #4ade80;
            color: #166534;
        }

        .priority-urgent {
            border-left: 4px solid #ef4444;
        }

        .priority-routine {
            border-left: 4px solid #4ade80;
        }

        .priority-stat {
            border-left: 4px solid #f59e0b;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
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
            border-bottom-color: #4ade80;
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
            color: #4ade80;
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
            border-left: 4px solid #4ade80;
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
        
        .ai-btn {
            background: linear-gradient(135deg, #8b5cf6, #a855f7) !important;
            border: none !important;
            color: white !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3) !important;
        }
        
        .ai-btn:hover {
            background: linear-gradient(135deg, #7c3aed, #9333ea) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(139, 92, 246, 0.4) !important;
        }
        
        .ai-btn:disabled {
            opacity: 0.7 !important;
            transform: none !important;
            cursor: not-allowed !important;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏥 Radiologist Dashboard</h1>
        <div class="user-info">
            <span>👨‍⚕️ {{ Auth::user()->name }}</span>
            <a href="{{ route('logout') }}" class="logout-btn" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

    <div class="main-container">
        <div class="sidebar">
            <!-- Notifications Panel -->
            <div class="card">
                <h3>🔔 New Imaging Requests</h3>
                <div id="notifications">
                    <div class="notification-item urgent" onclick="viewImagingRequest(1)">
                        <div class="notification-title">STAT - Chest X-Ray</div>
                        <div class="notification-details">Patient: John Doe • Doctor: Dr. Sarah Johnson</div>
                    </div>
                    <div class="notification-item" onclick="viewImagingRequest(2)">
                        <div class="notification-title">MRI Brain</div>
                        <div class="notification-details">Patient: Jane Smith • Doctor: Dr. Michael Chen</div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card">
                <h3>📊 Today's Statistics</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; text-align: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: 600; color: #4ade80;">12</div>
                        <div style="font-size: 0.9rem; opacity: 0.8;">Pending</div>
                    </div>
                    <div>
                        <div style="font-size: 2rem; font-weight: 600; color: #60a5fa;">8</div>
                        <div style="font-size: 0.9rem; opacity: 0.8;">Completed</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="tabs">
                <button class="tab-button active" onclick="switchTab('requests')">📋 Imaging Requests</button>
                <button class="tab-button" onclick="switchTab('completed')">✅ Completed Studies</button>
                <button class="tab-button" onclick="switchTab('patients')">👥 All Patients</button>
            </div>

            <!-- Imaging Requests Tab -->
            <div id="requests" class="tab-content active">
                <div class="card">
                    <h3>Pending Imaging Requests</h3>
                    <div id="imagingRequests">
                        <!-- Dynamic content will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Completed Studies Tab -->
            <div id="completed" class="tab-content">
                <div class="card">
                    <h3>Completed Studies</h3>
                    <div id="completedStudies">
                        <!-- Dynamic content will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- All Patients Tab -->
            <div id="patients" class="tab-content">
                <div class="card">
                    <h3>All Patients</h3>
                    <div id="allPatients">
                        <!-- Dynamic content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="uploadModal" class="modal">
        <div class="modal-content">
            <button class="close-btn" onclick="closeUploadModal()">&times;</button>
            <h3>📷 Upload Radiology Images & Report</h3>
            
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Patient & Study Information</label>
                    <input type="text" id="studyInfo" readonly style="background: rgba(255,255,255,0.05);">
                </div>

                <div class="upload-area" onclick="document.getElementById('imageFiles').click()">
                    <div>📁 Click to select images or drag & drop</div>
                    <div style="font-size: 0.9rem; margin-top: 0.5rem; opacity: 0.7;">Supports: JPEG, PNG, DICOM</div>
                </div>
                <input type="file" id="imageFiles" multiple accept=".jpg,.jpeg,.png,.dcm" style="display: none;">

                <div class="form-group">
                    <label>Radiological Findings</label>
                    <textarea id="findings" placeholder="Describe the radiological findings..." rows="4"></textarea>
                </div>

                <div class="form-group">
                    <label>Impression/Diagnosis</label>
                    <textarea id="impression" placeholder="Your professional impression and diagnosis..." rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>Study Status</label>
                    <select id="studyStatus">
                        <option value="completed">Completed</option>
                        <option value="preliminary">Preliminary Report</option>
                        <option value="addendum">Addendum Required</option>
                    </select>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="button" class="btn secondary" onclick="closeUploadModal()">Cancel</button>
                    <button type="submit" class="btn primary">💾 Save Report & Images</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let currentStudyId = null;

        // Tab switching
        function switchTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab content
            document.getElementById(tabName).classList.add('active');
            
            // Add active class to clicked button
            event.target.classList.add('active');

            // Load content based on tab
            switch(tabName) {
                case 'requests':
                    loadImagingRequests();
                    break;
                case 'completed':
                    loadCompletedStudies();
                    break;
                case 'patients':
                    loadAllPatients();
                    break;
            }
        }

        // Load imaging requests
        async function loadImagingRequests() {
            try {
                const response = await fetch('/api/radiologist/imaging-requests', {
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const requests = await response.json();
                    displayImagingRequests(requests);
                } else {
                    document.getElementById('imagingRequests').innerHTML = '<p style="text-align: center; opacity: 0.7;">No pending requests</p>';
                }
            } catch (error) {
                console.error('Error loading imaging requests:', error);
                document.getElementById('imagingRequests').innerHTML = '<p style="text-align: center; color: #ef4444;">Error loading requests</p>';
            }
        }

        function displayImagingRequests(requests) {
            const container = document.getElementById('imagingRequests');
            
            if (requests.length === 0) {
                container.innerHTML = '<p style="text-align: center; opacity: 0.7;">No pending imaging requests</p>';
                return;
            }

            container.innerHTML = requests.map(request => `
                <div class="imaging-request priority-${request.priority}">
                    <div class="imaging-request-header">
                        <div class="patient-info">
                            <div class="patient-name">${htmlEscape(request.patient_name)}</div>
                            <div class="patient-details">
                                Study: ${htmlEscape(request.description)} • 
                                Requested by: ${htmlEscape(request.doctor_name)} • 
                                ${request.ordered_date}
                            </div>
                            <div style="margin-top: 0.5rem;">
                                <span class="status-badge status-${request.status}">${request.status.toUpperCase()}</span>
                                ${request.priority === 'stat' ? '<span style="color: #ef4444; font-weight: 600; margin-left: 0.5rem;">⚡ STAT</span>' : ''}
                            </div>
                        </div>
                        <div class="request-actions">
                            <button class="btn primary" onclick="openUploadModal(${request.id}, '${htmlEscape(request.patient_name)}', '${htmlEscape(request.description)}')">
                                📷 Upload Images
                            </button>
                            <button class="btn secondary" onclick="viewPatientHistory(${request.patient_id})">
                                📋 Patient History
                            </button>
                        </div>
                    </div>
                    ${request.notes ? `<div style="background: rgba(255,255,255,0.05); padding: 0.75rem; border-radius: 8px; margin-top: 0.5rem;"><strong>Notes:</strong> ${htmlEscape(request.notes)}</div>` : ''}
                </div>
            `).join('');
        }

        // Open upload modal
        function openUploadModal(studyId, patientName, studyDescription) {
            currentStudyId = studyId;
            document.getElementById('studyInfo').value = `${patientName} - ${studyDescription}`;
            document.getElementById('uploadModal').classList.add('active');
        }

        function closeUploadModal() {
            document.getElementById('uploadModal').classList.remove('active');
            document.getElementById('uploadForm').reset();
            currentStudyId = null;
        }

        // Handle form submission
        document.getElementById('uploadForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!currentStudyId) {
                alert('No study selected');
                return;
            }

            // Validate required fields
            const findings = document.getElementById('findings').value.trim();
            const impression = document.getElementById('impression').value.trim();
            
            if (!findings) {
                alert('Please enter radiological findings');
                return;
            }
            
            if (!impression) {
                alert('Please enter impression/diagnosis');
                return;
            }

            const formData = new FormData();
            formData.append('study_id', currentStudyId);
            formData.append('findings', findings);
            formData.append('impression', impression);
            formData.append('status', document.getElementById('studyStatus').value);

            // Add files (optional)
            const files = document.getElementById('imageFiles').files;
            for (let i = 0; i < files.length; i++) {
                formData.append('images[]', files[i]);
            }

            try {
                console.log('Submitting radiology report...', {
                    study_id: currentStudyId,
                    findings: findings.substring(0, 50) + '...',
                    impression: impression.substring(0, 50) + '...',
                    files_count: files.length
                });

                const response = await fetch('/api/radiologist/upload-report', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf
                    },
                    body: formData
                });

                console.log('Upload response status:', response.status);
                
                if (response.ok) {
                    const result = await response.json();
                    console.log('Upload successful:', result);
                    alert('Report and images uploaded successfully!');
                    closeUploadModal();
                    loadImagingRequests(); // Refresh the list
                } else {
                    const errorText = await response.text();
                    console.error('Upload failed:', response.status, errorText);
                    
                    try {
                        const error = JSON.parse(errorText);
                        alert('Error uploading report: ' + (error.message || error.error || 'Unknown error'));
                    } catch {
                        alert('Error uploading report: Server error (Status: ' + response.status + ')');
                    }
                }
            } catch (error) {
                console.error('Network error uploading report:', error);
                alert('Network error uploading report. Please check your connection and try again.');
            }
        });

        // Load completed studies
        async function loadCompletedStudies() {
            document.getElementById('completedStudies').innerHTML = '<p style="text-align: center; opacity: 0.7;">Loading completed studies...</p>';
            
            try {
                const response = await fetch('/api/radiologist/completed-studies', {
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const studies = await response.json();
                    displayCompletedStudies(studies);
                } else {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
            } catch (error) {
                console.error('Error loading completed studies:', error);
                document.getElementById('completedStudies').innerHTML = `
                    <div style="text-align: center; padding: 2rem;">
                        <p style="color: #ef4444; margin-bottom: 1rem;">Error loading completed studies</p>
                        <p style="opacity: 0.7; font-size: 0.9rem;">${error.message}</p>
                        <p style="opacity: 0.7; font-size: 0.85rem; margin-top: 0.5rem;">Please ensure you are logged in as a radiologist.</p>
                        <button class="btn primary" onclick="loadCompletedStudies()" style="margin-top: 1rem;">Retry</button>
                    </div>
                `;
            }
        }

        function displayCompletedStudies(studies) {
            const container = document.getElementById('completedStudies');
            
            if (!studies || studies.length === 0) {
                container.innerHTML = `
                    <div style="text-align: center; padding: 2rem; opacity: 0.7;">
                        <p>No completed studies found.</p>
                        <p style="font-size: 0.9rem; margin-top: 0.5rem;">Studies you've completed will appear here.</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = studies.map(study => `
                <div class="study-card" style="background: rgba(255, 255, 255, 0.1); border-radius: 10px; padding: 1.5rem; margin-bottom: 1rem; border-left: 4px solid #4ade80;">
                    <div style="display: flex; justify-content: between; align-items: start; margin-bottom: 1rem;">
                        <div style="flex: 1;">
                            <h4 style="margin: 0 0 0.5rem 0; color: #4ade80;">${study.description || 'Imaging Study'}</h4>
                            <p style="margin: 0; opacity: 0.8;"><strong>Patient:</strong> ${study.patient_name}</p>
                            ${study.patient_mrn ? `<p style="margin: 0; opacity: 0.8; font-size: 0.9rem;"><strong>MRN:</strong> ${study.patient_mrn}</p>` : ''}
                        </div>
                        <div style="text-align: right;">
                            <span class="status-badge status-completed" style="background: #059669; color: white; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.85rem;">
                                ✅ ${study.status.charAt(0).toUpperCase() + study.status.slice(1)}
                            </span>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin: 1rem 0;">
                        <div>
                            <p style="margin: 0; font-size: 0.9rem; opacity: 0.7;">Modality</p>
                            <p style="margin: 0; font-weight: 500;">${study.modality || 'N/A'}</p>
                        </div>
                        <div>
                            <p style="margin: 0; font-size: 0.9rem; opacity: 0.7;">Completed Date</p>
                            <p style="margin: 0; font-weight: 500;">${study.completed_date || 'N/A'}</p>
                        </div>
                        <div>
                            <p style="margin: 0; font-size: 0.9rem; opacity: 0.7;">Images</p>
                            <p style="margin: 0; font-weight: 500;">${study.images_count || 0} files</p>
                        </div>
                    </div>

                    ${study.has_report ? `
                        <div style="background: rgba(255, 255, 255, 0.05); border-radius: 8px; padding: 1rem; margin-top: 1rem;">
                            <h5 style="margin: 0 0 0.5rem 0; color: #4ade80;">📋 Radiology Report</h5>
                            ${study.findings ? `<p style="margin: 0.5rem 0; font-size: 0.9rem;"><strong>Findings:</strong> ${study.findings}</p>` : ''}
                            ${study.impression ? `<p style="margin: 0.5rem 0; font-size: 0.9rem;"><strong>Impression:</strong> ${study.impression}</p>` : ''}
                        </div>
                    ` : ''}

                    ${study.images && study.images.length > 0 ? `
                        <div style="margin-top: 1rem;">
                            <h5 style="margin: 0 0 0.5rem 0; color: #4ade80;">📷 Attached Images</h5>
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                ${study.images.map(image => `
                                    <a href="${image.url}" target="_blank" style="display: inline-block; background: rgba(255, 255, 255, 0.1); padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; color: white; font-size: 0.85rem; transition: all 0.2s;">
                                        🖼️ ${image.filename}
                                    </a>
                                `).join('')}
                            </div>
                        </div>
                    ` : ''}

                    <div style="display: flex; gap: 0.5rem; margin-top: 1rem; flex-wrap: wrap;">
                        <button class="btn secondary" onclick="viewPatientHistory(${study.patient_id || study.id})" style="font-size: 0.85rem;">
                            📋 View Patient History
                        </button>
                        ${study.images && study.images.length > 0 ? `
                            <button class="btn secondary" onclick="window.open('${study.images[0].url}', '_blank')" style="font-size: 0.85rem;">
                                🔍 View Images
                            </button>
                            <button class="btn primary ai-btn" onclick="getAISecondOpinion(${study.id})" 
                                    id="ai-btn-${study.id}" style="font-size: 0.85rem;">
                                🤖 AI Second Opinion
                            </button>
                        ` : ''}
                    </div>
                    
                    <!-- AI Analysis Results Section -->
                    <div id="ai-analysis-${study.id}" style="display: none; margin-top: 1rem; background: rgba(139, 92, 246, 0.1); border-radius: 8px; padding: 1rem; border-left: 4px solid #8b5cf6;">
                        <h5 style="margin: 0 0 0.5rem 0; color: #8b5cf6; display: flex; align-items: center; gap: 0.5rem;">
                            🤖 AI Analysis Results
                            <span style="font-size: 0.8rem; background: rgba(139, 92, 246, 0.2); padding: 0.2rem 0.5rem; border-radius: 10px;">Independent Opinion</span>
                        </h5>
                        <div id="ai-content-${study.id}">
                            <!-- AI results will be populated here -->
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Load all patients
        async function loadAllPatients() {
            try {
                const response = await fetch('/api/patients', {
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const patients = await response.json();
                    displayAllPatients(patients);
                } else {
                    document.getElementById('allPatients').innerHTML = '<p style="text-align: center; color: #ef4444;">Error loading patients</p>';
                }
            } catch (error) {
                console.error('Error loading patients:', error);
                document.getElementById('allPatients').innerHTML = '<p style="text-align: center; color: #ef4444;">Error loading patients</p>';
            }
        }

        function displayAllPatients(patients) {
            const container = document.getElementById('allPatients');
            
            container.innerHTML = patients.map(patient => `
                <div class="imaging-request" style="border-left: 4px solid #4ade80;">
                    <div class="imaging-request-header">
                        <div class="patient-info">
                            <div class="patient-name">${htmlEscape(patient.first_name)} ${htmlEscape(patient.last_name)}</div>
                            <div class="patient-details">
                                DOB: ${patient.date_of_birth} • 
                                Gender: ${patient.gender} • 
                                MRN: ${patient.medical_record_number}
                            </div>
                        </div>
                        <div class="request-actions">
                            <button class="btn secondary" onclick="viewPatientHistory(${patient.id})">
                                📋 View History
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function viewPatientHistory(patientId) {
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

        function viewImagingRequest(requestId) {
            // Switch to requests tab and highlight specific request
            switchTab('requests');
        }

        function htmlEscape(str) {
            return str.replace(/&/g, '&amp;')
                     .replace(/</g, '&lt;')
                     .replace(/>/g, '&gt;')
                     .replace(/"/g, '&quot;')
                     .replace(/'/g, '&#39;');
        }

        // Drag and drop functionality
        const uploadArea = document.querySelector('.upload-area');
        
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            document.getElementById('imageFiles').files = files;
        });

        // AI Second Opinion functionality
        async function getAISecondOpinion(studyId) {
            const btn = document.getElementById(`ai-btn-${studyId}`);
            const analysisSection = document.getElementById(`ai-analysis-${studyId}`);
            const contentDiv = document.getElementById(`ai-content-${studyId}`);
            
            // Update button state
            btn.innerHTML = '⏳ Analyzing...';
            btn.disabled = true;
            
            // Show analysis section with loading state
            analysisSection.style.display = 'block';
            contentDiv.innerHTML = `
                <div style="text-align: center; padding: 1rem;">
                    <div style="display: inline-block; animation: spin 1s linear infinite; font-size: 1.5rem;">🤖</div>
                    <p style="margin: 0.5rem 0 0 0; opacity: 0.8;">Analyzing imaging data with AI...</p>
                    <p style="margin: 0; font-size: 0.85rem; opacity: 0.6;">This may take a few moments</p>
                </div>
            `;
            
            try {
                const response = await fetch(`/api/medgemma/imaging-study/${studyId}/analyze`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    }
                });
                
                if (response.ok) {
                    const result = await response.json();
                    
                    if (result.success) {
                        displayAIAnalysis(studyId, result);
                        btn.innerHTML = '✅ Analysis Complete';
                        btn.style.background = 'linear-gradient(135deg, #059669, #10b981)';
                    } else {
                        // Handle validation errors (non-medical images, etc.)
                        displayAIError(studyId, result);
                        btn.innerHTML = '⚠️ Cannot Analyze';
                        btn.style.background = 'linear-gradient(135deg, #f59e0b, #f97316)';
                    }
                } else {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
            } catch (error) {
                console.error('AI Analysis Error:', error);
                contentDiv.innerHTML = `
                    <div style="text-align: center; padding: 1rem; color: #ef4444;">
                        <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">⚠️</div>
                        <p style="margin: 0 0 0.5rem 0; font-weight: 500;">Analysis Failed</p>
                        <p style="margin: 0; font-size: 0.85rem; opacity: 0.8;">${error.message}</p>
                        <button onclick="getAISecondOpinion(${studyId})" style="margin-top: 0.75rem; padding: 0.5rem 1rem; background: #8b5cf6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.85rem;">
                            Try Again
                        </button>
                    </div>
                `;
                
                btn.innerHTML = '❌ Analysis Failed';
                btn.style.background = 'linear-gradient(135deg, #dc2626, #ef4444)';
                btn.disabled = false;
            }
        }
        
        function displayAIError(studyId, result) {
            const contentDiv = document.getElementById(`ai-content-${studyId}`);
            
            contentDiv.innerHTML = `
                <div style="margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 0.75rem;">
                        <h6 style="margin: 0; color: #f59e0b; font-weight: 600;">⚠️ AI Analysis Error</h6>
                        <span style="font-size: 0.75rem; background: rgba(245, 158, 11, 0.2); color: #f59e0b; padding: 0.2rem 0.5rem; border-radius: 8px;">
                            ${result.error_type || 'validation_error'}
                        </span>
                    </div>
                    <div style="background: rgba(245, 158, 11, 0.1); border-radius: 6px; padding: 0.75rem; font-size: 0.9rem; line-height: 1.4; border-left: 4px solid #f59e0b;">
                        ${result.error || result.impression || 'AI analysis could not be completed.'}
                    </div>
                </div>
                
                ${result.findings && result.findings.length > 0 ? `
                    <div style="margin-bottom: 1rem;">
                        <h6 style="margin: 0 0 0.5rem 0; color: #f59e0b; font-weight: 600;">🔍 Issue Details</h6>
                        <ul style="margin: 0; padding-left: 1.2rem; font-size: 0.9rem; line-height: 1.4;">
                            ${result.findings.map(finding => `<li style="margin-bottom: 0.25rem;">${finding}</li>`).join('')}
                        </ul>
                    </div>
                ` : ''}
                
                ${result.recommendations && result.recommendations.length > 0 ? `
                    <div style="margin-bottom: 1rem;">
                        <h6 style="margin: 0 0 0.5rem 0; color: #f59e0b; font-weight: 600;">💡 Recommendations</h6>
                        <ul style="margin: 0; padding-left: 1.2rem; font-size: 0.9rem; line-height: 1.4;">
                            ${result.recommendations.map(rec => `<li style="margin-bottom: 0.25rem;">${rec}</li>`).join('')}
                        </ul>
                    </div>
                ` : ''}
                
                ${result.note ? `
                    <div style="margin-top: 1rem; padding: 0.5rem; background: rgba(245, 158, 11, 0.05); border-radius: 4px; font-size: 0.8rem; opacity: 0.8; border-left: 2px solid #f59e0b;">
                        <strong>Note:</strong> ${result.note}
                    </div>
                ` : ''}
                
                <div style="text-align: center; margin-top: 1rem;">
                    <button onclick="document.getElementById('ai-analysis-${studyId}').style.display='none'; document.getElementById('ai-btn-${studyId}').style.display='inline-block';" 
                            style="padding: 0.4rem 1rem; background: rgba(245, 158, 11, 0.2); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 6px; cursor: pointer; font-size: 0.8rem;">
                        Hide Details
                    </button>
                </div>
            `;
        }
        
        function displayAIAnalysis(studyId, result) {
            const contentDiv = document.getElementById(`ai-content-${studyId}`);
            
            contentDiv.innerHTML = `
                <div style="margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 0.75rem;">
                        <h6 style="margin: 0; color: #8b5cf6; font-weight: 600;">🧠 AI Impression</h6>
                        ${result.confidence ? `
                            <span style="font-size: 0.75rem; background: rgba(139, 92, 246, 0.2); color: #8b5cf6; padding: 0.2rem 0.5rem; border-radius: 8px;">
                                ${Math.round(result.confidence * 100)}% confidence
                            </span>
                        ` : ''}
                    </div>
                    <div style="background: rgba(255, 255, 255, 0.05); border-radius: 6px; padding: 0.75rem; font-size: 0.9rem; line-height: 1.4;">
                        ${result.impression || result.analysis || 'AI analysis completed successfully.'}
                    </div>
                </div>
                
                ${result.findings && result.findings.length > 0 ? `
                    <div style="margin-bottom: 1rem;">
                        <h6 style="margin: 0 0 0.5rem 0; color: #8b5cf6; font-weight: 600;">🔍 Key Findings</h6>
                        <ul style="margin: 0; padding-left: 1.2rem; font-size: 0.9rem; line-height: 1.4;">
                            ${result.findings.map(finding => `<li style="margin-bottom: 0.25rem;">${finding}</li>`).join('')}
                        </ul>
                    </div>
                ` : ''}
                
                ${result.recommendations && result.recommendations.length > 0 ? `
                    <div style="margin-bottom: 1rem;">
                        <h6 style="margin: 0 0 0.5rem 0; color: #8b5cf6; font-weight: 600;">💡 AI Recommendations</h6>
                        <ul style="margin: 0; padding-left: 1.2rem; font-size: 0.9rem; line-height: 1.4;">
                            ${result.recommendations.map(rec => `<li style="margin-bottom: 0.25rem;">${rec}</li>`).join('')}
                        </ul>
                    </div>
                ` : ''}
                
                ${result.note ? `
                    <div style="margin-top: 1rem; padding: 0.5rem; background: rgba(255, 255, 255, 0.03); border-radius: 4px; font-size: 0.8rem; opacity: 0.7; border-left: 2px solid #8b5cf6;">
                        <strong>Note:</strong> ${result.note}
                    </div>
                ` : ''}
                
                <div style="margin-top: 1rem; padding: 0.5rem; background: rgba(255, 200, 0, 0.1); border-radius: 4px; font-size: 0.8rem; border-left: 2px solid #fbbf24;">
                    <strong>⚠️ Disclaimer:</strong> This AI analysis is for educational and research purposes only. It provides an independent opinion based solely on imaging data and should not replace professional medical judgment. Always rely on your clinical expertise and consider all available patient information.
                </div>
                
                <div style="text-align: center; margin-top: 1rem;">
                    <button onclick="document.getElementById('ai-analysis-${studyId}').style.display='none'; document.getElementById('ai-btn-${studyId}').style.display='inline-block';" 
                            style="padding: 0.4rem 1rem; background: rgba(139, 92, 246, 0.2); color: #8b5cf6; border: 1px solid rgba(139, 92, 246, 0.3); border-radius: 6px; cursor: pointer; font-size: 0.8rem;">
                        Hide Analysis
                    </button>
                </div>
            `;
        }
        
        // Add CSS for spin animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);

        // Load initial content
        document.addEventListener('DOMContentLoaded', function() {
            loadImagingRequests();
        });
    </script>
</body>
</html>
