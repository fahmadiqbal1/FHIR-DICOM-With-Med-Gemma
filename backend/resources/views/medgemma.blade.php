<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MedGemma AI - Healthcare AI Platform</title>
    @include('partials.global-styles')
    
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
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1rem 0;
            margin-bottom: 2rem;
        }
        
        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
        }
        
        .logo::before {
            content: "🏥";
            margin-right: 0.5rem;
        }
        
        .nav {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .nav a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
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
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
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
        
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card h2 {
            color: white;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .card h3 {
            color: white;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
            font-weight: 500;
        }
        
        .status-panel, .capabilities-grid, .analysis-panel, .results-panel, .model-info-panel {
            color: white;
        }
        
        .status-grid, .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .status-item, .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .status-item label, .info-item label {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
        }
        
        .capabilities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        
        .capability-item {
            text-align: center;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .capability-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-3px);
        }
        
        .capability-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .capability-item p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
        }
        
        .row {
            display: flex;
            gap: 1rem;
            align-items: end;
            flex-wrap: wrap;
        }
        
        .col {
            flex: 1;
            min-width: 200px;
        }
        
        .col-auto {
            flex: 0 0 auto;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: white;
        }
        
        .form-select {
            width: 100%;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            color: white;
            font-size: 0.95rem;
            backdrop-filter: blur(5px);
        }
        
        .form-select option {
            background: #4a5568;
            color: white;
        }
        
        .form-select:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
        }
        
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
        }
        
        .btn.primary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(5px);
        }
        
        .btn.primary:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }
        
        .full-width {
            width: 100%;
        }
        
        .tag {
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .tag.success {
            background: rgba(40, 167, 69, 0.3);
            color: #90ee90;
            border: 1px solid rgba(40, 167, 69, 0.5);
        }
        
        .tag.warning {
            background: rgba(255, 193, 7, 0.3);
            color: #ffd700;
            border: 1px solid rgba(255, 193, 7, 0.5);
        }
        
        .tag.error {
            background: rgba(220, 53, 69, 0.3);
            color: #ffb3b3;
            border: 1px solid rgba(220, 53, 69, 0.5);
        }
        
        .tag.info {
            background: rgba(23, 162, 184, 0.3);
            color: #87ceeb;
            border: 1px solid rgba(23, 162, 184, 0.5);
        }
        
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .analysis-result {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .result-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .result-header h4 {
            color: white;
            font-size: 1.1rem;
            margin: 0;
        }
        
        .timestamp {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.85rem;
        }
        
        .success-message, .error-message, .info-message {
            padding: 1rem;
            border-radius: 8px;
            backdrop-filter: blur(5px);
        }
        
        .success-message {
            background: rgba(40, 167, 69, 0.2);
            border: 1px solid rgba(40, 167, 69, 0.3);
        }
        
        .error-message {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.3);
        }
        
        .info-message {
            background: rgba(23, 162, 184, 0.2);
            border: 1px solid rgba(23, 162, 184, 0.3);
        }
        
        .status-error {
            color: #ffb3b3;
            text-align: center;
            padding: 1rem;
        }
        
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 1rem;
            }
            
            .nav {
                flex-wrap: wrap;
            }
            
            .page-header h1 {
                font-size: 2rem;
            }
            
            .row {
                flex-direction: column;
            }
            
            .col {
                min-width: auto;
            }
            
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <a href="/dashboard" class="logo">Healthcare AI Platform</a>
            
            <nav class="nav">
                <a href="/dashboard">Dashboard</a>
                <a href="/patients">Patients</a>
                <a href="/medgemma" class="active">AI Analysis</a>
                <a href="/reports">Reports</a>
                <a href="/dicom-upload">DICOM Upload</a>
            </nav>
            
            <div class="user-info">
                <span>👤 Welcome</span>
                <form method="POST" action="/logout" style="display: inline;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="logout-btn">🚪 Sign Out</button>
                </form>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="page-header">
            <h1>MedGemma AI Analysis</h1>
            <p class="muted">Advanced AI-powered medical analysis and diagnostic assistance</p>
        </div>

        <div class="grid">
            <div class="card">
                <h2>🔧 AI Integration Status</h2>
                <div id="medgemmaStatus" class="status-panel">Loading...</div>
            </div>
            
            <div class="card">
                <h2>🚀 AI Capabilities</h2>
                <div class="capabilities-grid">
                    <div class="capability-item">
                        <div class="capability-icon">🧠</div>
                        <h3>Medical Imaging Analysis</h3>
                        <p>AI-powered analysis of DICOM images including X-rays, MRIs, CT scans</p>
                    </div>
                    <div class="capability-item">
                        <div class="capability-icon">🔬</div>
                        <h3>Laboratory Results</h3>
                        <p>Intelligent interpretation of lab values and trend analysis</p>
                    </div>
                    <div class="capability-item">
                        <div class="capability-icon">👨‍⚕️</div>
                        <h3>Clinical Decision Support</h3>
                        <p>AI-assisted second opinions and treatment recommendations</p>
                    </div>
                    <div class="capability-item">
                        <div class="capability-icon">📊</div>
                        <h3>Predictive Analytics</h3>
                        <p>Risk assessment and outcome prediction based on patient data</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <h2>⚡ Quick Analysis</h2>
            <div class="analysis-panel">
                <div class="row">
                    <div class="col">
                        <label for="patientSelect" class="form-label">Select Patient:</label>
                        <select id="patientSelect" class="form-select">
                            <option value="">Choose a patient...</option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="analysisType" class="form-label">Analysis Type:</label>
                        <select id="analysisType" class="form-select">
                            <option value="labs">Laboratory Analysis</option>
                            <option value="imaging">Imaging Analysis</option>
                            <option value="combined">Combined Second Opinion</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <label class="form-label">&nbsp;</label>
                        <button id="runAnalysis" class="btn primary full-width">🔍 Run Analysis</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <h2>📋 Recent AI Analysis Results</h2>
            <div id="recentResults" class="results-panel">
                <div class="empty-state">
                    <p>No recent analysis results</p>
                    <small>Run an analysis above to see results here</small>
                </div>
            </div>
        </div>

        <div class="card">
            <h2>🤖 AI Model Information</h2>
            <div id="modelInfo" class="model-info-panel">
                <div class="info-grid">
                    <div class="info-item">
                        <label>Model Version:</label>
                        <span id="modelVersion">Loading...</span>
                    </div>
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
                </div>
            </div>
        </div>
    </main>

    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let patients = [];

        function tag(text, cls='') { return `<span class="tag ${cls}">${text}</span>`; }
        function htmlesc(str) { return (str||'').toString().replace(/[&<>\"]/g, s=>({"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;"}[s])); }

        async function loadMedGemmaStatus() {
            const el = document.getElementById('medgemmaStatus');
            try {
                const r = await fetch('/integrations/medgemma');
                const d = await r.json();
                
                const statusHtml = `
                    <div class="status-grid">
                        <div class="status-item ${d.enabled ? 'status-ok' : 'status-warning'}">
                            <label>Status:</label>
                            <span>${d.enabled ? tag('Enabled', 'success') : tag('Disabled', 'warning')}</span>
                        </div>
                        <div class="status-item ${d.configured ? 'status-ok' : 'status-error'}">
                            <label>Configuration:</label>
                            <span>${d.configured ? tag('Configured', 'success') : tag('Not Configured', 'error')}</span>
                        </div>
                        <div class="status-item">
                            <label>Model:</label>
                            <span>${htmlesc(d.model || 'medgemma')}</span>
                        </div>
                        <div class="status-item ${d.integrated ? 'status-ok' : 'status-error'}">
                            <label>Integration:</label>
                            <span>${d.integrated ? tag('Active', 'success') : tag('Inactive', 'error')}</span>
                        </div>
                    </div>
                `;
                
                el.innerHTML = statusHtml;
                document.getElementById('modelVersion').textContent = d.model || 'medgemma';
            } catch (e) {
                el.innerHTML = '<div class="status-error">Failed to load MedGemma status</div>';
            }
        }

        async function loadPatients() {
            try {
                const r = await fetch('/api/patients', {headers: {'Accept':'application/json'}});
                const d = await r.json();
                patients = d || [];
                
                const select = document.getElementById('patientSelect');
                select.innerHTML = '<option value="">Choose a patient...</option>';
                
                patients.forEach(p => {
                    const name = p.name || `${p.first_name || ''} ${p.last_name || ''}`.trim();
                    const option = document.createElement('option');
                    option.value = p.id;
                    option.textContent = `${name} (MRN: ${p.mrn || 'N/A'})`;
                    select.appendChild(option);
                });
            } catch (e) {
                console.error('Failed to load patients');
            }
        }

        async function runAnalysis() {
            const patientId = document.getElementById('patientSelect').value;
            const analysisType = document.getElementById('analysisType').value;
            
            if (!patientId) {
                alert('Please select a patient first');
                return;
            }
            
            const button = document.getElementById('runAnalysis');
            const originalText = button.textContent;
            button.textContent = '🔄 Running Analysis...';
            button.disabled = true;
            
            try {
                let endpoint;
                switch (analysisType) {
                    case 'labs':
                        endpoint = `/medgemma/analyze/labs/${patientId}`;
                        break;
                    case 'imaging':
                        // For imaging, we need to find the patient's imaging studies first
                        const patient = patients.find(p => p.id == patientId);
                        if (!patient || !patient.imaging_studies || patient.imaging_studies.length === 0) {
                            throw new Error('No imaging studies found for this patient');
                        }
                        endpoint = `/medgemma/analyze/imaging/${patient.imaging_studies[0].id}`;
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
                
            } catch (e) {
                alert('Failed to run analysis: ' + e.message);
            } finally {
                button.textContent = originalText;
                button.disabled = false;
            }
        }

        function displayAnalysisResult(type, patientId, result) {
            const resultsPanel = document.getElementById('recentResults');
            const patient = patients.find(p => p.id == patientId);
            const patientName = patient ? (patient.name || `${patient.first_name || ''} ${patient.last_name || ''}`.trim()) : 'Unknown Patient';
            
            const timestamp = new Date().toLocaleString();
            
            const resultHtml = `
                <div class="analysis-result">
                    <div class="result-header">
                        <h4>${getAnalysisTypeLabel(type)} - ${htmlesc(patientName)}</h4>
                        <span class="timestamp">${timestamp}</span>
                    </div>
                    <div class="result-content">
                        ${formatAnalysisResult(result)}
                    </div>
                </div>
            `;
            
            if (resultsPanel.innerHTML.includes('empty-state')) {
                resultsPanel.innerHTML = resultHtml;
            } else {
                resultsPanel.insertAdjacentHTML('afterbegin', resultHtml);
            }
        }

        function getAnalysisTypeLabel(type) {
            switch (type) {
                case 'labs': return 'Laboratory Analysis';
                case 'imaging': return 'Imaging Analysis';
                case 'combined': return 'Combined Second Opinion';
                default: return 'AI Analysis';
            }
        }

        function formatAnalysisResult(result) {
            if (result.success) {
                return `
                    <div class="success-message">
                        ${tag('Analysis Completed', 'success')}
                        <p>AI analysis has been completed successfully. Results have been saved to the patient record.</p>
                    </div>
                `;
            } else if (result.error) {
                return `
                    <div class="error-message">
                        ${tag('Analysis Failed', 'error')}
                        <p>${htmlesc(result.error)}</p>
                    </div>
                `;
            } else {
                return `
                    <div class="info-message">
                        ${tag('Analysis Initiated', 'info')}
                        <p>Analysis request has been submitted and is being processed.</p>
                    </div>
                `;
            }
        }

        // Event listeners
        document.getElementById('runAnalysis').addEventListener('click', runAnalysis);

        // Initialize
        loadMedGemmaStatus();
        loadPatients();
    </script>
</body>
</html>
