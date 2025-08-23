<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MedGemma AI Analysis - FHIR DICOM Medical System</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            content: "🏥";
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
        
        .logout-btn {
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
        
        .logout-btn:hover {
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
        
        .page-header .muted {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.8);
        }
        
        /* Grid Layout */
        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* Card Styles */
        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card h2 {
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        /* Form Elements */
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
        
        .form-select, .form-input {
            width: 100%;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            color: white;
            font-size: 0.95rem;
            backdrop-filter: blur(5px);
            transition: all 0.3s ease;
        }
        
        .form-select option {
            background: #4a5568;
            color: white;
        }
        
        .form-select:focus, .form-input:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
        }
        
        .form-row {
            display: flex;
            gap: 1rem;
            align-items: end;
            flex-wrap: wrap;
        }
        
        .form-col {
            flex: 1;
            min-width: 200px;
        }
        
        .form-col-auto {
            flex: 0 0 auto;
        }
        
        /* Button Styles */
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
            background: linear-gradient(45deg, #ff6b6b, #ee5a52);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .btn.primary:hover {
            background: linear-gradient(45deg, #ff5252, #e53935);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 107, 107, 0.3);
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
        
        .btn.small {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }
        
        .btn.full-width {
            width: 100%;
        }
        
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }
        
        /* Status Panel */
        .status-panel {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }
        
        .status-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .status-item label {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
        }
        
        .status-item .status-value {
            font-size: 1.1rem;
            font-weight: bold;
        }
        
        .status-online {
            color: #4ade80;
        }
        
        .status-offline {
            color: #f87171;
        }
        
        /* Results Panel */
        .results-panel {
            max-height: 500px;
            overflow-y: auto;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.05);
            padding: 1rem;
        }
        
        .results-panel::-webkit-scrollbar {
            width: 8px;
        }
        
        .results-panel::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }
        
        .results-panel::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .empty-state p {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        
        .empty-state small {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        /* Analysis Result */
        .analysis-result {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        
        .analysis-result:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }
        
        .result-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .result-info h4 {
            color: white;
            margin-bottom: 0.25rem;
            font-size: 1.1rem;
        }
        
        .timestamp {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }
        
        .result-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .result-content {
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
        }
        
        .patient-details {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .patient-details h5 {
            color: white;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }
        
        .patient-details p {
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
        }
        
        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .info-item label {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
        }
        
        .info-item span {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
        }
        
        /* Loading States */
        .loading {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .loading::after {
            content: "";
            width: 12px;
            height: 12px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Responsive Design */
        @media (max-width: 1024px) {
            .main-content {
                padding: 1.5rem;
            }
            
            .page-header h1 {
                font-size: 2.5rem;
            }
        }
        
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
            
            .form-row {
                flex-direction: column;
            }
            
            .form-col {
                min-width: unset;
            }
            
            .result-header {
                flex-direction: column;
                align-items: stretch;
            }
            
            .result-actions {
                justify-content: center;
            }
        }
        
        @media (max-width: 480px) {
            .nav {
                flex-direction: column;
                width: 100%;
            }
            
            .nav a {
                width: 100%;
                text-align: center;
            }
            
            .user-info {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <a href="/dashboard" class="logo">Medical AI System</a>
            <nav class="nav">
                <a href="/dashboard">Dashboard</a>
                <a href="/patients">Patients</a>
                <a href="/medgemma" class="active">AI Analysis</a>
                <a href="/dicom-upload">DICOM Upload</a>
                <a href="/help">Help</a>
            </nav>
            <div class="user-info">
                <span>Dr. {{ 
                    (Auth::user()->name && strlen(Auth::user()->name) < 50 && !str_contains(Auth::user()->name, 'eyJ')) 
                        ? Auth::user()->name 
                        : ucfirst(str_replace(['.', '_', '-'], ' ', explode('@', Auth::user()->email ?? 'Doctor')[0])) 
                }}</span>
                <a href="/logout" class="logout-btn">Logout</a>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="page-header">
            <h1>🤖 MedGemma AI Analysis</h1>
            <p class="muted">Advanced Healthcare AI Analysis Platform</p>
        </div>

        <div class="content-grid">
            <!-- AI Status Card -->
            <div class="card">
                <h2>🔍 AI System Status</h2>
                <div class="status-panel">
                    <div class="status-grid">
                        <div class="status-item">
                            <label>MedGemma Status:</label>
                            <span id="medgemmaStatus" class="status-value loading">Checking...</span>
                        </div>
                        <div class="status-item">
                            <label>Model Version:</label>
                            <span id="modelVersion" class="status-value">Loading...</span>
                        </div>
                        <div class="status-item">
                            <label>Response Time:</label>
                            <span id="responseTime" class="status-value">--</span>
                        </div>
                        <div class="status-item">
                            <label>Uptime:</label>
                            <span id="uptime" class="status-value">--</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Analysis Card -->
            <div class="card">
                <h2>⚡ Quick Analysis</h2>
                <form id="analysisForm">
                    <div class="form-row">
                        <div class="form-col">
                            <label class="form-label">Select Patient:</label>
                            <select id="patientSelect" class="form-select" required>
                                <option value="">Loading patients...</option>
                            </select>
                        </div>
                        <div class="form-col">
                            <label class="form-label">Analysis Type:</label>
                            <select id="analysisType" class="form-select" required>
                                <option value="">Select analysis type</option>
                                <option value="labs">Laboratory Analysis</option>
                                <option value="imaging">Imaging Analysis</option>
                                <option value="combined">Combined Second Opinion</option>
                            </select>
                        </div>
                        <div class="form-col-auto">
                            <button type="submit" id="runAnalysis" class="btn primary">🔍 Run Analysis</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Recent Results Card (Full Width) -->
        <div class="card">
            <h2>📋 Recent AI Analysis Results</h2>
            <div id="recentResults" class="results-panel">
                <div class="empty-state">
                    <p>No recent analysis results</p>
                    <small>Run an analysis above to see results here</small>
                </div>
            </div>
        </div>

        <!-- AI Model Information -->
        <div class="content-grid">
            <div class="card">
                <h2>🤖 AI Model Information</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Supported Modalities:</label>
                        <span>DICOM, FHIR, Lab Data, Clinical Notes</span>
                    </div>
                    <div class="info-item">
                        <label>Analysis Types:</label>
                        <span>Diagnostic, Predictive, Comparative</span>
                    </div>
                    <div class="info-item">
                        <label>Confidence Scoring:</label>
                        <span>Yes (0-100%)</span>
                    </div>
                    <div class="info-item">
                        <label>Languages Supported:</label>
                        <span>English, Medical Terminology</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <h2>📊 Analysis Capabilities</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <label>🩺 Clinical Diagnosis:</label>
                        <span>Advanced pattern recognition for medical conditions</span>
                    </div>
                    <div class="info-item">
                        <label>🔬 Lab Analysis:</label>
                        <span>Comprehensive laboratory result interpretation</span>
                    </div>
                    <div class="info-item">
                        <label>🏥 Imaging Review:</label>
                        <span>DICOM image analysis and reporting</span>
                    </div>
                    <div class="info-item">
                        <label>🤝 Second Opinion:</label>
                        <span>Multi-modal comprehensive analysis</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let patients = [];

        // Utility functions
        function htmlesc(str) {
            return (str || '').toString().replace(/[&<>\"]/g, s => ({"&": "&amp;", "<": "&lt;", ">": "&gt;", "\"": "&quot;"}[s]));
        }

        // Load MedGemma status
        async function loadMedGemmaStatus() {
            const statusEl = document.getElementById('medgemmaStatus');
            const versionEl = document.getElementById('modelVersion');
            const responseTimeEl = document.getElementById('responseTime');
            const uptimeEl = document.getElementById('uptime');

            try {
                const startTime = Date.now();
                const response = await fetch('/medgemma/status', {
                    headers: { 'X-CSRF-TOKEN': csrf }
                });
                const responseTime = Date.now() - startTime;

                if (response.ok) {
                    const data = await response.json();
                    statusEl.textContent = 'Online';
                    statusEl.className = 'status-value status-online';
                    versionEl.textContent = data.version || 'v2.1.0';
                    responseTimeEl.textContent = `${responseTime}ms`;
                    uptimeEl.textContent = data.uptime || '99.9%';
                } else {
                    throw new Error('Service unavailable');
                }
            } catch (error) {
                statusEl.textContent = 'Offline';
                statusEl.className = 'status-value status-offline';
                versionEl.textContent = 'Unknown';
                responseTimeEl.textContent = 'Timeout';
                uptimeEl.textContent = 'N/A';
            }
        }

        // Load patients
        async function loadPatients() {
            const select = document.getElementById('patientSelect');
            try {
                const response = await fetch('/api/patients', {
                    headers: { 'X-CSRF-TOKEN': csrf }
                });

                if (response.ok) {
                    patients = await response.json();
                    select.innerHTML = '<option value="">Select a patient</option>';
                    patients.forEach(patient => {
                        const name = patient.name || `${patient.first_name || ''} ${patient.last_name || ''}`.trim();
                        const option = document.createElement('option');
                        option.value = patient.id;
                        option.textContent = `${name} (${patient.mrn || 'No MRN'})`;
                        select.appendChild(option);
                    });
                } else {
                    throw new Error('Failed to load patients');
                }
            } catch (error) {
                select.innerHTML = '<option value="">Error loading patients</option>';
                console.error('Error loading patients:', error);
            }
        }

        // Run analysis
        async function runAnalysis(event) {
            event.preventDefault();
            
            const patientId = document.getElementById('patientSelect').value;
            const analysisType = document.getElementById('analysisType').value;
            const button = document.getElementById('runAnalysis');

            if (!patientId || !analysisType) {
                alert('Please select both a patient and analysis type');
                return;
            }

            const originalText = button.textContent;
            button.textContent = 'Analyzing...';
            button.disabled = true;

            try {
                let endpoint;
                switch (analysisType) {
                    case 'labs':
                        endpoint = `/medgemma/labs/${patientId}`;
                        break;
                    case 'imaging':
                        endpoint = `/medgemma/imaging/${patientId}`;
                        break;
                    case 'combined':
                        endpoint = `/medgemma/second-opinion/${patientId}`;
                        break;
                    default:
                        throw new Error('Invalid analysis type');
                }

                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Analysis failed');
                }

                const result = await response.json();
                displayAnalysisResult(analysisType, patientId, result);

            } catch (error) {
                alert('Failed to run analysis: ' + error.message);
            } finally {
                button.textContent = originalText;
                button.disabled = false;
            }
        }

        // Display analysis result
        function displayAnalysisResult(type, patientId, result) {
            const resultsPanel = document.getElementById('recentResults');
            const patient = patients.find(p => p.id == patientId);
            const patientName = patient ? (patient.name || `${patient.first_name || ''} ${patient.last_name || ''}`.trim()) : 'Unknown Patient';
            
            const timestamp = new Date().toLocaleString();
            const resultId = Date.now();
            
            const resultHtml = `
                <div class="analysis-result" data-result-id="${resultId}">
                    <div class="result-header">
                        <div class="result-info">
                            <h4>${getAnalysisTypeLabel(type)} - ${htmlesc(patientName)}</h4>
                            <span class="timestamp">${timestamp}</span>
                        </div>
                        <div class="result-actions">
                            <button class="btn secondary small" onclick="exportToPDF(${resultId})">📄 PDF</button>
                            <button class="btn secondary small" onclick="printResult(${resultId})">🖨️ Print</button>
                            <button class="btn secondary small" onclick="emailResult(${resultId})">📧 Email</button>
                        </div>
                    </div>
                    <div class="result-content">
                        ${formatAnalysisResult(result)}
                        <div class="patient-details">
                            <h5>Patient Information:</h5>
                            <p><strong>Name:</strong> ${htmlesc(patientName)}</p>
                            <p><strong>MRN:</strong> ${patient ? (patient.mrn || 'N/A') : 'N/A'}</p>
                            <p><strong>DOB:</strong> ${patient && patient.dob ? new Date(patient.dob).toLocaleDateString() : 'N/A'}</p>
                            <p><strong>Analysis Type:</strong> ${getAnalysisTypeLabel(type)}</p>
                            <p><strong>Analysis Date:</strong> ${timestamp}</p>
                        </div>
                    </div>
                </div>
            `;
            
            if (resultsPanel.innerHTML.includes('empty-state')) {
                resultsPanel.innerHTML = resultHtml;
            } else {
                resultsPanel.insertAdjacentHTML('afterbegin', resultHtml);
            }
        }

        // Get analysis type label
        function getAnalysisTypeLabel(type) {
            switch (type) {
                case 'labs': return 'Laboratory Analysis';
                case 'imaging': return 'Imaging Analysis';
                case 'combined': return 'Combined Second Opinion';
                default: return 'AI Analysis';
            }
        }

        // Format analysis result
        function formatAnalysisResult(result) {
            if (typeof result === 'string') {
                return `<p>${htmlesc(result)}</p>`;
            }
            
            if (result.analysis || result.recommendation) {
                return `
                    <div>
                        ${result.analysis ? `<p><strong>Analysis:</strong> ${htmlesc(result.analysis)}</p>` : ''}
                        ${result.recommendation ? `<p><strong>Recommendation:</strong> ${htmlesc(result.recommendation)}</p>` : ''}
                        ${result.confidence ? `<p><strong>Confidence:</strong> ${result.confidence}%</p>` : ''}
                    </div>
                `;
            }
            
            return `<p>${htmlesc(JSON.stringify(result))}</p>`;
        }

        // Export functions
        function exportToPDF(resultId) {
            const resultElement = document.querySelector(`[data-result-id="${resultId}"]`);
            if (!resultElement) return;

            const content = resultElement.cloneNode(true);
            content.querySelector('.result-actions').remove();

            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>AI Analysis Report</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        .analysis-result { border: 1px solid #ccc; padding: 20px; }
                        .result-header h4 { margin: 0 0 10px 0; }
                        .timestamp { color: #666; font-size: 0.9em; }
                        .patient-details { margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee; }
                    </style>
                </head>
                <body>
                    <h1>Medical AI Analysis Report</h1>
                    ${content.outerHTML}
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }

        function printResult(resultId) {
            exportToPDF(resultId);
        }

        function emailResult(resultId) {
            const resultElement = document.querySelector(`[data-result-id="${resultId}"]`);
            if (!resultElement) return;

            const patientName = resultElement.querySelector('h4').textContent;
            const subject = `AI Analysis Report - ${patientName}`;
            const body = `Please find the AI analysis report attached.\n\nGenerated on: ${new Date().toLocaleString()}`;
            
            const mailtoLink = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
            window.location.href = mailtoLink;
        }

        // Event listeners
        document.getElementById('analysisForm').addEventListener('submit', runAnalysis);

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadMedGemmaStatus();
            loadPatients();
            
            // Refresh status every 30 seconds
            setInterval(loadMedGemmaStatus, 30000);
        });
    </script>
</body>
</html>
