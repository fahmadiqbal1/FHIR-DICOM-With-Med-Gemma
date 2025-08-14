<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reports - Healthcare AI Platform</title>
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
            margin-bottom: 2rem;
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
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .card-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1rem;
        }
        
        .stat-item {
            text-align: center;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: white;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .filters-panel {
            color: white;
        }
        
        .row {
            display: flex;
            gap: 1rem;
            align-items: end;
            flex-wrap: wrap;
        }
        
        .col {
            flex: 1;
            min-width: 150px;
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
        
        .form-control, .form-select {
            width: 100%;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            color: white;
            font-size: 0.95rem;
            backdrop-filter: blur(5px);
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.6);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
        }
        
        .form-select option {
            background: #4a5568;
            color: white;
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
        
        .btn.outline {
            background: transparent;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        
        .btn.outline:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
        
        .btn.small {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }
        
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }
        
        .full-width {
            width: 100%;
        }
        
        .chart-container {
            color: white;
        }
        
        .demographics-grid {
            color: white;
        }
        
        .demographics-section {
            margin-bottom: 2rem;
        }
        
        .demographics-section h4 {
            color: white;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }
        
        .demo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.5rem;
        }
        
        .demo-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 5px;
        }
        
        .demo-label {
            color: rgba(255, 255, 255, 0.9);
        }
        
        .demo-value {
            color: white;
            font-weight: 500;
        }
        
        .activity-panel {
            color: white;
        }
        
        .activity-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }
        
        .activity-icon {
            font-size: 1.5rem;
            width: 3rem;
            height: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-description {
            color: white;
            margin-bottom: 0.25rem;
        }
        
        .activity-meta {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
        }
        
        .activity-date {
            color: rgba(255, 255, 255, 0.7);
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
        
        .reports-panel {
            color: white;
        }
        
        .generated-report {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .report-header {
            margin-bottom: 1rem;
        }
        
        .report-header h4 {
            color: white;
            margin-bottom: 0.5rem;
        }
        
        .report-meta {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.7);
        }
        
        .report-actions {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .report-table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .report-table th, .report-table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .report-table th {
            background: rgba(255, 255, 255, 0.1);
            font-weight: 600;
            color: white;
        }
        
        .report-table td {
            color: rgba(255, 255, 255, 0.9);
        }
        
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: rgba(255, 255, 255, 0.6);
        }
        
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 1rem;
            }
            
            .nav {
                flex-wrap: wrap;
                justify-content: center;
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
            
            .demo-grid {
                grid-template-columns: 1fr;
            }
            
            .report-meta {
                flex-direction: column;
                gap: 0.25rem;
            }
            
            .container {
                padding: 0 0.5rem;
            }
            
            .card {
                padding: 1rem;
                margin-bottom: 1rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.5rem;
            }
            
            .user-info {
                flex-direction: column;
                gap: 0.5rem;
                padding: 0.5rem;
            }
            
            .nav a {
                padding: 0.4rem 0.6rem;
                font-size: 0.8rem;
            }
        }
        
        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .row {
                gap: 0.5rem;
            }
            
            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.8rem;
            }
            
            .card h2 {
                font-size: 1.2rem;
            }
            
            .page-header h1 {
                font-size: 1.5rem;
            }
            
            .nav {
                overflow-x: auto;
                justify-content: flex-start;
                padding-bottom: 0.5rem;
            }
            
            .nav::-webkit-scrollbar {
                height: 4px;
            }
            
            .nav::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.1);
            }
            
            .nav::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.3);
                border-radius: 2px;
            }
        }
    </style>
</head>
<body>
    @include('partials.header')

    <main class="container">
        <div class="page-header">
            <h1>📊 Clinical Reports & Analytics</h1>
            <p class="muted">Comprehensive reporting and data analytics for healthcare insights</p>
        </div>

        <div class="grid">
            <div class="card">
                <h2>📈 Quick Stats</h2>
                <div id="quickStats" class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number" id="totalPatients">-</div>
                        <div class="stat-label">Total Patients</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" id="totalImaging">-</div>
                        <div class="stat-label">Imaging Studies</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" id="totalLabs">-</div>
                        <div class="stat-label">Lab Orders</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" id="totalPrescriptions">-</div>
                        <div class="stat-label">Prescriptions</div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <h2>🔍 Report Filters</h2>
                <div class="filters-panel">
                    <div class="row">
                        <div class="col">
                            <label for="dateFrom" class="form-label">From Date:</label>
                            <input type="date" id="dateFrom" class="form-control">
                        </div>
                        <div class="col">
                            <label for="dateTo" class="form-label">To Date:</label>
                            <input type="date" id="dateTo" class="form-control">
                        </div>
                        <div class="col">
                            <label for="reportType" class="form-label">Report Type:</label>
                            <select id="reportType" class="form-select">
                                <option value="patients">Patient Summary</option>
                                <option value="imaging">Imaging Studies</option>
                                <option value="labs">Laboratory Results</option>
                                <option value="prescriptions">Prescriptions</option>
                                <option value="ai-analysis">AI Analysis Results</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <label class="form-label">&nbsp;</label>
                            <button id="generateReport" class="btn primary full-width">📋 Generate Report</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>👥 Patient Demographics</h2>
                <div class="card-actions">
                    <button class="btn outline small" onclick="exportReport('demographics')">📤 Export</button>
                </div>
            </div>
            <div id="demographicsChart" class="chart-container">
                <div class="demographics-grid" id="demographicsData">
                    Loading demographics data...
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>🕒 Recent Activity</h2>
                <div class="card-actions">
                    <button class="btn outline small" onclick="refreshActivity()">🔄 Refresh</button>
                </div>
            </div>
            <div id="recentActivity" class="activity-panel">
                Loading recent activity...
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>📄 Generated Reports</h2>
                <div class="card-actions">
                    <button class="btn outline small" onclick="clearReports()">🗑️ Clear All</button>
                </div>
            </div>
            <div id="generatedReports" class="reports-panel">
                <div class="empty-state">
                    <p>No reports generated yet</p>
                    <small>Use the filters above to generate reports</small>
                </div>
            </div>
        </div>
    </main>

    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let patientsData = [];

        function tag(text, cls='') { return `<span class="tag ${cls}">${text}</span>`; }
        function htmlesc(str) { return (str||'').toString().replace(/[&<>\"]/g, s=>({"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;"}[s])); }

        async function loadQuickStats() {
            try {
                const r = await fetch('/api/patients', {headers: {'Accept':'application/json'}});
                const d = await r.json();
                patientsData = d || [];
                
                let totalImaging = 0;
                let totalLabs = 0;
                let totalPrescriptions = 0;
                
                patientsData.forEach(p => {
                    totalImaging += p.counts?.imaging_studies || 0;
                    totalLabs += p.counts?.lab_orders || 0;
                    totalPrescriptions += p.counts?.prescriptions || 0;
                });
                
                document.getElementById('totalPatients').textContent = patientsData.length;
                document.getElementById('totalImaging').textContent = totalImaging;
                document.getElementById('totalLabs').textContent = totalLabs;
                document.getElementById('totalPrescriptions').textContent = totalPrescriptions;
                
                updateDemographics();
                updateRecentActivity();
                
            } catch (e) {
                console.error('Failed to load stats:', e);
            }
        }

        function updateDemographics() {
            const demographics = {
                male: 0,
                female: 0,
                other: 0,
                unknown: 0
            };
            
            const ageGroups = {
                '0-18': 0,
                '19-35': 0,
                '36-50': 0,
                '51-65': 0,
                '65+': 0,
                'unknown': 0
            };
            
            patientsData.forEach(p => {
                // Gender distribution
                const gender = (p.sex || 'unknown').toLowerCase();
                if (demographics.hasOwnProperty(gender)) {
                    demographics[gender]++;
                } else {
                    demographics.unknown++;
                }
                
                // Age distribution
                if (p.dob) {
                    const age = calculateAge(p.dob);
                    if (age >= 0 && age <= 18) ageGroups['0-18']++;
                    else if (age >= 19 && age <= 35) ageGroups['19-35']++;
                    else if (age >= 36 && age <= 50) ageGroups['36-50']++;
                    else if (age >= 51 && age <= 65) ageGroups['51-65']++;
                    else if (age > 65) ageGroups['65+']++;
                    else ageGroups.unknown++;
                } else {
                    ageGroups.unknown++;
                }
            });
            
            const demographicsHtml = `
                <div class="demographics-section">
                    <h4>Gender Distribution</h4>
                    <div class="demo-grid">
                        <div class="demo-item">
                            <span class="demo-label">Male:</span>
                            <span class="demo-value">${demographics.male} (${getPercentage(demographics.male, patientsData.length)}%)</span>
                        </div>
                        <div class="demo-item">
                            <span class="demo-label">Female:</span>
                            <span class="demo-value">${demographics.female} (${getPercentage(demographics.female, patientsData.length)}%)</span>
                        </div>
                        <div class="demo-item">
                            <span class="demo-label">Other:</span>
                            <span class="demo-value">${demographics.other} (${getPercentage(demographics.other, patientsData.length)}%)</span>
                        </div>
                        <div class="demo-item">
                            <span class="demo-label">Unknown:</span>
                            <span class="demo-value">${demographics.unknown} (${getPercentage(demographics.unknown, patientsData.length)}%)</span>
                        </div>
                    </div>
                </div>
                
                <div class="demographics-section">
                    <h4>Age Distribution</h4>
                    <div class="demo-grid">
                        <div class="demo-item">
                            <span class="demo-label">0-18:</span>
                            <span class="demo-value">${ageGroups['0-18']} (${getPercentage(ageGroups['0-18'], patientsData.length)}%)</span>
                        </div>
                        <div class="demo-item">
                            <span class="demo-label">19-35:</span>
                            <span class="demo-value">${ageGroups['19-35']} (${getPercentage(ageGroups['19-35'], patientsData.length)}%)</span>
                        </div>
                        <div class="demo-item">
                            <span class="demo-label">36-50:</span>
                            <span class="demo-value">${ageGroups['36-50']} (${getPercentage(ageGroups['36-50'], patientsData.length)}%)</span>
                        </div>
                        <div class="demo-item">
                            <span class="demo-label">51-65:</span>
                            <span class="demo-value">${ageGroups['51-65']} (${getPercentage(ageGroups['51-65'], patientsData.length)}%)</span>
                        </div>
                        <div class="demo-item">
                            <span class="demo-label">65+:</span>
                            <span class="demo-value">${ageGroups['65+']} (${getPercentage(ageGroups['65+'], patientsData.length)}%)</span>
                        </div>
                        <div class="demo-item">
                            <span class="demo-label">Unknown:</span>
                            <span class="demo-value">${ageGroups.unknown} (${getPercentage(ageGroups.unknown, patientsData.length)}%)</span>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('demographicsData').innerHTML = demographicsHtml;
        }

        function updateRecentActivity() {
            const activities = [];
            
            patientsData.forEach(p => {
                const name = p.name || `${p.first_name || ''} ${p.last_name || ''}`.trim();
                
                if (p.imaging_studies) {
                    p.imaging_studies.forEach(study => {
                        activities.push({
                            type: 'imaging',
                            patient: name,
                            description: `${study.modality || 'Unknown'} imaging study`,
                            date: study.started_at || study.created_at,
                            hasAI: study.ai_results && study.ai_results.length > 0
                        });
                    });
                }
                
                if (p.lab_orders) {
                    p.lab_orders.forEach(lab => {
                        activities.push({
                            type: 'lab',
                            patient: name,
                            description: `Lab: ${lab.name || lab.code || 'Unknown test'}`,
                            date: lab.created_at,
                            status: lab.status
                        });
                    });
                }
            });
            
            // Sort by date (most recent first)
            activities.sort((a, b) => new Date(b.date || 0) - new Date(a.date || 0));
            
            const recentActivities = activities.slice(0, 10);
            
            if (recentActivities.length === 0) {
                document.getElementById('recentActivity').innerHTML = '<div class="empty-state"><p>No recent activity</p></div>';
                return;
            }
            
            const activityHtml = recentActivities.map(activity => `
                <div class="activity-item">
                    <div class="activity-icon activity-${activity.type}">
                        ${activity.type === 'imaging' ? '🏥' : '🔬'}
                    </div>
                    <div class="activity-content">
                        <div class="activity-description">
                            <strong>${htmlesc(activity.patient)}</strong> - ${htmlesc(activity.description)}
                        </div>
                        <div class="activity-meta">
                            <span class="activity-date">${formatDate(activity.date)}</span>
                            ${activity.hasAI ? tag('AI Analyzed', 'success') : ''}
                            ${activity.status ? tag(activity.status, activity.status === 'completed' ? 'success' : 'warning') : ''}
                        </div>
                    </div>
                </div>
            `).join('');
            
            document.getElementById('recentActivity').innerHTML = activityHtml;
        }

        function calculateAge(dob) {
            if (!dob) return null;
            const today = new Date();
            const birthDate = new Date(dob);
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            return age;
        }

        function getPercentage(value, total) {
            if (total === 0) return 0;
            return Math.round((value / total) * 100);
        }

        function formatDate(dateString) {
            if (!dateString) return 'Unknown date';
            try {
                return new Date(dateString).toLocaleDateString();
            } catch (e) {
                return 'Invalid date';
            }
        }

        async function generateReport() {
            const reportType = document.getElementById('reportType').value;
            const dateFrom = document.getElementById('dateFrom').value;
            const dateTo = document.getElementById('dateTo').value;
            
            const button = document.getElementById('generateReport');
            const originalText = button.textContent;
            button.textContent = '⏳ Generating...';
            button.disabled = true;
            
            try {
                const report = await createReport(reportType, dateFrom, dateTo);
                displayGeneratedReport(report);
            } catch (e) {
                alert('Failed to generate report: ' + e.message);
            } finally {
                button.textContent = originalText;
                button.disabled = false;
            }
        }

        async function createReport(type, dateFrom, dateTo) {
            let filteredData = patientsData;
            
            // Apply date filters if provided
            if (dateFrom || dateTo) {
                filteredData = patientsData.filter(p => {
                    const createdDate = new Date(p.created_at || Date.now());
                    const fromDate = dateFrom ? new Date(dateFrom) : new Date('1900-01-01');
                    const toDate = dateTo ? new Date(dateTo) : new Date();
                    return createdDate >= fromDate && createdDate <= toDate;
                });
            }
            
            const timestamp = new Date().toLocaleString();
            const dateRange = (dateFrom && dateTo) ? `${dateFrom} to ${dateTo}` : 'All dates';
            
            let reportData;
            let title;
            
            switch (type) {
                case 'patients':
                    title = 'Patient Summary Report';
                    reportData = generatePatientSummaryReport(filteredData);
                    break;
                case 'imaging':
                    title = 'Imaging Studies Report';
                    reportData = generateImagingReport(filteredData);
                    break;
                case 'labs':
                    title = 'Laboratory Results Report';
                    reportData = generateLabsReport(filteredData);
                    break;
                case 'prescriptions':
                    title = 'Prescriptions Report';
                    reportData = generatePrescriptionsReport(filteredData);
                    break;
                case 'ai-analysis':
                    title = 'AI Analysis Results Report';
                    reportData = generateAIAnalysisReport(filteredData);
                    break;
                default:
                    throw new Error('Invalid report type');
            }
            
            return {
                title,
                type,
                dateRange,
                timestamp,
                data: reportData,
                recordCount: filteredData.length
            };
        }

        function generatePatientSummaryReport(data) {
            return data.map(p => ({
                mrn: p.mrn || 'N/A',
                name: p.name || `${p.first_name || ''} ${p.last_name || ''}`.trim(),
                dob: p.dob || 'N/A',
                sex: p.sex || 'Unknown',
                imagingStudies: p.counts?.imaging_studies || 0,
                labOrders: p.counts?.lab_orders || 0,
                prescriptions: p.counts?.prescriptions || 0
            }));
        }

        function generateImagingReport(data) {
            const imaging = [];
            data.forEach(p => {
                if (p.imaging_studies) {
                    p.imaging_studies.forEach(study => {
                        imaging.push({
                            patient: p.name || `${p.first_name || ''} ${p.last_name || ''}`.trim(),
                            mrn: p.mrn || 'N/A',
                            modality: study.modality || 'Unknown',
                            description: study.description || 'No description',
                            date: study.started_at || 'Unknown',
                            aiAnalyzed: study.ai_results && study.ai_results.length > 0
                        });
                    });
                }
            });
            return imaging;
        }

        function generateLabsReport(data) {
            const labs = [];
            data.forEach(p => {
                if (p.lab_orders) {
                    p.lab_orders.forEach(lab => {
                        labs.push({
                            patient: p.name || `${p.first_name || ''} ${p.last_name || ''}`.trim(),
                            mrn: p.mrn || 'N/A',
                            test: lab.name || lab.code || 'Unknown',
                            status: lab.status || 'Unknown',
                            result: lab.result_value || 'Pending',
                            flag: lab.result_flag || 'Normal'
                        });
                    });
                }
            });
            return labs;
        }

        function generatePrescriptionsReport(data) {
            const prescriptions = [];
            data.forEach(p => {
                if (p.prescriptions) {
                    p.prescriptions.forEach(rx => {
                        prescriptions.push({
                            patient: p.name || `${p.first_name || ''} ${p.last_name || ''}`.trim(),
                            mrn: p.mrn || 'N/A',
                            medication: rx.medication || 'Unknown',
                            strength: rx.strength || 'N/A',
                            dosage: rx.dosage || 'N/A',
                            frequency: rx.frequency || 'N/A',
                            status: rx.status || 'Unknown'
                        });
                    });
                }
            });
            return prescriptions;
        }

        function generateAIAnalysisReport(data) {
            const aiResults = [];
            data.forEach(p => {
                if (p.imaging_studies) {
                    p.imaging_studies.forEach(study => {
                        if (study.ai_results) {
                            study.ai_results.forEach(result => {
                                aiResults.push({
                                    patient: p.name || `${p.first_name || ''} ${p.last_name || ''}`.trim(),
                                    mrn: p.mrn || 'N/A',
                                    type: 'Imaging',
                                    model: result.model || 'Unknown',
                                    confidence: result.confidence_score || 'N/A',
                                    date: result.created_at || 'Unknown'
                                });
                            });
                        }
                    });
                }
            });
            return aiResults;
        }

        function displayGeneratedReport(report) {
            const reportsPanel = document.getElementById('generatedReports');
            
            const reportHtml = `
                <div class="generated-report">
                    <div class="report-header">
                        <h4>${htmlesc(report.title)}</h4>
                        <div class="report-meta">
                            <span class="report-timestamp">${report.timestamp}</span>
                            <span class="report-range">${report.dateRange}</span>
                            <span class="report-count">${report.recordCount} records</span>
                        </div>
                    </div>
                    <div class="report-actions">
                        <button class="btn small outline" onclick="exportReport('${report.type}')">📤 Export CSV</button>
                        <button class="btn small outline" onclick="printReport()">🖨️ Print</button>
                    </div>
                    <div class="report-preview">
                        ${formatReportData(report)}
                    </div>
                </div>
            `;
            
            if (reportsPanel.innerHTML.includes('empty-state')) {
                reportsPanel.innerHTML = reportHtml;
            } else {
                reportsPanel.insertAdjacentHTML('afterbegin', reportHtml);
            }
        }

        function formatReportData(report) {
            if (!report.data || report.data.length === 0) {
                return '<p class="muted">No data available for this report</p>';
            }
            
            const headers = Object.keys(report.data[0]);
            const rows = report.data.slice(0, 5); // Show first 5 rows
            
            let html = '<table class="report-table"><thead><tr>';
            headers.forEach(header => {
                html += `<th>${htmlesc(header.replace(/([A-Z])/g, ' $1').replace(/^./, str => str.toUpperCase()))}</th>`;
            });
            html += '</tr></thead><tbody>';
            
            rows.forEach(row => {
                html += '<tr>';
                headers.forEach(header => {
                    html += `<td>${htmlesc(String(row[header] || ''))}</td>`;
                });
                html += '</tr>';
            });
            
            html += '</tbody></table>';
            
            if (report.data.length > 5) {
                html += `<p style="color: rgba(255, 255, 255, 0.6); margin-top: 1rem;">Showing 5 of ${report.data.length} records</p>`;
            }
            
            return html;
        }

        function exportReport(type) {
            alert(`📊 Exporting ${type} report as CSV...`);
        }

        function printReport() {
            alert('🖨️ Opening print dialog...');
        }

        function refreshActivity() {
            updateRecentActivity();
        }

        function clearReports() {
            document.getElementById('generatedReports').innerHTML = `
                <div class="empty-state">
                    <p>No reports generated yet</p>
                    <small>Use the filters above to generate reports</small>
                </div>
            `;
        }

        // Event listeners
        document.getElementById('generateReport').addEventListener('click', generateReport);

        // Set default dates (last 30 days)
        const today = new Date();
        const thirtyDaysAgo = new Date(today.getTime() - (30 * 24 * 60 * 60 * 1000));
        document.getElementById('dateTo').value = today.toISOString().split('T')[0];
        document.getElementById('dateFrom').value = thirtyDaysAgo.toISOString().split('T')[0];

        // Initialize
        loadQuickStats();
    </script>
</body>
</html>
