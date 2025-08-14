<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MedGemma Healthcare Platform') }} • Help</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            min-height: 100vh;
            line-height: 1.6;
        }
        
        /* Next-gen help styling */
        .help-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 0.5rem 0;
        }
        .help-header h2 {
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            color: #fff;
            margin-bottom: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .help-container {
            background: rgba(255,255,255,0.04);
            border-radius: 18px;
            box-shadow: 0 4px 32px rgba(102,126,234,0.08);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .help-section {
            background: rgba(255,255,255,0.06);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s;
        }
        .help-section:hover {
            background: rgba(255,255,255,0.08);
            transform: translateY(-2px);
        }
        .help-section h3 {
            color: #fff;
            margin-bottom: 1rem;
            font-size: 1.3rem;
            font-weight: 600;
        }
        .faq-item {
            background: rgba(255,255,255,0.04);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        .faq-item:hover {
            background: rgba(255,255,255,0.08);
        }
        .faq-question {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .faq-answer {
            color: rgba(255,255,255,0.8);
            display: none;
        }
        .faq-item.active .faq-answer {
            display: block;
        }
        
        .app-header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .mark {
            width: 32px;
            height: 32px;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            border-radius: 8px;
        }
        
        .nav {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn.ghost {
            background: transparent;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .btn.ghost:hover, .btn.ghost.active {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        
        .btn.primary {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn.primary:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
        }
        
        .btn.outline {
            background: transparent;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn.outline:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .btn.small {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #fff 0%, rgba(255,255,255,0.8) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .page-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
        }
        
        .muted {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .grid {
            display: grid;
            gap: 2rem;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            margin-bottom: 2rem;
        }
        
        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 2rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .card h2 {
            margin-bottom: 1.5rem;
            color: #fff;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .card h3 {
            color: #fff;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        
        .card h4 {
            color: #fff;
            margin-bottom: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
        }
        
        .help-section ol {
            padding-left: 1.5rem;
        }
        
        .help-section li {
            margin-bottom: 0.5rem;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .help-section li strong {
            color: #fff;
        }
        
        .help-section code {
            background: rgba(0, 0, 0, 0.3);
            padding: 0.2rem 0.4rem;
            border-radius: 4px;
            font-family: 'Monaco', 'Courier New', monospace;
            font-size: 0.9rem;
            color: #ff6b6b;
        }
        
        .nav-help-grid {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }
        
        .nav-item {
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .nav-item h4 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
            color: #fff;
        }
        
        .nav-item p {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 0.5rem;
        }
        
        .nav-item code {
            background: rgba(0, 0, 0, 0.3);
            padding: 0.2rem 0.4rem;
            border-radius: 4px;
            font-family: 'Monaco', 'Courier New', monospace;
            font-size: 0.8rem;
            color: #4ecdc4;
        }
        
        .help-tabs {
            margin-top: 1rem;
        }
        
        .tab-buttons {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        
        .tab-btn {
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.9rem;
        }
        
        .tab-btn.active, .tab-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            border-color: rgba(255, 255, 255, 0.4);
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .feature-list {
            display: grid;
            gap: 1.5rem;
        }
        
        .feature-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 1.5rem;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .feature-item h4 {
            color: #fff;
            margin-bottom: 0.75rem;
            font-size: 1.1rem;
        }
        
        .feature-item p {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 1rem;
        }
        
        .feature-item ul {
            padding-left: 1.5rem;
        }
        
        .feature-item li {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 0.3rem;
        }
        
        .api-section h3 {
            margin-bottom: 1.5rem;
            color: #fff;
        }
        
        .api-grid {
            display: grid;
            gap: 0.75rem;
        }
        
        .api-item {
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 1rem;
            align-items: center;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .api-method {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.8rem;
            min-width: 50px;
            text-align: center;
        }
        
        .api-method.get {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }
        
        .api-method.post {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }
        
        .api-path {
            font-family: 'Monaco', 'Courier New', monospace;
            font-size: 0.9rem;
            color: #fff;
        }
        
        .api-desc {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .requirements-grid {
            display: grid;
            gap: 2rem;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
        
        .req-section h3 {
            color: #fff;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }
        
        .req-section ul {
            padding-left: 1.5rem;
        }
        
        .req-section li {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 0.5rem;
        }
        
        .troubleshooting-section {
            margin-top: 1rem;
        }
        
        .faq-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 1.5rem;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1rem;
        }
        
        .faq-item h4 {
            color: #fff;
            margin-bottom: 0.75rem;
            font-size: 1rem;
        }
        
        .faq-item p {
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
        }
        
        .faq-item strong {
            color: #fff;
        }
        
        .faq-item code {
            background: rgba(0, 0, 0, 0.3);
            padding: 0.2rem 0.4rem;
            border-radius: 4px;
            font-family: 'Monaco', 'Courier New', monospace;
            font-size: 0.9rem;
            color: #4ecdc4;
        }
        
        .support-section {
            margin-top: 1rem;
        }
        
        .support-grid {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }
        
        .support-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 1.5rem;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }
        
        .support-item h4 {
            color: #fff;
            margin-bottom: 0.75rem;
            font-size: 1.1rem;
        }
        
        .support-item p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .inner {
                padding: 0 1rem;
                flex-direction: column;
                gap: 1rem;
            }
            
            .nav {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .container {
                padding: 1rem;
            }
            
            .page-header h1 {
                font-size: 2rem;
            }
            
            .grid {
                grid-template-columns: 1fr;
            }
            
            .tab-buttons {
                flex-direction: column;
            }
            
            .api-item {
                grid-template-columns: 1fr;
                gap: 0.5rem;
                text-align: left;
            }
        }
    </style>
</head>
<body>
<header class="app-header">
    <div class="inner">
        <div class="logo">
            <div class="mark"></div>
            <span>FHIR • DICOM • MedGemma</span>
        </div>
        <nav class="nav">
            <a class="btn ghost" href="/app" title="Dashboard">Dashboard</a>
            <a class="btn ghost" href="/patients" title="Patients">Patients</a>
            <a class="btn ghost" href="/medgemma" title="MedGemma AI">MedGemma AI</a>
            <a class="btn ghost" href="/reports" title="Reports">Reports</a>
            <a class="btn ghost" href="/dicom-upload" title="DICOM Upload">DICOM Upload</a>
            <a class="btn ghost active" href="/help" title="Help">Help</a>
        </nav>
    </div>
</header>

<div class="container">
    <div class="page-header">
        <h1>Help & Documentation</h1>
        <p class="muted">Complete guide to using the FHIR DICOM With MedGemma Healthcare Platform</p>
    </div>

    <div class="grid">
        <div class="card">
            <h2>Quick Start Guide</h2>
            <div class="help-section">
                <h3>Getting Started</h3>
                <ol>
                    <li><strong>Dashboard:</strong> Start at <code>/app</code> for the main overview</li>
                    <li><strong>Patients:</strong> View and manage patients at <code>/patients</code></li>
                    <li><strong>AI Analysis:</strong> Access MedGemma features at <code>/medgemma</code></li>
                    <li><strong>Reports:</strong> Generate clinical reports at <code>/reports</code></li>
                    <li><strong>DICOM Upload:</strong> Upload medical images at <code>/dicom-upload</code></li>
                </ol>
            </div>
        </div>

        <div class="card">
            <h2>Navigation</h2>
            <div class="help-section">
                <div class="nav-help-grid">
                    <div class="nav-item">
                        <h4>🏠 Dashboard</h4>
                        <p>Main overview with patient list, MedGemma status, and quick actions</p>
                        <code>/app</code>
                    </div>
                    <div class="nav-item">
                        <h4>👥 Patients</h4>
                        <p>Comprehensive patient management with detailed records</p>
                        <code>/patients</code>
                    </div>
                    <div class="nav-item">
                        <h4>🧠 MedGemma AI</h4>
                        <p>AI-powered medical analysis and diagnostic assistance</p>
                        <code>/medgemma</code>
                    </div>
                    <div class="nav-item">
                        <h4>📊 Reports</h4>
                        <p>Clinical reporting and data analytics</p>
                        <code>/reports</code>
                    </div>
                    <div class="nav-item">
                        <h4>📁 DICOM Upload</h4>
                        <p>Medical imaging upload and processing</p>
                        <code>/dicom-upload</code>
                    </div>
                    <div class="nav-item">
                        <h4>❓ Help</h4>
                        <p>Documentation and support resources</p>
                        <code>/help</code>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>Feature Guide</h2>
        <div class="help-tabs">
            <div class="tab-buttons">
                <button class="tab-btn active" onclick="showTab('patients-tab')">Patient Management</button>
                <button class="tab-btn" onclick="showTab('ai-tab')">AI Analysis</button>
                <button class="tab-btn" onclick="showTab('dicom-tab')">DICOM Processing</button>
                <button class="tab-btn" onclick="showTab('reports-tab')">Reports</button>
            </div>
            
            <div id="patients-tab" class="tab-content active">
                <h3>Patient Management</h3>
                <div class="feature-list">
                    <div class="feature-item">
                        <h4>Patient Directory</h4>
                        <p>Browse all patients with search and filtering capabilities</p>
                        <ul>
                            <li>Search by name or MRN</li>
                            <li>View patient demographics</li>
                            <li>Quick access to medical records</li>
                        </ul>
                    </div>
                    <div class="feature-item">
                        <h4>Medical Records</h4>
                        <p>Comprehensive view of patient clinical data</p>
                        <ul>
                            <li>Imaging studies with AI analysis</li>
                            <li>Laboratory results and trends</li>
                            <li>Prescription management</li>
                            <li>Clinical notes and assessments</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div id="ai-tab" class="tab-content">
                <h3>MedGemma AI Analysis</h3>
                <div class="feature-list">
                    <div class="feature-item">
                        <h4>Laboratory Analysis</h4>
                        <p>AI-powered interpretation of lab results</p>
                        <ul>
                            <li>Automated result interpretation</li>
                            <li>Trend analysis and alerts</li>
                            <li>Reference range validation</li>
                        </ul>
                    </div>
                    <div class="feature-item">
                        <h4>Imaging Analysis</h4>
                        <p>AI analysis of medical imaging studies</p>
                        <ul>
                            <li>DICOM image processing</li>
                            <li>Automated findings detection</li>
                            <li>Confidence scoring</li>
                        </ul>
                    </div>
                    <div class="feature-item">
                        <h4>Clinical Decision Support</h4>
                        <p>AI-assisted clinical recommendations</p>
                        <ul>
                            <li>Combined analysis of all patient data</li>
                            <li>Second opinion generation</li>
                            <li>Risk assessment</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div id="dicom-tab" class="tab-content">
                <h3>DICOM Processing</h3>
                <div class="feature-list">
                    <div class="feature-item">
                        <h4>Image Upload</h4>
                        <p>Upload and process DICOM medical images</p>
                        <ul>
                            <li>Drag-and-drop interface</li>
                            <li>Multiple file support</li>
                            <li>Automatic metadata extraction</li>
                        </ul>
                    </div>
                    <div class="feature-item">
                        <h4>FHIR Integration</h4>
                        <p>Standards-compliant healthcare data exchange</p>
                        <ul>
                            <li>Automatic FHIR resource creation</li>
                            <li>ImagingStudy resource generation</li>
                            <li>Patient linking and validation</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div id="reports-tab" class="tab-content">
                <h3>Clinical Reports</h3>
                <div class="feature-list">
                    <div class="feature-item">
                        <h4>Patient Reports</h4>
                        <p>Comprehensive patient summaries</p>
                        <ul>
                            <li>Demographics and statistics</li>
                            <li>Medical history summaries</li>
                            <li>Treatment timelines</li>
                        </ul>
                    </div>
                    <div class="feature-item">
                        <h4>Analytics</h4>
                        <p>Healthcare data insights and trends</p>
                        <ul>
                            <li>Population health metrics</li>
                            <li>AI analysis summaries</li>
                            <li>Custom date range filtering</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>API Reference</h2>
        <div class="api-section">
            <h3>Available Endpoints</h3>
            <div class="api-grid">
                <div class="api-item">
                    <div class="api-method get">GET</div>
                    <div class="api-path">/reports/patients</div>
                    <div class="api-desc">List all patients with summary data</div>
                </div>
                <div class="api-item">
                    <div class="api-method get">GET</div>
                    <div class="api-path">/reports/patients/{id}</div>
                    <div class="api-desc">Get detailed patient information</div>
                </div>
                <div class="api-item">
                    <div class="api-method post">POST</div>
                    <div class="api-path">/medgemma/analyze/labs/{patient}</div>
                    <div class="api-desc">Analyze patient lab results</div>
                </div>
                <div class="api-item">
                    <div class="api-method post">POST</div>
                    <div class="api-path">/medgemma/analyze/imaging/{study}</div>
                    <div class="api-desc">Analyze imaging study</div>
                </div>
                <div class="api-item">
                    <div class="api-method post">POST</div>
                    <div class="api-path">/medgemma/second-opinion/{patient}</div>
                    <div class="api-desc">Get AI second opinion</div>
                </div>
                <div class="api-item">
                    <div class="api-method post">POST</div>
                    <div class="api-path">/dicom/upload</div>
                    <div class="api-desc">Upload DICOM files</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>System Requirements</h2>
        <div class="requirements-grid">
            <div class="req-section">
                <h3>Server Requirements</h3>
                <ul>
                    <li>PHP 8.2 or higher</li>
                    <li>Laravel 12.x</li>
                    <li>SQLite/MySQL/PostgreSQL</li>
                    <li>Node.js 18+ (for asset compilation)</li>
                </ul>
            </div>
            <div class="req-section">
                <h3>Browser Support</h3>
                <ul>
                    <li>Chrome 90+</li>
                    <li>Firefox 88+</li>
                    <li>Safari 14+</li>
                    <li>Edge 90+</li>
                </ul>
            </div>
            <div class="req-section">
                <h3>Features</h3>
                <ul>
                    <li>FHIR R4 compliance</li>
                    <li>DICOM support</li>
                    <li>MedGemma AI integration</li>
                    <li>Role-based access control</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>Troubleshooting</h2>
        <div class="troubleshooting-section">
            <div class="faq-item">
                <h4>Q: Pages showing 404 errors?</h4>
                <p><strong>A:</strong> Make sure the Laravel server is running with <code>php artisan serve</code> and you're accessing the correct URLs.</p>
            </div>
            <div class="faq-item">
                <h4>Q: MedGemma AI not working?</h4>
                <p><strong>A:</strong> Check your environment configuration. Set <code>MEDGEMMA_ENABLED=true</code> and provide API credentials in your <code>.env</code> file.</p>
            </div>
            <div class="faq-item">
                <h4>Q: No patients showing up?</h4>
                <p><strong>A:</strong> Run <code>php artisan migrate --seed</code> to create demo data, or ensure your database is properly configured.</p>
            </div>
            <div class="faq-item">
                <h4>Q: DICOM upload failing?</h4>
                <p><strong>A:</strong> Check file permissions on the storage directory and ensure your web server can handle large file uploads.</p>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>Support & Contact</h2>
        <div class="support-section">
            <div class="support-grid">
                <div class="support-item">
                    <h4>📚 Documentation</h4>
                    <p>Complete documentation available in the project README</p>
                </div>
                <div class="support-item">
                    <h4>🐛 Issues</h4>
                    <p>Report bugs and feature requests on GitHub</p>
                </div>
                <div class="support-item">
                    <h4>💬 Community</h4>
                    <p>Join discussions and get help from the community</p>
                </div>
                <div class="support-item">
                    <h4>🔧 Technical Support</h4>
                    <p>Professional support available for enterprise users</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabId) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabId).classList.add('active');
    
    // Add active class to clicked button
    event.target.classList.add('active');
}
</script>
</body>
</html>
