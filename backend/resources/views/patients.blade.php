<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MedGemma Healthcare Platform') }} • Patient Management</title>
    @include('partials.global-styles')
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
        
        .app-header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        /* Fix dropdown z-index issues for any potential dropdowns */
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
            font-size: 0.9rem;
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
        
        .btn.outline, .btn.secondary {
            background: transparent;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn.outline:hover, .btn.secondary:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .btn.small {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .btn.danger {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        .btn.danger:hover {
            background: rgba(239, 68, 68, 0.3);
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

        /* Quick Actions Section */
        .quick-actions-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .quick-actions-section h2 {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .quick-action-card {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .quick-action-card:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .action-icon {
            font-size: 2rem;
            min-width: 50px;
            text-align: center;
        }

        .action-content h3 {
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .action-content p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            margin: 0;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-name {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
        }
        
        .grid {
            display: grid;
            gap: 2rem;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            margin-bottom: 2rem;
            align-items: start;
        }
        
        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 2rem;
            transition: transform 0.2s, box-shadow 0.2s;
            min-height: 700px;
            display: flex;
            flex-direction: column;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .card-body {
            flex: 1;
            overflow-y: auto;
        }
        
        .card h2 {
            margin-bottom: 1.5rem;
            color: #fff;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .card-header h2 {
            margin-bottom: 0;
        }
        
        .row {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .input {
            padding: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 0.9rem;
        }
        
        .input:focus {
            outline: none;
            border-color: #4ecdc4;
            box-shadow: 0 0 0 3px rgba(78, 205, 196, 0.1);
        }
        
        .input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .input option {
            background: #2d3748;
            color: #fff;
        }
        
        .patients-container {
            height: 600px;
            overflow-y: auto;
            margin-top: 1rem;
        }
        
        .loading, .empty-state, .error-state {
            text-align: center;
            padding: 3rem 2rem;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .error-state {
            color: #ef4444;
        }
        
        .patient-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            margin-bottom: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .patient-item:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(4px);
        }
        
        .patient-item.selected {
            background: rgba(78, 205, 196, 0.2);
            border-color: #4ecdc4;
        }
        
        .patient-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4ecdc4, #44a08d);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .patient-info {
            flex: 1;
        }
        
        .patient-name {
            font-weight: 600;
            color: #fff;
            margin-bottom: 0.25rem;
        }
        
        .patient-meta {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.8rem;
            display: flex;
            gap: 1rem;
        }
        
        .patient-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .tag {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        
        .tag.male {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }
        
        .tag.female {
            background: rgba(236, 72, 153, 0.2);
            color: #ec4899;
            border: 1px solid rgba(236, 72, 153, 0.3);
        }
        
        .tag.other, .tag.unknown {
            background: rgba(168, 85, 247, 0.2);
            color: #a855f7;
            border: 1px solid rgba(168, 85, 247, 0.3);
        }
        
        .tag.success {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }
        
        .tag.warning {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }
        
        .tag.error {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
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
            color: #fff;
            font-size: 1.25rem;
            font-weight: 600;
        }
        
        .btn-close {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.6);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 4px;
            transition: all 0.2s;
        }
        
        .btn-close:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #fff;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 0.9rem;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #4ecdc4;
            box-shadow: 0 0 0 3px rgba(78, 205, 196, 0.1);
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .form-control option {
            background: #2d3748;
            color: #fff;
        }

        /* Drag and Drop Styles */
        .drag-drop-container {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .drag-drop-box {
            min-height: 300px;
            max-height: 400px;
            overflow-y: auto;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            padding: 15px;
            background: #f9fafb;
            transition: all 0.3s ease;
        }

        .drag-drop-box.drag-over {
            border-color: #4f46e5;
            background: #f0f7ff;
        }

        .available-box {
            border-color: #10b981;
            background: #f0fdf4;
        }

        .selected-box {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .test-item {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 8px;
            cursor: grab;
            transition: all 0.2s ease;
            user-select: none;
        }

        .test-item:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-1px);
        }

        .test-item.dragging {
            opacity: 0.5;
            cursor: grabbing;
        }

        .test-item.selected {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .test-item.selected .remove-btn {
            opacity: 1;
        }

        .test-name {
            font-weight: 600;
            color: #374151;
            margin-bottom: 4px;
        }

        .test-code {
            font-size: 0.8em;
            color: #6b7280;
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 3px;
            margin-right: 8px;
        }

        .test-cost {
            float: right;
            font-weight: 600;
            color: #059669;
        }

        .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .remove-btn:hover {
            background: #dc2626;
        }

        .selected-tests-container .test-item {
            position: relative;
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .empty-message {
            text-align: center;
            padding: 30px;
            color: #9ca3af;
        }

        .loading-indicator {
            text-align: center;
            padding: 20px;
            color: #6b7280;
        }

        .order-summary {
            font-size: 0.9em;
            color: #6b7280;
            padding: 8px 12px;
            background: #f3f4f6;
            border-radius: 4px;
            text-align: center;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .btn-group .btn.active {
            background: #4f46e5;
            color: white;
            border-color: #4f46e5;
        }

        .order-interface {
            display: none;
        }

        .order-interface.active {
            display: block;
        }
        
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            backdrop-filter: blur(5px);
        }
        
        .alert-success {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }
        
        .alert-error {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        
        .alert-warning {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }
        
        .patient-details {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1rem;
            max-width: 100%;
            overflow: hidden;
        }
        
        .detail-section {
            margin-bottom: 2rem;
            max-width: 100%;
            overflow: hidden;
        }
        
        .detail-section h4, .detail-section h3 {
            color: #fff;
            margin-bottom: 1rem;
            font-size: 1.1rem;
            font-weight: 600;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .detail-grid {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
        
        .detail-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 1rem;
            border-radius: 6px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        .detail-label {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 0.25rem;
        }
        
        .detail-value {
            color: #fff;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        /* Table styles with proper overflow handling */
        .table-container {
            max-width: 100%;
            overflow-x: auto;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            margin: 1rem 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }
        
        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            word-wrap: break-word;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        th {
            background: rgba(255, 255, 255, 0.1);
            font-weight: 600;
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        
        tr:hover td {
            color: #fff !important;
            background: transparent;
        }
        
        /* Clinical notes specific styling */
        .clinical-notes {
            max-width: 100%;
            overflow: hidden;
        }
        
        .clinical-notes .card {
            margin: 0.5rem 0;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-width: 100%;
            overflow: hidden;
        }
        
        .clinical-notes pre {
            white-space: pre-wrap;
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-width: 100%;
            overflow: hidden;
            color: #fff;
            background: rgba(0, 0, 0, 0.2);
            padding: 0.5rem;
            border-radius: 4px;
            margin: 0.5rem 0;
        }
        
        /* AI Results and Imaging Studies */
        .ai-results, .imaging-studies {
            max-width: 100%;
            overflow: hidden;
        }
        
        .ai-results pre, .imaging-studies pre {
            white-space: pre-wrap;
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-width: 100%;
            overflow: hidden;
            color: #fff;
            background: rgba(0, 0, 0, 0.2);
            padding: 1rem;
            border-radius: 6px;
            margin: 0.5rem 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
            font-size: 0.9rem;
            line-height: 1.5;
        }
        
        /* Fix hover effects that make text disappear */
        .study-item:hover, .lab-item:hover, .rx-item:hover, .note-item:hover {
            background: rgba(255, 255, 255, 0.08);
        }
        
        .study-item:hover *, .lab-item:hover *, .rx-item:hover *, .note-item:hover * {
            color: #fff !important;
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
            
            .row {
                flex-direction: column;
                align-items: stretch;
            }
            
            .patient-item {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .patient-actions {
                justify-content: center;
            }
            
            .card-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <!-- Professional Healthcare Header -->
    <div class="app-header">
        <div class="inner">
            <div class="logo">
                <div class="mark">🩺</div>
                <span>Doctor Dashboard - MedGemma</span>
            </div>
            <div class="user-info">
                <span class="user-name">{{ auth()->user()->name ?? 'Doctor' }}</span>
                <form method="POST" action="/logout" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn outline small">
                        🚪 Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Quick Actions Section -->
        <div class="quick-actions-section">
            <h2>🚀 Quick Actions</h2>
            <div class="quick-actions-grid">
                <div class="quick-action-card" onclick="showCreatePatientModal()">
                    <div class="action-icon">👤➕</div>
                    <div class="action-content">
                        <h3>Add Patient</h3>
                        <p>Register new patient</p>
                    </div>
                </div>
                <div class="quick-action-card" onclick="showInvoiceModal()">
                    <div class="action-icon">💰</div>
                    <div class="action-content">
                        <h3>Generate Invoice</h3>
                        <p>Create billing invoice</p>
                    </div>
                </div>
                <div class="quick-action-card" onclick="loadPatients()">
                    <div class="action-icon">🔄</div>
                    <div class="action-content">
                        <h3>Refresh Data</h3>
                        <p>Update patient list</p>
                    </div>
                </div>
                <div class="quick-action-card" onclick="window.location.href='/doctor/ai-analysis'">
                    <div class="action-icon">🤖</div>
                    <div class="action-content">
                        <h3>AI Analysis</h3>
                        <p>Medical AI assistant</p>
                    </div>
                </div>
                <div class="quick-action-card" onclick="window.location.href='/financial/doctor-dashboard'">
                    <div class="action-icon">📊</div>
                    <div class="action-content">
                        <h3>Financial Dashboard</h3>
                        <p>Revenue & Analytics</p>
                    </div>
                </div>
                <div class="quick-action-card" onclick="window.location.href='/dicom-upload'">
                    <div class="action-icon">📁</div>
                    <div class="action-content">
                        <h3>DICOM Upload</h3>
                        <p>Medical imaging</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-header" style="margin-bottom: 20px;">
            <h1>Patient Management</h1>
            <p class="muted">Comprehensive patient records and clinical data management</p>
        </div>

    <!-- Alert container -->
    <div id="alert-container" style="margin-bottom: 20px;"></div>

    <div class="grid" style="margin-bottom: 20px;">
        <div class="card">
            <div class="row" style="align-items: center; margin-bottom: 16px;">
                <h2 style="margin: 0;">Patient Directory</h2>
                <div style="margin-left: auto;">
                    <button class="btn primary" onclick="showCreatePatientModal()">Add New Patient</button>
                    <button class="btn ghost" onclick="showInvoiceModal()">Generate Invoice</button>
                    <button class="btn ghost" onclick="loadPatients()">Refresh</button>
                </div>
            </div>
            
            <div class="row" style="margin-bottom: 16px;">
                <input id="patientSearch" type="search" placeholder="Search by name, CNIC, or email" class="input" style="flex:1; margin-right: 8px;">
                <select id="sexFilter" class="input" style="width: 120px;">
                    <option value="">All Genders</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                    <option value="unknown">Unknown</option>
                </select>
            </div>

            <div id="patients-list" class="patients-container">
                <div class="loading">Loading patients...</div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h2 id="patientDetailsTitle">Patient Details</h2>
                <div id="patientDetailsMeta" class="muted">Select a patient to view comprehensive details.</div>
                <div id="patientDetailsActions" class="row" style="margin:10px 0; display:none">
                    <button class="btn outline" onclick="editPatient(currentPatientId)">Edit Patient</button>
                    <button class="btn primary" onclick="openNewOrderModal()">New Order</button>
                    <button class="btn secondary" onclick="analyzeLabs(currentPatientId)">Analyze Labs</button>
                    <button class="btn ghost" onclick="secondOpinion(currentPatientId)">Combined Second Opinion</button>
                </div>
            </div>
            <div class="card-body">
                <div id="patientBasicInfo" style="margin-top:16px"></div>
                <div id="patientImaging" style="margin-top:16px"></div>
                <div id="patientLabs" style="margin-top:16px"></div>
                <div id="patientRx" style="margin-top:16px"></div>
                <div id="patientNotes" style="margin-top:16px"></div>
            </div>
        </div>
    </div>
</div>

<!-- Medical Data Modals -->
<!-- Modal for Imaging Studies -->
<div id="imagingModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Imaging Studies</h3>
            <button class="modal-close" onclick="closeModal('imagingModal')">&times;</button>
        </div>
        <div class="modal-body" id="imagingModalBody">
            Loading...
        </div>
    </div>
</div>

<!-- Modal for Lab Results -->
<div id="labModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Laboratory Results</h3>
            <button class="modal-close" onclick="closeModal('labModal')">&times;</button>
        </div>
        <div class="modal-body" id="labModalBody">
            Loading...
        </div>
    </div>
</div>

<!-- Modal for Prescriptions -->
<div id="rxModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Prescriptions</h3>
            <button class="modal-close" onclick="closeModal('rxModal')">&times;</button>
        </div>
        <div class="modal-body" id="rxModalBody">
            Loading...
        </div>
    </div>
</div>

<!-- New Order Modal -->
<div id="newOrderModal" class="modal-overlay">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h3 class="modal-title">New Order</h3>
            <button class="modal-close" onclick="closeModal('newOrderModal')">&times;</button>
        </div>
        <div class="modal-body">
            <!-- Order Type Selection -->
            <div class="order-type-selector" style="margin-bottom: 20px;">
                <h4>Select Order Type:</h4>
                <div class="btn-group" style="display: flex; gap: 10px; margin-top: 10px;">
                    <button class="btn primary" id="labOrderBtn" onclick="showLabOrder()">Lab Order</button>
                    <button class="btn outline" id="imagingOrderBtn" onclick="showImagingOrder()">Imaging Order</button>
                    <button class="btn outline" id="prescriptionOrderBtn" onclick="showPrescriptionOrder()">Prescription</button>
                </div>
            </div>

            <!-- Lab Order Interface -->
            <div id="labOrderInterface" class="order-interface">
                <div class="drag-drop-container" style="display: flex; gap: 20px; margin-top: 20px;">
                    <!-- Available Tests -->
                    <div class="available-tests-container" style="flex: 1;">
                        <h5 style="margin-bottom: 10px;">Available Lab Tests</h5>
                        <div class="search-box" style="margin-bottom: 10px;">
                            <input type="text" id="testSearchInput" class="form-control" placeholder="Search tests..." onkeyup="filterAvailableTests()">
                        </div>
                        <div id="availableTests" class="drag-drop-box available-box">
                            <div class="loading-indicator" style="text-align: center; padding: 20px; color: #888;">
                                <i class="fas fa-spinner fa-spin"></i> Loading available tests...
                            </div>
                        </div>
                    </div>

                    <!-- Selected Tests -->
                    <div class="selected-tests-container" style="flex: 1;">
                        <h5 style="margin-bottom: 10px;">Selected Tests for Patient</h5>
                        <div class="order-summary" style="margin-bottom: 10px; font-size: 0.9em; color: #666;">
                            <span id="selectedCount">0</span> tests selected • Total: $<span id="totalCost">0.00</span>
                        </div>
                        <div id="selectedTests" class="drag-drop-box selected-box">
                            <div class="empty-message" style="text-align: center; padding: 30px; color: #888;">
                                <i class="fas fa-arrow-left" style="font-size: 24px; margin-bottom: 10px;"></i>
                                <p>Drag lab tests here</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Notes -->
                <div style="margin-top: 20px;">
                    <label for="orderNotes" style="display: block; margin-bottom: 5px; font-weight: bold;">Order Notes (Optional):</label>
                    <textarea id="orderNotes" class="form-control" rows="3" placeholder="Enter any special instructions or notes for this lab order..."></textarea>
                </div>

                <!-- Priority Selection -->
                <div style="margin-top: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Priority:</label>
                    <select id="orderPriority" class="form-control" style="max-width: 200px;">
                        <option value="routine">Routine</option>
                        <option value="urgent">Urgent</option>
                        <option value="stat">STAT</option>
                    </select>
                </div>
            </div>

            <!-- Placeholder for other order types -->
            <div id="imagingOrderInterface" class="order-interface" style="display: none;">
                <p>Imaging order interface will be implemented here.</p>
            </div>

            <div id="prescriptionOrderInterface" class="order-interface" style="display: none;">
                <p>Prescription order interface will be implemented here.</p>
            </div>
        </div>
        <div class="modal-footer" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-top: 1px solid #ddd; margin-top: 20px;">
            <div class="order-total" style="font-weight: bold; color: #333;">
                Total: $<span id="footerTotalCost">0.00</span>
            </div>
            <div>
                <button class="btn ghost" onclick="closeModal('newOrderModal')">Cancel</button>
                <button class="btn primary" onclick="submitOrder()" id="submitOrderBtn" disabled>Submit Order</button>
            </div>
        </div>
    </div>
</div>

<!-- Create Patient Modal -->
<div id="createPatientModal" class="modal" style="display:none;">
    <div class="modal-backdrop" onclick="closeCreatePatientModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Patient</h3>
            <button class="btn-close" onclick="closeCreatePatientModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="createPatientForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="patientMrn">CNIC Number *</label>
                        <input type="text" id="patientMrn" name="mrn" class="input" required>
                    </div>
                    <div class="form-group">
                        <label for="patientSex">Sex</label>
                        <select id="patientSex" name="sex" class="input">
                            <option value="">Select...</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                            <option value="unknown">Unknown</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="patientFirstName">First Name *</label>
                        <input type="text" id="patientFirstName" name="first_name" class="input" required>
                    </div>
                    <div class="form-group">
                        <label for="patientLastName">Last Name *</label>
                        <input type="text" id="patientLastName" name="last_name" class="input" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="patientDob">Date of Birth</label>
                        <input type="date" id="patientDob" name="dob" class="input">
                    </div>
                    <div class="form-group">
                        <label for="patientPhone">Phone Number</label>
                        <input type="tel" id="patientPhone" name="phone" class="input">
                    </div>
                </div>
                <div class="form-group">
                    <label for="patientEmail">Email Address</label>
                    <input type="email" id="patientEmail" name="email" class="input">
                </div>
                <div class="form-group">
                    <label for="patientAddress">Address</label>
                    <textarea id="patientAddress" name="address" class="input" rows="3"></textarea>
                </div>
                
                <!-- Doctor Assignment and Check-up Fee Section -->
                <div class="billing-section">
                    <h4>Doctor Assignment & Billing</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="assignedDoctor">Assign Doctor *</label>
                            <select id="assignedDoctor" name="doctor_id" class="input" required>
                                <option value="">Select a doctor...</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="checkupFee">Check-up Fee (PKR) *</label>
                            <input type="number" id="checkupFee" name="checkup_fee" class="input" step="0.01" min="0" required placeholder="0.00">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="serviceDescription">Service Description</label>
                        <textarea id="serviceDescription" name="service_description" class="input" rows="4" placeholder="Initial consultation, Follow-up visit, etc."></textarea>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn primary">Create Patient & Generate Invoice</button>
                    <button type="button" class="btn ghost" onclick="closeCreatePatientModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Patient Modal -->
<div id="editPatientModal" class="modal" style="display:none;">
    <div class="modal-backdrop" onclick="closeEditPatientModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Patient</h3>
            <button class="btn-close" onclick="closeEditPatientModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="editPatientForm">
                <input type="hidden" id="editPatientId" name="id">
                <div class="form-row">
                    <div class="form-group">
                        <label for="editPatientMrn">CNIC Number *</label>
                        <input type="text" id="editPatientMrn" name="mrn" class="input" required>
                    </div>
                    <div class="form-group">
                        <label for="editPatientSex">Sex</label>
                        <select id="editPatientSex" name="sex" class="input">
                            <option value="">Select...</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                            <option value="unknown">Unknown</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editPatientFirstName">First Name *</label>
                        <input type="text" id="editPatientFirstName" name="first_name" class="input" required>
                    </div>
                    <div class="form-group">
                        <label for="editPatientLastName">Last Name *</label>
                        <input type="text" id="editPatientLastName" name="last_name" class="input" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editPatientDob">Date of Birth</label>
                        <input type="date" id="editPatientDob" name="dob" class="input">
                    </div>
                    <div class="form-group">
                        <label for="editPatientPhone">Phone Number</label>
                        <input type="tel" id="editPatientPhone" name="phone" class="input">
                    </div>
                </div>
                <div class="form-group">
                    <label for="editPatientEmail">Email Address</label>
                    <input type="email" id="editPatientEmail" name="email" class="input">
                </div>
                <div class="form-group">
                    <label for="editPatientAddress">Address</label>
                    <textarea id="editPatientAddress" name="address" class="input" rows="3"></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn primary">Update Patient</button>
                    <button type="button" class="btn ghost" onclick="closeEditPatientModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Generate Invoice Modal -->
<div id="invoiceModal" class="modal" style="display:none;">
    <div class="modal-backdrop" onclick="closeInvoiceModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3>Generate Invoice for Existing Patient</h3>
            <button class="btn-close" onclick="closeInvoiceModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="invoiceForm">
                <div class="form-group">
                    <label for="invoicePatient">Select Patient *</label>
                    <select id="invoicePatient" name="patient_id" class="input" required>
                        <option value="">Select a patient...</option>
                    </select>
                </div>
                
                <!-- Doctor Assignment and Check-up Fee Section -->
                <div class="billing-section">
                    <h4>Doctor Assignment & Billing</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="invoiceDoctor">Assign Doctor *</label>
                            <select id="invoiceDoctor" name="doctor_id" class="input" required>
                                <option value="">Select a doctor...</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="invoiceCheckupFee">Check-up Fee (PKR) *</label>
                            <input type="number" id="invoiceCheckupFee" name="checkup_fee" class="input" step="0.01" min="0" required placeholder="0.00">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="invoiceServiceDescription">Service Description</label>
                        <textarea id="invoiceServiceDescription" name="service_description" class="input" rows="4" placeholder="Follow-up visit, consultation, etc."></textarea>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn primary">Generate Invoice</button>
                    <button type="button" class="btn ghost" onclick="closeInvoiceModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let allPatients = [];
let currentPatientId = null;
let doctorsData = [];

function htmlesc(str) { 
    return (str||'').toString().replace(/[&<>\"]/g, s=>({"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;"}[s])); 
}

function tag(text, cls = '') {
    return `<span class="tag ${cls}">${text}</span>`;
}

function formatAiResults(result) {
    try {
        let parsed = typeof result === 'string' ? JSON.parse(result) : result;
        
        // If it's a simple string, return it as is
        if (typeof parsed === 'string') {
            return parsed;
        }
        
        // Format structured results into readable text
        let formatted = '';
        
        if (parsed.diagnosis || parsed.findings || parsed.impression) {
            formatted += 'DIAGNOSIS/FINDINGS:\n';
            formatted += (parsed.diagnosis || parsed.findings || parsed.impression) + '\n\n';
        }
        
        if (parsed.recommendations || parsed.treatment) {
            formatted += 'RECOMMENDATIONS:\n';
            formatted += (parsed.recommendations || parsed.treatment) + '\n\n';
        }
        
        if (parsed.summary) {
            formatted += 'SUMMARY:\n';
            formatted += parsed.summary + '\n\n';
        }
        
        if (parsed.abnormalities || parsed.observations) {
            formatted += 'OBSERVATIONS:\n';
            formatted += (parsed.abnormalities || parsed.observations) + '\n\n';
        }
        
        // If no structured fields found, try to create a readable format
        if (!formatted) {
            Object.keys(parsed).forEach(key => {
                if (typeof parsed[key] === 'string' && parsed[key].length > 0) {
                    formatted += key.toUpperCase().replace(/_/g, ' ') + ':\n';
                    formatted += parsed[key] + '\n\n';
                }
            });
        }
        
        // If still no readable content, fall back to formatted JSON
        if (!formatted) {
            formatted = JSON.stringify(parsed, null, 2);
        }
        
        return htmlesc(formatted.trim());
    } catch (e) {
        // If parsing fails, return the original result
        return htmlesc(String(result || 'No results available'));
    }
}

function getInitials(name) {
    if (!name) return 'P';
    return name.split(' ').map(n => n.charAt(0)).join('').substring(0, 2).toUpperCase();
}

function calculateAgeFromDob(dob) {
    if (!dob) return 'Unknown';
    try {
        const birthDate = new Date(dob);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age + ' years';
    } catch (e) {
        return 'Unknown';
    }
}

function showAlert(message, type = 'info') {
    const alertContainer = document.getElementById('alert-container');
    const alertId = 'alert-' + Date.now();
    
    const alertHtml = `
        <div id="${alertId}" class="alert alert-${type}">
            ${htmlesc(message)}
            <button class="btn-close" onclick="document.getElementById('${alertId}').remove()">&times;</button>
        </div>
    `;
    
    alertContainer.innerHTML = alertHtml;
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        const alertEl = document.getElementById(alertId);
        if (alertEl) alertEl.remove();
    }, 5000);
}

async function loadPatients() {
    try {
        const response = await fetch('/reports/patients', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to load patients');
        }
        
        const data = await response.json();
        allPatients = data.data || [];
        renderPatients(allPatients);
    } catch (e) {
        document.getElementById('patients-list').innerHTML = `
            <div class="error-state">
                <p>Failed to load patients: ${htmlesc(e.message)}</p>
                <button class="btn primary" onclick="loadPatients()">Retry</button>
            </div>
        `;
    }
}

function filterPatients() {
    const search = document.getElementById('patientSearch').value.toLowerCase();
    const sexFilter = document.getElementById('sexFilter').value;
    
    let filtered = allPatients.filter(patient => {
        const matchesSearch = !search || 
            (patient.name || '').toLowerCase().includes(search) ||
            (patient.mrn || '').toLowerCase().includes(search) ||
            (patient.email || '').toLowerCase().includes(search);
        
        const matchesSex = !sexFilter || patient.sex === sexFilter;
        
        return matchesSearch && matchesSex;
    });
    
    renderPatients(filtered);
}

function renderPatients(patients) {
    if (!patients || patients.length === 0) {
        document.getElementById('patients-list').innerHTML = `
            <div class="empty-state">
                <p>No patients found</p>
            </div>
        `;
        return;
    }
    
    const patientsHtml = patients.map(patient => {
        const initials = patient.name ? patient.name.split(' ').map(n => n.charAt(0)).join('').substring(0, 2).toUpperCase() : 'P';
        const age = patient.dob ? calculateAge(patient.dob) : 'Unknown';
        
        // Count medical data
        const imagingCount = (patient.imaging_studies && patient.imaging_studies.length) || (patient.counts && patient.counts.imaging_studies) || 0;
        const labCount = (patient.lab_orders && patient.lab_orders.length) || (patient.counts && patient.counts.lab_orders) || 0;
        const rxCount = (patient.prescriptions && patient.prescriptions.length) || (patient.counts && patient.counts.prescriptions) || 0;
        
        return `
            <div class="patient-card" onclick="selectPatient(${patient.id})">
                <div class="patient-avatar">
                    <div class="avatar-circle">${initials}</div>
                </div>
                <div class="patient-info">
                    <h3>${htmlesc(patient.name || 'Unknown Name')}</h3>
                    <p class="patient-cnic">CNIC: ${htmlesc(patient.mrn || 'N/A')}</p>
                    <p class="patient-details">
                        Age: ${age} • ${htmlesc(patient.sex || 'Unknown')} • DOB: ${htmlesc(patient.dob || 'N/A')}
                    </p>
                    <p class="patient-contact">
                        ${htmlesc(patient.phone || 'No phone')} • ${htmlesc(patient.email || 'No email')}
                    </p>
                    <div class="patient-medical-indicators">
                        <span class="medical-tag" onclick="event.stopPropagation(); showImagingModal(${patient.id})">
                            📸 IMG ${imagingCount}
                        </span>
                        <span class="medical-tag" onclick="event.stopPropagation(); showLabModal(${patient.id})">
                            🧪 LAB ${labCount}
                        </span>
                        <span class="medical-tag" onclick="event.stopPropagation(); showRxModal(${patient.id})">
                            💊 RX ${rxCount}
                        </span>
                    </div>
                </div>
                <div class="patient-actions" onclick="event.stopPropagation()">
                    <button class="btn small primary" onclick="editPatient(${patient.id})">Edit</button>
                    <button class="btn small danger" onclick="deletePatient(${patient.id}, '${htmlesc(patient.name || 'this patient')}')">Delete</button>
                </div>
            </div>
        `;
    }).join('');
    
    document.getElementById('patients-list').innerHTML = patientsHtml;
}

function calculateAge(dob) {
    if (!dob) return 'Unknown';
    const birthDate = new Date(dob);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    return age;
}

async function selectPatient(patientId) {
    currentPatientId = patientId;
    document.getElementById('patientDetailsTitle').innerText = 'Patient Details';
    document.getElementById('patientDetailsMeta').innerText = 'Loading patient details...';
    document.getElementById('patientDetailsActions').style.display = 'none';
    document.getElementById('patientBasicInfo').innerHTML = '';
    document.getElementById('patientImaging').innerHTML = '';
    document.getElementById('patientLabs').innerHTML = '';
    document.getElementById('patientRx').innerHTML = '';
    document.getElementById('patientNotes').innerHTML = '';

    try {
        const response = await fetch(`/reports/patients/${patientId}`, {
            headers: {'Accept': 'application/json'}
        });
        const patient = await response.json();
        
        const title = patient.name || 'Unknown Patient';
        document.getElementById('patientDetailsTitle').innerText = title;
        document.getElementById('patientDetailsMeta').innerHTML = `CNIC: <b>${htmlesc(patient.mrn||'N/A')}</b> • DOB: ${htmlesc(patient.dob||'N/A')} • ${htmlesc(patient.sex||'Unknown')}`;

        // Show actions
        const actions = document.getElementById('patientDetailsActions');
        actions.style.display = 'flex';

        // Basic Info - Beautiful Profile Layout
        let basicHtml = `
            <div class="patient-profile">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <div class="avatar-circle-large">${getInitials(patient.name)}</div>
                    </div>
                    <div class="profile-info">
                        <h3 class="profile-name">${htmlesc(patient.name || 'Unknown Patient')}</h3>
                        <p class="profile-meta">CNIC: ${htmlesc(patient.mrn || 'N/A')} • ${htmlesc(patient.sex || 'Unknown')} • Age: ${calculateAgeFromDob(patient.dob)}</p>
                    </div>
                </div>
                
                <div class="profile-details">
                    <div class="detail-section">
                        <h4><i class="fas fa-user"></i> Personal Information</h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Full Name</span>
                                <span class="detail-value">${htmlesc(patient.name || 'N/A')}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Date of Birth</span>
                                <span class="detail-value">${htmlesc(patient.dob || 'N/A')}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Gender</span>
                                <span class="detail-value">${htmlesc(patient.sex || 'N/A')}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">CNIC Number</span>
                                <span class="detail-value">${htmlesc(patient.mrn || 'N/A')}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-section">
                        <h4><i class="fas fa-address-book"></i> Contact Information</h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Email Address</span>
                                <span class="detail-value">${patient.email ? `<a href="mailto:${htmlesc(patient.email)}">${htmlesc(patient.email)}</a>` : 'N/A'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Phone Number</span>
                                <span class="detail-value">${patient.phone ? `<a href="tel:${htmlesc(patient.phone)}">${htmlesc(patient.phone)}</a>` : 'N/A'}</span>
                            </div>
                            <div class="detail-item detail-item-full">
                                <span class="detail-label">Address</span>
                                <span class="detail-value">${htmlesc(patient.address || 'N/A')}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.getElementById('patientBasicInfo').innerHTML = basicHtml;

        // Imaging Studies
        const imaging = patient.imaging_studies || [];
        let imagingHtml = `<h3>Imaging Studies (${imaging.length})</h3>`;
        if (imaging.length === 0) {
            imagingHtml += '<div class="muted">No imaging studies.</div>';
        } else {
            imagingHtml += '<div class="table-container"><table class="table"><thead><tr><th>Modality</th><th>Description</th><th>Date</th><th>AI Analysis</th><th></th></tr></thead><tbody>';
            imaging.forEach(study => {
                const lastAI = (study.ai_results||[])[0];
                const aiCell = lastAI ? `${htmlesc(lastAI.model)} <span class="tag ok">${(lastAI.confidence_score||'').toString()}</span>` : '<span class="muted">None</span>';
                imagingHtml += `<tr>
                    <td style="color: #fff;">${htmlesc(study.modality||'N/A')}</td>
                    <td style="color: #fff; word-wrap: break-word; max-width: 200px;">${htmlesc(study.description||'N/A')}</td>
                    <td style="color: #fff;">${htmlesc(study.started_at||'N/A')}</td>
                    <td style="color: #fff;">${aiCell}</td>
                    <td><button class="btn small primary" onclick="analyzeImaging(${study.id})">Analyze</button></td>
                </tr>`;
                if (lastAI && lastAI.result) {
                    imagingHtml += `<tr><td colspan="5" style="padding: 1rem;"><div style="background: rgba(0, 0, 0, 0.2); padding: 1rem; border-radius: 6px; color: #fff; white-space: pre-wrap; word-wrap: break-word; overflow-wrap: break-word; max-width: 100%; overflow: hidden; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif; line-height: 1.6;">${formatAiResults(lastAI.result)}</div></td></tr>`;
                }
            });
            imagingHtml += '</tbody></table></div>';
        }
        document.getElementById('patientImaging').innerHTML = imagingHtml;

        // Lab Orders
        const labs = patient.lab_orders || [];
        let labsHtml = `<h3>Lab Orders (${labs.length})</h3>`;
        if (labs.length === 0) {
            labsHtml += '<div class="muted">No lab orders.</div>';
        } else {
            labsHtml += '<div class="table-container"><table class="table"><thead><tr><th>Test</th><th>Status</th><th>Priority</th><th>Result</th><th>Notes</th></tr></thead><tbody>';
            labs.forEach(order => {
                const resultFlag = order.result_flag;
                const flagClass = resultFlag === 'critical' ? 'err' : (resultFlag === 'normal' ? 'ok' : 'warn');
                const flagTag = resultFlag ? `<span class="tag ${flagClass}">${htmlesc(resultFlag)}</span>` : '';
                
                labsHtml += `<tr class="lab-item">
                    <td style="color: #fff; word-wrap: break-word; max-width: 150px;">${htmlesc(order.code || '')} ${htmlesc(order.name||'')}</td>
                    <td style="color: #fff;">${htmlesc(order.status||'')}</td>
                    <td style="color: #fff;">${htmlesc(order.priority||'')}</td>
                    <td style="color: #fff; word-wrap: break-word; max-width: 120px;">${htmlesc(order.result_value||'')} ${flagTag}</td>
                    <td style="color: #fff; word-wrap: break-word; max-width: 200px;">${htmlesc(order.result_notes||'')}</td>
                </tr>`;
            });
            labsHtml += '</tbody></table></div>';
        }
        document.getElementById('patientLabs').innerHTML = labsHtml;

        // Prescriptions
        const prescriptions = patient.prescriptions || [];
        let rxHtml = `<h3>Prescriptions (${prescriptions.length})</h3>`;
        if (prescriptions.length === 0) {
            rxHtml += '<div class="muted">No prescriptions.</div>';
        } else {
            rxHtml += '<div class="table-container"><table class="table"><thead><tr><th>Medication</th><th>Strength</th><th>Dosage</th><th>Frequency</th><th>Status</th></tr></thead><tbody>';
            prescriptions.forEach(rx => {
                rxHtml += `<tr class="rx-item">
                    <td style="color: #fff; word-wrap: break-word; max-width: 150px;">${htmlesc(rx.medication||'')}</td>
                    <td style="color: #fff; word-wrap: break-word; max-width: 100px;">${htmlesc(rx.strength||'')}</td>
                    <td style="color: #fff; word-wrap: break-word; max-width: 100px;">${htmlesc(rx.dosage||'')}</td>
                    <td style="color: #fff; word-wrap: break-word; max-width: 120px;">${htmlesc(rx.frequency||'')}</td>
                    <td style="color: #fff;">${htmlesc(rx.status||'')}</td>
                </tr>`;
            });
            rxHtml += '</tbody></table></div>';
        }
        document.getElementById('patientRx').innerHTML = rxHtml;

        // Clinical Notes
        const notes = patient.clinical_notes || [];
        let notesHtml = `<h3>Clinical Notes (${notes.length})</h3>`;
        if (notes.length === 0) {
            notesHtml += '<div class="muted">No clinical notes.</div>';
        } else {
            notesHtml += '<div class="clinical-notes">';
            notes.forEach(note => {
                notesHtml += `<div class="card note-item" style="margin:8px 0; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 8px; padding: 1rem; max-width: 100%; overflow: hidden;">
                    <div class="muted" style="color: rgba(255, 255, 255, 0.7); margin-bottom: 0.5rem;">${htmlesc(note.created_at||'')}</div>
                    <div style="color: #fff; margin-bottom: 0.5rem; word-wrap: break-word;"><b>Assessment:</b> ${htmlesc(note.soap_assessment||'')}</div>
                    <div style="color: #fff; word-wrap: break-word;"><b>Plan:</b><br><div style="background: rgba(0, 0, 0, 0.2); padding: 0.75rem; border-radius: 4px; margin-top: 0.5rem; white-space: pre-wrap; word-wrap: break-word; overflow-wrap: break-word; max-width: 100%; overflow: hidden; color: #fff;">${htmlesc(note.soap_plan||'')}</div></div>
                </div>`;
            });
            notesHtml += '</div>';
        }
        document.getElementById('patientNotes').innerHTML = notesHtml;
        
        // Highlight selected patient
        document.querySelectorAll('.patient-card').forEach(card => card.classList.remove('selected'));
        document.querySelectorAll('.patient-card').forEach(card => {
            if (card.onclick.toString().includes(`selectPatient(${patientId})`)) {
                card.classList.add('selected');
            }
        });
        
    } catch (e) {
        document.getElementById('patientDetailsMeta').innerText = 'Failed to load patient details.';
        console.error('Error loading patient details:', e);
    }
}

async function editPatient(patientId) {
    const patient = allPatients.find(p => p.id === patientId);
    if (!patient) {
        showAlert('Patient not found', 'error');
        return;
    }
    
    // Populate edit form
    document.getElementById('editPatientId').value = patient.id;
    document.getElementById('editPatientMrn').value = patient.mrn || '';
    document.getElementById('editPatientFirstName').value = patient.first_name || '';
    document.getElementById('editPatientLastName').value = patient.last_name || '';
    document.getElementById('editPatientDob').value = patient.dob || '';
    document.getElementById('editPatientSex').value = patient.sex || '';
    document.getElementById('editPatientPhone').value = patient.phone || '';
    document.getElementById('editPatientEmail').value = patient.email || '';
    document.getElementById('editPatientAddress').value = patient.address || '';
    
    document.getElementById('editPatientModal').style.display = 'block';
}

async function deletePatient(patientId, patientName) {
    if (!confirm(`Are you sure you want to delete patient "${patientName}"? This action cannot be undone and will remove all associated medical records.`)) {
        return;
    }
    
    try {
        const response = await fetch(`/api/patients/${patientId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to delete patient');
        }
        
        showAlert(`Patient "${patientName}" deleted successfully`, 'success');
        loadPatients();
        
        // Clear details panel if this patient was selected
        if (currentPatientId === patientId) {
            document.getElementById('patientDetailsTitle').innerText = 'Patient Details';
            document.getElementById('patientDetailsMeta').innerText = 'Select a patient to view comprehensive details.';
            document.getElementById('patientDetailsActions').style.display = 'none';
            document.getElementById('patientBasicInfo').innerHTML = '';
            document.getElementById('patientImaging').innerHTML = '';
            document.getElementById('patientLabs').innerHTML = '';
            document.getElementById('patientRx').innerHTML = '';
            document.getElementById('patientNotes').innerHTML = '';
            currentPatientId = null;
        }
    } catch (e) {
        showAlert(`Failed to delete patient: ${e.message}`, 'error');
    }
}

// Form submissions
document.getElementById('createPatientForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitButton = e.target.querySelector('button[type="submit"]');
    submitButton.disabled = true;
    submitButton.textContent = 'Creating...';
    
    const formData = new FormData(this);
    const patientData = {
        mrn: formData.get('mrn'),
        first_name: formData.get('first_name'),
        last_name: formData.get('last_name'),
        dob: formData.get('dob'),
        sex: formData.get('sex'),
        phone: formData.get('phone'),
        email: formData.get('email'),
        address: formData.get('address')
    };
    
    const doctorId = formData.get('doctor_id');
    const checkupFee = formData.get('checkup_fee');
    const serviceDescription = formData.get('service_description') || 'Initial consultation';
    
    try {
        // First create the patient
        const patientResponse = await fetch('/api/patients', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify(patientData)
        });
        
        const patientResult = await patientResponse.json();
        
        if (!patientResponse.ok) {
            throw new Error(patientResult.message || `Server returned ${patientResponse.status}`);
        }
        
        const newPatient = patientResult.patient;
        
        // Then create the invoice if checkup fee is provided
        if (checkupFee && doctorId) {
            const invoiceData = {
                patient_id: newPatient.id,
                doctor_id: doctorId,
                service_type: serviceDescription,
                amount: parseFloat(checkupFee),
                description: `${serviceDescription} for ${patientData.first_name} ${patientData.last_name}`
            };
            
            const invoiceResponse = await fetch('/admin/api/invoices', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify(invoiceData)
            });
            
            if (!invoiceResponse.ok) {
                console.warn('Failed to create invoice, but patient was created successfully');
                showAlert('Patient created successfully, but invoice generation failed. You can generate an invoice later.', 'warning');
            } else {
                showAlert('Patient created successfully and invoice generated!', 'success');
            }
        } else {
            showAlert('Patient created successfully!', 'success');
        }
        
        closeCreatePatientModal();
        loadPatients();
    } catch (e) {
        console.error('Error creating patient:', e);
        showAlert(`Failed to create patient: ${e.message}`, 'error');
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = 'Create Patient & Generate Invoice';
    }
});

document.getElementById('editPatientForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const patientId = formData.get('id');
    const data = {
        mrn: formData.get('mrn'),
        first_name: formData.get('first_name'),
        last_name: formData.get('last_name'),
        dob: formData.get('dob'),
        sex: formData.get('sex'),
        phone: formData.get('phone'),
        email: formData.get('email'),
        address: formData.get('address')
    };
    
    try {
        const response = await fetch(`/api/patients/${patientId}`, {
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify(data)
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Failed to update patient');
        }
        
        showAlert('Patient updated successfully', 'success');
        closeEditPatientModal();
        loadPatients();
        
        // Refresh details if this patient is currently selected
        if (currentPatientId == patientId) {
            selectPatient(patientId);
        }
    } catch (e) {
        showAlert(`Failed to update patient: ${e.message}`, 'error');
    }
});

// Invoice form submission
document.getElementById('invoiceForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const invoiceData = {
        patient_id: formData.get('patient_id'),
        doctor_id: formData.get('doctor_id'),
        service_type: formData.get('service_description') || 'Follow-up consultation',
        amount: parseFloat(formData.get('checkup_fee')),
        description: formData.get('service_description') || 'Follow-up consultation'
    };
    
    try {
        const response = await fetch('/admin/api/invoices', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify(invoiceData)
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Failed to create invoice');
        }
        
        const invoiceResult = await response.json();
        
        // Redirect to invoice preview page
        if (invoiceResult.view_url) {
            window.open(invoiceResult.view_url, '_blank');
            showAlert('Invoice created successfully! Opening preview...', 'success');
        } else {
            showAlert('Invoice created successfully!', 'success');
        }
        closeInvoiceModal();
    } catch (e) {
        showAlert(`Failed to generate invoice: ${e.message}`, 'error');
    }
});

// MedGemma analysis functions
async function postJson(url) {
    const r = await fetch(url, {method:'POST', headers:{'X-CSRF-TOKEN': csrf, 'Accept':'application/json'}});
    if (!r.ok) throw new Error('Request failed');
    return r.json().catch(()=>({ok:true}))
}

async function analyzeImaging(studyId){
    try { 
        await postJson(`/medgemma/analyze/imaging/${studyId}`); 
        if (currentPatientId) await selectPatient(currentPatientId); 
        showAlert('Imaging analysis completed', 'success');
    }
    catch(e){ showAlert('Failed to analyze imaging', 'error'); }
}

async function analyzeLabs(patientId){
    try { 
        await postJson(`/medgemma/analyze/labs/${patientId}`); 
        if (currentPatientId) await selectPatient(currentPatientId); 
        showAlert('Lab analysis completed', 'success');
    }
    catch(e){ showAlert('Failed to analyze labs', 'error'); }
}

async function secondOpinion(patientId){
    try { 
        await postJson(`/medgemma/second-opinion/${patientId}`); 
        if (currentPatientId) await selectPatient(currentPatientId); 
        showAlert('Second opinion analysis completed', 'success');
    }
    catch(e){ showAlert('Failed to get second opinion', 'error'); }
}

// Load doctors for dropdown
function loadDoctors() {
    // Try to fetch doctors from API
    fetch('/admin/api/doctors')
        .then(response => {
            if (!response.ok) {
                throw new Error('API not available');
            }
            return response.json();
        })
        .then(data => {
            doctorsData = data;
            populateCreateDoctorSelect(data);
        })
        .catch(error => {
            console.warn('Failed to load doctors from API, using fallback:', error);
            // Fallback doctor data - only active doctors
            doctorsData = [
                {id: 10, name: 'Dr. Sarah Johnson', email: 'doctor1@medgemma.com'},
                {id: 12, name: 'Dr. MedGemma Doctor', email: 'doctor@medgemma.com'},
                {id: 29, name: 'Dr. Amna Iqbal', email: 'amnaiqbal10396@gmail.com'}
            ];
            populateCreateDoctorSelect(doctorsData);
        });
}

function populateCreateDoctorSelect(doctors) {
    const doctorSelect = document.getElementById('assignedDoctor');
    
    // Clear existing options except the first one
    while (doctorSelect.children.length > 1) {
        doctorSelect.removeChild(doctorSelect.lastChild);
    }
    
    doctors.forEach(doctor => {
        const option = document.createElement('option');
        option.value = doctor.id;
        option.textContent = doctor.name;
        doctorSelect.appendChild(option);
    });
}

function showCreatePatientModal() {
    document.getElementById('createPatientModal').style.display = 'block';
    loadDoctors(); // Load doctors when modal opens
    
    // Reset form
    document.getElementById('createPatientForm').reset();
}

function closeCreatePatientModal() {
    document.getElementById('createPatientModal').style.display = 'none';
}

function showEditPatientModal() {
    document.getElementById('editPatientModal').style.display = 'block';
}

function closeEditPatientModal() {
    document.getElementById('editPatientModal').style.display = 'none';
}

function showInvoiceModal() {
    document.getElementById('invoiceModal').style.display = 'block';
    loadDoctorsForInvoice(); // Load doctors when modal opens
    loadPatientsForInvoice(); // Load patients when modal opens
    
    // Reset form
    document.getElementById('invoiceForm').reset();
}

function editPatient(patientId) {
    // Find the patient data
    const patient = allPatients.find(p => p.id == patientId);
    if (!patient) {
        showAlert('Patient not found', 'error');
        return;
    }
    
    // Populate the edit form
    document.getElementById('editPatientId').value = patient.id;
    document.getElementById('editPatientMrn').value = patient.mrn || '';
    document.getElementById('editPatientFirstName').value = patient.first_name || '';
    document.getElementById('editPatientLastName').value = patient.last_name || '';
    document.getElementById('editPatientDob').value = patient.dob || '';
    document.getElementById('editPatientSex').value = patient.sex || '';
    document.getElementById('editPatientPhone').value = patient.phone || '';
    document.getElementById('editPatientEmail').value = patient.email || '';
    document.getElementById('editPatientAddress').value = patient.address || '';
    
    // Show the edit modal
    showEditPatientModal();
}

function closeInvoiceModal() {
    document.getElementById('invoiceModal').style.display = 'none';
}

function loadPatientsForInvoice() {
    const patientSelect = document.getElementById('invoicePatient');
    patientSelect.innerHTML = '<option value="">Select a patient...</option>';
    
    allPatients.forEach(patient => {
        const option = document.createElement('option');
        option.value = patient.id;
        option.textContent = `${patient.first_name} ${patient.last_name} - ${patient.mrn}`;
        patientSelect.appendChild(option);
    });
}

function loadDoctorsForInvoice() {
    // Use already loaded doctors data if available
    if (doctorsData.length === 0) {
        // Try to fetch doctors from API
        fetch('/admin/api/doctors')
            .then(response => {
                if (!response.ok) {
                    throw new Error('API not available');
                }
                return response.json();
            })
            .then(data => {
                doctorsData = data;
                populateInvoiceDoctorSelect(data);
            })
            .catch(error => {
                console.warn('Failed to load doctors from API, using fallback:', error);
                // Fallback doctor data - only active doctors
                doctorsData = [
                    {id: 10, name: 'Dr. Sarah Johnson', email: 'doctor1@medgemma.com'},
                    {id: 12, name: 'Dr. MedGemma Doctor', email: 'doctor@medgemma.com'},
                    {id: 29, name: 'Dr. Amna Iqbal', email: 'amnaiqbal10396@gmail.com'}
                ];
                populateInvoiceDoctorSelect(doctorsData);
            });
    } else {
        populateInvoiceDoctorSelect(doctorsData);
    }
}

function populateInvoiceDoctorSelect(doctors) {
    const doctorSelect = document.getElementById('invoiceDoctor');
    doctorSelect.innerHTML = '<option value="">Select a doctor...</option>';
    
    doctors.forEach(doctor => {
        const option = document.createElement('option');
        option.value = doctor.id;
        option.textContent = doctor.name;
        doctorSelect.appendChild(option);
    });
}

// Medical Data Modal Functions
function showModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

async function showImagingModal(patientId) {
    try {
        showModal('imagingModal');
        const modalBody = document.getElementById('imagingModalBody');
        modalBody.innerHTML = 'Loading imaging studies...';
        
        const response = await fetch(`/reports/patients/${patientId}`, {
            headers: {'Accept': 'application/json'}
        });
        const data = await response.json();
        const patient = data.data;
        
        if (!patient.imaging_studies || patient.imaging_studies.length === 0) {
            modalBody.innerHTML = '<div class="muted">No imaging studies found for this patient.</div>';
            return;
        }
        
        let html = '<div class="image-grid">';
        patient.imaging_studies.forEach(study => {
            const studyDate = new Date(study.started_at).toLocaleDateString();
            html += `
                <div class="image-item" style="padding: 1rem; border: 1px solid rgba(255,255,255,0.2); border-radius: 8px; margin-bottom: 1rem;">
                    <div class="image-placeholder" style="text-align: center; margin-bottom: 0.5rem;">
                        <i class="fas fa-x-ray" style="font-size: 2rem; color: #667eea;"></i>
                    </div>
                    <div><strong>${study.modality}</strong></div>
                    <div class="muted">${study.description}</div>
                    <div class="muted">${studyDate}</div>
                    <div class="muted">Status: ${study.status}</div>
                </div>
            `;
        });
        html += '</div>';
        
        modalBody.innerHTML = html;
    } catch (error) {
        console.error('Error loading imaging studies:', error);
        document.getElementById('imagingModalBody').innerHTML = '<div class="muted">Error loading imaging studies.</div>';
    }
}

async function showLabModal(patientId) {
    try {
        showModal('labModal');
        const modalBody = document.getElementById('labModalBody');
        modalBody.innerHTML = 'Loading lab results...';
        
        const response = await fetch(`/reports/patients/${patientId}`, {
            headers: {'Accept': 'application/json'}
        });
        const data = await response.json();
        const patient = data.data;
        
        if (!patient.lab_orders || patient.lab_orders.length === 0) {
            modalBody.innerHTML = '<div class="muted">No lab results found for this patient.</div>';
            return;
        }
        
        let html = '<table class="table" style="width: 100%; color: #fff;"><thead><tr><th>Test</th><th>Result</th><th>Flag</th><th>Reference Range</th><th>Notes</th></tr></thead><tbody>';
        patient.lab_orders.forEach(lab => {
            const flagClass = lab.result_flag === 'critical' ? 'err' : (lab.result_flag === 'normal' ? 'ok' : 'warn');
            const flagTag = lab.result_flag ? `<span class="tag ${flagClass}">${htmlesc(lab.result_flag)}</span>` : '-';
            html += `
                <tr>
                    <td><strong>${htmlesc(lab.code || '')} ${htmlesc(lab.name || '')}</strong></td>
                    <td>${htmlesc(lab.result_value || '')}</td>
                    <td>${flagTag}</td>
                    <td>${htmlesc(lab.reference_range || '-')}</td>
                    <td>${htmlesc(lab.result_notes || '-')}</td>
                </tr>
            `;
        });
        html += '</tbody></table>';
        
        modalBody.innerHTML = html;
    } catch (error) {
        console.error('Error loading lab results:', error);
        document.getElementById('labModalBody').innerHTML = '<div class="muted">Error loading lab results.</div>';
    }
}

async function showRxModal(patientId) {
    try {
        showModal('rxModal');
        const modalBody = document.getElementById('rxModalBody');
        modalBody.innerHTML = 'Loading prescriptions...';
        
        const response = await fetch(`/reports/patients/${patientId}`, {
            headers: {'Accept': 'application/json'}
        });
        const data = await response.json();
        const patient = data.data;
        
        if (!patient.prescriptions || patient.prescriptions.length === 0) {
            modalBody.innerHTML = '<div class="muted">No prescriptions found for this patient.</div>';
            return;
        }
        
        let html = '<table class="table" style="width: 100%; color: #fff;"><thead><tr><th>Medication</th><th>Strength</th><th>Dosage</th><th>Frequency</th><th>Status</th><th>Prescriber</th></tr></thead><tbody>';
        patient.prescriptions.forEach(rx => {
            html += `
                <tr>
                    <td><strong>${htmlesc(rx.medication_name || rx.medication || '')}</strong></td>
                    <td>${htmlesc(rx.strength || '-')}</td>
                    <td>${htmlesc(rx.dosage_instruction || rx.dosage || '-')}</td>
                    <td>${htmlesc(rx.frequency || '-')}</td>
                    <td><span class="tag">${htmlesc(rx.status || 'Unknown')}</span></td>
                    <td>${htmlesc(rx.prescriber_name || '-')}</td>
                </tr>
            `;
        });
        html += '</tbody></table>';
        
        modalBody.innerHTML = html;
    } catch (error) {
        console.error('Error loading prescriptions:', error);
        document.getElementById('rxModalBody').innerHTML = '<div class="muted">Error loading prescriptions.</div>';
    }
}

// Search and filter event listeners
document.getElementById('patientSearch').addEventListener('input', filterPatients);
document.getElementById('sexFilter').addEventListener('change', filterPatients);

// Initialize
loadPatients();

// Close modals when clicking outside
window.onclick = function(event) {
    const createModal = document.getElementById('createPatientModal');
    const editModal = document.getElementById('editPatientModal');
    const invoiceModal = document.getElementById('invoiceModal');
    const imagingModal = document.getElementById('imagingModal');
    const labModal = document.getElementById('labModal');
    const rxModal = document.getElementById('rxModal');
    
    if (event.target === createModal) {
        closeCreatePatientModal();
    }
    
    if (event.target === editModal) {
        closeEditPatientModal();
    }
    
    if (event.target === invoiceModal) {
        closeInvoiceModal();
    }
    
    if (event.target === imagingModal) {
        closeModal('imagingModal');
    }
    
    if (event.target === labModal) {
        closeModal('labModal');
    }
    
    if (event.target === rxModal) {
        closeModal('rxModal');
    }
}
</script>

<style>
.patients-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
    max-height: 500px;
    overflow-y: auto;
}

.patient-card {
    display: flex;
    align-items: center;
    padding: 16px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    backdrop-filter: blur(10px);
}

.patient-card:hover {
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.3);
    transform: translateY(-1px);
}

.patient-card.selected {
    background: rgba(59, 130, 246, 0.2);
    border-color: rgba(59, 130, 246, 0.5);
}

.patient-avatar {
    margin-right: 16px;
}

.avatar-circle {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    font-weight: bold;
}

.patient-info {
    flex: 1;
}

.patient-medical-indicators {
    display: flex;
    gap: 8px;
    margin-top: 8px;
    flex-wrap: wrap;
}

.medical-tag {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    background: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(255, 255, 255, 0.2);
    cursor: pointer;
    transition: all 0.2s ease;
    user-select: none;
}

.medical-tag:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.4);
    transform: translateY(-1px);
}

/* Modal Overlay Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(5px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-content {
    background: rgba(30, 30, 30, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    width: 90%;
    max-width: 800px;
    max-height: 80vh;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(255, 255, 255, 0.05);
}

.modal-title {
    color: #fff;
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.modal-close {
    background: none;
    border: none;
    color: rgba(255, 255, 255, 0.7);
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s ease;
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
}

.modal-body {
    padding: 1.5rem;
    max-height: 60vh;
    overflow-y: auto;
    color: #fff;
}

.tag {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    background: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.tag.ok {
    background: rgba(34, 197, 94, 0.2);
    color: #22c55e;
    border: 1px solid rgba(34, 197, 94, 0.3);
}

.tag.warn {
    background: rgba(245, 158, 11, 0.2);
    color: #f59e0b;
    border: 1px solid rgba(245, 158, 11, 0.3);
}

.tag.err {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.patient-info {
    flex: 1;
}

.patient-info h3 {
    margin: 0 0 4px 0;
    color: #ffffff;
    font-size: 16px;
}

.patient-cnic {
    margin: 0 0 4px 0;
    color: rgba(59, 130, 246, 0.9);
    font-weight: 600;
    font-size: 14px;
}

.patient-details, .patient-contact {
    margin: 0 0 2px 0;
    color: #718096;
    font-size: 13px;
}

.patient-actions {
    display: flex;
    gap: 8px;
}

.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1000;
}

.modal-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
}

.modal-content {
    position: relative;
    max-width: 700px;
    width: 90%;
    margin: 30px auto;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
    max-height: 90vh;
    overflow-y: auto;
    z-index: 1001;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px 10px 0 0;
}

.modal-header h3 {
    margin: 0;
    color: #ffffff;
    font-size: 18px;
    font-weight: 600;
}

.btn-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: rgba(255, 255, 255, 0.7);
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s ease;
}

.btn-close:hover {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.1);
}

.modal-body {
    padding: 25px;
    background: transparent;
    color: #ffffff;
}

.form-row {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
}

.form-group {
    flex: 1;
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #ffffff;
    font-size: 14px;
}

.modal .input {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 6px;
    font-size: 14px;
    background: rgba(255, 255, 255, 0.1) !important;
    color: #ffffff !important;
    transition: all 0.2s ease;
    backdrop-filter: blur(10px);
}

.modal .input:focus {
    outline: none;
    border-color: rgba(59, 130, 246, 0.8);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
}

.modal .input::placeholder {
    color: rgba(255, 255, 255, 0.6) !important;
}

.modal select.input {
    background: rgba(255, 255, 255, 0.1) !important;
    color: #ffffff !important;
}

.modal textarea.input {
    background: rgba(255, 255, 255, 0.1) !important;
    color: #ffffff !important;
    resize: vertical;
    min-height: 80px;
}

.billing-section {
    margin-top: 25px;
    padding: 20px;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.billing-section h4 {
    margin: 0 0 15px 0;
    color: #ffffff;
    font-size: 16px;
    font-weight: 600;
}

.form-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
}

.alert {
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 14px;
    backdrop-filter: blur(10px);
}

.alert-info {
    background: rgba(59, 130, 246, 0.2);
    border: 1px solid rgba(59, 130, 246, 0.5);
    color: #ffffff;
}

.alert-success {
    background: rgba(34, 197, 94, 0.2);
    border: 1px solid rgba(34, 197, 94, 0.5);
    color: #ffffff;
}

.alert-error {
    background: rgba(239, 68, 68, 0.2);
    border: 1px solid rgba(239, 68, 68, 0.5);
    color: #ffffff;
}

.loading, .error-state, .empty-state {
    text-align: center;
    padding: 40px;
    color: rgba(255, 255, 255, 0.7);
}

.error-state {
    color: rgba(239, 68, 68, 0.9);
}

.btn.danger {
    background: #e53e3e;
    color: white;
    border-color: #e53e3e;
}

.btn.danger:hover {
    background: #c53030;
    border-color: #c53030;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 12px;
    margin-bottom: 16px;
}

.info-item {
    padding: 12px;
    background: #f8fafc;
    border-radius: 6px;
    font-size: 14px;
}

.info-item strong {
    color: rgba(255, 255, 255, 0.9);
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 12px;
    font-size: 14px;
}

.table th, .table td {
    padding: 8px 12px;
    text-align: left;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.table th {
    background: rgba(255, 255, 255, 0.05);
    font-weight: 600;
    color: #ffffff;
}

.table tr:hover {
    background: rgba(255, 255, 255, 0.05);
}

/* Patient Profile Styles */
.patient-profile {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    padding: 0;
}

.profile-header {
    display: flex;
    align-items: center;
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px 8px 0 0;
    margin-bottom: 0;
}

.profile-avatar {
    margin-right: 20px;
}

.avatar-circle-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: bold;
    color: white;
    border: 3px solid rgba(255, 255, 255, 0.3);
}

.profile-name {
    margin: 0 0 5px 0;
    font-size: 24px;
    font-weight: 600;
}

.profile-meta {
    margin: 0;
    opacity: 0.9;
    font-size: 14px;
}

.profile-details {
    padding: 20px;
}

.detail-section {
    margin-bottom: 25px;
}

.detail-section:last-child {
    margin-bottom: 0;
}

.detail-section h4 {
    margin: 0 0 15px 0;
    font-size: 16px;
    font-weight: 600;
    color: #333;
    display: flex;
    align-items: center;
}

.detail-section h4 i {
    margin-right: 8px;
    color: #667eea;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.detail-item {
    display: flex;
    flex-direction: column;
}

.detail-item-full {
    grid-column: 1 / -1;
}

.detail-label {
    font-size: 12px;
    font-weight: 600;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.detail-value {
    font-size: 14px;
    color: #333;
    font-weight: 500;
}

.detail-value a {
    color: #667eea;
    text-decoration: none;
}

.detail-value a:hover {
    text-decoration: underline;
}

/* Card improvements */
.card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 20px;
    border-bottom: 1px solid #eee;
    background: #f8f9fa;
}

.card-header h2 {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
    color: #333;
}

.card-header .muted {
    font-size: 14px;
    color: #666;
    margin: 5px 0 0 0;
}

.card-body {
    padding: 0;
}

/* Action buttons in header */
#patientDetailsActions {
    display: flex;
    gap: 10px;
    margin: 10px 0 0 0;
}

#patientDetailsActions .btn {
    font-size: 12px;
    padding: 6px 12px;
}

/* Patient Profile Styles */
.patient-profile {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    padding: 0;
}

.profile-header {
    display: flex;
    align-items: center;
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px 8px 0 0;
    margin-bottom: 0;
}

.profile-avatar {
    margin-right: 20px;
}

.avatar-circle-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: bold;
    color: white;
    border: 3px solid rgba(255, 255, 255, 0.3);
}

.profile-name {
    margin: 0 0 5px 0;
    font-size: 24px;
    font-weight: 600;
}

.profile-meta {
    margin: 0;
    opacity: 0.9;
    font-size: 14px;
}

.profile-details {
    padding: 20px;
}

.detail-section {
    margin-bottom: 25px;
}

.detail-section:last-child {
    margin-bottom: 0;
}

.detail-section h4 {
    margin: 0 0 15px 0;
    font-size: 16px;
    font-weight: 600;
    color: #ffffff;
    display: flex;
    align-items: center;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.detail-section h4 i {
    margin-right: 8px;
    color: #667eea;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.detail-item {
    display: flex;
    flex-direction: column;
    background: rgba(255, 255, 255, 0.05);
    padding: 1rem;
    border-radius: 6px;
}

.detail-item-full {
    grid-column: 1 / -1;
}

.detail-label {
    font-size: 12px;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.8);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.detail-value {
    font-size: 14px;
    color: #ffffff;
    font-weight: 500;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.detail-value a {
    color: #667eea;
    text-decoration: none;
}

.detail-value a:hover {
    text-decoration: underline;
}

/* Card improvements */
.card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    background: rgba(255, 255, 255, 0.05);
}

.card-header h2 {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
    color: #ffffff;
}

.card-header .muted {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.6);
    margin: 5px 0 0 0;
}

.card-body {
    padding: 0;
}

/* Action buttons in header */
#patientDetailsActions {
    display: flex;
    gap: 10px;
    margin: 10px 0 0 0;
}

/* Responsive design */
@media (max-width: 768px) {
    .profile-header {
        flex-direction: column;
        text-align: center;
    }
    
    .profile-avatar {
        margin-right: 0;
        margin-bottom: 15px;
    }
    
    .detail-grid {
        grid-template-columns: 1fr;
    }
    
    .card-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    #patientDetailsActions {
        margin-top: 15px;
        flex-wrap: wrap;
    }
}
</style>

<script>
// Available lab tests data
let availableTests = [];
let selectedTests = [];

// New Order Modal Functions
function openNewOrderModal() {
    document.getElementById('newOrderModal').style.display = 'flex';
    showLabOrder(); // Default to lab order
}

function showLabOrder() {
    // Update button states
    document.getElementById('labOrderBtn').className = 'btn primary active';
    document.getElementById('imagingOrderBtn').className = 'btn outline';
    document.getElementById('prescriptionOrderBtn').className = 'btn outline';
    
    // Show lab order interface
    document.getElementById('labOrderInterface').className = 'order-interface active';
    document.getElementById('imagingOrderInterface').className = 'order-interface';
    document.getElementById('prescriptionOrderInterface').className = 'order-interface';
    
    // Load available tests if not already loaded
    if (availableTests.length === 0) {
        loadAvailableTests();
    }
}

function showImagingOrder() {
    document.getElementById('labOrderBtn').className = 'btn outline';
    document.getElementById('imagingOrderBtn').className = 'btn primary active';
    document.getElementById('prescriptionOrderBtn').className = 'btn outline';
    
    document.getElementById('labOrderInterface').className = 'order-interface';
    document.getElementById('imagingOrderInterface').className = 'order-interface active';
    document.getElementById('prescriptionOrderInterface').className = 'order-interface';
}

function showPrescriptionOrder() {
    document.getElementById('labOrderBtn').className = 'btn outline';
    document.getElementById('imagingOrderBtn').className = 'btn outline';
    document.getElementById('prescriptionOrderBtn').className = 'btn primary active';
    
    document.getElementById('labOrderInterface').className = 'order-interface';
    document.getElementById('imagingOrderInterface').className = 'order-interface';
    document.getElementById('prescriptionOrderInterface').className = 'order-interface active';
}

// Load available lab tests from API
async function loadAvailableTests() {
    try {
        const response = await fetch('/api/configuration/lab-tests');
        if (response.ok) {
            const data = await response.json();
            availableTests = data.data || data; // Handle different response formats
            renderAvailableTests();
        } else {
            console.error('Failed to load lab tests:', response.statusText);
            document.getElementById('availableTests').innerHTML = 
                '<div style="text-align: center; padding: 20px; color: #ef4444;">Failed to load available tests</div>';
        }
    } catch (error) {
        console.error('Error loading lab tests:', error);
        document.getElementById('availableTests').innerHTML = 
            '<div style="text-align: center; padding: 20px; color: #ef4444;">Error loading tests</div>';
    }
}

// Render available tests in the drag-and-drop box
function renderAvailableTests(filteredTests = null) {
    const testsToRender = filteredTests || availableTests;
    const availableTestsContainer = document.getElementById('availableTests');
    
    if (testsToRender.length === 0) {
        availableTestsContainer.innerHTML = 
            '<div style="text-align: center; padding: 20px; color: #888;">No tests available</div>';
        return;
    }
    
    availableTestsContainer.innerHTML = testsToRender.map(test => `
        <div class="test-item" draggable="true" data-test-id="${test.id}" ondragstart="dragStart(event)" ondragend="dragEnd(event)">
            <div class="test-name">${test.test_name}</div>
            <div>
                <span class="test-code">${test.test_code}</span>
                <span class="test-cost">$${parseFloat(test.cost || 0).toFixed(2)}</span>
            </div>
        </div>
    `).join('');
}

// Search and filter available tests
function filterAvailableTests() {
    const searchTerm = document.getElementById('testSearchInput').value.toLowerCase();
    const filteredTests = availableTests.filter(test => 
        test.test_name.toLowerCase().includes(searchTerm) || 
        test.test_code.toLowerCase().includes(searchTerm)
    );
    renderAvailableTests(filteredTests);
}

// Drag and drop functions
function dragStart(event) {
    event.target.classList.add('dragging');
    event.dataTransfer.setData('text/plain', event.target.dataset.testId);
}

function dragEnd(event) {
    event.target.classList.remove('dragging');
}

// Selected tests container setup
const selectedTestsContainer = document.getElementById('selectedTests');

selectedTestsContainer.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('drag-over');
});

selectedTestsContainer.addEventListener('dragleave', function(e) {
    this.classList.remove('drag-over');
});

selectedTestsContainer.addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('drag-over');
    
    const testId = e.dataTransfer.getData('text/plain');
    const test = availableTests.find(t => t.id == testId);
    
    if (test && !selectedTests.find(t => t.id == testId)) {
        selectedTests.push(test);
        renderSelectedTests();
        updateOrderSummary();
    }
});

// Render selected tests
function renderSelectedTests() {
    const selectedTestsContainer = document.getElementById('selectedTests');
    
    if (selectedTests.length === 0) {
        selectedTestsContainer.innerHTML = `
            <div class="empty-message">
                <i class="fas fa-arrow-left" style="font-size: 24px; margin-bottom: 10px;"></i>
                <p>Drag lab tests here</p>
            </div>
        `;
        return;
    }
    
    selectedTestsContainer.innerHTML = selectedTests.map(test => `
        <div class="test-item selected">
            <button class="remove-btn" onclick="removeSelectedTest(${test.id})">&times;</button>
            <div class="test-name">${test.test_name}</div>
            <div>
                <span class="test-code">${test.test_code}</span>
                <span class="test-cost">$${parseFloat(test.cost || 0).toFixed(2)}</span>
            </div>
        </div>
    `).join('');
}

// Remove selected test
function removeSelectedTest(testId) {
    selectedTests = selectedTests.filter(test => test.id != testId);
    renderSelectedTests();
    updateOrderSummary();
}

// Update order summary and totals
function updateOrderSummary() {
    const count = selectedTests.length;
    const total = selectedTests.reduce((sum, test) => sum + parseFloat(test.cost || 0), 0);
    
    document.getElementById('selectedCount').textContent = count;
    document.getElementById('totalCost').textContent = total.toFixed(2);
    document.getElementById('footerTotalCost').textContent = total.toFixed(2);
    
    // Enable/disable submit button
    document.getElementById('submitOrderBtn').disabled = count === 0;
}

// Submit order function
async function submitOrder() {
    const activePatient = document.querySelector('.patient-item.active');
    if (!activePatient) {
        alert('Please select a patient first');
        return;
    }
    
    const patientId = activePatient.dataset.patientId;
    const orderNotes = document.getElementById('orderNotes').value.trim();
    const priority = document.getElementById('orderPriority').value;
    
    if (selectedTests.length === 0) {
        alert('Please select at least one test');
        return;
    }
    
    const orderData = {
        patient_id: patientId,
        tests: selectedTests.map(test => ({
            test_id: test.id,
            test_code: test.test_code,
            test_name: test.test_name,
            cost: test.cost
        })),
        notes: orderNotes,
        priority: priority,
        order_type: 'lab'
    };
    
    try {
        document.getElementById('submitOrderBtn').disabled = true;
        document.getElementById('submitOrderBtn').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        
        const response = await fetch('/api/orders/lab-orders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(orderData)
        });
        
        if (response.ok) {
            const result = await response.json();
            alert('Lab order submitted successfully!');
            closeModal('newOrderModal');
            
            // Reset form
            selectedTests = [];
            renderSelectedTests();
            updateOrderSummary();
            document.getElementById('orderNotes').value = '';
            document.getElementById('orderPriority').value = 'routine';
            document.getElementById('testSearchInput').value = '';
            renderAvailableTests();
        } else {
            const error = await response.json();
            alert('Failed to submit order: ' + (error.message || response.statusText));
        }
    } catch (error) {
        console.error('Error submitting order:', error);
        alert('Error submitting order. Please try again.');
    } finally {
        document.getElementById('submitOrderBtn').disabled = false;
        document.getElementById('submitOrderBtn').innerHTML = 'Submit Order';
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Set up available tests container drag and drop prevention (to remove items)
    const availableTestsContainer = document.getElementById('availableTests');
    
    availableTestsContainer.addEventListener('dragover', function(e) {
        e.preventDefault();
    });
    
    availableTestsContainer.addEventListener('drop', function(e) {
        e.preventDefault();
        // Allow dropping back to available tests (remove from selected)
        const testId = e.dataTransfer.getData('text/plain');
        removeSelectedTest(testId);
    });
});
</script>

</body>
</html>
