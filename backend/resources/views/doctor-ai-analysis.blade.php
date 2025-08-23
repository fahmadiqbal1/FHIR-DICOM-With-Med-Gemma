<!DOCTYPE html>
<html         .app-header {
            background: rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        /* Fix dropdown z-index issues */
        .dropdown-menu, .dropdown {
            z-index: 9999 !important;
            position: relative !important;
        }
        
        .dropdown-menu {
            background: white !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2) !important;
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
        }
        
        .dropdown-item {
            color: #333 !important;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa !important;
            color: #333 !important;
        }>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Analysis - Doctor Portal</title>
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
        }

        /* Header */
        .header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
        }

        .nav-links {
            display: flex;
            gap: 1rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        /* Main Content */
        .main-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-title {
            text-align: center;
            margin-bottom: 2rem;
        }

        .page-title h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .page-title p {
            font-size: 1.1rem;
            opacity: 0.8;
        }

        /* AI Analysis Types Grid */
        .ai-types-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .ai-type-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .ai-type-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .ai-type-card .card-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            display: block;
        }

        .ai-type-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: white;
        }

        .ai-type-card p {
            font-size: 1rem;
            opacity: 0.8;
            margin-bottom: 1.5rem;
        }

        .ai-type-card .features {
            list-style: none;
            text-align: left;
            font-size: 0.9rem;
            opacity: 0.7;
        }

        .ai-type-card .features li {
            margin-bottom: 0.5rem;
            padding-left: 1rem;
            position: relative;
        }

        .ai-type-card .features li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #4ade80;
            font-weight: bold;
        }

        /* Analysis Panel */
        .analysis-panel {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            margin-top: 2rem;
            display: none;
        }

        .analysis-panel.active {
            display: block;
        }

        .panel-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .panel-title {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .close-panel {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .close-panel:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Patient Selection */
        .patient-selection {
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: white;
        }

        .form-group select,
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 0.9rem;
        }

        .form-group select option {
            background: #2d3748;
            color: white;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        /* Analysis Results */
        .analysis-results {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 1.5rem;
            margin-top: 2rem;
            display: none;
        }

        .analysis-results.show {
            display: block;
        }

        .results-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .results-header h4 {
            font-size: 1.3rem;
            color: #8b5cf6;
        }

        .results-content {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 1.5rem;
            border-left: 4px solid #8b5cf6;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #8b5cf6, #a855f7);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #7c3aed, #9333ea);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(139, 92, 246, 0.3);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 10px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Loading State */
        .loading {
            text-align: center;
            padding: 2rem;
            font-style: italic;
            opacity: 0.7;
        }

        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.1);
            border-left: 4px solid #8b5cf6;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .ai-types-grid {
                grid-template-columns: 1fr;
            }
            
            .main-content {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <div class="logo">
                🤖 AI Analysis Portal
            </div>
            
            <div class="nav-links">
                <a href="/patients" class="nav-link">← Back to Dashboard</a>
                <a href="/dashboard" class="nav-link">Main Dashboard</a>
            </div>

            @auth
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">
                    🚪 Logout
                </button>
            </form>
            @else
            <a href="/login" class="logout-btn">Sign In</a>
            @endauth
        </div>
    </header>

    <main class="main-content">
        <div class="page-title">
            <h1>🩺 Medical AI Analysis</h1>
            <p>Advanced AI-powered clinical analysis for comprehensive patient care</p>
        </div>

        <!-- AI Analysis Types -->
        <div class="ai-types-grid">
            <!-- Clinical Notes/History Analysis -->
            <div class="ai-type-card" onclick="openAnalysisPanel('clinical')">
                <span class="card-icon">📋</span>
                <h3>Clinical Notes Analysis</h3>
                <p>Analyze patient history, clinical notes, and documentation for insights and patterns</p>
                <ul class="features">
                    <li>Review clinical documentation</li>
                    <li>Identify medical patterns</li>
                    <li>Extract key symptoms</li>
                    <li>Timeline analysis</li>
                    <li>Risk assessment</li>
                </ul>
            </div>

            <!-- Lab Results Analysis -->
            <div class="ai-type-card" onclick="openAnalysisPanel('labs')">
                <span class="card-icon">🧪</span>
                <h3>Lab Results Analysis</h3>
                <p>AI interpretation of laboratory test results with trend analysis and recommendations</p>
                <ul class="features">
                    <li>Lab value interpretation</li>
                    <li>Trend analysis over time</li>
                    <li>Abnormal value alerts</li>
                    <li>Reference range guidance</li>
                    <li>Clinical correlations</li>
                </ul>
            </div>

            <!-- Imaging Results Analysis -->
            <div class="ai-type-card" onclick="openAnalysisPanel('imaging')">
                <span class="card-icon">🩻</span>
                <h3>Imaging Analysis</h3>
                <p>Advanced AI analysis of radiology reports and imaging studies</p>
                <ul class="features">
                    <li>Imaging report analysis</li>
                    <li>Finding correlations</li>
                    <li>Comparative studies</li>
                    <li>Progress tracking</li>
                    <li>Diagnostic suggestions</li>
                </ul>
            </div>

            <!-- Comprehensive Second Opinion -->
            <div class="ai-type-card" onclick="openAnalysisPanel('comprehensive')" style="grid-column: 1 / -1;">
                <span class="card-icon">🔬</span>
                <h3>Comprehensive Second Opinion</h3>
                <p>Complete AI analysis combining latest clinical notes, lab results, and imaging studies for comprehensive patient assessment</p>
                <ul class="features" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                    <li>Integrated multi-source analysis</li>
                    <li>Comprehensive risk assessment</li>
                    <li>Treatment recommendations</li>
                    <li>Differential diagnosis support</li>
                    <li>Follow-up suggestions</li>
                    <li>Drug interaction analysis</li>
                </ul>
            </div>
        </div>

        <!-- Clinical Notes Analysis Panel -->
        <div id="clinical-panel" class="analysis-panel">
            <div class="panel-header">
                <h3 class="panel-title">📋 Clinical Notes Analysis</h3>
                <button class="close-panel" onclick="closeAnalysisPanel()">✕ Close</button>
            </div>
            
            <div class="patient-selection">
                <div class="form-group">
                    <label>Select Patient:</label>
                    <select id="clinical-patient" onchange="loadPatientClinicalNotes()">
                        <option value="">Choose a patient...</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Additional Clinical Information (Optional):</label>
                    <textarea id="clinical-notes" rows="4" placeholder="Enter any additional clinical notes or specific areas of concern..."></textarea>
                </div>
            </div>

            <button class="btn-primary" onclick="analyzeClinicalNotes()">
                🤖 Analyze Clinical History
            </button>

            <div id="clinical-results" class="analysis-results">
                <div class="results-header">
                    <span>🤖</span>
                    <h4>Clinical Analysis Results</h4>
                </div>
                <div class="results-content" id="clinical-content">
                    <!-- Results will be populated here -->
                </div>
            </div>
        </div>

        <!-- Lab Results Analysis Panel -->
        <div id="labs-panel" class="analysis-panel">
            <div class="panel-header">
                <h3 class="panel-title">🧪 Lab Results Analysis</h3>
                <button class="close-panel" onclick="closeAnalysisPanel()">✕ Close</button>
            </div>
            
            <div class="patient-selection">
                <div class="form-group">
                    <label>Select Patient:</label>
                    <select id="labs-patient" onchange="loadPatientLabResults()">
                        <option value="">Choose a patient...</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Analysis Focus (Optional):</label>
                    <textarea id="labs-focus" rows="3" placeholder="Specify particular lab values or trends to focus on..."></textarea>
                </div>
            </div>

            <button class="btn-primary" onclick="analyzeLabResults()">
                🤖 Analyze Lab Results
            </button>

            <div id="labs-results" class="analysis-results">
                <div class="results-header">
                    <span>🤖</span>
                    <h4>Lab Analysis Results</h4>
                </div>
                <div class="results-content" id="labs-content">
                    <!-- Results will be populated here -->
                </div>
            </div>
        </div>

        <!-- Imaging Analysis Panel -->
        <div id="imaging-panel" class="analysis-panel">
            <div class="panel-header">
                <h3 class="panel-title">🩻 Imaging Analysis</h3>
                <button class="close-panel" onclick="closeAnalysisPanel()">✕ Close</button>
            </div>
            
            <div class="patient-selection">
                <div class="form-group">
                    <label>Select Patient:</label>
                    <select id="imaging-patient" onchange="loadPatientImagingStudies()">
                        <option value="">Choose a patient...</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Clinical Questions (Optional):</label>
                    <textarea id="imaging-questions" rows="3" placeholder="Specific imaging findings or clinical questions to address..."></textarea>
                </div>
            </div>

            <button class="btn-primary" onclick="analyzeImagingResults()">
                🤖 Analyze Imaging Studies
            </button>

            <div id="imaging-results" class="analysis-results">
                <div class="results-header">
                    <span>🤖</span>
                    <h4>Imaging Analysis Results</h4>
                </div>
                <div class="results-content" id="imaging-content">
                    <!-- Results will be populated here -->
                </div>
            </div>
        </div>

        <!-- Comprehensive Second Opinion Panel -->
        <div id="comprehensive-panel" class="analysis-panel">
            <div class="panel-header">
                <h3 class="panel-title">🔬 Comprehensive Second Opinion</h3>
                <button class="close-panel" onclick="closeAnalysisPanel()">✕ Close</button>
            </div>
            
            <div class="patient-selection">
                <div class="form-group">
                    <label>Select Patient:</label>
                    <select id="comprehensive-patient" onchange="loadPatientComprehensiveData()">
                        <option value="">Choose a patient...</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Clinical Questions or Concerns:</label>
                    <textarea id="comprehensive-questions" rows="4" placeholder="Describe your clinical questions, differential diagnosis considerations, or specific areas where you'd like a second opinion..."></textarea>
                </div>

                <div class="form-group">
                    <label>Analysis Date Range:</label>
                    <select id="comprehensive-range">
                        <option value="latest">Latest data only</option>
                        <option value="30">Last 30 days</option>
                        <option value="90">Last 90 days</option>
                        <option value="180">Last 6 months</option>
                        <option value="all">All available data</option>
                    </select>
                </div>
            </div>

            <button class="btn-primary" onclick="getComprehensiveSecondOpinion()">
                🔬 Get Comprehensive Second Opinion
            </button>

            <div id="comprehensive-results" class="analysis-results">
                <div class="results-header">
                    <span>🤖</span>
                    <h4>Comprehensive Second Opinion</h4>
                </div>
                <div class="results-content" id="comprehensive-content">
                    <!-- Results will be populated here -->
                </div>
            </div>
        </div>
    </main>

    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Load patients on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadPatients();
        });

        // Load patients for all dropdowns
        async function loadPatients() {
            try {
                const response = await fetch('/api/patients', {
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const patients = await response.json();
                    populatePatientDropdowns(patients);
                } else {
                    console.error('Failed to load patients');
                }
            } catch (error) {
                console.error('Error loading patients:', error);
            }
        }

        function populatePatientDropdowns(patients) {
            const dropdowns = ['clinical-patient', 'labs-patient', 'imaging-patient', 'comprehensive-patient'];
            
            dropdowns.forEach(dropdownId => {
                const dropdown = document.getElementById(dropdownId);
                dropdown.innerHTML = '<option value="">Choose a patient...</option>';
                
                patients.forEach(patient => {
                    const option = document.createElement('option');
                    option.value = patient.id;
                    option.textContent = `${patient.name} (MRN: ${patient.mrn || 'N/A'})`;
                    dropdown.appendChild(option);
                });
            });
        }

        // Panel management
        function openAnalysisPanel(type) {
            closeAnalysisPanel(); // Close any open panels
            document.getElementById(`${type}-panel`).classList.add('active');
        }

        function closeAnalysisPanel() {
            document.querySelectorAll('.analysis-panel').forEach(panel => {
                panel.classList.remove('active');
            });
        }

        // Clinical Notes Analysis
        async function analyzeClinicalNotes() {
            const patientId = document.getElementById('clinical-patient').value;
            const additionalNotes = document.getElementById('clinical-notes').value;

            if (!patientId) {
                alert('Please select a patient first');
                return;
            }

            const resultsDiv = document.getElementById('clinical-results');
            const contentDiv = document.getElementById('clinical-content');
            
            resultsDiv.classList.add('show');
            contentDiv.innerHTML = '<div class="loading"><div class="spinner"></div>Analyzing clinical notes...</div>';

            try {
                const response = await fetch('/api/ai-analysis/clinical', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        patient_id: patientId,
                        additional_notes: additionalNotes
                    })
                });

                if (response.ok) {
                    const result = await response.json();
                    displayAnalysisResults(contentDiv, result);
                } else {
                    throw new Error('Analysis failed');
                }
            } catch (error) {
                contentDiv.innerHTML = `<p style="color: #ef4444;">Error analyzing clinical notes: ${error.message}</p>`;
            }
        }

        // Lab Results Analysis
        async function analyzeLabResults() {
            const patientId = document.getElementById('labs-patient').value;
            const focus = document.getElementById('labs-focus').value;

            if (!patientId) {
                alert('Please select a patient first');
                return;
            }

            const resultsDiv = document.getElementById('labs-results');
            const contentDiv = document.getElementById('labs-content');
            
            resultsDiv.classList.add('show');
            contentDiv.innerHTML = '<div class="loading"><div class="spinner"></div>Analyzing lab results...</div>';

            try {
                const response = await fetch('/api/ai-analysis/labs', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        patient_id: patientId,
                        focus: focus
                    })
                });

                if (response.ok) {
                    const result = await response.json();
                    displayAnalysisResults(contentDiv, result);
                } else {
                    throw new Error('Analysis failed');
                }
            } catch (error) {
                contentDiv.innerHTML = `<p style="color: #ef4444;">Error analyzing lab results: ${error.message}</p>`;
            }
        }

        // Imaging Analysis
        async function analyzeImagingResults() {
            const patientId = document.getElementById('imaging-patient').value;
            const questions = document.getElementById('imaging-questions').value;

            if (!patientId) {
                alert('Please select a patient first');
                return;
            }

            const resultsDiv = document.getElementById('imaging-results');
            const contentDiv = document.getElementById('imaging-content');
            
            resultsDiv.classList.add('show');
            contentDiv.innerHTML = '<div class="loading"><div class="spinner"></div>Analyzing imaging studies...</div>';

            try {
                const response = await fetch('/api/ai-analysis/imaging', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        patient_id: patientId,
                        questions: questions
                    })
                });

                if (response.ok) {
                    const result = await response.json();
                    displayAnalysisResults(contentDiv, result);
                } else {
                    throw new Error('Analysis failed');
                }
            } catch (error) {
                contentDiv.innerHTML = `<p style="color: #ef4444;">Error analyzing imaging results: ${error.message}</p>`;
            }
        }

        // Comprehensive Second Opinion
        async function getComprehensiveSecondOpinion() {
            const patientId = document.getElementById('comprehensive-patient').value;
            const questions = document.getElementById('comprehensive-questions').value;
            const range = document.getElementById('comprehensive-range').value;

            if (!patientId) {
                alert('Please select a patient first');
                return;
            }

            const resultsDiv = document.getElementById('comprehensive-results');
            const contentDiv = document.getElementById('comprehensive-content');
            
            resultsDiv.classList.add('show');
            contentDiv.innerHTML = '<div class="loading"><div class="spinner"></div>Generating comprehensive second opinion...</div>';

            try {
                const response = await fetch('/api/ai-analysis/comprehensive', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        patient_id: patientId,
                        questions: questions,
                        date_range: range
                    })
                });

                if (response.ok) {
                    const result = await response.json();
                    displayAnalysisResults(contentDiv, result);
                } else {
                    throw new Error('Analysis failed');
                }
            } catch (error) {
                contentDiv.innerHTML = `<p style="color: #ef4444;">Error generating second opinion: ${error.message}</p>`;
            }
        }

        // Display analysis results
        function displayAnalysisResults(contentDiv, result) {
            if (result.analysis) {
                contentDiv.innerHTML = `
                    <div style="white-space: pre-wrap; line-height: 1.6;">
                        ${result.analysis}
                    </div>
                    ${result.recommendations ? `
                        <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                            <h5 style="color: #4ade80; margin-bottom: 1rem;">Recommendations:</h5>
                            <div style="white-space: pre-wrap; line-height: 1.6;">
                                ${result.recommendations}
                            </div>
                        </div>
                    ` : ''}
                    <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid rgba(255, 255, 255, 0.1); font-size: 0.85rem; opacity: 0.7;">
                        <p><strong>Analysis completed:</strong> ${new Date().toLocaleString()}</p>
                        <p><em>This AI analysis is for informational purposes only and should not replace professional medical judgment.</em></p>
                    </div>
                `;
            } else {
                contentDiv.innerHTML = '<p style="color: #f59e0b;">No analysis results available</p>';
            }
        }

        // Placeholder functions for loading patient data
        function loadPatientClinicalNotes() {
            console.log('Loading clinical notes for patient');
        }

        function loadPatientLabResults() {
            console.log('Loading lab results for patient');
        }

        function loadPatientImagingStudies() {
            console.log('Loading imaging studies for patient');
        }

        function loadPatientComprehensiveData() {
            console.log('Loading comprehensive patient data');
        }
    </script>
</body>
</html>
