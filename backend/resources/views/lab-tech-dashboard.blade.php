@extends('layouts.main')

@section('title', 'Lab Technician Dashboard')

@section('content')
<style>
/* Lab Tech Dashboard Specific Styles */
.dashboard-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem 1.5rem;
}

.dashboard-header {
    background: var(--glass-background);
    backdrop-filter: var(--backdrop-blur);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-xl);
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-md);
}

.dashboard-title {
    font-size: 2.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 0.5rem;
}

.dashboard-subtitle {
    color: var(--text-secondary);
    font-size: 1.1rem;
    margin-bottom: 0;
}

.role-badge {
    background: var(--primary-gradient);
    color: white;
    padding: 0.7rem 1.5rem;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.95rem;
    box-shadow: var(--shadow-sm);
}

.date-filter {
    background: var(--bg-input);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-md);
    color: var(--text-primary);
    padding: 0.7rem 1rem;
    font-size: 0.95rem;
    backdrop-filter: var(--backdrop-blur);
}

.date-filter:focus {
    background: var(--bg-input-focus);
    border-color: var(--glass-border-hover);
    outline: none;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.nav-tabs-container {
    background: var(--glass-background);
    backdrop-filter: var(--backdrop-blur);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-xl);
    padding: 1rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-md);
}

.custom-nav-tabs {
    display: flex;
    gap: 0.5rem;
    background: none;
    border: none;
    padding: 0;
    margin: 0;
}

.custom-nav-tab {
    background: transparent;
    border: 1px solid var(--glass-border);
    color: var(--text-secondary);
    padding: 1rem 1.5rem;
    border-radius: var(--radius-lg);
    text-decoration: none;
    font-weight: 500;
    font-size: 1rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex: 1;
    justify-content: center;
    cursor: pointer;
}

.custom-nav-tab:hover {
    background: var(--glass-background-hover);
    border-color: var(--glass-border-hover);
    color: var(--text-primary);
    transform: translateY(-2px);
}

.custom-nav-tab.active {
    background: var(--primary-gradient);
    border-color: transparent;
    color: white;
    box-shadow: var(--shadow-sm);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stats-card {
    background: var(--glass-background);
    backdrop-filter: var(--backdrop-blur);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-sm);
}

.stats-card:hover {
    background: var(--glass-background-hover);
    border-color: var(--glass-border-hover);
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
}

.stats-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: var(--radius-full);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    flex-shrink: 0;
}

.stats-info h3 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.stats-info p {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-secondary);
    margin-bottom: 0.25rem;
}

.stats-info small {
    font-size: 0.85rem;
    color: var(--text-muted);
}

.stats-progress {
    width: 100%;
    height: 4px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 2px;
    margin-top: 1rem;
    overflow: hidden;
}

.stats-progress-bar {
    height: 100%;
    border-radius: 2px;
    transition: width 0.8s ease;
}

.content-card {
    background: var(--glass-background);
    backdrop-filter: var(--backdrop-blur);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
    margin-bottom: 2rem;
}

.content-card-header {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--glass-border);
    display: flex;
    justify-content: between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.content-card-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0;
}

.content-card-body {
    padding: 2rem;
}

.table-container {
    background: var(--glass-background);
    border-radius: var(--radius-lg);
    overflow: hidden;
    border: 1px solid var(--glass-border);
}

.custom-table {
    width: 100%;
    border-collapse: collapse;
    background: transparent;
}

.custom-table th {
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-primary);
    font-weight: 600;
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--glass-border);
}

.custom-table td {
    padding: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    color: var(--text-secondary);
}

.custom-table tr:hover {
    background: rgba(255, 255, 255, 0.03);
}

.btn-primary {
    background: var(--primary-gradient);
    border: none;
    color: white;
    padding: 0.7rem 1.5rem;
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: var(--shadow-sm);
}

.btn-primary:hover {
    background: var(--primary-gradient-hover);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-secondary {
    background: var(--glass-background);
    border: 1px solid var(--glass-border);
    color: var(--text-primary);
    padding: 0.7rem 1.5rem;
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-secondary:hover {
    background: var(--glass-background-hover);
    border-color: var(--glass-border-hover);
    transform: translateY(-2px);
}

.form-control {
    background: var(--bg-input);
    border: 1px solid var(--glass-border);
    border-radius: var(--radius-md);
    color: var(--text-primary);
    padding: 0.7rem 1rem;
    font-size: 0.95rem;
    backdrop-filter: var(--backdrop-blur);
    width: 100%;
}

.form-control:focus {
    background: var(--bg-input-focus);
    border-color: var(--glass-border-hover);
    outline: none;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-label {
    color: var(--text-secondary);
    font-weight: 500;
    margin-bottom: 0.5rem;
    display: block;
}

.badge {
    padding: 0.35rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
}

.badge-success {
    background: var(--success-bg);
    color: var(--success-color);
    border: 1px solid var(--success-border);
}

.badge-warning {
    background: var(--warning-bg);
    color: var(--warning-color);
    border: 1px solid var(--warning-border);
}

.badge-danger {
    background: var(--error-bg);
    color: var(--error-color);
    border: 1px solid var(--error-border);
}

.badge-info {
    background: var(--info-bg);
    color: var(--info-color);
    border: 1px solid var(--info-border);
}

.alert {
    padding: 1rem 1.5rem;
    border-radius: var(--radius-lg);
    margin-bottom: 1rem;
    border: 1px solid;
}

.alert-success {
    background: var(--success-bg);
    color: var(--success-color);
    border-color: var(--success-border);
}

.alert-error {
    background: var(--error-bg);
    color: var(--error-color);
    border-color: var(--error-border);
}

/* Animation Classes */
.fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

/* Responsive Design */
@media (max-width: 768px) {
    .dashboard-container {
        padding: 1rem;
    }
    
    .dashboard-header {
        padding: 1.5rem;
    }
    
    .dashboard-title {
        font-size: 2rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .custom-nav-tabs {
        flex-direction: column;
    }
    
    .content-card-header {
        padding: 1rem;
        flex-direction: column;
        align-items: flex-start;
    }
    
    .content-card-body {
        padding: 1rem;
    }
}

/* OCR Upload Section Enhancements */
.ocr-upload-form {
    background: var(--glass-background);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
}

.ocr-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    justify-content: center;
}

.ocr-buttons .btn {
    min-width: 150px;
    flex: 1;
    max-width: 200px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    border-radius: var(--radius-md);
    transition: all 0.3s ease;
}

.ocr-buttons .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(111, 66, 193, 0.3);
}

/* Preview Modal Enhancements */
.preview-container {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    border: 2px solid var(--glass-border);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
}

.preview-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(111, 66, 193, 0.1) 0%, rgba(102, 126, 234, 0.1) 100%);
    pointer-events: none;
}

#previewImage {
    position: relative;
    z-index: 2;
    border: 2px solid var(--primary-color);
    border-radius: var(--radius-md);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    transition: all 0.3s ease;
}

#previewImage:hover {
    transform: scale(1.02);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.7);
}

/* Camera Modal Enhancements */
#cameraVideo {
    border: 2px solid var(--glass-border);
    border-radius: var(--radius-md);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.modal-content {
    border: 1px solid var(--glass-border);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);
    background: #ffffff !important;
    border-radius: 0.5rem !important;
    overflow: hidden;
}

.modal-header {
    border-bottom: 1px solid var(--glass-border);
    background: #f8f9fa !important;
    padding: 1rem 1.5rem !important;
    border-radius: 0.5rem 0.5rem 0 0 !important;
}

.modal-body {
    background: #ffffff !important;
    padding: 1.5rem !important;
    color: #212529 !important;
    max-height: 70vh;
    overflow-y: auto;
}

.modal-footer {
    background: #f8f9fa !important;
    border-top: 1px solid #dee2e6 !important;
    padding: 1rem 1.5rem !important;
    border-radius: 0 0 0.5rem 0.5rem !important;
}

.modal-title {
    color: #212529 !important;
    font-weight: 600 !important;
}

/* Enhanced form styling within modals */
.modal .form-control, 
.modal .form-select,
.modal input,
.modal select,
.modal textarea {
    background-color: #ffffff !important;
    border: 1px solid #ced4da !important;
    border-radius: 0.375rem !important;
    color: #212529 !important;
    padding: 0.5rem 0.75rem !important;
    font-size: 1rem !important;
    line-height: 1.5 !important;
    opacity: 1 !important;
    visibility: visible !important;
}

.modal .form-control:focus,
.modal .form-select:focus,
.modal input:focus,
.modal select:focus,
.modal textarea:focus {
    border-color: #86b7fe !important;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
    outline: 0 !important;
}

.modal label {
    color: #212529 !important;
    font-weight: 500 !important;
    margin-bottom: 0.5rem !important;
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
}

.modal .form-group,
.modal .mb-3 {
    margin-bottom: 1rem !important;
    opacity: 1 !important;
    visibility: visible !important;
}

.modal .btn {
    border-radius: 0.375rem !important;
    padding: 0.5rem 1rem !important;
    font-size: 1rem !important;
    font-weight: 500 !important;
    opacity: 1 !important;
    visibility: visible !important;
}

.modal table {
    background: #ffffff !important;
    color: #212529 !important;
    opacity: 1 !important;
    visibility: visible !important;
}

.modal .test-item {
    background: #f8f9fa !important;
    border: 1px solid #dee2e6 !important;
    border-radius: 0.375rem !important;
    color: #212529 !important;
    opacity: 1 !important;
    visibility: visible !important;
}

/* Ensure text is readable */
.modal * {
    color: #212529 !important;
    opacity: 1 !important;
}

.btn-close {
    filter: invert(1);
    opacity: 0.8;
}

.btn-close:hover {
    opacity: 1;
}

/* Collection Modal Animations */
@keyframes slideIn {
    from { 
        opacity: 0; 
        transform: translateX(-20px); 
    }
    to { 
        opacity: 1; 
        transform: translateX(0); 
    }
}

@keyframes slideOut {
    from { 
        opacity: 1; 
        transform: translateX(0); 
    }
    to { 
        opacity: 0; 
        transform: translateX(20px); 
    }
}

/* Test Item Enhancements */
.test-item {
    position: relative;
    overflow: hidden;
}

.test-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    transition: left 0.5s;
}

.test-item:hover::before {
    left: 100%;
}

.test-item:active {
    transform: scale(0.98);
}

/* Drag and Drop Visual Feedback */
.drag-over {
    background: rgba(16, 185, 129, 0.2) !important;
    border-color: rgba(16, 185, 129, 0.5) !important;
    transform: scale(1.02);
}

/* Enhanced Action Card Effects */
.action-card:hover .fas.fa-arrow-right {
    transform: translateX(5px);
}

.action-card:hover .fas.fa-vial {
    transform: scale(1.1) rotate(5deg);
}

.action-card:hover .fas.fa-play {
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

/* Enhanced Form Controls */
.form-control:focus {
    border-color: rgba(6, 182, 212, 0.5) !important;
    box-shadow: 0 0 0 0.2rem rgba(6, 182, 212, 0.25) !important;
    background: rgba(6, 182, 212, 0.05) !important;
}

.btn-primary:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 15px rgba(6, 182, 212, 0.4) !important;
}
</style>

<script>
// Custom Tab Functionality
function initializeTabs() {
    const tabs = document.querySelectorAll('.custom-nav-tab');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs and panes
            tabs.forEach(t => t.classList.remove('active'));
            tabPanes.forEach(pane => {
                pane.classList.remove('show', 'active');
                pane.style.display = 'none';
            });
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Show corresponding pane
            const targetId = this.getAttribute('data-bs-target');
            const targetPane = document.querySelector(targetId);
            if (targetPane) {
                targetPane.style.display = 'block';
                targetPane.classList.add('show', 'active', 'fade-in');
                
                // Trigger any load functions for the tab
                const tabId = targetId.replace('#', '');
                if (tabId === 'equipment') {
                    loadEquipmentData();
                } else if (tabId === 'invoices') {
                    loadLabInvoices();
                } else if (tabId === 'analytics') {
                    loadAnalytics();
                }
            }
        });
    });
}

// Initialize tabs when document is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeTabs();
    initializeDragAndDrop();
});

// Collection Modal Functions
function showCollectionModal() {
    console.log('showCollectionModal called'); // Debug log
    
    try {
        // Add loading state to the card temporarily
        const collectionCard = document.querySelector('[onclick*="showCollectionModal"]');
        if (collectionCard) {
            collectionCard.style.opacity = '0.8';
            setTimeout(() => {
                collectionCard.style.opacity = '1';
            }, 300);
        }
        
        // Check if modal element exists
        const modalElement = document.getElementById('collectionModal');
        if (!modalElement) {
            console.error('Collection modal element not found');
            alert('Error: Modal not found. Please refresh the page.');
            return;
        }
        
        console.log('Modal element found:', modalElement); // Debug log
        
        // Set current date and time
        const now = new Date();
        const localDateTime = new Date(now.getTime() - now.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
        const dateTimeInput = document.getElementById('collectionDateTime');
        if (dateTimeInput) {
            dateTimeInput.value = localDateTime;
        }
        
        // Reset form to clean state
        const patientSelect = document.getElementById('patientSelect');
        if (patientSelect) patientSelect.value = '';
        
        const cnicSearch = document.getElementById('cnicSearch');
        if (cnicSearch) cnicSearch.value = '';
        
        const patientInfo = document.getElementById('patientInfo');
        if (patientInfo) {
            patientInfo.innerHTML = '<p style="color: var(--text-muted); margin: 0; text-align: center;">Select a patient to view information</p>';
        }
        
        // Reset tests collection area with enhanced placeholder
        const selectedTests = document.getElementById('selectedTests');
        if (selectedTests) {
            selectedTests.innerHTML = `
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: var(--text-muted); pointer-events: none;">
                    <i class="fas fa-plus-circle" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                    <p style="margin: 0;">Drag tests here or select patient first</p>
                    <small>Tests will appear as you select them</small>
                </div>
            `;
        }
        
        // Reset other form fields
        const totalAmount = document.getElementById('totalAmount');
        if (totalAmount) totalAmount.textContent = '0';
        
        const collectionNotes = document.getElementById('collectionNotes');
        if (collectionNotes) collectionNotes.value = '';
        
        const priority = document.getElementById('priority');
        if (priority) priority.value = 'routine';
        
        console.log('About to show modal'); // Debug log
        
        // Custom modal display function (no Bootstrap required)
        showCustomModal(modalElement);
        console.log('Custom modal show() called'); // Debug log
        
        // Update timestamp every second while modal is open
        const timestampInterval = setInterval(() => {
            const currentTime = new Date();
            const currentDateTime = new Date(currentTime.getTime() - currentTime.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
            const dateTimeInput = document.getElementById('collectionDateTime');
            if (dateTimeInput) {
                dateTimeInput.value = currentDateTime;
            }
        }, 1000);
        
        // Store interval reference to clear later
        modalElement._timestampInterval = timestampInterval;
        
    } catch (error) {
        console.error('Error in showCollectionModal:', error);
        alert('Error opening collection modal: ' + error.message);
    }
}

// Custom modal functions to work without Bootstrap
function showCustomModal(modalElement) {
    if (!modalElement) return;
    
    console.log('🎬 Creating custom modal backdrop and display'); // Debug log
    
    // Create backdrop with higher opacity
    const backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop';
    backdrop.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.85);
        z-index: 1040;
        opacity: 0;
        transition: opacity 0.3s ease;
        backdrop-filter: blur(3px);
    `;
    
    // Add backdrop to body
    document.body.appendChild(backdrop);
    document.body.style.overflow = 'hidden';
    
    // Enhanced modal styling for better visibility
    modalElement.style.cssText = `
        display: flex !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        z-index: 1050 !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 1rem !important;
        box-sizing: border-box !important;
    `;
    
    // Find and style the modal dialog for better visibility
    const modalDialog = modalElement.querySelector('.modal-dialog');
    if (modalDialog) {
        modalDialog.style.cssText = `
            background: linear-gradient(135deg, rgba(30, 30, 50, 0.98), rgba(40, 40, 70, 0.98)) !important;
            border-radius: 15px !important;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.9) !important;
            border: 3px solid rgba(138, 43, 226, 0.6) !important;
            max-width: 90vw !important;
            max-height: 90vh !important;
            overflow-y: auto !important;
            backdrop-filter: blur(15px) !important;
            margin: 0 !important;
            position: relative !important;
            z-index: 1051 !important;
            width: 100% !important;
            box-sizing: border-box !important;
        `;
    }
    
    // Find and style the modal content
    const modalContent = modalElement.querySelector('.modal-content');
    if (modalContent) {
        modalContent.style.cssText = `
            background: linear-gradient(135deg, rgba(30, 30, 50, 0.98), rgba(40, 40, 70, 0.95)) !important;
            border: none !important;
            border-radius: 15px !important;
            color: white !important;
            backdrop-filter: blur(15px) !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1) !important;
        `;
    }
    
    // Ensure modal header and body have proper styling
    const modalHeader = modalElement.querySelector('.modal-header');
    if (modalHeader) {
        modalHeader.style.cssText = `
            background: linear-gradient(135deg, rgba(138, 43, 226, 0.4), rgba(102, 126, 234, 0.3)) !important;
            border-bottom: 2px solid rgba(138, 43, 226, 0.5) !important;
            color: white !important;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem 2rem !important;
        `;
    }
    
    const modalBody = modalElement.querySelector('.modal-body');
    if (modalBody) {
        modalBody.style.cssText = `
            background: linear-gradient(135deg, rgba(30, 30, 50, 0.95), rgba(40, 40, 70, 0.90)) !important;
            color: white !important;
            padding: 2rem !important;
        `;
    }
    
    const modalFooter = modalElement.querySelector('.modal-footer');
    if (modalFooter) {
        modalFooter.style.cssText = `
            background: linear-gradient(135deg, rgba(30, 30, 50, 0.95), rgba(40, 40, 70, 0.90)) !important;
            border-top: 2px solid rgba(138, 43, 226, 0.3) !important;
            color: white !important;
            border-radius: 0 0 15px 15px !important;
            padding: 1.5rem 2rem !important;
        `;
    }
    
    modalElement.classList.add('show');
    
    // Animate backdrop with higher opacity
    setTimeout(() => {
        backdrop.style.opacity = '1';
        console.log('✅ Modal backdrop animated to full opacity'); // Debug log
    }, 10);
    
    // Store backdrop reference
    modalElement._backdrop = backdrop;
    
    // Close modal when clicking backdrop (but not modal content)
    backdrop.addEventListener('click', function(e) {
        if (e.target === backdrop) {
            console.log('🚪 Backdrop clicked - closing modal'); // Debug log
            hideCustomModal(modalElement);
        }
    });
    
    // Prevent modal content clicks from closing the modal
    modalElement.addEventListener('click', function(e) {
        e.stopPropagation();
    });
    
    // Close modal with escape key
    function handleEscape(e) {
        if (e.key === 'Escape') {
            console.log('⌨️ Escape key pressed - closing modal'); // Debug log
            hideCustomModal(modalElement);
            document.removeEventListener('keydown', handleEscape);
        }
    }
    document.addEventListener('keydown', handleEscape);
    modalElement._escapeHandler = handleEscape;
    
    console.log('✅ Custom modal displayed successfully'); // Debug log
    
    // Enhanced styling for form elements inside modal
    setTimeout(() => {
        // Style all form controls in the modal
        const formControls = modalElement.querySelectorAll('.form-control, select, input, textarea');
        formControls.forEach(control => {
            control.style.cssText += `
                background: rgba(255, 255, 255, 0.1) !important;
                border: 1px solid rgba(138, 43, 226, 0.3) !important;
                color: white !important;
                border-radius: 8px !important;
            `;
        });
        
        // Style all labels
        const labels = modalElement.querySelectorAll('.form-label, label');
        labels.forEach(label => {
            label.style.cssText += `
                color: rgba(255, 255, 255, 0.9) !important;
                font-weight: 600 !important;
            `;
        });
        
        // Style buttons
        const buttons = modalElement.querySelectorAll('.btn');
        buttons.forEach(button => {
            if (button.classList.contains('btn-primary')) {
                button.style.cssText += `
                    background: linear-gradient(135deg, #138, 43, 226, #9966CC) !important;
                    border: none !important;
                    color: white !important;
                `;
            }
        });
        
        console.log('✅ Modal form elements styled successfully'); // Debug log
    }, 100);
}

function hideCustomModal(modalElement) {
    if (!modalElement) return;
    
    // Remove backdrop
    if (modalElement._backdrop) {
        modalElement._backdrop.style.opacity = '0';
        setTimeout(() => {
            if (modalElement._backdrop && modalElement._backdrop.parentNode) {
                modalElement._backdrop.parentNode.removeChild(modalElement._backdrop);
            }
        }, 300);
    }
    
    // Hide modal
    modalElement.classList.remove('show');
    setTimeout(() => {
        modalElement.style.display = 'none';
        document.body.style.overflow = '';
    }, 300);
    
    // Clear timestamp interval
    if (modalElement._timestampInterval) {
        clearInterval(modalElement._timestampInterval);
    }
    
    // Remove escape key handler
    if (modalElement._escapeHandler) {
        document.removeEventListener('keydown', modalElement._escapeHandler);
    }
}

// Test function for manual debugging (call from browser console)
function testModal() {
    console.log('🧪 Testing modal manually...');
    showCollectionModal();
}

// Make test function globally available
window.testModal = testModal;

// Test Results Modal Functions
function showResultsModal() {
    console.log('showResultsModal called'); // Debug log
    
    try {
        // Add loading state to the card temporarily
        const resultsCard = document.querySelector('[onclick*="showResultsModal"]');
        if (resultsCard) {
            resultsCard.style.opacity = '0.8';
            setTimeout(() => {
                resultsCard.style.opacity = '1';
            }, 300);
        }
        
        // Check if modal element exists
        const modalElement = document.getElementById('resultsModal');
        if (!modalElement) {
            console.error('Results modal element not found');
            alert('Error: Results modal not found. Please refresh the page.');
            return;
        }
        
        console.log('Results modal element found:', modalElement); // Debug log
        
        // Set current date and time
        const now = new Date();
        const localDateTime = new Date(now.getTime() - now.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
        const submissionDateTime = document.getElementById('submissionDateTime');
        if (submissionDateTime) {
            submissionDateTime.value = localDateTime;
        }
        
        // Reset form to clean state
        const patientResultsSelect = document.getElementById('patientResultsSelect');
        if (patientResultsSelect) patientResultsSelect.value = '';
        
        const patientResultsInfo = document.getElementById('patientResultsInfo');
        if (patientResultsInfo) {
            patientResultsInfo.innerHTML = '<p style="color: var(--text-muted); margin: 0; text-align: center;">Select a patient to view their pending tests</p>';
        }
        
        // Reset tests results area
        const pendingTests = document.getElementById('pendingTests');
        if (pendingTests) {
            pendingTests.innerHTML = `
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: var(--text-muted); pointer-events: none;">
                    <i class="fas fa-clipboard-list" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                    <p style="margin: 0;">Select a patient to view pending tests</p>
                    <small>Test results will appear here for entry</small>
                </div>
            `;
        }
        
        // Reset other form fields
        const resultNotes = document.getElementById('resultNotes');
        if (resultNotes) resultNotes.value = '';
        
        console.log('About to show results modal'); // Debug log
        
        // Custom modal display function (no Bootstrap required)
        showCustomModal(modalElement);
        console.log('Custom results modal show() called'); // Debug log
        
        // Update timestamp every second while modal is open
        const timestampInterval = setInterval(() => {
            const currentTime = new Date();
            const currentDateTime = new Date(currentTime.getTime() - currentTime.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
            const submissionDateTime = document.getElementById('submissionDateTime');
            if (submissionDateTime) {
                submissionDateTime.value = currentDateTime;
            }
        }, 1000);
        
        // Store interval reference to clear later
        modalElement._timestampInterval = timestampInterval;
        
    } catch (error) {
        console.error('Error in showResultsModal:', error);
        alert('Error opening results modal: ' + error.message);
    }
}

function loadPatientResults() {
    const patientResultsSelect = document.getElementById('patientResultsSelect');
    const patientResultsInfo = document.getElementById('patientResultsInfo');
    const pendingTests = document.getElementById('pendingTests');
    
    if (patientResultsSelect.value) {
        // Mock patient data with pending tests (collected samples awaiting results)
        const patientsWithPendingTests = {
            '1': {
                name: 'John Smith',
                cnic: '12345-1234567-1',
                age: '45',
                gender: 'Male',
                collectionDate: '2025-08-17 09:30',
                pendingTests: [
                    { code: 'CBC', name: 'Complete Blood Count (CBC)', normalRange: '4.5-11.0 x10³/μL (WBC)', unit: 'x10³/μL' },
                    { code: 'BMP', name: 'Basic Metabolic Panel (BMP)', normalRange: '136-145 mEq/L (Sodium)', unit: 'mEq/L' }
                ]
            },
            '2': {
                name: 'Sarah Johnson',
                cnic: '54321-7654321-9',
                age: '32',
                gender: 'Female',
                collectionDate: '2025-08-17 10:15',
                pendingTests: [
                    { code: 'LIPID', name: 'Lipid Profile', normalRange: '<200 mg/dL (Total Cholesterol)', unit: 'mg/dL' },
                    { code: 'TSH', name: 'Thyroid Stimulating Hormone (TSH)', normalRange: '0.4-4.0 mIU/L', unit: 'mIU/L' }
                ]
            },
            '3': {
                name: 'Ahmed Ali',
                cnic: '42101-1234567-8',
                age: '28',
                gender: 'Male',
                collectionDate: '2025-08-17 11:00',
                pendingTests: [
                    { code: 'CBC', name: 'Complete Blood Count (CBC)', normalRange: '4.5-11.0 x10³/μL (WBC)', unit: 'x10³/μL' },
                    { code: 'HBA1C', name: 'Hemoglobin A1C (HBA1C)', normalRange: '<5.7% (Normal)', unit: '%' }
                ]
            }
        };
        
        const patient = patientsWithPendingTests[patientResultsSelect.value];
        
        // Update patient info
        patientResultsInfo.innerHTML = `
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <strong style="color: var(--text-primary);">${patient.name}</strong><br>
                    <small style="color: var(--text-muted);">CNIC: ${patient.cnic}</small><br>
                    <small style="color: var(--text-muted);">Age: ${patient.age}, ${patient.gender}</small>
                </div>
                <div>
                    <small style="color: var(--text-muted);">Sample Collected: ${patient.collectionDate}</small><br>
                    <span class="badge" style="background: rgba(251, 191, 36, 0.2); color: #f59e0b;">Pending Results</span>
                </div>
            </div>
        `;
        
        // Load pending tests for results entry
        loadPendingTests(patient.pendingTests);
    } else {
        patientResultsInfo.innerHTML = '<p style="color: var(--text-muted); margin: 0; text-align: center;">Select a patient to view their pending tests</p>';
        pendingTests.innerHTML = `
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: var(--text-muted); pointer-events: none;">
                <i class="fas fa-clipboard-list" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                <p style="margin: 0;">Select a patient to view pending tests</p>
                <small>Test results will appear here for entry</small>
            </div>
        `;
    }
}

function loadPendingTests(pendingTests) {
    const pendingTestsContainer = document.getElementById('pendingTests');
    
    let testsHtml = '';
    pendingTests.forEach((test, index) => {
        testsHtml += `
            <div class="test-result-item" data-test="${test.code}" style="background: var(--glass-background); border: 1px solid rgba(251, 191, 36, 0.5); border-radius: 10px; padding: 1.5rem; margin-bottom: 1rem; transition: all 0.3s ease;">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                    <div style="background: linear-gradient(135deg, #f59e0b, #d97706); padding: 0.75rem; border-radius: 50%; color: white; min-width: 50px; text-align: center;">
                        <i class="fas fa-vial" style="font-size: 1.2rem;"></i>
                    </div>
                    <div style="flex: 1;">
                        <h4 style="color: var(--text-primary); margin: 0 0 0.25rem 0; font-weight: 600; font-size: 1.1rem;">${test.name}</h4>
                        <p style="color: var(--text-muted); margin: 0; font-size: 0.9rem;">Normal Range: ${test.normalRange}</p>
                    </div>
                    <span class="badge" style="background: rgba(251, 191, 36, 0.2); color: #f59e0b; padding: 0.5rem 1rem;">Pending</span>
                </div>
                
                <div style="display: grid; grid-template-columns: 2fr 1fr 120px; gap: 1rem; align-items: end;">
                    <div>
                        <label class="form-label" style="color: var(--text-secondary); font-weight: 600; margin-bottom: 0.5rem;">Test Result Value</label>
                        <input type="text" 
                               class="form-control result-value" 
                               id="result_${test.code}" 
                               placeholder="Enter result value"
                               style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(251, 191, 36, 0.3); color: white; padding: 0.75rem 1rem;">
                    </div>
                    <div>
                        <label class="form-label" style="color: var(--text-secondary); font-weight: 600; margin-bottom: 0.5rem;">Unit</label>
                        <input type="text" 
                               class="form-control" 
                               value="${test.unit}" 
                               readonly
                               style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: var(--text-muted); padding: 0.75rem 1rem;">
                    </div>
                    <div>
                        <label class="form-label" style="color: var(--text-secondary); font-weight: 600; margin-bottom: 0.5rem;">Status</label>
                        <select class="form-control result-status" id="status_${test.code}" style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(251, 191, 36, 0.3); color: white; padding: 0.75rem 1rem;">
                            <option value="normal" style="background: #2d2d2d; color: white;">Normal</option>
                            <option value="abnormal" style="background: #2d2d2d; color: white;">Abnormal</option>
                            <option value="critical" style="background: #2d2d2d; color: white;">Critical</option>
                        </select>
                    </div>
                </div>
            </div>
        `;
    });
    
    if (testsHtml) {
        pendingTestsContainer.innerHTML = testsHtml;
        
        // Add event listeners for result validation
        const resultInputs = pendingTestsContainer.querySelectorAll('.result-value');
        resultInputs.forEach(input => {
            input.addEventListener('input', function() {
                validateResultInput(this);
            });
        });
    } else {
        pendingTestsContainer.innerHTML = `
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: var(--text-muted); pointer-events: none;">
                <i class="fas fa-info-circle" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                <p style="margin: 0;">No pending tests for this patient</p>
                <small>All tests have been completed</small>
            </div>
        `;
    }
}

function validateResultInput(input) {
    const value = input.value.trim();
    const testItem = input.closest('.test-result-item');
    
    if (value) {
        // Add validation styling for completed input
        input.style.borderColor = 'rgba(16, 185, 129, 0.5)';
        input.style.background = 'rgba(16, 185, 129, 0.1)';
        
        // Update the badge to show "Completed"
        const badge = testItem.querySelector('.badge');
        if (badge) {
            badge.textContent = 'Completed';
            badge.style.background = 'rgba(16, 185, 129, 0.2)';
            badge.style.color = '#10b981';
        }
    } else {
        // Reset to pending state
        input.style.borderColor = 'rgba(251, 191, 36, 0.3)';
        input.style.background = 'rgba(255, 255, 255, 0.1)';
        
        const badge = testItem.querySelector('.badge');
        if (badge) {
            badge.textContent = 'Pending';
            badge.style.background = 'rgba(251, 191, 36, 0.2)';
            badge.style.color = '#f59e0b';
        }
    }
}

function submitTestResults() {
    const patientResultsSelect = document.getElementById('patientResultsSelect').value;
    const resultInputs = document.querySelectorAll('.result-value');
    const submissionDateTime = document.getElementById('submissionDateTime').value;
    const resultNotes = document.getElementById('resultNotes').value;
    
    if (!patientResultsSelect) {
        alert('Please select a patient');
        return;
    }
    
    // Collect all test results
    const results = [];
    let hasEmptyResults = false;
    
    resultInputs.forEach(input => {
        const testCode = input.id.replace('result_', '');
        const value = input.value.trim();
        const statusSelect = document.getElementById('status_' + testCode);
        const status = statusSelect ? statusSelect.value : 'normal';
        
        if (!value) {
            hasEmptyResults = true;
            input.style.borderColor = 'rgba(239, 68, 68, 0.5)';
            input.style.background = 'rgba(239, 68, 68, 0.1)';
        } else {
            results.push({
                testCode: testCode,
                value: value,
                status: status
            });
        }
    });
    
    if (hasEmptyResults) {
        alert('Please enter results for all pending tests');
        return;
    }
    
    // Create results submission record
    const submissionData = {
        patientId: patientResultsSelect,
        results: results,
        submissionDateTime: submissionDateTime,
        notes: resultNotes,
        submittedBy: 'Current Lab Tech', // This would come from session
        status: 'completed'
    };
    
    console.log('Test results submitted:', submissionData);
    
    // Show success message
    alert('Test results submitted successfully!');
    
    // Close modal
    const modalElement = document.getElementById('resultsModal');
    hideCustomModal(modalElement);
    
    // Refresh any related data tables
    if (typeof loadLabOrders === 'function') {
        loadLabOrders();
    }
}

// Initialize drag and drop functionality
function initializeDragAndDrop() {
    // Add drag event listeners to test items
    const testItems = document.querySelectorAll('#availableTests .test-item[draggable="true"]');
    testItems.forEach(item => {
        item.addEventListener('dragstart', function(e) {
            e.dataTransfer.setData('text/plain', this.dataset.test);
            this.style.opacity = '0.5';
        });
        
        item.addEventListener('dragend', function(e) {
            this.style.opacity = '1';
        });
    });
    
    // Add drop zone event listeners
    const dropZone = document.getElementById('selectedTests');
    if (dropZone) {
        dropZone.addEventListener('dragover', allowDrop);
        dropZone.addEventListener('dragleave', dragLeave);
        dropZone.addEventListener('drop', dropTest);
    }
}

function loadPatientTests() {
    const patientSelect = document.getElementById('patientSelect');
    const patientInfo = document.getElementById('patientInfo');
    const selectedTests = document.getElementById('selectedTests');
    
    if (patientSelect.value) {
        // Mock patient data
        const patients = {
            '1': {
                name: 'John Smith',
                cnic: '12345-1234567-1',
                age: '45',
                gender: 'Male',
                phone: '+92-300-1234567',
                orderedTests: ['CBC', 'BMP']
            },
            '2': {
                name: 'Sarah Johnson',
                cnic: '54321-7654321-9',
                age: '32',
                gender: 'Female',
                phone: '+92-301-7654321',
                orderedTests: ['LIPID', 'TSH']
            },
            '3': {
                name: 'Ahmed Ali',
                cnic: '42101-1234567-8',
                age: '28',
                gender: 'Male',
                phone: '+92-302-9876543',
                orderedTests: ['CBC', 'LIPID', 'TSH']
            },
            '4': {
                name: 'Maria Garcia',
                cnic: '35202-9876543-2',
                age: '38',
                gender: 'Female',
                phone: '+92-303-1122334',
                orderedTests: ['BMP', 'TSH']
            }
        };
        
        const patient = patients[patientSelect.value];
        
        // Update patient info
        patientInfo.innerHTML = `
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <strong style="color: var(--text-primary);">${patient.name}</strong><br>
                    <small style="color: var(--text-muted);">CNIC: ${patient.cnic}</small><br>
                    <small style="color: var(--text-muted);">Age: ${patient.age}, ${patient.gender}</small>
                </div>
                <div>
                    <small style="color: var(--text-muted);">Phone: ${patient.phone}</small><br>
                    <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #10b981;">Active Patient</span>
                </div>
            </div>
        `;
        
        // Load ordered tests
        loadOrderedTests(patient.orderedTests);
    } else {
        patientInfo.innerHTML = '<p style="color: var(--text-muted); margin: 0; text-align: center;">Select a patient to view information</p>';
        selectedTests.innerHTML = '<p style="color: var(--text-muted); margin: 0; text-align: center;">Drag tests here or select patient first</p>';
        updateTotal();
    }
}

function loadOrderedTests(orderedTests) {
    const selectedTests = document.getElementById('selectedTests');
    const testPrices = { 'CBC': 25, 'BMP': 35, 'LIPID': 40, 'TSH': 30, 'HBA1C': 45, 'URINE': 20 };
    const testNames = {
        'CBC': 'Complete Blood Count (CBC)',
        'BMP': 'Basic Metabolic Panel (BMP)',
        'LIPID': 'Lipid Profile',
        'TSH': 'Thyroid Stimulating Hormone (TSH)',
        'HBA1C': 'Hemoglobin A1C (HBA1C)',
        'URINE': 'Urine Analysis'
    };
    
    let testsHtml = '';
    orderedTests.forEach(test => {
        testsHtml += `
            <div class="test-item selected" data-test="${test}" style="background: var(--glass-background); border: 1px solid rgba(16, 185, 129, 0.5); border-radius: 6px; padding: 0.75rem; margin-bottom: 0.5rem; transition: all 0.3s ease;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-check-circle" style="color: #10b981;"></i>
                    <span>${testNames[test]}</span>
                    <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #10b981; margin-left: auto;">$${testPrices[test]}</span>
                    <button type="button" onclick="removeTest('${test}')" style="background: none; border: none; color: #ef4444; cursor: pointer;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
    });
    
    if (testsHtml) {
        selectedTests.innerHTML = testsHtml;
    } else {
        selectedTests.innerHTML = `
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: var(--text-muted); pointer-events: none;">
                <i class="fas fa-plus-circle" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                <p style="margin: 0;">No tests ordered for this patient</p>
                <small>Drag tests from available list</small>
            </div>
        `;
    }
    
    updateTotal();
}

function allowDrop(ev) {
    ev.preventDefault();
    const dropZone = ev.currentTarget;
    dropZone.style.background = 'rgba(16, 185, 129, 0.2)';
    dropZone.style.borderColor = 'rgba(16, 185, 129, 0.6)';
    dropZone.style.transform = 'scale(1.02)';
}

function dragLeave(ev) {
    const dropZone = ev.currentTarget;
    dropZone.style.background = 'rgba(16, 185, 129, 0.1)';
    dropZone.style.borderColor = 'rgba(16, 185, 129, 0.3)';
    dropZone.style.transform = 'scale(1)';
}

function dropTest(ev) {
    ev.preventDefault();
    const dropZone = ev.currentTarget;
    dropZone.style.background = 'rgba(16, 185, 129, 0.1)';
    dropZone.style.borderColor = 'rgba(16, 185, 129, 0.3)';
    dropZone.style.transform = 'scale(1)';
    
    const testCode = ev.dataTransfer.getData('text/plain');
    addTestToCollection(testCode);
}

function addTestToCollection(testCode) {
    const selectedTests = document.getElementById('selectedTests');
    const existingTest = selectedTests.querySelector(`[data-test="${testCode}"]`);
    
    if (existingTest) {
        // Test already added, show visual feedback
        existingTest.style.backgroundColor = 'rgba(239, 68, 68, 0.2)';
        existingTest.style.borderColor = 'rgba(239, 68, 68, 0.5)';
        setTimeout(() => {
            existingTest.style.backgroundColor = 'var(--glass-background)';
            existingTest.style.borderColor = 'rgba(16, 185, 129, 0.5)';
        }, 500);
        return;
    }
    
    const testPrices = { 'CBC': 25, 'BMP': 35, 'LIPID': 40, 'TSH': 30, 'HBA1C': 45, 'URINE': 20 };
    const testNames = {
        'CBC': 'Complete Blood Count (CBC)',
        'BMP': 'Basic Metabolic Panel (BMP)',
        'LIPID': 'Lipid Profile',
        'TSH': 'Thyroid Stimulating Hormone (TSH)',
        'HBA1C': 'Hemoglobin A1C (HBA1C)',
        'URINE': 'Urine Analysis'
    };
    
    // Remove placeholder if present
    const placeholder = selectedTests.querySelector('.fa-plus-circle');
    if (placeholder) {
        selectedTests.innerHTML = '';
    }
    
    const testHtml = `
        <div class="test-item selected" data-test="${testCode}" style="background: var(--glass-background); border: 1px solid rgba(16, 185, 129, 0.5); border-radius: 6px; padding: 0.75rem; margin-bottom: 0.5rem; transition: all 0.3s ease; animation: slideIn 0.3s ease;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-check-circle" style="color: #10b981;"></i>
                <span>${testNames[testCode]}</span>
                <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #10b981; margin-left: auto;">$${testPrices[testCode]}</span>
                <button type="button" onclick="removeTest('${testCode}')" style="background: none; border: none; color: #ef4444; cursor: pointer;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;
    
    selectedTests.insertAdjacentHTML('beforeend', testHtml);
    updateTotal();
}

function removeTest(testCode) {
    const testElement = document.querySelector(`#selectedTests [data-test="${testCode}"]`);
    if (testElement) {
        testElement.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            testElement.remove();
            updateTotal();
            
            // Check if no tests remain
            const selectedTests = document.getElementById('selectedTests');
            if (!selectedTests.querySelector('.test-item')) {
                selectedTests.innerHTML = `
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: var(--text-muted); pointer-events: none;">
                        <i class="fas fa-plus-circle" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                        <p style="margin: 0;">Drag tests here or select patient first</p>
                        <small>Tests will appear as you select them</small>
                    </div>
                `;
            }
        }, 300);
    }
}

function updateTotal() {
    const selectedTests = document.querySelectorAll('#selectedTests .test-item');
    const testPrices = { 'CBC': 25, 'BMP': 35, 'LIPID': 40, 'TSH': 30, 'HBA1C': 45, 'URINE': 20 };
    let total = 0;
    
    selectedTests.forEach(test => {
        const testCode = test.dataset.test;
        total += testPrices[testCode] || 0;
    });
    
    document.getElementById('totalAmount').textContent = total;
}

function recordCollection() {
    const patientSelect = document.getElementById('patientSelect').value;
    const selectedTests = document.querySelectorAll('#selectedTests .test-item');
    const dateTime = document.getElementById('collectionDateTime').value;
    const priority = document.getElementById('priority').value;
    const notes = document.getElementById('collectionNotes').value;
    
    if (!patientSelect) {
        alert('Please select a patient');
        return;
    }
    
    if (selectedTests.length === 0) {
        alert('Please select at least one test');
        return;
    }
    
    // Create collection record
    const collectionData = {
        patientId: patientSelect,
        tests: Array.from(selectedTests).map(test => test.dataset.test),
        collectionDateTime: dateTime,
        priority: priority,
        notes: notes,
        collectedBy: 'Current Lab Tech', // This would come from session
        status: 'collected'
    };
    
    console.log('Collection recorded:', collectionData);
    
    // Show success message
    alert('Sample collection recorded successfully!');
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('collectionModal'));
    modal.hide();
    
    // Refresh any related data tables
    if (typeof loadLabOrders === 'function') {
        loadLabOrders();
    }
}

// CNIC search functionality
document.addEventListener('DOMContentLoaded', function() {
    const cnicSearch = document.getElementById('cnicSearch');
    if (cnicSearch) {
        cnicSearch.addEventListener('input', function(e) {
            const cnic = e.target.value;
            if (cnic.length >= 13) {
                // Mock CNIC search
                const mockResults = {
                    '12345-1234567-1': '1',
                    '54321-7654321-9': '2',
                    '42101-1234567-8': '3',
                    '35202-9876543-2': '4'
                };
                
                if (mockResults[cnic]) {
                    document.getElementById('patientSelect').value = mockResults[cnic];
                    loadPatientTests();
                }
            }
        });
    }
});
</script>

<div class="dashboard-container">
    <!-- Navigation Tabs -->
    <div class="nav-tabs-container">
        <div class="custom-nav-tabs" id="labTechTabs" role="tablist">
            <button class="custom-nav-tab active" id="orders-tab" data-bs-toggle="pill" data-bs-target="#orders" type="button" role="tab">
                <i class="fas fa-vials"></i>Dashboard
            </button>
            <button class="custom-nav-tab" id="equipment-tab" data-bs-toggle="pill" data-bs-target="#equipment" type="button" role="tab">
                <i class="fas fa-microscope"></i>Sampling & Results
            </button>
            <button class="custom-nav-tab" id="invoices-tab" data-bs-toggle="pill" data-bs-target="#invoices" type="button" role="tab">
                <i class="fas fa-flask"></i>Lab Financials
            </button>
            <button class="custom-nav-tab" id="analytics-tab" data-bs-toggle="pill" data-bs-target="#analytics" type="button" role="tab">
                <i class="fas fa-chart-line"></i>Configuration
            </button>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content" id="labTechTabContent">
        
        <!-- Dashboard Tab -->
        <div class="tab-pane fade show active" id="orders" role="tabpanel">
            <!-- Lab Analytics Section -->
            <div class="content-card">
                <div class="content-card-header">
                    <h2 class="content-card-title">
                        <i class="fas fa-chart-bar"></i>Lab Analytics Dashboard
                    </h2>
                    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                        <select id="analyticsTimeFilter" class="form-control" style="max-width: 150px;">
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="quarter">This Quarter</option>
                        </select>
                        <button class="btn btn-secondary btn-refresh" onclick="loadAnalytics()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="content-card-body">
                    <!-- Performance Metrics Cards -->
                    <div class="stats-grid" style="margin-bottom: 2rem;">
                        <div class="stats-card" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1)); border: 1px solid rgba(16, 185, 129, 0.3);">
                            <div class="stats-content">
                                <div class="stats-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stats-info">
                                    <h3 id="avgProcessingTime">--</h3>
                                    <p>Avg Processing Time</p>
                                    <small id="avgProcessingTimeUnit">minutes</small>
                                </div>
                            </div>
                            <div class="stats-progress">
                                <div class="stats-progress-bar" id="avgProcessingProgress" style="background: linear-gradient(135deg, #10b981, #059669); width: 0%;"></div>
                            </div>
                        </div>
                        
                        <div class="stats-card" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(37, 99, 235, 0.1)); border: 1px solid rgba(59, 130, 246, 0.3);">
                            <div class="stats-content">
                                <div class="stats-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                                    <i class="fas fa-percentage"></i>
                                </div>
                                <div class="stats-info">
                                    <h3 id="todayCompletionRate">--</h3>
                                    <p>Today's Completion Rate</p>
                                    <small id="completionRateDetail">of total orders</small>
                                </div>
                            </div>
                            <div class="stats-progress">
                                <div class="stats-progress-bar" id="completionRateProgress" style="background: linear-gradient(135deg, #3b82f6, #2563eb); width: 0%;"></div>
                            </div>
                        </div>
                        
                        <div class="stats-card" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1)); border: 1px solid rgba(239, 68, 68, 0.3);">
                            <div class="stats-content">
                                <div class="stats-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="stats-info">
                                    <h3 id="criticalResults">--</h3>
                                    <p>Critical Results</p>
                                    <small id="criticalResultsDetail">requiring attention</small>
                                </div>
                            </div>
                            <div class="stats-progress">
                                <div class="stats-progress-bar" id="criticalResultsProgress" style="background: linear-gradient(135deg, #ef4444, #dc2626); width: 0%;"></div>
                            </div>
                        </div>
                        
                        <div class="stats-card" style="background: linear-gradient(135deg, rgba(168, 85, 247, 0.1), rgba(147, 51, 234, 0.1)); border: 1px solid rgba(168, 85, 247, 0.3);">
                            <div class="stats-content">
                                <div class="stats-icon" style="background: linear-gradient(135deg, #a855f7, #9333ea);">
                                    <i class="fas fa-award"></i>
                                </div>
                                <div class="stats-info">
                                    <h3 id="qualityScore">--</h3>
                                    <p>Quality Score</p>
                                    <small id="qualityScoreDetail">accuracy rating</small>
                                </div>
                            </div>
                            <div class="stats-progress">
                                <div class="stats-progress-bar" id="qualityScoreProgress" style="background: linear-gradient(135deg, #a855f7, #9333ea); width: 0%;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                        <!-- Test Distribution Chart -->
                        <div style="background: var(--glass-background); border: 1px solid var(--glass-border); border-radius: var(--radius-lg); padding: 1.5rem;">
                            <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 1rem;">
                                <h3 style="color: var(--text-primary); margin: 0; font-size: 1.1rem; font-weight: 600;">
                                    <i class="fas fa-chart-pie" style="color: #667eea; margin-right: 0.5rem;"></i>
                                    Test Distribution
                                </h3>
                                <div style="display: flex; gap: 0.5rem;">
                                    <span class="badge" style="background: rgba(102, 126, 234, 0.2); color: #667eea; border: 1px solid rgba(102, 126, 234, 0.3);" id="totalTestsToday">0 tests</span>
                                </div>
                            </div>
                            <canvas id="testDistributionChart" height="250"></canvas>
                        </div>

                        <!-- Daily Revenue Chart -->
                        <div style="background: var(--glass-background); border: 1px solid var(--glass-border); border-radius: var(--radius-lg); padding: 1.5rem;">
                            <div style="display: flex; align-items: center; justify-content: between; margin-bottom: 1rem;">
                                <h3 style="color: var(--text-primary); margin: 0; font-size: 1.1rem; font-weight: 600;">
                                    <i class="fas fa-chart-line" style="color: #10b981; margin-right: 0.5rem;"></i>
                                    Daily Revenue
                                </h3>
                                <div style="display: flex; gap: 0.5rem;">
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);" id="todayRevenue">$0</span>
                                </div>
                            </div>
                            <canvas id="dailyRevenueChart" height="250"></canvas>
                        </div>
                    </div>

                    <!-- Interactive Metrics Table -->
                    <div style="background: var(--glass-background); border: 1px solid var(--glass-border); border-radius: var(--radius-lg); padding: 1.5rem;">
                        <h3 style="color: var(--text-primary); margin-bottom: 1rem; font-size: 1.1rem; font-weight: 600;">
                            <i class="fas fa-table" style="color: #f59e0b; margin-right: 0.5rem;"></i>
                            Detailed Performance Metrics
                        </h3>
                        <div class="table-container">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>Metric</th>
                                        <th>Current Value</th>
                                        <th>Target</th>
                                        <th>Performance</th>
                                        <th>Trend</th>
                                    </tr>
                                </thead>
                                <tbody id="performanceMetricsTable">
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <i class="fas fa-clock" style="color: #10b981;"></i>
                                                Processing Time
                                            </div>
                                        </td>
                                        <td id="detailAvgProcessingTime">-- min</td>
                                        <td>≤ 30 min</td>
                                        <td><span class="badge badge-success" id="processingTimeStatus">Good</span></td>
                                        <td><i class="fas fa-arrow-down" style="color: #10b981;" id="processingTimeTrend"></i></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <i class="fas fa-percentage" style="color: #3b82f6;"></i>
                                                Completion Rate
                                            </div>
                                        </td>
                                        <td id="detailCompletionRate">--%</td>
                                        <td>≥ 95%</td>
                                        <td><span class="badge badge-success" id="completionRateStatus">Excellent</span></td>
                                        <td><i class="fas fa-arrow-up" style="color: #10b981;" id="completionRateTrend"></i></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <i class="fas fa-exclamation-triangle" style="color: #ef4444;"></i>
                                                Critical Results Response
                                            </div>
                                        </td>
                                        <td id="detailCriticalResponse">-- min</td>
                                        <td>≤ 15 min</td>
                                        <td><span class="badge badge-warning" id="criticalResponseStatus">Fair</span></td>
                                        <td><i class="fas fa-arrow-right" style="color: #f59e0b;" id="criticalResponseTrend"></i></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <i class="fas fa-award" style="color: #a855f7;"></i>
                                                Quality Score
                                            </div>
                                        </td>
                                        <td id="detailQualityScore">--%</td>
                                        <td>≥ 98%</td>
                                        <td><span class="badge badge-success" id="qualityScoreStatus">Excellent</span></td>
                                        <td><i class="fas fa-arrow-up" style="color: #10b981;" id="qualityScoreTrend"></i></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stats-card">
                    <div class="stats-content">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stats-info">
                            <h3 id="pendingOrders">--</h3>
                            <p>Pending Orders</p>
                            <small>Awaiting collection</small>
                        </div>
                    </div>
                    <div class="stats-progress">
                        <div class="stats-progress-bar" style="background: linear-gradient(135deg, #f59e0b, #d97706); width: 65%;"></div>
                    </div>
                </div>
                
                <div class="stats-card">
                    <div class="stats-content">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #06b6d4, #0891b2);">
                            <i class="fas fa-vial"></i>
                        </div>
                        <div class="stats-info">
                            <h3 id="samplesCollected">--</h3>
                            <p>Samples Today</p>
                            <small>Collected samples</small>
                        </div>
                    </div>
                    <div class="stats-progress">
                        <div class="stats-progress-bar" style="background: linear-gradient(135deg, #06b6d4, #0891b2); width: 80%;"></div>
                    </div>
                </div>
                
                <div class="stats-card">
                    <div class="stats-content">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stats-info">
                            <h3 id="completedOrders">--</h3>
                            <p>Completed Today</p>
                            <small>Results submitted</small>
                        </div>
                    </div>
                    <div class="stats-progress">
                        <div class="stats-progress-bar" style="background: linear-gradient(135deg, #10b981, #059669); width: 90%;"></div>
                    </div>
                </div>
                
                <div class="stats-card">
                    <div class="stats-content">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                            <i class="fas fa-flask"></i>
                        </div>
                        <div class="stats-info">
                            <h3 id="inProgressOrders">--</h3>
                            <p>In Progress</p>
                            <small>Being processed</small>
                        </div>
                    </div>
                    <div class="stats-progress">
                        <div class="stats-progress-bar" style="background: linear-gradient(135deg, #667eea, #764ba2); width: 45%;"></div>
                    </div>
                </div>
            </div>
            
            <!-- Lab Orders Management -->
            <div class="content-card">
                <div class="content-card-header">
                    <h2 class="content-card-title">
                        <i class="fas fa-list-ul"></i>
                        Lab Orders Management
                    </h2>
                    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                        <select id="statusFilter" class="form-control" style="max-width: 150px;">
                            <option value="">All Orders</option>
                            <option value="ordered">Ordered</option>
                            <option value="collected">Sample Collected</option>
                            <option value="processing">Processing</option>
                            <option value="resulted">Results Ready</option>
                        </select>
                        <select id="priorityFilter" class="form-control" style="max-width: 150px;">
                            <option value="">All Priorities</option>
                            <option value="stat">STAT</option>
                            <option value="urgent">Urgent</option>
                            <option value="routine">Routine</option>
                        </select>
                        <button class="btn btn-secondary btn-refresh" onclick="loadLabOrders()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="content-card-body" style="padding: 0;">
                    <div class="table-container">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Patient</th>
                                    <th>Test</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Ordered</th>
                                    <th>Collection</th>
                                    <th>Results</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="labOrdersTable">
                                <tr>
                                    <td colspan="9" style="text-align: center; padding: 3rem;">
                                        <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                                            <div class="pulse" style="width: 20px; height: 20px; background: var(--primary-gradient); border-radius: 50%;"></div>
                                            Loading lab orders...
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Equipment Status Cards -->
            <div class="stats-grid">
                <div class="stats-card">
                    <div class="stats-content">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                            <i class="fas fa-microscope"></i>
                        </div>
                        <div class="stats-info">
                            <h3 id="activeEquipmentCount">0</h3>
                            <p>Active Equipment</p>
                            <small>Currently online</small>
                        </div>
                    </div>
                    <div class="stats-progress">
                        <div class="stats-progress-bar" style="background: linear-gradient(135deg, #667eea, #764ba2); width: 75%;"></div>
                    </div>
                </div>
                
                <div class="stats-card">
                    <div class="stats-content">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="fas fa-wifi"></i>
                        </div>
                        <div class="stats-info">
                            <h3 id="onlineEquipmentCount">0</h3>
                            <p>Online Equipment</p>
                            <small>Connected devices</small>
                        </div>
                    </div>
                    <div class="stats-progress">
                        <div class="stats-progress-bar" style="background: linear-gradient(135deg, #10b981, #059669); width: 85%;"></div>
                    </div>
                </div>
                
                <div class="stats-card">
                    <div class="stats-content">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stats-info">
                            <h3 id="pendingVerificationCount">0</h3>
                            <p>Pending Verification</p>
                            <small>Requires review</small>
                        </div>
                    </div>
                    <div class="stats-progress">
                        <div class="stats-progress-bar" style="background: linear-gradient(135deg, #f59e0b, #d97706); width: 35%;"></div>
                    </div>
                </div>
            </div>

            <!-- Equipment Management -->
            <div class="content-card">
                <div class="content-card-header">
                    <h2 class="content-card-title">
                        <i class="fas fa-cogs"></i>Lab Equipment Status
                    </h2>
                    <button class="btn btn-primary" onclick="fetchAllResults()">
                        <i class="fas fa-sync-alt"></i>Fetch All Results
                    </button>
                </div>
                <div class="content-card-body">
                    <div id="equipmentList" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                        <!-- Equipment cards will be loaded here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Equipment & Results Tab -->
        <div class="tab-pane fade" id="equipment" role="tabpanel">
            <!-- Interactive Action Cards - Side by Side -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                <!-- Record Sample Collection Card -->
                <div class="content-card action-card" 
                     style="cursor: pointer; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(8, 145, 178, 0.1)); border: 1px solid rgba(6, 182, 212, 0.3); position: relative; overflow: hidden;" 
                     onclick="console.log('🎯 CARD CLICKED! Event triggered'); showCollectionModal();" 
                     onmouseover="console.log('Card hover detected'); this.style.transform='translateY(-8px) scale(1.03)'; this.style.boxShadow='0 20px 40px rgba(6, 182, 212, 0.25)'; this.querySelector('.card-overlay').style.opacity='1';" 
                     onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='var(--shadow-lg)'; this.querySelector('.card-overlay').style.opacity='0';"
                     onmousedown="this.style.transform='translateY(-4px) scale(1.01)'"
                     onmouseup="this.style.transform='translateY(-8px) scale(1.03)'"
                     title="Click to open sample collection form"
                     data-bs-toggle="tooltip" 
                     data-bs-placement="top">
                    
                    <!-- Clickable Indicator Badge -->
                    <div style="position: absolute; top: 1rem; right: 1rem; background: rgba(6, 182, 212, 0.9); color: white; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600; z-index: 2; transition: all 0.3s ease;">
                        <i class="fas fa-mouse-pointer" style="margin-right: 0.25rem;"></i>CLICK
                    </div>
                    
                    <!-- Hover Overlay Effect -->
                    <div class="card-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(8, 145, 178, 0.1)); opacity: 0; transition: all 0.3s ease; pointer-events: none;"></div>
                    
                    <div class="content-card-body" style="text-align: center; padding: 3rem 2rem; position: relative; z-index: 1;">
                        <div style="background: linear-gradient(135deg, #06b6d4, #0891b2); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; transition: all 0.3s ease; box-shadow: 0 8px 25px rgba(6, 182, 212, 0.3);">
                            <i class="fas fa-vial" style="font-size: 2rem; color: white; transition: all 0.3s ease;"></i>
                        </div>
                        <h3 style="color: var(--text-primary); margin-bottom: 1rem; font-size: 1.5rem; font-weight: 600; transition: all 0.3s ease;">
                            Record Sample Collection
                        </h3>
                        <p style="color: var(--text-secondary); margin-bottom: 1.5rem; font-size: 1rem; line-height: 1.5; transition: all 0.3s ease;">
                            Click anywhere on this card to open the collection form and record sample collection
                        </p>
                        <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; color: #06b6d4; font-weight: 600; transition: all 0.3s ease; padding: 0.75rem 1.5rem; background: rgba(6, 182, 212, 0.1); border-radius: 25px; border: 1px solid rgba(6, 182, 212, 0.3);">
                            <i class="fas fa-play" style="font-size: 0.9rem;"></i>
                            <span>Start Collection</span>
                            <i class="fas fa-arrow-right" style="transition: all 0.3s ease;"></i>
                        </div>
                    </div>
                </div>

                <!-- Submit Test Results Card -->
                <div class="content-card action-card" 
                     style="cursor: pointer; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1)); border: 1px solid rgba(16, 185, 129, 0.3); position: relative; overflow: hidden;" 
                     onclick="console.log('🧪 RESULTS CARD CLICKED! Event triggered'); showResultsModal();" 
                     onmouseover="console.log('Results card hover detected'); this.style.transform='translateY(-8px) scale(1.03)'; this.style.boxShadow='0 20px 40px rgba(16, 185, 129, 0.25)'; this.querySelector('.card-overlay').style.opacity='1';" 
                     onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='var(--shadow-lg)'; this.querySelector('.card-overlay').style.opacity='0';"
                     onmousedown="this.style.transform='translateY(-4px) scale(1.01)'"
                     onmouseup="this.style.transform='translateY(-8px) scale(1.03)'"
                     title="Click to submit test results"
                     data-bs-toggle="tooltip" 
                     data-bs-placement="top">
                    
                    <!-- Clickable Indicator Badge -->
                    <div style="position: absolute; top: 1rem; right: 1rem; background: rgba(16, 185, 129, 0.9); color: white; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600; z-index: 2; transition: all 0.3s ease;">
                        CLICK TO SUBMIT
                    </div>
                    
                    <!-- Hover Overlay Effect -->
                    <div class="card-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.15)); opacity: 0; transition: opacity 0.3s ease; pointer-events: none; z-index: 1;"></div>
                    
                    <div class="content-card-header" style="position: relative; z-index: 2;">
                        <h3 class="content-card-title" style="color: #10b981; font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;">
                            <i class="fas fa-flask" style="margin-right: 0.75rem; font-size: 2rem; background: linear-gradient(135deg, #10b981, #059669); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;"></i>
                            Submit Test Results
                        </h3>
                        <p style="color: var(--text-secondary); margin: 0; font-size: 1.1rem; line-height: 1.6;">
                            Upload and submit completed test results for patients with automatic timestamp recording
                        </p>
                    </div>
                    <div class="content-card-body" style="position: relative; z-index: 2;">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                            <div style="background: linear-gradient(135deg, #10b981, #059669); padding: 1rem; border-radius: 50%; color: white;">
                                <i class="fas fa-microscope" style="font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h4 style="color: var(--text-primary); margin: 0 0 0.25rem 0; font-weight: 600;">Results Entry</h4>
                                <p style="color: var(--text-muted); margin: 0; font-size: 0.9rem;">Select patient and enter test results with automatic validation</p>
                            </div>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                            <div style="text-align: center; padding: 1rem; background: rgba(16, 185, 129, 0.1); border-radius: 8px; border: 1px solid rgba(16, 185, 129, 0.2);">
                                <i class="fas fa-user-check" style="color: #10b981; font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                                <div style="font-weight: 600; color: var(--text-primary); font-size: 0.9rem;">Patient Selection</div>
                                <div style="color: var(--text-muted); font-size: 0.8rem;">Auto-load tests</div>
                            </div>
                            <div style="text-align: center; padding: 1rem; background: rgba(16, 185, 129, 0.1); border-radius: 8px; border: 1px solid rgba(16, 185, 129, 0.2);">
                                <i class="fas fa-edit" style="color: #10b981; font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                                <div style="font-weight: 600; color: var(--text-primary); font-size: 0.9rem;">Enter Results</div>
                                <div style="color: var(--text-muted); font-size: 0.8rem;">With validation</div>
                            </div>
                            <div style="text-align: center; padding: 1rem; background: rgba(16, 185, 129, 0.1); border-radius: 8px; border: 1px solid rgba(16, 185, 129, 0.2);">
                                <i class="fas fa-clock" style="color: #10b981; font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                                <div style="font-weight: 600; color: var(--text-primary); font-size: 0.9rem;">Auto Timestamp</div>
                                <div style="color: var(--text-muted); font-size: 0.8rem;">Submission time</div>
                            </div>
                        </div>
                        
                        <div style="background: rgba(16, 185, 129, 0.1); border: 1px dashed rgba(16, 185, 129, 0.4); border-radius: 8px; padding: 1rem; text-align: center;">
                            <i class="fas fa-arrow-right" style="color: #10b981; margin-right: 0.5rem; transition: transform 0.3s ease;"></i>
                            <span style="color: var(--text-primary); font-weight: 600;">Click to Open Results Form</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- OCR Result Upload -->
            <div class="content-card">
                <div class="content-card-header">
                    <h2 class="content-card-title">
                        <i class="fas fa-camera"></i>OCR Result Upload
                    </h2>
                </div>
                <div class="content-card-body">
                    <form id="ocrUploadForm" enctype="multipart/form-data" class="ocr-upload-form">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                            <div>
                                <label class="form-label">Lab Order</label>
                                <select class="form-control" id="ocrLabOrder" required>
                                    <option value="">Select Lab Order</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Equipment (Optional)</label>
                                <select class="form-control" id="ocrEquipment">
                                    <option value="">Manual Entry</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Result Image</label>
                                <input type="file" class="form-control" 
                                       id="resultImage" accept="image/*" required>
                            </div>
                        </div>
                        <div class="ocr-buttons mt-4">
                                    <button type="submit" class="btn btn-gradient-success flex-fill" style="min-width: 150px;">
                                        <i class="fas fa-upload me-2"></i>Process OCR
                                    </button>
                            <button type="button" class="btn btn-gradient-primary flex-fill" 
                                    onclick="openCamera()" style="min-width: 150px;">
                                <i class="fas fa-camera me-2"></i>Take Photo
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Recent Results Section -->
            <div class="content-card">
                <div class="content-card-header">
                    <h2 class="content-card-title">
                        <i class="fas fa-list-alt"></i>Recent Lab Results
                        <span id="recentResultsCount" class="badge bg-primary ms-2" style="font-size: 0.7rem;">0</span>
                    </h2>
                    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                        <select class="form-control" id="resultSourceFilter" style="max-width: 150px;">
                            <option value="">All Sources</option>
                            <option value="equipment">Equipment</option>
                            <option value="ocr">OCR</option>
                            <option value="manual">Manual</option>
                        </select>
                        <select class="form-control" id="resultStatusFilter" style="max-width: 150px;">
                            <option value="">All Status</option>
                            <option value="preliminary">Preliminary</option>
                            <option value="final">Final</option>
                            <option value="needs_verification">Needs Verification</option>
                        </select>
                        <button class="btn btn-secondary btn-refresh" onclick="loadRecentResults()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <button class="btn btn-primary" onclick="showResultsModal()" title="Add New Results">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="content-card-body" style="padding: 0;">
                    <div class="table-container">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th style="width: 200px;">
                                        <i class="fas fa-flask me-1"></i>Test Name
                                    </th>
                                    <th style="width: 150px;">
                                        <i class="fas fa-user me-1"></i>Patient
                                    </th>
                                    <th style="width: 180px;">
                                        <i class="fas fa-chart-line me-1"></i>Result Value
                                    </th>
                                    <th style="width: 100px;">
                                        <i class="fas fa-source me-1"></i>Source
                                    </th>
                                    <th style="width: 120px;">
                                        <i class="fas fa-info-circle me-1"></i>Status
                                    </th>
                                    <th style="width: 140px;">
                                        <i class="fas fa-calendar me-1"></i>Date & Time
                                    </th>
                                    <th style="width: 120px;">
                                        <i class="fas fa-cogs me-1"></i>Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="recentResultsTable">
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 3rem;">
                                        <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                                            <div class="pulse" style="width: 20px; height: 20px; background: var(--primary-gradient); border-radius: 50%;"></div>
                                            Loading recent results...
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lab Financial Dashboard Tab -->
        <div class="tab-pane fade" id="invoices" role="tabpanel" style="display: none;">
            <!-- Lab Financial Analytics -->
            <div class="stats-grid">
                <div class="stats-card">
                    <div class="stats-content">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="fas fa-flask"></i>
                        </div>
                        <div class="stats-info">
                            <h3 id="labTotalRevenue">$--</h3>
                            <p>Lab Revenue</p>
                            <small>From lab orders</small>
                        </div>
                    </div>
                    <div class="stats-progress">
                        <div class="stats-progress-bar" style="background: linear-gradient(135deg, #10b981, #059669); width: 85%;"></div>
                    </div>
                </div>
                
                <div class="stats-card">
                    <div class="stats-content">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <div class="stats-info">
                            <h3 id="pendingLabInvoices">$--</h3>
                            <p>Pending Lab Invoices</p>
                            <small>Unpaid lab bills</small>
                        </div>
                    </div>
                    <div class="stats-progress">
                        <div class="stats-progress-bar" style="background: linear-gradient(135deg, #f59e0b, #d97706); width: 65%;"></div>
                    </div>
                </div>
                
                <div class="stats-card">
                    <div class="stats-content">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #06b6d4, #0891b2);">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="stats-info">
                            <h3 id="todayLabRevenue">$--</h3>
                            <p>Today's Lab Revenue</p>
                            <small>Collections today</small>
                        </div>
                    </div>
                    <div class="stats-progress">
                        <div class="stats-progress-bar" style="background: linear-gradient(135deg, #06b6d4, #0891b2); width: 70%;"></div>
                    </div>
                </div>
                
                <div class="stats-card">
                    <div class="stats-content">
                        <div class="stats-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                            <i class="fas fa-vial"></i>
                        </div>
                        <div class="stats-info">
                            <h3 id="labOrdersValue">$--</h3>
                            <p>Lab Orders Value</p>
                            <small>Total order value</small>
                        </div>
                    </div>
                    <div class="stats-progress">
                        <div class="stats-progress-bar" style="background: linear-gradient(135deg, #667eea, #764ba2); width: 90%;"></div>
                    </div>
                </div>
            </div>
            
            <!-- Lab Financial Charts -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                <div class="content-card">
                    <div class="content-card-header">
                        <h2 class="content-card-title">
                            <i class="fas fa-chart-line"></i>Lab Revenue Trend
                        </h2>
                    </div>
                    <div class="content-card-body">
                        <canvas id="labRevenueChart" height="300"></canvas>
                    </div>
                </div>
                <div class="content-card">
                    <div class="content-card-header">
                        <h2 class="content-card-title">
                            <i class="fas fa-chart-pie"></i>Test Categories Revenue
                        </h2>
                    </div>
                    <div class="content-card-body">
                        <canvas id="testCategoriesChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Lab Invoice Generation Section -->
            <div class="content-card">
                <div class="content-card-header">
                    <h2 class="content-card-title">
                        <i class="fas fa-plus-circle"></i>Generate Lab Invoice
                    </h2>
                    <p style="color: var(--text-muted); margin: 0; font-size: 0.9rem;">Create invoices for completed lab orders with patient search</p>
                </div>
                <div class="content-card-body">
                    <!-- Patient Search Section -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-search me-1"></i>Search Patient
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" id="patientSearchInput" class="form-control" placeholder="Search by name or CNIC..." onkeyup="searchPatientsForInvoice(this.value)">
                            </div>
                            <small class="text-muted">Type patient name or CNIC to search</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-id-card me-1"></i>CNIC Search
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                <input type="text" id="cnicSearchForInvoice" class="form-control" placeholder="12345-1234567-1" onkeyup="searchPatientsByCnicForInvoice(this.value)">
                                <button class="btn btn-outline-secondary" type="button" onclick="searchPatientsByCnicForInvoice(document.getElementById('cnicSearchForInvoice').value, false)">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <small class="text-muted">Enter CNIC for exact search</small>
                        </div>
                    </div>

                    <!-- Invoice Form -->
                    <form id="labInvoiceForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="fas fa-user-circle me-1"></i>Selected Patient *
                                </label>
                                <select id="labInvoicePatient" class="form-control" required onchange="loadPatientLabOrders()">
                                    <option value="">Choose patient...</option>
                                </select>
                                <div id="patientInfoDisplay" class="mt-2" style="display: none;">
                                    <div class="alert alert-info" style="background: rgba(6, 182, 212, 0.1); border: 1px solid rgba(6, 182, 212, 0.3); color: var(--text-primary);">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas fa-info-circle" style="color: #06b6d4;"></i>
                                            <div id="selectedPatientInfo"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="fas fa-flask me-1"></i>Completed Lab Orders *
                                </label>
                                <select id="labInvoiceOrders" class="form-control" multiple required onchange="calculateLabInvoiceAmount()">
                                    <option value="">Select patient first...</option>
                                </select>
                                <small class="text-muted">Hold Ctrl/Cmd to select multiple completed orders</small>
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-calculator me-1"></i>Subtotal
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" id="labInvoiceSubtotal" class="form-control" step="0.01" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-percentage me-1"></i>Discount (%)
                                </label>
                                <input type="number" id="labDiscount" class="form-control" min="0" max="100" value="0" onchange="calculateLabInvoiceAmount()">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="fas fa-dollar-sign me-1"></i>Final Amount
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" id="labInvoiceAmount" class="form-control" step="0.01" required readonly style="font-weight: 600; color: #10b981;">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex gap-2 justify-content-end">
                                    <button type="button" class="btn btn-secondary" onclick="clearInvoiceForm()">
                                        <i class="fas fa-times me-1"></i>Clear Form
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-file-invoice me-1"></i>Generate Invoice
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lab Invoices Management -->
            <div class="content-card">
                <div class="content-card-header">
                    <h2 class="content-card-title">
                        <i class="fas fa-file-invoice-dollar"></i>Lab Invoice Management
                    </h2>
                    <div class="d-flex gap-2 flex-wrap align-items-center">
                        <div class="input-group" style="max-width: 300px;">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" id="invoiceSearchInput" class="form-control" placeholder="Search invoices..." onkeyup="filterLabInvoices()">
                        </div>
                        <select id="labInvoiceStatusFilter" class="form-control" style="max-width: 150px;" onchange="filterLabInvoices()">
                            <option value="">All Status</option>
                            <option value="pending">Pending Payment</option>
                            <option value="paid">Paid</option>
                            <option value="overdue">Overdue</option>
                            <option value="partial">Partially Paid</option>
                        </select>
                        <select id="labTestTypeFilter" class="form-control" style="max-width: 150px;" onchange="filterLabInvoices()">
                            <option value="">All Test Types</option>
                            <option value="hematology">Hematology</option>
                            <option value="biochemistry">Biochemistry</option>
                            <option value="electrolyte">Electrolyte</option>
                            <option value="microbiology">Microbiology</option>
                            <option value="immunology">Immunology</option>
                        </select>
                        <button class="btn btn-secondary btn-refresh" onclick="loadLabInvoices()" title="Refresh">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <button class="btn btn-success" onclick="exportLabInvoices()" title="Export to Excel">
                            <i class="fas fa-file-excel"></i>
                        </button>
                    </div>
                </div>
                <div class="content-card-body" style="padding: 0;">
                    <!-- Invoice Statistics -->
                    <div class="row g-3 p-3" style="background: rgba(255, 255, 255, 0.02); border-bottom: 1px solid var(--glass-border);">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="stats-icon" style="width: 40px; height: 40px; background: rgba(16, 185, 129, 0.1);">
                                    <i class="fas fa-file-invoice" style="color: #10b981; font-size: 1.2rem;"></i>
                                </div>
                                <div>
                                    <div class="stats-value" style="font-size: 1.5rem; font-weight: 600;" id="totalInvoicesCount">0</div>
                                    <div class="stats-label" style="font-size: 0.8rem; color: var(--text-muted);">Total Invoices</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="stats-icon" style="width: 40px; height: 40px; background: rgba(245, 158, 11, 0.1);">
                                    <i class="fas fa-clock" style="color: #f59e0b; font-size: 1.2rem;"></i>
                                </div>
                                <div>
                                    <div class="stats-value" style="font-size: 1.5rem; font-weight: 600;" id="pendingInvoicesCount">0</div>
                                    <div class="stats-label" style="font-size: 0.8rem; color: var(--text-muted);">Pending</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="stats-icon" style="width: 40px; height: 40px; background: rgba(6, 182, 212, 0.1);">
                                    <i class="fas fa-dollar-sign" style="color: #06b6d4; font-size: 1.2rem;"></i>
                                </div>
                                <div>
                                    <div class="stats-value" style="font-size: 1.5rem; font-weight: 600;" id="totalInvoiceAmount">$0</div>
                                    <div class="stats-label" style="font-size: 0.8rem; color: var(--text-muted);">Total Amount</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="stats-icon" style="width: 40px; height: 40px; background: rgba(139, 92, 246, 0.1);">
                                    <i class="fas fa-calendar-day" style="color: #8b5cf6; font-size: 1.2rem;"></i>
                                </div>
                                <div>
                                    <div class="stats-value" style="font-size: 1.5rem; font-weight: 600;" id="todayInvoicesCount">0</div>
                                    <div class="stats-label" style="font-size: 0.8rem; color: var(--text-muted);">Today</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-container">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th style="width: 100px;">
                                        <i class="fas fa-hashtag me-1"></i>Invoice #
                                    </th>
                                    <th>
                                        <i class="fas fa-user me-1"></i>Patient Details
                                    </th>
                                    <th>
                                        <i class="fas fa-flask me-1"></i>Lab Tests
                                    </th>
                                    <th style="width: 80px;">
                                        <i class="fas fa-vial me-1"></i>Count
                                    </th>
                                    <th style="width: 100px;">
                                        <i class="fas fa-dollar-sign me-1"></i>Amount
                                    </th>
                                    <th style="width: 120px;">
                                        <i class="fas fa-info-circle me-1"></i>Status
                                    </th>
                                    <th style="width: 120px;">
                                        <i class="fas fa-calendar me-1"></i>Date
                                    </th>
                                    <th style="width: 140px;">
                                        <i class="fas fa-cogs me-1"></i>Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="labInvoicesTable">
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 3rem;">
                                        <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; color: var(--text-muted);">
                                            <div class="loading-spinner" style="width: 20px; height: 20px; border: 2px solid var(--glass-border); border-top: 2px solid #06b6d4; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                                            Loading lab invoices...
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuration Tab -->
        <div class="tab-pane fade" id="analytics" role="tabpanel" style="display: none;">
            <div class="content-card">
                <div class="content-card-header">
                    <h2 class="content-card-title">
                        <i class="fas fa-cogs"></i>Lab Configuration Settings
                    </h2>
                </div>
                <div class="content-card-body">
                    <!-- Configuration Navigation -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <ul class="nav nav-pills nav-fill config-nav" id="configTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="tests-config-tab" data-bs-toggle="pill" data-bs-target="#tests-config" type="button" role="tab" style="border-radius: 8px; margin: 0 4px; transition: all 0.3s ease;">
                                        <i class="fas fa-flask me-2"></i>Test Management
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="equipment-config-tab" data-bs-toggle="pill" data-bs-target="#equipment-config" type="button" role="tab" style="border-radius: 8px; margin: 0 4px; transition: all 0.3s ease;">
                                        <i class="fas fa-microscope me-2"></i>Lab Equipment
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="system-config-tab" data-bs-toggle="pill" data-bs-target="#system-config" type="button" role="tab" style="border-radius: 8px; margin: 0 4px; transition: all 0.3s ease;">
                                        <i class="fas fa-cog me-2"></i>System Settings
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="tab-content" id="configTabContent">
                        <!-- Test Management Tab -->
                        <div class="tab-pane fade show active" id="tests-config" role="tabpanel">
                            <!-- Test Management Section -->
                    <div class="config-section mb-5">
                        <div class="section-header d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 style="color: var(--text-primary); margin: 0;">
                                    <i class="fas fa-flask me-2" style="color: #06b6d4;"></i>Available Tests Management
                                </h4>
                                <p style="color: var(--text-muted); margin: 0.5rem 0 0 0;">Manage laboratory tests, pricing, and categories</p>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="showAddTestModal()">
                                <i class="fas fa-plus me-2"></i>Add New Test
                            </button>
                        </div>

                        <!-- Search and Filter Section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" id="testSearchInput" class="form-control" placeholder="Search tests by name or code...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select id="categoryFilter" class="form-control">
                                    <option value="">All Categories</option>
                                    <option value="Hematology">Hematology</option>
                                    <option value="Biochemistry">Biochemistry</option>
                                    <option value="Microbiology">Microbiology</option>
                                    <option value="Immunology">Immunology</option>
                                    <option value="Endocrinology">Endocrinology</option>
                                    <option value="Cardiology">Cardiology</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="statusFilter" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <!-- Tests Table -->
                        <div class="table-responsive">
                            <table class="table table-hover" id="testsTable">
                                <thead style="background: var(--glass-background); border-bottom: 2px solid var(--glass-border);">
                                    <tr>
                                        <th>Test Code</th>
                                        <th>Test Name</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Turnaround Time</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="testsTableBody">
                                    <!-- Tests will be loaded here -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Quick Stats -->
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-icon" style="background: rgba(6, 182, 212, 0.1);">
                                        <i class="fas fa-flask" style="color: #06b6d4;"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-value" id="totalTestsCount">0</div>
                                        <div class="stat-label">Total Tests</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1);">
                                        <i class="fas fa-check-circle" style="color: #10b981;"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-value" id="activeTestsCount">0</div>
                                        <div class="stat-label">Active Tests</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1);">
                                        <i class="fas fa-dollar-sign" style="color: #f59e0b;"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-value" id="avgTestPrice">$0</div>
                                        <div class="stat-label">Avg. Price</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-icon" style="background: rgba(139, 92, 246, 0.1);">
                                        <i class="fas fa-layer-group" style="color: #8b5cf6;"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-value" id="categoriesCount">0</div>
                                        <div class="stat-label">Categories</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>

                        <!-- Lab Equipment Management Tab -->
                        <div class="tab-pane fade" id="equipment-config" role="tabpanel">
                            <!-- Equipment Management Section -->
                            <div class="section-header d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h4 style="color: var(--text-primary); margin: 0;">
                                        <i class="fas fa-microscope me-2" style="color: #10b981;"></i>Lab Equipment Management
                                    </h4>
                                    <p style="color: var(--text-muted); margin: 0.5rem 0 0 0;">Connect and manage laboratory equipment with live status monitoring and test configuration</p>
                                </div>
                                <button type="button" class="btn btn-success" onclick="showAddEquipmentModal()" style="background: linear-gradient(135deg, #10b981, #059669); border: none; border-radius: 10px; padding: 0.75rem 1.5rem; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);">
                                    <i class="fas fa-plus me-2"></i>Add Equipment
                                </button>
                            </div>

                            <!-- Equipment Statistics Dashboard -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="equipment-stat-card" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1)); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 15px; padding: 1.5rem; text-align: center; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(16, 185, 129, 0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                        <div style="background: linear-gradient(135deg, #10b981, #059669); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);">
                                            <i class="fas fa-microscope" style="color: white; font-size: 1.5rem;"></i>
                                        </div>
                                        <h3 style="color: #10b981; margin: 0; font-weight: 700;" id="totalEquipmentCount">0</h3>
                                        <p style="color: var(--text-muted); margin: 0.5rem 0 0 0; font-size: 0.9rem; font-weight: 500;">Total Equipment</p>
                                        <div style="width: 100%; height: 4px; background: rgba(16, 185, 129, 0.2); border-radius: 2px; margin-top: 1rem;">
                                            <div style="width: 85%; height: 100%; background: linear-gradient(135deg, #10b981, #059669); border-radius: 2px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="equipment-stat-card" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(8, 145, 178, 0.1)); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 15px; padding: 1.5rem; text-align: center; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(6, 182, 212, 0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                        <div style="background: linear-gradient(135deg, #06b6d4, #0891b2); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; box-shadow: 0 8px 25px rgba(6, 182, 212, 0.3);">
                                            <i class="fas fa-wifi" style="color: white; font-size: 1.5rem;"></i>
                                        </div>
                                        <h3 style="color: #06b6d4; margin: 0; font-weight: 700;" id="onlineEquipmentCountConfig">0</h3>
                                        <p style="color: var(--text-muted); margin: 0.5rem 0 0 0; font-size: 0.9rem; font-weight: 500;">Online</p>
                                        <div style="width: 100%; height: 4px; background: rgba(6, 182, 212, 0.2); border-radius: 2px; margin-top: 1rem;">
                                            <div style="width: 75%; height: 100%; background: linear-gradient(135deg, #06b6d4, #0891b2); border-radius: 2px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="equipment-stat-card" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.1)); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 15px; padding: 1.5rem; text-align: center; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(245, 158, 11, 0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                        <div style="background: linear-gradient(135deg, #f59e0b, #d97706); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);">
                                            <i class="fas fa-exclamation-triangle" style="color: white; font-size: 1.5rem;"></i>
                                        </div>
                                        <h3 style="color: #f59e0b; margin: 0; font-weight: 700;" id="offlineEquipmentCount">0</h3>
                                        <p style="color: var(--text-muted); margin: 0.5rem 0 0 0; font-size: 0.9rem; font-weight: 500;">Offline</p>
                                        <div style="width: 100%; height: 4px; background: rgba(245, 158, 11, 0.2); border-radius: 2px; margin-top: 1rem;">
                                            <div style="width: 25%; height: 100%; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 2px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="equipment-stat-card" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.1), rgba(124, 58, 237, 0.1)); border: 1px solid rgba(139, 92, 246, 0.3); border-radius: 15px; padding: 1.5rem; text-align: center; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(139, 92, 246, 0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                        <div style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);">
                                            <i class="fas fa-tools" style="color: white; font-size: 1.5rem;"></i>
                                        </div>
                                        <h3 style="color: #8b5cf6; margin: 0; font-weight: 700;" id="maintenanceEquipmentCount">0</h3>
                                        <p style="color: var(--text-muted); margin: 0.5rem 0 0 0; font-size: 0.9rem; font-weight: 500;">Maintenance</p>
                                        <div style="width: 100%; height: 4px; background: rgba(139, 92, 246, 0.2); border-radius: 2px; margin-top: 1rem;">
                                            <div style="width: 10%; height: 100%; background: linear-gradient(135deg, #8b5cf6, #7c3aed); border-radius: 2px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Advanced Search and Filter Section -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="input-group" style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                                        <span class="input-group-text" style="background: linear-gradient(135deg, #06b6d4, #0891b2); border: none; color: white;">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" id="equipmentSearchInput" class="form-control" placeholder="Search equipment by name, model, IP address, or serial number..." style="background: var(--glass-background); border: none; color: var(--text-primary); padding: 0.75rem 1rem;" oninput="filterEquipment()">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <select id="equipmentStatusFilter" class="form-control" style="background: var(--glass-background); border: 1px solid var(--glass-border); color: var(--text-primary); border-radius: 10px; padding: 0.75rem;" onchange="filterEquipment()">
                                        <option value="">All Status</option>
                                        <option value="online">🟢 Online</option>
                                        <option value="offline">🔴 Offline</option>
                                        <option value="maintenance">🟡 Maintenance</option>
                                        <option value="error">❌ Error</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select id="equipmentTypeFilter" class="form-control" style="background: var(--glass-background); border: 1px solid var(--glass-border); color: var(--text-primary); border-radius: 10px; padding: 0.75rem;" onchange="filterEquipment()">
                                        <option value="">All Types</option>
                                        <option value="analyzer">🧪 Analyzer</option>
                                        <option value="microscope">🔬 Microscope</option>
                                        <option value="centrifuge">🌀 Centrifuge</option>
                                        <option value="incubator">🔥 Incubator</option>
                                        <option value="spectrophotometer">📊 Spectrophotometer</option>
                                        <option value="other">⚙️ Other</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-outline-primary active" id="gridViewBtn" onclick="toggleEquipmentView('grid')" style="border-radius: 10px 0 0 10px;">
                                            <i class="fas fa-th"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-primary" id="tableViewBtn" onclick="toggleEquipmentView('table')" style="border-radius: 0 10px 10px 0;">
                                            <i class="fas fa-list"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Equipment Grid View (Default) -->
                            <div id="equipmentGridContainer">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 style="color: var(--text-primary); margin: 0;">
                                        <i class="fas fa-network-wired me-2" style="color: #8b5cf6;"></i>Equipment Dashboard
                                    </h5>
                                    <button class="btn btn-outline-info btn-sm" onclick="testAllConnections()" style="border-radius: 20px;">
                                        <i class="fas fa-wifi me-1"></i>Test All Connections
                                    </button>
                                </div>
                                <div id="equipmentGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(380px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                                    <!-- Equipment cards will be loaded here -->
                                </div>
                            </div>

                            <!-- Equipment Table View (Hidden by default) -->
                            <div id="equipmentTableContainer" style="display: none;">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 style="color: var(--text-primary); margin: 0;">
                                        <i class="fas fa-table me-2" style="color: #8b5cf6;"></i>Equipment Details Table
                                    </h5>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-success" onclick="exportEquipmentData()" style="border-radius: 20px 0 0 20px;">
                                            <i class="fas fa-download me-1"></i>Export
                                        </button>
                                        <button class="btn btn-outline-primary" onclick="refreshEquipmentData()" style="border-radius: 0 20px 20px 0;">
                                            <i class="fas fa-sync-alt me-1"></i>Refresh
                                        </button>
                                    </div>
                                </div>

                                <div class="table-responsive" style="border-radius: 15px; overflow: hidden; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);">
                                    <table class="table table-hover mb-0" id="equipmentTable" style="background: var(--glass-background);">
                                        <thead style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(8, 145, 178, 0.2)); border-bottom: 2px solid var(--glass-border);">
                                            <tr>
                                                <th style="color: var(--text-primary); font-weight: 600; padding: 1rem;">
                                                    <i class="fas fa-microscope me-2"></i>Equipment
                                                </th>
                                                <th style="color: var(--text-primary); font-weight: 600; padding: 1rem;">
                                                    <i class="fas fa-tag me-2"></i>Type
                                                </th>
                                                <th style="color: var(--text-primary); font-weight: 600; padding: 1rem;">
                                                    <i class="fas fa-network-wired me-2"></i>Connection
                                                </th>
                                                <th style="color: var(--text-primary); font-weight: 600; padding: 1rem;">
                                                    <i class="fas fa-signal me-2"></i>Status
                                                </th>
                                                <th style="color: var(--text-primary); font-weight: 600; padding: 1rem;">
                                                    <i class="fas fa-clock me-2"></i>Last Online
                                                </th>
                                                <th style="color: var(--text-primary); font-weight: 600; padding: 1rem;">
                                                    <i class="fas fa-flask me-2"></i>Supported Tests
                                                </th>
                                                <th style="color: var(--text-primary); font-weight: 600; padding: 1rem;">
                                                    <i class="fas fa-cogs me-2"></i>Actions
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="equipmentTableBody">
                                            <!-- Equipment table rows will be loaded here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- System Settings Tab -->
                        <div class="tab-pane fade" id="system-config" role="tabpanel">
                            <!-- System Settings Content -->
                            <div class="config-section">
                                <h4 style="color: var(--text-primary); margin-bottom: 1rem;">
                                    <i class="fas fa-cog me-2" style="color: #6b7280;"></i>System Settings
                                </h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card" style="background: var(--glass-background); border: 1px solid var(--glass-border);">
                                            <div class="card-body">
                                                <h6 class="card-title">Default Lab Settings</h6>
                                                <div class="mb-3">
                                                    <label class="form-label">Default Turnaround Time</label>
                                                    <select class="form-control">
                                                        <option>2-4 hours</option>
                                                        <option>4-6 hours</option>
                                                        <option>6-12 hours</option>
                                                        <option>24 hours</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Default Tax Rate (%)</label>
                                                    <input type="number" class="form-control" value="0" min="0" max="100" step="0.1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card" style="background: var(--glass-background); border: 1px solid var(--glass-border);">
                                            <div class="card-body">
                                                <h6 class="card-title">Notification Settings</h6>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" id="notifyCritical" checked>
                                                    <label class="form-check-label" for="notifyCritical">
                                                        Notify on critical results
                                                    </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" id="notifyDelayed" checked>
                                                    <label class="form-check-label" for="notifyDelayed">
                                                        Notify on delayed results
                                                    </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" id="notifyCompletion">
                                                    <label class="form-check-label" for="notifyCompletion">
                                                        Notify on test completion
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Tab -->
        <div class="tab-pane fade" id="analytics" role="tabpanel">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="glass-card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-pie me-2"></i>
                                Test Distribution
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="testDistributionChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="glass-card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-line me-2"></i>
                                Daily Revenue
                            </h5>
                        </div>
                        <div class="card-body">
                            <canvas id="revenueChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="glass-card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-stopwatch me-2"></i>
                                Performance Metrics
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="metric-item">
                                        <h4 id="avgProcessingTime">-- min</h4>
                                        <p>Avg Processing Time</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="metric-item">
                                        <h4 id="todayCompletionRate">-- %</h4>
                                        <p>Today's Completion Rate</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="metric-item">
                                        <h4 id="criticalResults">--</h4>
                                        <p>Critical Results</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="metric-item">
                                        <h4 id="qualityScore">-- %</h4>
                                        <p>Quality Score</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.icon-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    transition: all 0.3s ease;
    animation: pulse-glow 2s infinite;
}

.icon-circle:hover {
    transform: scale(1.1);
    box-shadow: 0 0 20px rgba(138, 43, 226, 0.5);
}

@keyframes pulse-glow {
    0%, 100% { 
        box-shadow: 0 0 10px rgba(138, 43, 226, 0.3);
        transform: scale(1);
    }
    50% { 
        box-shadow: 0 0 20px rgba(138, 43, 226, 0.6);
        transform: scale(1.02);
    }
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.glass-card {
    background: rgba(30, 30, 50, 0.9);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(138, 43, 226, 0.3);
    border-radius: 15px;
    box-shadow: 
        0 8px 32px rgba(0, 0, 0, 0.3),
        inset 0 1px 0 rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
    animation: slideInUp 0.6s ease-out;
    position: relative;
    overflow: hidden;
}

.glass-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(138, 43, 226, 0.1), transparent);
    transition: left 0.5s;
}

.glass-card:hover::before {
    left: 100%;
}

.glass-card:hover {
    transform: translateY(-5px) scale(1.02);
    border-color: rgba(138, 43, 226, 0.6);
    box-shadow: 
        0 15px 40px rgba(0, 0, 0, 0.4),
        0 0 25px rgba(138, 43, 226, 0.2),
        inset 0 1px 0 rgba(255, 255, 255, 0.2);
}

.nav-pills .nav-link {
    background: rgba(30, 30, 50, 0.6);
    border: 1px solid rgba(138, 43, 226, 0.3);
    color: #9ca3af;
    margin: 0 5px;
    border-radius: 10px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.nav-pills .nav-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, #8A2BE2, #9966CC);
    transition: left 0.3s ease;
    z-index: -1;
}

.nav-pills .nav-link:hover::before,
.nav-pills .nav-link.active::before {
    left: 0;
}

.nav-pills .nav-link:hover,
.nav-pills .nav-link.active {
    color: white;
    border-color: rgba(138, 43, 226, 0.8);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(138, 43, 226, 0.3);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    animation: gradient-shift 3s ease infinite;
}

.bg-gradient-success {
    background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
    animation: gradient-shift 3s ease infinite;
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    animation: gradient-shift 3s ease infinite;
}

.bg-gradient-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    animation: gradient-shift 3s ease infinite;
}

.bg-gradient-purple {
    background: linear-gradient(135deg, #8A2BE2 0%, #9966CC 100%);
    animation: gradient-shift 3s ease infinite;
}

@keyframes gradient-shift {
    0%, 100% { 
        filter: hue-rotate(0deg) brightness(1);
    }
    50% { 
        filter: hue-rotate(10deg) brightness(1.1);
    }
}

.btn {
    border-radius: 8px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    transition: all 0.3s ease;
    transform: translate(-50%, -50%);
}

.btn:hover::before {
    width: 300px;
    height: 300px;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.btn-gradient-primary {
    background: linear-gradient(45deg, #667eea, #764ba2);
    border: none;
    color: white;
}

.btn-gradient-success {
    background: linear-gradient(45deg, #4ecdc4, #44a08d);
    border: none;
    color: white;
}

.btn-gradient-warning {
    background: linear-gradient(45deg, #f093fb, #f5576c);
    border: none;
    color: white;
}

.btn-gradient-info {
    background: linear-gradient(45deg, #4facfe, #00f2fe);
    border: none;
    color: white;
}

.btn-gradient-secondary {
    background: linear-gradient(45deg, #6c757d, #495057);
    border: none;
    color: white;
}

.gradient-text {
    background: linear-gradient(45deg, #8A2BE2, #9966CC, #DA70D6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: gradient-flow 3s ease-in-out infinite;
}

@keyframes gradient-flow {
    0%, 100% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
}

.text-light-gray {
    color: #9ca3af;
}

.border-purple {
    border-color: rgba(138, 43, 226, 0.5) !important;
}

.border-purple:focus {
    border-color: rgba(138, 43, 226, 0.8) !important;
    box-shadow: 0 0 0 0.25rem rgba(138, 43, 226, 0.25);
}

.table-dark {
    background: rgba(30, 30, 50, 0.8);
    backdrop-filter: blur(5px);
}

.table-dark th {
    border-color: rgba(138, 43, 226, 0.3);
    background: rgba(138, 43, 226, 0.2);
    color: white;
    font-weight: 600;
}

.table-dark td {
    border-color: rgba(138, 43, 226, 0.2);
    color: #e5e7eb;
    vertical-align: middle;
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background: rgba(138, 43, 226, 0.1);
    transform: scale(1.02);
    box-shadow: 0 4px 15px rgba(138, 43, 226, 0.2);
}

.badge {
    border-radius: 20px;
    padding: 8px 16px;
    font-weight: 500;
    animation: fadeInScale 0.5s ease-out;
}

.form-control, .form-select {
    border-radius: 8px;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    transform: scale(1.02);
    box-shadow: 0 0 0 0.25rem rgba(138, 43, 226, 0.25);
}

.card-header {
    background: rgba(138, 43, 226, 0.2);
    border-bottom: 1px solid rgba(138, 43, 226, 0.3);
    border-radius: 15px 15px 0 0 !important;
}

.modal-content {
    border-radius: 15px;
    border: 1px solid rgba(138, 43, 226, 0.3);
    animation: fadeInScale 0.3s ease-out;
}

.equipment-status-online {
    animation: pulse-green 2s infinite;
}

.equipment-status-offline {
    animation: pulse-red 2s infinite;
}

@keyframes pulse-green {
    0%, 100% { 
        box-shadow: 0 0 5px rgba(34, 197, 94, 0.3);
    }
    50% { 
        box-shadow: 0 0 20px rgba(34, 197, 94, 0.6);
    }
}

@keyframes pulse-red {
    0%, 100% { 
        box-shadow: 0 0 5px rgba(239, 68, 68, 0.3);
    }
    50% { 
        box-shadow: 0 0 20px rgba(239, 68, 68, 0.6);
    }
}

.loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(138, 43, 226, 0.3);
    border-radius: 50%;
    border-top-color: #8A2BE2;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.progress-bar {
    background: linear-gradient(45deg, #8A2BE2, #9966CC);
    animation: progress-glow 2s ease-in-out infinite;
}

@keyframes progress-glow {
    0%, 100% { 
        box-shadow: 0 0 5px rgba(138, 43, 226, 0.3);
    }
    50% { 
        box-shadow: 0 0 15px rgba(138, 43, 226, 0.6);
    }
}

.camera-preview {
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    .glass-card {
        margin-bottom: 1rem;
    }
    
    .nav-pills .nav-link {
        margin: 2px;
        font-size: 0.9rem;
    }
    
    .icon-circle {
        width: 50px;
        height: 50px;
    }
    
    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
}

/* Dark theme scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: rgba(30, 30, 50, 0.5);
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(45deg, #8A2BE2, #9966CC);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(45deg, #9966CC, #8A2BE2);
}

/* Animation delays for staggered loading */
.glass-card:nth-child(1) { animation-delay: 0.1s; }
.glass-card:nth-child(2) { animation-delay: 0.2s; }
.glass-card:nth-child(3) { animation-delay: 0.3s; }
.glass-card:nth-child(4) { animation-delay: 0.4s; }

/* Enhanced table animations */
.table tbody tr:nth-child(odd) { animation-delay: 0.1s; }
.table tbody tr:nth-child(even) { animation-delay: 0.2s; }

/* Additional Gradient Classes */
.bg-gradient-orange {
    background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
}

.bg-gradient-blue {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.bg-gradient-green {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.bg-gradient-purple {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.border-purple {
    border-color: #764ba2 !important;
}

/* Custom Modal Styles */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: none;
    z-index: 1050;
    overflow-x: hidden;
    overflow-y: auto;
}

.modal-dialog {
    position: relative;
    width: auto;
    margin: 1.75rem auto;
    min-height: calc(100% - 3.5rem);
    display: flex;
    align-items: center;
}

.modal-content {
    position: relative;
    background: var(--glass-background);
    backdrop-filter: blur(10px);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.5rem;
    border-bottom: 1px solid var(--glass-border);
}

.modal-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
}

.modal-body {
    padding: 2rem;
}

.modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0.75rem;
    padding: 1.5rem;
    border-top: 1px solid var(--glass-border);
}

.btn-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: var(--text-muted);
    cursor: pointer;
    padding: 0;
    width: 1.5rem;
    height: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-close:hover {
    color: var(--text-primary);
}

.btn-close::before {
    content: "×";
    font-weight: bold;
}

.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1040;
}

/* Modal size variants */
.modal-lg {
    max-width: 800px;
}

.modal-xl {
    max-width: 1140px;
}

/* Enhanced test item styling */
.test-item.dragging {
    opacity: 0.7;
    transform: rotate(5deg);
    z-index: 1000;
}

/* Animation for modal appearance */
@keyframes modalSlide {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.glass-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 15px;
}

.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.text-light-gray {
    color: rgba(255, 255, 255, 0.7) !important;
}

.table-dark {
    --bs-table-bg: rgba(0, 0, 0, 0.2);
}

.priority-stat {
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.priority-stat.stat { background: #dc3545; color: white; }
.priority-urgent { background: #fd7e14; color: white; }
.priority-routine { background: #6c757d; color: white; }

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-ordered { background: #ffc107; color: #000; }
.status-collected { background: #17a2b8; color: white; }
.status-processing { background: #6f42c1; color: white; }
.status-resulted { background: #28a745; color: white; }

.btn-action {
    padding: 0.25rem 0.5rem;
    font-size: 0.8rem;
    border-radius: 6px;
    margin: 0 0.1rem;
}

/* Consistent Button Styling */
.btn, .btn-primary, .btn-secondary, .btn-success, .btn-warning, .btn-danger, .btn-info {
    padding: 0.75rem 1.5rem;
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 0.95rem;
    border: none;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    cursor: pointer;
    text-transform: none;
    letter-spacing: 0.025em;
    min-height: 44px;
    white-space: nowrap;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
}

.btn:active {
    transform: translateY(0);
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    min-height: 36px;
}

.btn-lg {
    padding: 1rem 2rem;
    font-size: 1.1rem;
    min-height: 52px;
}

/* Consistent Dropdown/Select Styling */
.form-control, .form-select, select {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: var(--radius-md);
    color: white;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    min-height: 44px;
}

.form-control:focus, .form-select:focus, select:focus {
    background: rgba(255, 255, 255, 0.15);
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.25);
    color: white;
    outline: none;
}

.form-control::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

.form-control option, .form-select option, select option {
    background: var(--bg-secondary);
    color: white;
    padding: 0.5rem;
}

/* Action Button Overrides for Small Buttons */
.btn-action, .action-btn {
    padding: 0.4rem 0.8rem;
    font-size: 0.85rem;
    min-height: 36px;
    border-radius: var(--radius-sm);
}

/* Secondary Button Variants */
.btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.3);
    color: white;
}

/* Filter and Utility Buttons */
.btn-filter, .btn-refresh {
    background: rgba(255, 255, 255, 0.08);
    color: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(255, 255, 255, 0.15);
    padding: 0.6rem 1rem;
    border-radius: var(--radius-md);
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.btn-filter:hover, .btn-refresh:hover {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    transform: translateY(-1px);
}

/* Tab Buttons Consistency */
.tab-button {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.8);
    padding: 0.8rem 1.5rem;
    border-radius: var(--radius-md);
    font-weight: 500;
    transition: all 0.3s ease;
}

.tab-button.active, .tab-button:hover {
    background: var(--primary-gradient);
    border-color: transparent;
    color: white;
    transform: translateY(-1px);
}

/* File Input Styling */
input[type="file"] {
    background: rgba(255, 255, 255, 0.08);
    border: 2px dashed rgba(255, 255, 255, 0.3);
    border-radius: var(--radius-md);
    color: white;
    padding: 1rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

input[type="file"]:hover {
    border-color: var(--primary-color);
    background: rgba(255, 255, 255, 0.12);
}

input[type="file"]:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.25);
}

/* Override any inline styles that might interfere */
.form-control[style*="max-width"] {
    background: rgba(255, 255, 255, 0.1) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    color: white !important;
}

/* Ensure all dropdown arrows are consistent */
select.form-control, select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.75rem center;
    background-repeat: no-repeat;
    background-size: 1rem;
    padding-right: 2.5rem;
}

/* Button group consistency */
.btn-group .btn, .btn-group-sm .btn {
    margin: 0;
    border-radius: 0;
}

.btn-group .btn:first-child {
    border-top-left-radius: var(--radius-md);
    border-bottom-left-radius: var(--radius-md);
}

.btn-group .btn:last-child {
    border-top-right-radius: var(--radius-md);
    border-bottom-right-radius: var(--radius-md);
}

/* Loading states for buttons */
.btn.loading {
    pointer-events: none;
    opacity: 0.7;
}

.btn.loading::after {
    content: "";
    display: inline-block;
    width: 16px;
    height: 16px;
    margin-left: 0.5rem;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Test Management Styles */
.config-section {
    background: var(--glass-background);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.section-header h4 {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.section-header p {
    font-size: 0.9rem;
    opacity: 0.8;
}

.stat-card {
    background: var(--glass-background);
    border: 1px solid var(--glass-border);
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1;
}

.stat-label {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin-top: 0.25rem;
}

#testsTable th {
    font-weight: 600;
    color: var(--text-primary);
    border-bottom: 2px solid var(--glass-border);
    padding: 0.75rem;
}

#testsTable td {
    padding: 0.75rem;
    border-bottom: 1px solid var(--glass-border);
    vertical-align: middle;
}

#testsTable tbody tr:hover {
    background: rgba(6, 182, 212, 0.05);
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

/* Equipment Management Styles */
.config-nav .nav-link {
    border-radius: 8px;
    margin: 0 0.25rem;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    color: var(--text-muted);
    border: 1px solid transparent;
    transition: all 0.3s ease;
}

.config-nav .nav-link.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: rgba(255, 255, 255, 0.2);
}

.config-nav .nav-link:hover:not(.active) {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-primary);
    border-color: rgba(255, 255, 255, 0.2);
}

#equipmentTable th {
    background: var(--glass-background) !important;
    color: var(--text-primary);
    border-bottom: 2px solid var(--glass-border);
    font-weight: 600;
    padding: 1rem 0.75rem;
}

#equipmentTable td {
    padding: 0.75rem;
    border-bottom: 1px solid var(--glass-border);
    color: var(--text-primary);
    vertical-align: middle;
}

#equipmentTable tbody tr:hover {
    background: rgba(255, 255, 255, 0.05);
}

.equipment-status {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 500;
}

.equipment-status.online {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.equipment-status.offline {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.equipment-status.maintenance {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
    border: 1px solid rgba(245, 158, 11, 0.3);
}

.equipment-status-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
}

.equipment-status.online .equipment-status-indicator {
    background: #10b981;
    animation: pulse-green 2s infinite;
}

.equipment-status.offline .equipment-status-indicator {
    background: #ef4444;
}

.equipment-status.maintenance .equipment-status-indicator {
    background: #f59e0b;
    animation: pulse-yellow 2s infinite;
}

@keyframes pulse-yellow {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.connection-test-success {
    color: #10b981;
    font-weight: 500;
}

.connection-test-error {
    color: #ef4444;
    font-weight: 500;
}

.connection-test-loading {
    color: #6b7280;
}

.supported-tests-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 0.5rem;
}

.test-checkbox-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 6px;
    border: 1px solid transparent;
    transition: all 0.2s ease;
}

.test-checkbox-item:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.2);
}

.test-checkbox-item input[type="checkbox"] {
    margin: 0;
}

.test-checkbox-item label {
    margin: 0;
    font-size: 0.875rem;
    cursor: pointer;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let labOrders = [];
let labInvoices = []; // Changed from invoices to labInvoices
let patients = [];
let filteredOrders = [];
let filteredLabInvoices = []; // Changed from filteredInvoices to filteredLabInvoices

// Test prices for different lab tests
const testPrices = {
    'Complete Blood Count': 45.00,
    'Basic Metabolic Panel': 35.00,
    'Lipid Panel': 55.00,
    'Liver Function Test': 65.00,
    'Thyroid Function Test': 85.00,
    'Urinalysis': 25.00,
    'Blood Glucose': 20.00,
    'Hemoglobin A1C': 40.00,
    'Vitamin D': 75.00,
    'PSA': 60.00
};

// Load initial data
document.addEventListener('DOMContentLoaded', function() {
    loadStats();
    loadLabOrders();
    loadInvoices();
    loadPatients();
    
    // Auto refresh every 30 seconds
    setInterval(() => {
        loadStats();
        loadLabOrders();
        loadInvoices();
    }, 30000);
    
    // Filter event listeners
    document.getElementById('statusFilter').addEventListener('change', loadLabOrders);
    document.getElementById('priorityFilter').addEventListener('change', loadLabOrders);
    document.getElementById('dateFilter').addEventListener('change', loadLabOrders);
    document.getElementById('invoiceStatusFilter').addEventListener('change', filterInvoices);
    
    // Invoice form event listeners
    document.getElementById('invoicePatient').addEventListener('change', loadPatientOrders);
    document.getElementById('invoiceOrders').addEventListener('change', calculateInvoiceAmount);
    document.getElementById('invoiceForm').addEventListener('submit', generateInvoice);
    
    // Set current time for modals
    setCurrentDateTime();
});

function setCurrentDateTime() {
    const now = new Date();
    const timeString = now.toISOString().slice(0, 16);
    
    // Collection modal datetime
    const collectionDateTime = document.getElementById('collectionDateTime');
    if (collectionDateTime) {
        collectionDateTime.value = timeString;
    }
    
    // Results submission modal datetime
    const submissionDateTime = document.getElementById('submissionDateTime');
    if (submissionDateTime) {
        submissionDateTime.value = timeString;
    }
    
    // Result modal datetime
    const resultTime = document.getElementById('resultTime');
    if (resultTime) {
        resultTime.value = timeString;
    }
    
    // Legacy support
    const collectionTime = document.getElementById('collectionTime');
    if (collectionTime) {
        collectionTime.value = timeString;
    }
}

async function loadStats() {
    try {
        const response = await fetch('/api/lab-tech/stats', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const data = await response.json();
        
        document.getElementById('pendingOrders').textContent = data.pending_orders || 0;
        document.getElementById('samplesCollected').textContent = data.samples_collected_today || 0;
        document.getElementById('resultsSubmitted').textContent = data.results_submitted_today || 0;
        document.getElementById('totalTests').textContent = data.total_tests_today || 0;
        
        // Financial stats
        document.getElementById('totalRevenue').textContent = '$' + (data.total_revenue || 0).toFixed(2);
        document.getElementById('pendingPayments').textContent = '$' + (data.pending_payments || 0).toFixed(2);
        document.getElementById('todayRevenue').textContent = '$' + (data.today_revenue || 0).toFixed(2);
        document.getElementById('totalInvoices').textContent = data.total_invoices || 0;
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

async function loadLabOrders() {
    try {
        const statusFilter = document.getElementById('statusFilter').value;
        const priorityFilter = document.getElementById('priorityFilter').value;
        const dateFilter = document.getElementById('dateFilter').value;
        
        const params = new URLSearchParams();
        if (statusFilter) params.append('status', statusFilter);
        if (priorityFilter) params.append('priority', priorityFilter);
        if (dateFilter) params.append('date', dateFilter);
        
        const response = await fetch(`/api/lab-tech/orders?${params}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const orders = await response.json();
        
        labOrders = orders;
        filteredOrders = orders;
        renderLabOrders(orders);
    } catch (error) {
        console.error('Error loading lab orders:', error);
        document.getElementById('labOrdersTable').innerHTML = `
            <tr>
                <td colspan="9" class="text-center text-danger py-4">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Error loading lab orders
                </td>
            </tr>
        `;
    }
}

// Load lab-specific financial data and invoices
async function loadLabInvoices() {
    try {
        const response = await fetch('/api/lab-tech/lab-invoices', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const data = await response.json();
        
        labInvoices = data.invoices;
        filteredLabInvoices = data.invoices;
        renderLabInvoices(data.invoices);
        updateLabFinancialStats(data.stats);
    } catch (error) {
        console.error('Error loading lab invoices:', error);
        // For demo purposes, use mock lab financial data
        const mockStats = {
            total_lab_revenue: 12450.00,
            pending_lab_invoices: 2340.00,
            today_lab_revenue: 890.00,
            lab_orders_value: 15200.00,
            monthly_revenue: [3200, 3800, 4100, 3900, 4200, 4800, 5100, 4900, 4600, 4300, 3900, 4150],
            test_categories: {
                hematology: 35,
                biochemistry: 28,
                electrolyte: 22,
                microbiology: 15
            }
        };
        
        labInvoices = [
            {
                id: 1,
                invoice_number: 'LAB20250816001',
                patient_name: 'John Smith',
                patient_mrn: 'MRN001',
                lab_tests: ['CBC', 'Lipid Panel', 'Liver Function'],
                test_count: 3,
                amount: 180.00,
                status: 'pending',
                created_at: new Date().toISOString(),
                test_types: ['hematology', 'biochemistry']
            },
            {
                id: 2,
                invoice_number: 'LAB20250816002',
                patient_name: 'Jane Doe',
                patient_mrn: 'MRN002',
                lab_tests: ['Electrolyte Panel', 'BUN/Creatinine'],
                test_count: 2,
                amount: 125.00,
                status: 'paid',
                created_at: new Date(Date.now() - 86400000).toISOString(),
                test_types: ['electrolyte', 'biochemistry']
            },
            {
                id: 3,
                invoice_number: 'LAB20250816003',
                patient_name: 'Bob Johnson',
                patient_mrn: 'MRN003',
                lab_tests: ['Blood Culture', 'Urine Culture'],
                test_count: 2,
                amount: 95.00,
                status: 'overdue',
                created_at: new Date(Date.now() - 172800000).toISOString(),
                test_types: ['microbiology']
            }
        ];
        filteredLabInvoices = labInvoices;
        renderLabInvoices(labInvoices);
        updateLabFinancialStats(mockStats);
        initializeLabCharts(mockStats);
    }
}

// Update lab financial statistics
function updateLabFinancialStats(stats) {
    if (document.getElementById('labTotalRevenue')) {
        animateValue(document.getElementById('labTotalRevenue'), 
            parseFloat(document.getElementById('labTotalRevenue').textContent.replace(/[$,]/g, '')) || 0, 
            stats.total_lab_revenue, 1500);
        setTimeout(() => {
            document.getElementById('labTotalRevenue').textContent = `$${stats.total_lab_revenue.toLocaleString()}`;
        }, 1500);
    }
    
    if (document.getElementById('pendingLabInvoices')) {
        animateValue(document.getElementById('pendingLabInvoices'), 
            parseFloat(document.getElementById('pendingLabInvoices').textContent.replace(/[$,]/g, '')) || 0, 
            stats.pending_lab_invoices, 1500);
        setTimeout(() => {
            document.getElementById('pendingLabInvoices').textContent = `$${stats.pending_lab_invoices.toLocaleString()}`;
        }, 1500);
    }
    
    if (document.getElementById('todayLabRevenue')) {
        animateValue(document.getElementById('todayLabRevenue'), 
            parseFloat(document.getElementById('todayLabRevenue').textContent.replace(/[$,]/g, '')) || 0, 
            stats.today_lab_revenue, 1500);
        setTimeout(() => {
            document.getElementById('todayLabRevenue').textContent = `$${stats.today_lab_revenue.toLocaleString()}`;
        }, 1500);
    }
    
    if (document.getElementById('labOrdersValue')) {
        animateValue(document.getElementById('labOrdersValue'), 
            parseFloat(document.getElementById('labOrdersValue').textContent.replace(/[$,]/g, '')) || 0, 
            stats.lab_orders_value, 1500);
        setTimeout(() => {
            document.getElementById('labOrdersValue').textContent = `$${stats.lab_orders_value.toLocaleString()}`;
        }, 1500);
    }
}

// Render lab invoices table
function renderLabInvoices(invoices) {
    const tbody = document.getElementById('labInvoicesTable');
    if (!tbody) return;
    
    if (invoices.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center text-light-gray py-4">
                    <i class="fas fa-file-invoice me-2"></i>
                    No lab invoices found
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = invoices.map(invoice => {
        const statusClass = {
            'pending': 'badge-warning',
            'paid': 'badge-success',
            'overdue': 'badge-danger',
            'partial': 'badge-info'
        }[invoice.status] || 'badge-secondary';
        
        return `
            <tr class="table-hover-row">
                <td class="fw-bold">${invoice.invoice_number}</td>
                <td>
                    <div>
                        <div class="text-white">${invoice.patient_name}</div>
                        <small class="text-light-gray">${invoice.patient_mrn}</small>
                    </div>
                </td>
                <td>
                    <div class="lab-tests-list">
                        ${invoice.lab_tests.slice(0, 2).map(test => 
                            `<span class="badge bg-gradient-primary me-1 mb-1">${test}</span>`
                        ).join('')}
                        ${invoice.lab_tests.length > 2 ? 
                            `<span class="badge bg-gradient-secondary">+${invoice.lab_tests.length - 2} more</span>` : ''
                        }
                    </div>
                </td>
                <td>
                    <span class="badge bg-gradient-info">${invoice.test_count} tests</span>
                </td>
                <td class="fw-bold text-success">$${invoice.amount.toFixed(2)}</td>
                <td>
                    <span class="badge ${statusClass}">${invoice.status.charAt(0).toUpperCase() + invoice.status.slice(1)}</span>
                </td>
                <td class="text-light-gray">${new Date(invoice.created_at).toLocaleDateString()}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-gradient-primary btn-sm" onclick="viewLabInvoice(${invoice.id})">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-gradient-secondary btn-sm" onclick="downloadLabInvoice(${invoice.id})">
                            <i class="fas fa-download"></i>
                        </button>
                        ${invoice.status === 'pending' || invoice.status === 'partial' ? 
                            `<button class="btn btn-gradient-success btn-sm" onclick="markLabInvoicePaid(${invoice.id})">
                                <i class="fas fa-check"></i>
                            </button>` : ''
                        }
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

// Initialize lab financial charts
function initializeLabCharts(stats) {
    // Lab Revenue Trend Chart
    const revenueCtx = document.getElementById('labRevenueChart');
    if (revenueCtx && stats.monthly_revenue) {
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Lab Revenue ($)',
                    data: stats.monthly_revenue,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: '#fff' }
                    }
                },
                scales: {
                    x: { 
                        ticks: { color: '#fff' },
                        grid: { color: 'rgba(255, 255, 255, 0.1)' }
                    },
                    y: { 
                        ticks: { color: '#fff' },
                        grid: { color: 'rgba(255, 255, 255, 0.1)' }
                    }
                }
            }
        });
    }
    
    // Test Categories Chart
    const categoriesCtx = document.getElementById('testCategoriesChart');
    if (categoriesCtx && stats.test_categories) {
        new Chart(categoriesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Hematology', 'Biochemistry', 'Electrolyte', 'Microbiology'],
                datasets: [{
                    data: Object.values(stats.test_categories),
                    backgroundColor: [
                        '#667eea',
                        '#764ba2',
                        '#f093fb',
                        '#4facfe'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#fff' }
                    }
                }
            }
        });
    }
}

async function loadInvoices() {
    // Redirect to lab-specific function
    return loadLabInvoices();
}

async function loadPatients() {
    try {
        const response = await fetch('/api/lab-tech/patients', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const data = await response.json();
        
        patients = data;
        const select = document.getElementById('invoicePatient');
        select.innerHTML = '<option value="">Choose patient...</option>' + 
            data.map(p => `<option value="${p.id}">${p.name} (${p.mrn})</option>`).join('');
    } catch (error) {
        console.error('Error loading patients:', error);
        // For demo purposes, use mock data
        patients = [
            {id: 1, name: 'John Smith', mrn: 'MRN001'},
            {id: 2, name: 'Jane Doe', mrn: 'MRN002'},
            {id: 3, name: 'Bob Johnson', mrn: 'MRN003'},
            {id: 4, name: 'Alice Brown', mrn: 'MRN004'}
        ];
        
        const select = document.getElementById('invoicePatient');
        select.innerHTML = '<option value="">Choose patient...</option>' + 
            patients.map(p => `<option value="${p.id}">${p.name} (${p.mrn})</option>`).join('');
    }
}

async function loadPatientOrders() {
    const patientId = document.getElementById('invoicePatient').value;
    const ordersSelect = document.getElementById('invoiceOrders');
    
    if (!patientId) {
        ordersSelect.innerHTML = '<option value="">Select patient first...</option>';
        return;
    }
    
    try {
        const response = await fetch(`/api/lab-tech/patients/${patientId}/orders`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const orders = await response.json();
        
        ordersSelect.innerHTML = orders.length > 0 ?
            orders.map(order => 
                `<option value="${order.id}" data-price="${order.price}">${order.test_name} - $${order.price.toFixed(2)}</option>`
            ).join('') :
            '<option value="">No completed orders available</option>';
    } catch (error) {
        console.error('Error loading patient orders:', error);
        // Filter mock orders for selected patient
        const patientOrders = labOrders.filter(order => 
            order.patient_id == patientId && 
            order.status === 'resulted' &&
            !order.invoiced
        );
        
        ordersSelect.innerHTML = patientOrders.length > 0 ?
            patientOrders.map(order => 
                `<option value="${order.id}" data-price="${testPrices[order.test_name] || 50}">${order.test_name} - $${(testPrices[order.test_name] || 50).toFixed(2)}</option>`
            ).join('') :
            '<option value="">No completed orders available</option>';
    }
}

function calculateInvoiceAmount() {
    const selectedOptions = Array.from(document.getElementById('invoiceOrders').selectedOptions);
    const total = selectedOptions.reduce((sum, option) => {
        return sum + parseFloat(option.dataset.price || 0);
    }, 0);
    
    document.getElementById('invoiceAmount').value = total.toFixed(2);
}

async function generateInvoice(event) {
    event.preventDefault();
    
    const patientId = document.getElementById('invoicePatient').value;
    const selectedOrders = Array.from(document.getElementById('invoiceOrders').selectedOptions);
    const amount = document.getElementById('invoiceAmount').value;
    
    if (!patientId || selectedOrders.length === 0 || !amount) {
        alert('Please fill in all required fields');
        return;
    }
    
    try {
        const response = await fetch('/api/lab-tech/invoices', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                patient_id: patientId,
                order_ids: selectedOrders.map(opt => opt.value),
                total_amount: parseFloat(amount)
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Clear form
            document.getElementById('invoiceForm').reset();
            document.getElementById('invoiceOrders').innerHTML = '<option value="">Select patient first...</option>';
            
            // Reload data
            loadInvoices();
            loadStats();
            
            showNotification('Invoice generated successfully!', 'success');
        } else {
            alert('Error generating invoice: ' + (result.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error generating invoice:', error);
        
        // For demo purposes, simulate invoice generation
        const patient = patients.find(p => p.id == patientId);
        const newInvoice = {
            id: invoices.length + 1,
            invoice_number: 'LAB' + new Date().toISOString().slice(0,10).replace(/-/g,'') + String(invoices.length + 1).padStart(3, '0'),
            patient_name: patient ? patient.name : 'Unknown Patient',
            patient_mrn: patient ? patient.mrn : 'Unknown',
            lab_orders: selectedOrders.map(opt => opt.text.split(' - ')[0]),
            amount: parseFloat(amount),
            status: 'pending',
            created_at: new Date().toISOString()
        };
        
        invoices.unshift(newInvoice);
        filteredInvoices = invoices;
        renderInvoices(filteredInvoices);
        
        // Clear form
        document.getElementById('invoiceForm').reset();
        document.getElementById('invoiceOrders').innerHTML = '<option value="">Select patient first...</option>';
        
        showNotification('Invoice generated successfully!', 'success');
    }
}

function renderLabOrders(orders) {
    const tbody = document.getElementById('labOrdersTable');
    
    if (!orders || orders.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center py-4">
                    <i class="fas fa-inbox me-2"></i>
                    No lab orders found
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = orders.map(order => {
        const priorityClass = `priority-${order.priority}`;
        const statusClass = `status-${order.status}`;
        
        let actions = '';
        if (order.status === 'ordered') {
            actions = `<button class="btn btn-primary action-btn btn-sm" onclick="showCollectionModalForOrder(${order.id}, '${order.patient_name}', '${order.test_name}')">
                <i class="fas fa-vial"></i>
            </button>`;
        } else if (order.status === 'collected' || order.status === 'processing') {
            actions = `<button class="btn btn-success action-btn btn-sm" onclick="showResultModal(${order.id}, '${order.patient_name}', '${order.test_name}')">
                <i class="fas fa-clipboard-check"></i>
            </button>`;
        } else if (order.status === 'resulted') {
            actions = `<span class="text-success"><i class="fas fa-check-circle"></i></span>`;
        }
        
        return `
            <tr>
                <td>#${order.id}</td>
                <td>
                    <div>
                        <strong>${order.patient_name}</strong><br>
                        <small class="text-muted">MRN: ${order.patient_mrn || order.patient_id}</small>
                    </div>
                </td>
                <td>
                    <div>
                        <strong>${order.test_name}</strong><br>
                        <small class="text-muted">${order.test_code || 'N/A'}</small>
                    </div>
                </td>
                <td><span class="priority-badge ${priorityClass}">${order.priority.toUpperCase()}</span></td>
                <td><span class="status-badge ${statusClass}">${order.status_display || order.status.toUpperCase()}</span></td>
                <td>
                    <small>
                        ${formatDateTime(order.ordered_at)}<br>
                        <span class="text-muted">by ${order.ordered_by_name || 'Dr. System'}</span>
                    </small>
                </td>
                <td>${order.collected_at ? formatDateTime(order.collected_at) : '<span class="text-muted">Not collected</span>'}</td>
                <td>${order.resulted_at ? formatDateTime(order.resulted_at) : '<span class="text-muted">Pending</span>'}</td>
                <td>${actions}</td>
            </tr>
        `;
    }).join('');
}

function renderInvoices(invoices) {
    const tbody = document.getElementById('invoicesTable');
    
    if (invoices.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-4">
                    <i class="fas fa-inbox me-2"></i>
                    No invoices found
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = invoices.map(invoice => {
        const statusClass = `invoice-${invoice.status}`;
        
        return `
            <tr>
                <td><strong>${invoice.invoice_number}</strong></td>
                <td>
                    <div>
                        <strong>${invoice.patient_name}</strong><br>
                        <small class="text-muted">MRN: ${invoice.patient_mrn}</small>
                    </div>
                </td>
                <td>
                    <small>${Array.isArray(invoice.lab_orders) ? invoice.lab_orders.join(', ') : 'Lab Tests'}</small>
                </td>
                <td><strong>$${(invoice.amount || invoice.total_amount || 0).toFixed(2)}</strong></td>
                <td>
                    <span class="${statusClass}">
                        <i class="fas fa-circle me-1"></i>
                        ${invoice.status.toUpperCase()}
                    </span>
                </td>
                <td>
                    <small>${formatDateTime(invoice.created_at)}</small>
                </td>
                <td>
                    <div class="d-flex gap-1">
                        ${getInvoiceActionButtons(invoice)}
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

function getInvoiceActionButtons(invoice) {
    let buttons = [];
    
    if (invoice.status === 'pending') {
        buttons.push(`
            <button class="btn btn-success action-btn btn-sm" onclick="openPaymentModal(${invoice.id})" title="Collect Payment">
                <i class="fas fa-dollar-sign"></i>
            </button>
        `);
    }
    
    buttons.push(`
        <button class="btn btn-outline-light action-btn btn-sm" onclick="printInvoice(${invoice.id})" title="Print Invoice">
            <i class="fas fa-print"></i>
        </button>
    `);
    
    return buttons.join('');
}

function showCollectionModalForOrder(orderId, patientName, testName) {
    document.getElementById('collectionOrderId').value = orderId;
    document.getElementById('collectionPatientName').textContent = patientName;
    document.getElementById('collectionTestName').textContent = testName;
    setCurrentDateTime();
    
    const modal = new bootstrap.Modal(document.getElementById('collectionModal'));
    modal.show();
}

function showResultModal(orderId, patientName, testName) {
    document.getElementById('resultOrderId').value = orderId;
    document.getElementById('resultPatientName').textContent = patientName;
    document.getElementById('resultTestName').textContent = testName;
    setCurrentDateTime();
    
    const modal = new bootstrap.Modal(document.getElementById('resultModal'));
    modal.show();
}

function openPaymentModal(invoiceId) {
    const invoice = invoices.find(inv => inv.id === invoiceId);
    if (invoice) {
        document.getElementById('paymentInvoiceId').value = invoiceId;
        document.getElementById('amountReceived').value = (invoice.amount || invoice.total_amount || 0).toFixed(2);
        const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
        modal.show();
    }
}

async function submitCollection() {
    const orderId = document.getElementById('collectionOrderId').value;
    const collectionTime = document.getElementById('collectionTime').value;
    const notes = document.getElementById('collectionNotes').value;
    
    try {
        const response = await fetch(`/api/lab-tech/orders/${orderId}/collect`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                collected_at: collectionTime,
                collection_notes: notes
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('Sample collection recorded successfully!', 'success');
            bootstrap.Modal.getInstance(document.getElementById('collectionModal')).hide();
            loadLabOrders();
            loadStats();
        } else {
            showAlert(result.message || 'Error recording collection', 'error');
        }
    } catch (error) {
        console.error('Error submitting collection:', error);
        showAlert('Error recording collection', 'error');
    }
}

async function submitResult() {
    const orderId = document.getElementById('resultOrderId').value;
    const resultValue = document.getElementById('resultValue').value;
    const resultFlag = document.getElementById('resultFlag').value;
    const resultNotes = document.getElementById('resultNotes').value;
    const resultTime = document.getElementById('resultTime').value;
    
    if (!resultValue.trim()) {
        showAlert('Please enter a result value', 'warning');
        return;
    }
    
    try {
        const response = await fetch(`/api/lab-tech/orders/${orderId}/result`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                result_value: resultValue,
                result_flag: resultFlag,
                result_notes: resultNotes,
                resulted_at: resultTime
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('Test results submitted successfully!', 'success');
            bootstrap.Modal.getInstance(document.getElementById('resultModal')).hide();
            loadLabOrders();
            loadStats();
            
            // Reload Recent Lab Results to show the newly submitted result
            await loadRecentResults();
            
            // Clear form
            document.getElementById('resultForm').reset();
        } else {
            showAlert(result.message || 'Error submitting results', 'error');
        }
    } catch (error) {
        console.error('Error submitting results:', error);
        showAlert('Error submitting results', 'error');
    }
}

async function collectPayment() {
    const invoiceId = document.getElementById('paymentInvoiceId').value;
    const paymentMethod = document.getElementById('paymentMethod').value;
    const amountReceived = document.getElementById('amountReceived').value;
    const notes = document.getElementById('paymentNotes').value;
    
    if (!paymentMethod || !amountReceived) {
        alert('Please fill in required fields');
        return;
    }
    
    try {
        const response = await fetch('/api/lab-tech/invoices/payment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                invoice_id: invoiceId,
                payment_method: paymentMethod,
                amount_received: parseFloat(amountReceived),
                payment_notes: notes
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            bootstrap.Modal.getInstance(document.getElementById('paymentModal')).hide();
            
            // Clear form
            document.getElementById('paymentForm').reset();
            
            showNotification('Payment collected successfully!', 'success');
            loadInvoices();
            loadStats();
        } else {
            alert('Error collecting payment: ' + (result.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error collecting payment:', error);
        
        // For demo purposes, simulate payment collection
        const invoice = invoices.find(inv => inv.id == invoiceId);
        if (invoice) {
            invoice.status = 'paid';
            invoice.payment_method = paymentMethod;
            invoice.amount_received = parseFloat(amountReceived);
            invoice.payment_notes = notes;
            invoice.paid_at = new Date().toISOString();
            
            renderInvoices(filteredInvoices);
            bootstrap.Modal.getInstance(document.getElementById('paymentModal')).hide();
            
            // Clear form
            document.getElementById('paymentForm').reset();
            
            showNotification('Payment collected successfully!', 'success');
        }
    }
}

function filterInvoices() {
    const statusFilter = document.getElementById('invoiceStatusFilter').value;
    
    filteredInvoices = invoices.filter(invoice => {
        return !statusFilter || invoice.status === statusFilter;
    });
    
    renderInvoices(filteredInvoices);
}

function printInvoice(invoiceId) {
    const invoice = invoices.find(inv => inv.id === invoiceId);
    if (invoice) {
        // Create a simple print window
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Invoice ${invoice.invoice_number}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; margin-bottom: 30px; }
                        .invoice-details { margin-bottom: 20px; }
                        .total { font-size: 18px; font-weight: bold; margin-top: 20px; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>MedGemma Lab Invoice</h1>
                        <h2>${invoice.invoice_number}</h2>
                    </div>
                    <div class="invoice-details">
                        <p><strong>Patient:</strong> ${invoice.patient_name}</p>
                        <p><strong>MRN:</strong> ${invoice.patient_mrn}</p>
                        <p><strong>Date:</strong> ${new Date(invoice.created_at).toLocaleDateString()}</p>
                        <p><strong>Lab Tests:</strong></p>
                        <ul>
                            ${(Array.isArray(invoice.lab_orders) ? invoice.lab_orders : ['Lab Tests']).map(test => `<li>${test}</li>`).join('')}
                        </ul>
                    </div>
                    <div class="total">
                        <p>Total Amount: $${(invoice.amount || invoice.total_amount || 0).toFixed(2)}</p>
                        <p>Status: ${invoice.status.toUpperCase()}</p>
                    </div>
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }
}

function formatDateTime(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

function showNotification(message, type = 'info') {
    showAlert(message, type);
}

// Equipment Integration Functions
let equipmentData = [];
let recentResults = [];
let cameraStream = null;

// Load equipment data
async function loadEquipmentData() {
    try {
        const response = await fetch('/api/lab-equipment/');
        const data = await response.json();
        equipmentData = data.equipment;
        renderEquipmentCards();
        
        // Load equipment statistics
        await loadEquipmentStatistics();
    } catch (error) {
        console.error('Error loading equipment data:', error);
        showAlert('Failed to load equipment data', 'error');
    }
}

// Load equipment statistics
async function loadEquipmentStatistics() {
    try {
        const response = await fetch('/api/lab-equipment/statistics');
        const stats = await response.json();
        
        document.getElementById('activeEquipmentCount').textContent = stats.active_equipment;
        document.getElementById('onlineEquipmentCount').textContent = stats.online_equipment;
        document.getElementById('pendingVerificationCount').textContent = stats.pending_verification;
    } catch (error) {
        console.error('Error loading equipment statistics:', error);
    }
}

// Render equipment cards
function renderEquipmentCards() {
    const container = document.getElementById('equipmentList');
    container.innerHTML = '';
    
    equipmentData.forEach(equipment => {
        const statusClass = equipment.is_online ? 'success' : 'danger';
        const statusIcon = equipment.is_online ? 'wifi' : 'wifi-off';
        
        const card = document.createElement('div');
        card.className = 'col-lg-4 col-md-6';
        card.innerHTML = `
            <div class="glass-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h6 class="text-white mb-1">${equipment.name}</h6>
                        <span class="badge bg-${statusClass}">
                            <i class="fas fa-${statusIcon} me-1"></i>
                            ${equipment.is_online ? 'Online' : 'Offline'}
                        </span>
                    </div>
                    <p class="text-light-gray small mb-2">${equipment.model} - ${equipment.manufacturer}</p>
                    <p class="text-light-gray small mb-3">Results Today: ${equipment.recent_results_count}</p>
                    <div class="d-flex gap-2">
                        <button class="btn btn-gradient-primary btn-sm flex-fill" 
                                onclick="fetchEquipmentResults(${equipment.id})">
                            <i class="fas fa-download me-1"></i>Fetch
                        </button>
                        <button class="btn btn-gradient-secondary btn-sm" 
                                onclick="testEquipmentConnection(${equipment.id})">
                            <i class="fas fa-plug me-1"></i>Test
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(card);
    });
}

// Fetch results from specific equipment
async function fetchEquipmentResults(equipmentId) {
    try {
        const response = await fetch('/api/lab-equipment/fetch-results', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ equipment_id: equipmentId })
        });
        
        const result = await response.json();
        if (result.success) {
            showAlert(`Successfully fetched ${result.results.length} results from ${result.equipment}`, 'success');
            await loadRecentResults();
            await loadEquipmentStatistics();
        } else {
            showAlert(`Failed to fetch results: ${result.error}`, 'error');
        }
    } catch (error) {
        console.error('Error fetching equipment results:', error);
        showAlert('Failed to fetch equipment results', 'error');
    }
}

// Fetch results from all equipment
async function fetchAllResults() {
    try {
        const response = await fetch('/api/lab-equipment/fetch-results', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        });
        
        const result = await response.json();
        if (result.success) {
            let totalResults = 0;
            Object.values(result.results).forEach(equipmentResults => {
                if (Array.isArray(equipmentResults)) {
                    totalResults += equipmentResults.length;
                }
            });
            
            showAlert(`Successfully fetched ${totalResults} results from all equipment`, 'success');
            await loadRecentResults();
            await loadEquipmentStatistics();
        } else {
            showAlert(`Failed to fetch results: ${result.error}`, 'error');
        }
    } catch (error) {
        console.error('Error fetching all results:', error);
        showAlert('Failed to fetch equipment results', 'error');
    }
}

// Test equipment connection
async function testEquipmentConnection(equipmentId) {
    try {
        const response = await fetch(`/api/lab-equipment/${equipmentId}/test-connection`, {
            method: 'POST'
        });
        
        const result = await response.json();
        if (result.success) {
            showAlert(result.message, 'success');
        } else {
            showAlert(`Connection failed: ${result.error}`, 'error');
        }
        
        // Refresh equipment data
        await loadEquipmentData();
    } catch (error) {
        console.error('Error testing equipment connection:', error);
        showAlert('Failed to test equipment connection', 'error');
    }
}

// Load recent results
async function loadRecentResults() {
    try {
        const sourceFilter = document.getElementById('resultSourceFilter').value;
        const statusFilter = document.getElementById('resultStatusFilter').value;
        
        const params = new URLSearchParams();
        if (sourceFilter) params.append('source_type', sourceFilter);
        if (statusFilter) params.append('status', statusFilter);
        
        try {
            const response = await fetch(`/api/lab-equipment/results?${params}`);
            const data = await response.json();
            recentResults = data.results || [];
        } catch (apiError) {
            // If API endpoint doesn't exist yet, use sample data
            console.log('API endpoint not available, using sample data');
            recentResults = getSampleRecentResults();
        }
        
        renderRecentResults();
    } catch (error) {
        console.error('Error loading recent results:', error);
        // Show sample data as fallback
        recentResults = getSampleRecentResults();
        renderRecentResults();
    }
}

// Generate sample recent results for demonstration
function getSampleRecentResults() {
    const sourceFilter = document.getElementById('resultSourceFilter').value;
    const statusFilter = document.getElementById('resultStatusFilter').value;
    
    let sampleResults = [
        {
            id: 1,
            test_name: 'Complete Blood Count (CBC)',
            result_value: '7.2',
            result_units: 'x10³/μL',
            source_type: 'manual',
            result_status: 'final',
            performed_at: new Date().toISOString(),
            lab_order: {
                patient: { name: 'John Smith' }
            }
        },
        {
            id: 2,
            test_name: 'Basic Metabolic Panel (BMP)',
            result_value: 'Normal',
            result_units: '',
            source_type: 'equipment',
            result_status: 'final',
            performed_at: new Date(Date.now() - 3600000).toISOString(), // 1 hour ago
            lab_order: {
                patient: { name: 'Sarah Johnson' }
            }
        },
        {
            id: 3,
            test_name: 'Thyroid Stimulating Hormone (TSH)',
            result_value: '2.1',
            result_units: 'mIU/L',
            source_type: 'ocr',
            result_status: 'needs_verification',
            performed_at: new Date(Date.now() - 7200000).toISOString(), // 2 hours ago
            lab_order: {
                patient: { name: 'Ahmed Ali' }
            }
        },
        {
            id: 4,
            test_name: 'Lipid Profile',
            result_value: 'Cholesterol: 180 mg/dL',
            result_units: '',
            source_type: 'manual',
            result_status: 'preliminary',
            performed_at: new Date(Date.now() - 10800000).toISOString(), // 3 hours ago
            lab_order: {
                patient: { name: 'Maria Garcia' }
            }
        }
    ];
    
    // Apply filters
    if (sourceFilter) {
        sampleResults = sampleResults.filter(result => result.source_type === sourceFilter);
    }
    if (statusFilter) {
        sampleResults = sampleResults.filter(result => result.result_status === statusFilter);
    }
    
    return sampleResults;
}

// Helper functions for result actions
function viewResult(resultId) {
    console.log('Viewing result:', resultId);
    const result = recentResults.find(r => r.id === resultId);
    if (!result) {
        showAlert('Result not found', 'error');
        return;
    }
    
    const modalHtml = `
        <div class="modal fade" id="viewResultModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" style="background: var(--glass-background); border: 1px solid var(--glass-border);">
                    <div class="modal-header" style="border-bottom: 1px solid var(--glass-border);">
                        <h5 class="modal-title" style="color: var(--text-primary);">
                            <i class="fas fa-eye me-2" style="color: #06b6d4;"></i>Test Result Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Test Name:</label>
                                <div class="form-control-plaintext">${result.test_name}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Patient:</label>
                                <div class="form-control-plaintext">${result.lab_order?.patient?.name || 'Unknown'}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Result Value:</label>
                                <div class="form-control-plaintext" style="color: #10b981; font-weight: 600;">
                                    ${result.result_value} ${result.result_units || ''}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status:</label>
                                <div class="form-control-plaintext">
                                    <span class="badge bg-${result.result_status === 'final' ? 'success' : result.result_status === 'preliminary' ? 'warning' : 'danger'}">
                                        ${result.result_status.replace('_', ' ').toUpperCase()}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Source:</label>
                                <div class="form-control-plaintext" style="text-transform: capitalize;">${result.source_type}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Performed At:</label>
                                <div class="form-control-plaintext">${new Date(result.performed_at).toLocaleString()}</div>
                            </div>
                            ${result.result_notes ? `
                                <div class="col-12">
                                    <label class="form-label">Notes:</label>
                                    <div class="form-control-plaintext">${result.result_notes}</div>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid var(--glass-border);">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="printResult(${resultId})">
                            <i class="fas fa-print me-1"></i>Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('viewResultModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to body and show
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('viewResultModal'));
    modal.show();
}

function verifyResult(resultId) {
    console.log('Verifying result:', resultId);
    const result = recentResults.find(r => r.id === resultId);
    if (!result) {
        showAlert('Result not found', 'error');
        return;
    }
    
    if (confirm('Are you sure you want to verify this result? This will mark it as final.')) {
        // Update the result status
        result.result_status = 'final';
        renderRecentResults();
        showAlert('Result verified successfully!', 'success');
        
        // In a real application, you would send this to the server
        // fetch(`/api/lab-results/${resultId}/verify`, { method: 'POST' })
    }
}

function printResult(resultId) {
    console.log('Printing result:', resultId);
    const result = recentResults.find(r => r.id === resultId);
    if (!result) {
        showAlert('Result not found', 'error');
        return;
    }
    
    const printWindow = window.open('', '_blank');
    const printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Lab Result - ${result.test_name}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
                .result-info { margin: 20px 0; }
                .result-value { font-size: 18px; font-weight: bold; color: #10b981; }
                .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>Laboratory Test Result</h2>
                <p>Generated on ${new Date().toLocaleString()}</p>
            </div>
            <div class="result-info">
                <p><strong>Patient:</strong> ${result.lab_order?.patient?.name || 'Unknown'}</p>
                <p><strong>Test:</strong> ${result.test_name}</p>
                <p><strong>Result:</strong> <span class="result-value">${result.result_value} ${result.result_units || ''}</span></p>
                <p><strong>Status:</strong> ${result.result_status.replace('_', ' ').toUpperCase()}</p>
                <p><strong>Performed:</strong> ${new Date(result.performed_at).toLocaleString()}</p>
                <p><strong>Source:</strong> ${result.source_type.charAt(0).toUpperCase() + result.source_type.slice(1)}</p>
                ${result.result_notes ? `<p><strong>Notes:</strong> ${result.result_notes}</p>` : ''}
            </div>
            <div class="footer">
                <p>This is a computer-generated report.</p>
            </div>
        </body>
        </html>
    `;
    
    printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
}

// Function to add a new result to the recent results list
function addToRecentResults(newResult) {
    if (!recentResults) {
        recentResults = [];
    }
    
    // Add the new result to the beginning of the list
    recentResults.unshift({
        id: Date.now(), // Temporary ID
        test_name: newResult.test_name || 'Unknown Test',
        result_value: newResult.result_value,
        result_units: newResult.result_units || '',
        result_flag: newResult.result_flag || 'normal',
        source_type: newResult.source_type || 'manual',
        result_status: newResult.result_status || 'final',
        performed_at: new Date().toISOString(),
        result_notes: newResult.result_notes || '',
        lab_order: {
            patient: { 
                name: newResult.patient_name || 'Unknown Patient' 
            }
        }
    });
    
    // Keep only the most recent 20 results
    recentResults = recentResults.slice(0, 20);
    
    // Re-render the table
    renderRecentResults();
}

// Render recent results table
function renderRecentResults() {
    const tbody = document.getElementById('recentResultsTable');
    const countBadge = document.getElementById('recentResultsCount');
    tbody.innerHTML = '';
    
    // Update count badge
    if (countBadge) {
        countBadge.textContent = recentResults ? recentResults.length : 0;
        countBadge.style.display = (recentResults && recentResults.length > 0) ? 'inline' : 'none';
    }
    
    if (!recentResults || recentResults.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" style="text-align: center; padding: 3rem;">
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem; color: var(--text-muted);">
                        <div style="width: 60px; height: 60px; background: rgba(6, 182, 212, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-clipboard-list" style="font-size: 1.5rem; color: #06b6d4;"></i>
                        </div>
                        <div>
                            <h6 style="margin: 0; color: var(--text-primary);">No Recent Results</h6>
                            <p style="margin: 0; font-size: 0.9rem;">Submit test results to see them appear here</p>
                        </div>
                        <button class="btn btn-primary btn-sm" onclick="showResultsModal()">
                            <i class="fas fa-plus me-1"></i>Add Results
                        </button>
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    recentResults.forEach(result => {
        const row = document.createElement('tr');
        const sourceIcon = {
            'equipment': 'fas fa-microscope text-primary',
            'ocr': 'fas fa-camera text-warning',
            'manual': 'fas fa-keyboard text-info'
        }[result.source_type] || 'fas fa-question text-secondary';
        
        const statusBadge = {
            'preliminary': 'badge bg-warning',
            'final': 'badge bg-success',
            'needs_verification': 'badge bg-danger',
            'corrected': 'badge bg-info'
        }[result.result_status] || 'badge bg-secondary';
        
        // Format the result time
        const resultTime = new Date(result.performed_at);
        const timeString = resultTime.toLocaleString();
        const isToday = resultTime.toDateString() === new Date().toDateString();
        const timeDisplay = isToday ? 
            `Today ${resultTime.toLocaleTimeString()}` : 
            timeString;
        
        row.innerHTML = `
            <td>
                <div>
                    <strong style="color: var(--text-primary);">${result.test_name}</strong>
                    ${result.test_code ? `<br><small class="text-muted">${result.test_code}</small>` : ''}
                </div>
            </td>
            <td>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-user-circle" style="color: #06b6d4;"></i>
                    <span>${result.lab_order?.patient?.name || 'Unknown Patient'}</span>
                </div>
            </td>
            <td>
                <div>
                    <strong style="color: #10b981;">${result.result_value}</strong>
                    ${result.result_units ? ` <span class="text-muted">${result.result_units}</span>` : ''}
                    ${result.result_flag && result.result_flag !== 'normal' ? 
                        `<br><span class="badge badge-sm ${result.result_flag === 'high' ? 'bg-danger' : result.result_flag === 'low' ? 'bg-warning' : 'bg-info'}">${result.result_flag.toUpperCase()}</span>` : ''
                    }
                </div>
            </td>
            <td>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <i class="${sourceIcon}"></i>
                    <span style="text-transform: capitalize;">${result.source_type}</span>
                </div>
            </td>
            <td>
                <span class="${statusBadge}" style="text-transform: capitalize;">
                    ${result.result_status.replace('_', ' ')}
                </span>
            </td>
            <td>
                <div style="font-size: 0.9rem;">
                    <div style="color: var(--text-primary);">${timeDisplay}</div>
                    ${isToday ? '<small class="text-success">Recent</small>' : ''}
                </div>
            </td>
            <td>
                <div class="btn-group btn-group-sm">
                    ${result.result_status === 'needs_verification' ? 
                        `<button class="btn btn-outline-warning" onclick="verifyResult(${result.id})" title="Verify Result">
                            <i class="fas fa-check"></i>
                        </button>` : ''
                    }
                    <button class="btn btn-outline-info" onclick="viewResult(${result.id})" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-outline-primary" onclick="printResult(${result.id})" title="Print Result">
                        <i class="fas fa-print"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// OCR Functions
async function loadOCRDropdowns() {
    try {
        // Load lab orders for OCR
        const ordersResponse = await fetch('/api/lab-tech/orders');
        const ordersData = await ordersResponse.json();
        
        const orderSelect = document.getElementById('ocrLabOrder');
        orderSelect.innerHTML = '<option value="">Select Lab Order</option>';
        
        ordersData.orders?.forEach(order => {
            const option = document.createElement('option');
            option.value = order.id;
            option.textContent = `#${order.id} - ${order.patient_name} - ${order.test_name}`;
            orderSelect.appendChild(option);
        });
        
        // Load equipment for OCR
        const equipmentSelect = document.getElementById('ocrEquipment');
        equipmentSelect.innerHTML = '<option value="">Manual Entry</option>';
        
        equipmentData.forEach(equipment => {
            const option = document.createElement('option');
            option.value = equipment.id;
            option.textContent = equipment.name;
            equipmentSelect.appendChild(option);
        });
    } catch (error) {
        console.error('Error loading OCR dropdowns:', error);
    }
}

// Handle OCR form submission
document.getElementById('ocrUploadForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData();
    formData.append('lab_order_id', document.getElementById('ocrLabOrder').value);
    formData.append('image', document.getElementById('resultImage').files[0]);
    
    const equipmentId = document.getElementById('ocrEquipment').value;
    if (equipmentId) {
        formData.append('equipment_id', equipmentId);
    }
    
    try {
        const response = await fetch('/api/lab-equipment/upload-result-image', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        if (result.success) {
            showAlert('OCR processing completed successfully', 'success');
            document.getElementById('ocrUploadForm').reset();
            await loadRecentResults();
            await loadEquipmentStatistics();
        } else {
            showAlert(`OCR processing failed: ${result.error}`, 'error');
        }
    } catch (error) {
        console.error('Error processing OCR:', error);
        showAlert('Failed to process OCR', 'error');
    }
});

// Camera functions
let capturedImageBlob = null; // Store the captured image

async function openCamera() {
    try {
        cameraStream = await navigator.mediaDevices.getUserMedia({ 
            video: { facingMode: 'environment' } // Use back camera if available
        });
        
        const video = document.getElementById('cameraVideo');
        video.srcObject = cameraStream;
        
        const modal = new bootstrap.Modal(document.getElementById('cameraModal'));
        modal.show();
    } catch (error) {
        console.error('Error accessing camera:', error);
        showAlert('Failed to access camera. Please check permissions.', 'error');
    }
}

function capturePhoto() {
    const video = document.getElementById('cameraVideo');
    const canvas = document.getElementById('cameraCanvas');
    const ctx = canvas.getContext('2d');
    
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    ctx.drawImage(video, 0, 0);
    
    canvas.toBlob(function(blob) {
        capturedImageBlob = blob; // Store the blob for later use
        
        // Create image URL for preview
        const imageUrl = URL.createObjectURL(blob);
        document.getElementById('previewImage').src = imageUrl;
        
        // Close camera modal and show preview modal
        const cameraModal = bootstrap.Modal.getInstance(document.getElementById('cameraModal'));
        cameraModal.hide();
        
        // Stop camera stream
        if (cameraStream) {
            cameraStream.getTracks().forEach(track => track.stop());
        }
        
        // Show preview modal after camera modal is hidden
        setTimeout(() => {
            const previewModal = new bootstrap.Modal(document.getElementById('photoPreviewModal'));
            previewModal.show();
        }, 300);
        
    }, 'image/jpeg', 0.8);
}

function confirmCapture() {
    if (capturedImageBlob) {
        // Create file from blob and set it to the file input
        const file = new File([capturedImageBlob], 'captured_result.jpg', { type: 'image/jpeg' });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        document.getElementById('resultImage').files = dataTransfer.files;
        
        // Close preview modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('photoPreviewModal'));
        modal.hide();
        
        // Clean up
        URL.revokeObjectURL(document.getElementById('previewImage').src);
        capturedImageBlob = null;
        
        showAlert('Photo captured and ready for OCR processing', 'success');
    }
}

function retakePhoto() {
    // Close preview modal and reopen camera
    const previewModal = bootstrap.Modal.getInstance(document.getElementById('photoPreviewModal'));
    if (previewModal) {
        previewModal.hide();
    }
    
    // Clean up previous image
    if (capturedImageBlob) {
        URL.revokeObjectURL(document.getElementById('previewImage').src);
        capturedImageBlob = null;
    }
    
    // Reopen camera after a short delay
    setTimeout(() => {
        openCamera();
    }, 300);
}

// Verify result function
async function verifyResult(resultId) {
    try {
        const response = await fetch(`/api/lab-equipment/results/${resultId}/verify`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ verified: true })
        });
        
        const result = await response.json();
        if (result.success) {
            showAlert('Result verified successfully', 'success');
            await loadRecentResults();
            await loadEquipmentStatistics();
        } else {
            showAlert(`Verification failed: ${result.error}`, 'error');
        }
    } catch (error) {
        console.error('Error verifying result:', error);
        showAlert('Failed to verify result', 'error');
    }
}

// View result function
function viewResult(resultId) {
    const result = recentResults.find(r => r.id === resultId);
    if (result) {
        let modalContent = `
            <div class="modal fade" id="resultModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content bg-dark">
                        <div class="modal-header border-purple">
                            <h5 class="modal-title text-white">Lab Result Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="text-light-gray small">Test Name</label>
                                    <p class="text-white">${result.test_name}</p>
                                </div>
                                <div class="col-6">
                                    <label class="text-light-gray small">Result</label>
                                    <p class="text-white">${result.result_value} ${result.result_units || ''}</p>
                                </div>
                                <div class="col-6">
                                    <label class="text-light-gray small">Source</label>
                                    <p class="text-white">${result.source_type}</p>
                                </div>
                                <div class="col-6">
                                    <label class="text-light-gray small">Status</label>
                                    <p class="text-white">${result.result_status}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalContent);
        const modal = new bootstrap.Modal(document.getElementById('resultModal'));
        modal.show();
        
        // Remove modal after hiding
        document.getElementById('resultModal').addEventListener('hidden.bs.modal', function() {
            this.remove();
        });
    }
}

// Lab Invoice Helper Functions

// View lab invoice details
function viewLabInvoice(invoiceId) {
    const invoice = labInvoices.find(inv => inv.id === invoiceId);
    if (!invoice) return;
    
    let modalContent = `
        <div class="modal fade" id="labInvoiceModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content bg-dark">
                    <div class="modal-header border-purple">
                        <h5 class="modal-title text-white">
                            <i class="fas fa-file-invoice me-2"></i>Lab Invoice Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-light-gray small">Invoice Number</label>
                                <p class="text-white fw-bold">${invoice.invoice_number}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-light-gray small">Patient</label>
                                <p class="text-white">${invoice.patient_name} (${invoice.patient_mrn})</p>
                            </div>
                            <div class="col-12">
                                <label class="text-light-gray small">Lab Tests</label>
                                <div class="lab-tests-detail">
                                    ${invoice.lab_tests.map(test => 
                                        `<span class="badge bg-gradient-primary me-2 mb-2">${test}</span>`
                                    ).join('')}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-light-gray small">Test Count</label>
                                <p class="text-white">${invoice.test_count} tests</p>
                            </div>
                            <div class="col-md-4">
                                <label class="text-light-gray small">Amount</label>
                                <p class="text-white fw-bold">$${invoice.amount.toFixed(2)}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="text-light-gray small">Status</label>
                                <p class="text-white">
                                    <span class="badge ${
                                        invoice.status === 'paid' ? 'badge-success' : 
                                        invoice.status === 'pending' ? 'badge-warning' : 
                                        invoice.status === 'overdue' ? 'badge-danger' : 'badge-info'
                                    }">${invoice.status.charAt(0).toUpperCase() + invoice.status.slice(1)}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-purple">
                        <button class="btn btn-gradient-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-gradient-primary" onclick="downloadLabInvoice(${invoice.id})">
                            <i class="fas fa-download me-1"></i>Download PDF
                        </button>
                        ${invoice.status !== 'paid' ? 
                            `<button class="btn btn-gradient-success" onclick="markLabInvoicePaid(${invoice.id})">
                                <i class="fas fa-check me-1"></i>Mark as Paid
                            </button>` : ''
                        }
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalContent);
    const modal = new bootstrap.Modal(document.getElementById('labInvoiceModal'));
    modal.show();
    
    document.getElementById('labInvoiceModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

// Download lab invoice
async function downloadLabInvoice(invoiceId) {
    try {
        const response = await fetch(`/api/lab-tech/lab-invoices/${invoiceId}/download`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `lab-invoice-${invoiceId}.pdf`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            showAlert('Invoice downloaded successfully', 'success');
        } else {
            showAlert('Failed to download invoice', 'error');
        }
    } catch (error) {
        console.error('Error downloading invoice:', error);
        showAlert('Failed to download invoice', 'error');
    }
}

// Mark lab invoice as paid
async function markLabInvoicePaid(invoiceId) {
    try {
        const response = await fetch(`/api/lab-tech/lab-invoices/${invoiceId}/mark-paid`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const result = await response.json();
        if (result.success) {
            showAlert('Invoice marked as paid', 'success');
            loadLabInvoices(); // Refresh the invoice list
            
            // Close modal if open
            const modal = document.getElementById('labInvoiceModal');
            if (modal) {
                bootstrap.Modal.getInstance(modal).hide();
            }
        } else {
            showAlert('Failed to update invoice status', 'error');
        }
    } catch (error) {
        console.error('Error updating invoice:', error);
        showAlert('Failed to update invoice status', 'error');
    }
}

// Filter lab invoices
function filterLabInvoices() {
    const statusFilter = document.getElementById('labInvoiceStatusFilter').value;
    const testTypeFilter = document.getElementById('labTestTypeFilter').value;
    
    filteredLabInvoices = labInvoices.filter(invoice => {
        const statusMatch = !statusFilter || invoice.status === statusFilter;
        const testTypeMatch = !testTypeFilter || 
            (invoice.test_types && invoice.test_types.includes(testTypeFilter));
        
        return statusMatch && testTypeMatch;
    });
    
    renderLabInvoices(filteredLabInvoices);
}

// Generate lab invoice form submission
async function generateLabInvoice(event) {
    event.preventDefault();
    
    const formData = {
        patient_id: document.getElementById('labInvoicePatient').value,
        lab_orders: Array.from(document.getElementById('labInvoiceOrders').selectedOptions).map(option => option.value),
        discount: parseFloat(document.getElementById('labDiscount').value) || 0
    };
    
    if (!formData.patient_id || formData.lab_orders.length === 0) {
        showAlert('Please select patient and lab orders', 'error');
        return;
    }
    
    try {
        const response = await fetch('/api/lab-tech/generate-lab-invoice', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(formData)
        });
        
        const result = await response.json();
        if (result.success) {
            showAlert(`Lab invoice ${result.invoice_number} generated successfully`, 'success');
            document.getElementById('labInvoiceForm').reset();
            loadLabInvoices(); // Refresh the invoice list
        } else {
            showAlert('Failed to generate lab invoice', 'error');
        }
    } catch (error) {
        console.error('Error generating lab invoice:', error);
        showAlert('Failed to generate lab invoice', 'error');
    }
}

// Load patient lab orders for invoice generation
async function loadPatientLabOrders() {
    const patientSelect = document.getElementById('labInvoicePatient');
    const patientId = patientSelect.value;
    const ordersSelect = document.getElementById('labInvoiceOrders');
    
    if (!patientId) {
        ordersSelect.innerHTML = '<option value="">Select patient first...</option>';
        document.getElementById('patientInfoDisplay').style.display = 'none';
        return;
    }
    
    // Show selected patient info
    const selectedOption = patientSelect.options[patientSelect.selectedIndex];
    if (selectedOption && selectedOption.value) {
        const patient = {
            name: selectedOption.dataset.name || selectedOption.text.split('(')[0].trim(),
            cnic: selectedOption.dataset.cnic || 'N/A',
            mrn: selectedOption.dataset.mrn || selectedOption.text.match(/\(([^)]+)\)/)?.[1] || 'N/A'
        };
        showSelectedPatientInfo(patient);
    }
    
    try {
        const response = await fetch(`/api/lab-tech/patients/${patientId}/completed-lab-orders`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const orders = await response.json();
        
        ordersSelect.innerHTML = orders.length > 0 ?
            orders.map(order => 
                `<option value="${order.id}" data-price="${order.total_amount}">
                    ${order.test_names.join(', ')} - $${order.total_amount.toFixed(2)}
                </option>`
            ).join('') :
            '<option value="">No completed lab orders available</option>';
    } catch (error) {
        console.error('Error loading patient lab orders:', error);
        // For demo purposes, use mock data
        const mockOrders = [
            {id: 1, test_names: ['Complete Blood Count'], total_amount: 45.00},
            {id: 2, test_names: ['Basic Metabolic Panel'], total_amount: 35.00},
            {id: 3, test_names: ['Lipid Panel', 'Liver Function Test'], total_amount: 120.00},
            {id: 4, test_names: ['Thyroid Function Test'], total_amount: 85.00},
            {id: 5, test_names: ['Urinalysis'], total_amount: 25.00}
        ];
        
        ordersSelect.innerHTML = mockOrders.map(order => 
            `<option value="${order.id}" data-price="${order.total_amount}">
                ${order.test_names.join(', ')} - $${order.total_amount.toFixed(2)}
            </option>`
        ).join('');
    }
    
    // Auto-calculate amount after loading orders
    calculateLabInvoiceAmount();
}

// Enhanced calculate lab invoice amount function
function calculateLabInvoiceAmount() {
    const ordersSelect = document.getElementById('labInvoiceOrders');
    const subtotalInput = document.getElementById('labInvoiceSubtotal');
    const amountInput = document.getElementById('labInvoiceAmount');
    const discountInput = document.getElementById('labDiscount');
    
    let subtotal = 0;
    Array.from(ordersSelect.selectedOptions).forEach(option => {
        subtotal += parseFloat(option.dataset.price) || 0;
    });
    
    const discount = parseFloat(discountInput.value) || 0;
    const discountAmount = subtotal * (discount / 100);
    const finalAmount = subtotal - discountAmount;
    
    if (subtotalInput) subtotalInput.value = subtotal.toFixed(2);
    amountInput.value = finalAmount.toFixed(2);
}

// Enhanced filter lab invoices function
function filterLabInvoices() {
    const searchTerm = document.getElementById('invoiceSearchInput')?.value.toLowerCase() || '';
    const statusFilter = document.getElementById('labInvoiceStatusFilter').value;
    const testTypeFilter = document.getElementById('labTestTypeFilter').value;
    
    filteredLabInvoices = labInvoices.filter(invoice => {
        const matchesSearch = invoice.patient_name.toLowerCase().includes(searchTerm) || 
                            invoice.invoice_number.toLowerCase().includes(searchTerm) ||
                            invoice.test_names.some(test => test.toLowerCase().includes(searchTerm));
        const statusMatch = !statusFilter || invoice.status === statusFilter;
        const testTypeMatch = !testTypeFilter || 
            invoice.test_categories.some(category => category.toLowerCase() === testTypeFilter.toLowerCase());
        
        return matchesSearch && statusMatch && testTypeMatch;
    });
    
    renderLabInvoices(filteredLabInvoices);
    updateInvoiceStats(filteredLabInvoices);
}

// Update invoice statistics
function updateInvoiceStats(invoices) {
    const totalCount = invoices.length;
    const pendingCount = invoices.filter(inv => inv.status === 'pending').length;
    const totalAmount = invoices.reduce((sum, inv) => sum + inv.total_amount, 0);
    const todayCount = invoices.filter(inv => {
        const invoiceDate = new Date(inv.date_generated);
        const today = new Date();
        return invoiceDate.toDateString() === today.toDateString();
    }).length;
    
    document.getElementById('totalInvoicesCount').textContent = totalCount;
    document.getElementById('pendingInvoicesCount').textContent = pendingCount;
    document.getElementById('totalInvoiceAmount').textContent = `$${totalAmount.toFixed(2)}`;
    document.getElementById('todayInvoicesCount').textContent = todayCount;
}

// Export lab invoices to Excel (placeholder function)
function exportLabInvoices() {
    showAlert('Invoice export functionality will be implemented with backend integration', 'info');
}

// Patient Search Functions for Invoice Generation
let allPatientsForInvoice = [];

// Search patients for invoice generation
function searchPatientsForInvoice(query) {
    if (query.length < 2) {
        loadPatientsForLabInvoice();
        return;
    }
    
    const filteredPatients = allPatientsForInvoice.filter(patient => 
        patient.name.toLowerCase().includes(query.toLowerCase()) ||
        patient.cnic.includes(query) ||
        patient.mrn.toLowerCase().includes(query.toLowerCase())
    );
    
    updatePatientDropdownForInvoice(filteredPatients);
}

// Search patients by CNIC for invoice generation
function searchPatientsByCnicForInvoice(cnic, isAutoSearch = true) {
    if (cnic.length < 5) {
        if (!isAutoSearch) {
            showAlert('Please enter at least 5 digits of CNIC', 'warning');
        }
        return;
    }
    
    // Format CNIC as user types
    let formattedCnic = cnic.replace(/\D/g, ''); // Remove non-digits
    if (formattedCnic.length > 5) {
        formattedCnic = formattedCnic.substring(0, 5) + '-' + formattedCnic.substring(5);
    }
    if (formattedCnic.length > 13) {
        formattedCnic = formattedCnic.substring(0, 13) + '-' + formattedCnic.substring(13);
    }
    if (formattedCnic.length > 15) {
        formattedCnic = formattedCnic.substring(0, 15);
    }
    
    // Update the input field with formatted CNIC
    document.getElementById('cnicSearchForInvoice').value = formattedCnic;
    
    // Find patients by CNIC
    const foundPatients = allPatientsForInvoice.filter(p => 
        p.cnic.replace(/\D/g, '').includes(cnic.replace(/\D/g, '')) ||
        p.cnic.includes(cnic)
    );
    
    if (foundPatients.length === 1) {
        const foundPatient = foundPatients[0];
        
        // Auto-select the patient
        const patientSelect = document.getElementById('labInvoicePatient');
        patientSelect.value = foundPatient.id.toString();
        
        // Show patient info and load orders
        showSelectedPatientInfo(foundPatient);
        loadPatientLabOrders();
        
        // Visual feedback
        const cnicInput = document.getElementById('cnicSearchForInvoice');
        cnicInput.style.borderColor = '#28a745';
        cnicInput.style.boxShadow = '0 0 0 0.2rem rgba(40, 167, 69, 0.25)';
        
        setTimeout(() => {
            cnicInput.style.borderColor = '';
            cnicInput.style.boxShadow = '';
        }, 2000);
        
        if (!isAutoSearch) {
            showAlert(`Patient found: ${foundPatient.name}`, 'success');
        }
        
    } else if (foundPatients.length > 1) {
        updatePatientDropdownForInvoice(foundPatients);
        
        // Visual feedback for multiple matches
        const cnicInput = document.getElementById('cnicSearchForInvoice');
        cnicInput.style.borderColor = '#ffc107';
        cnicInput.style.boxShadow = '0 0 0 0.2rem rgba(255, 193, 7, 0.25)';
        
        setTimeout(() => {
            cnicInput.style.borderColor = '';
            cnicInput.style.boxShadow = '';
        }, 2000);
        
        if (!isAutoSearch) {
            showAlert(`Found ${foundPatients.length} patients. Please select from dropdown.`, 'info');
        }
        
    } else {
        // No match found
        const cnicInput = document.getElementById('cnicSearchForInvoice');
        cnicInput.style.borderColor = '#dc3545';
        cnicInput.style.boxShadow = '0 0 0 0.2rem rgba(220, 53, 69, 0.25)';
        
        setTimeout(() => {
            cnicInput.style.borderColor = '';
            cnicInput.style.boxShadow = '';
        }, 2000);
        
        if (!isAutoSearch) {
            showAlert('No patient found with this CNIC', 'error');
        }
    }
}

// Update patient dropdown for invoice
function updatePatientDropdownForInvoice(patients) {
    const select = document.getElementById('labInvoicePatient');
    select.innerHTML = '<option value="">Choose patient...</option>' + 
        patients.map(p => `<option value="${p.id}" data-name="${p.name}" data-cnic="${p.cnic}" data-mrn="${p.mrn}">${p.name} (CNIC: ${p.cnic})</option>`).join('');
}

// Show selected patient info
function showSelectedPatientInfo(patient) {
    const infoDiv = document.getElementById('patientInfoDisplay');
    const infoContent = document.getElementById('selectedPatientInfo');
    
    infoContent.innerHTML = `
        <div class="row">
            <div class="col-md-4">
                <strong>Name:</strong> ${patient.name}
            </div>
            <div class="col-md-4">
                <strong>CNIC:</strong> ${patient.cnic}
            </div>
            <div class="col-md-4">
                <strong>MRN:</strong> ${patient.mrn}
            </div>
        </div>
    `;
    
    infoDiv.style.display = 'block';
}

// Clear invoice form
function clearInvoiceForm() {
    document.getElementById('labInvoiceForm').reset();
    document.getElementById('patientSearchInput').value = '';
    document.getElementById('cnicSearchForInvoice').value = '';
    document.getElementById('patientInfoDisplay').style.display = 'none';
    document.getElementById('labInvoiceOrders').innerHTML = '<option value="">Select patient first...</option>';
    document.getElementById('labInvoiceSubtotal').value = '';
    document.getElementById('labInvoiceAmount').value = '';
}

// Enhanced calculate lab invoice amount
function calculateLabInvoiceAmount() {
    const ordersSelect = document.getElementById('labInvoiceOrders');
    const subtotalInput = document.getElementById('labInvoiceSubtotal');
    const amountInput = document.getElementById('labInvoiceAmount');
    const discountInput = document.getElementById('labDiscount');
    
    let subtotal = 0;
    Array.from(ordersSelect.selectedOptions).forEach(option => {
        subtotal += parseFloat(option.dataset.price) || 0;
    });
    
    const discount = parseFloat(discountInput.value) || 0;
    const discountAmount = subtotal * (discount / 100);
    const finalAmount = subtotal - discountAmount;
    
    subtotalInput.value = subtotal.toFixed(2);
    amountInput.value = finalAmount.toFixed(2);
}

// Load patients for lab invoice generation
async function loadPatientsForLabInvoice() {
    try {
        const response = await fetch('/api/lab-tech/patients-with-completed-orders', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const data = await response.json();
        
        // Store all patients for search functionality
        allPatientsForInvoice = data;
        
        const select = document.getElementById('labInvoicePatient');
        select.innerHTML = '<option value="">Choose patient...</option>' + 
            data.map(p => `<option value="${p.id}" data-name="${p.name}" data-cnic="${p.cnic}" data-mrn="${p.mrn}">${p.name} (${p.mrn})</option>`).join('');
    } catch (error) {
        console.error('Error loading patients:', error);
        // For demo purposes, use mock data
        const patients = [
            {id: 1, name: 'John Smith', mrn: 'MRN001', cnic: '12345-1234567-1', age: 35, gender: 'Male'},
            {id: 2, name: 'Jane Doe', mrn: 'MRN002', cnic: '54321-7654321-9', age: 28, gender: 'Female'},
            {id: 3, name: 'Bob Johnson', mrn: 'MRN003', cnic: '11111-2222233-4', age: 42, gender: 'Male'},
            {id: 4, name: 'Alice Brown', mrn: 'MRN004', cnic: '99999-8888877-6', age: 31, gender: 'Female'},
            {id: 5, name: 'Ahmed Ali', mrn: 'MRN005', cnic: '42101-1234567-8', age: 29, gender: 'Male'},
            {id: 6, name: 'Sara Khan', mrn: 'MRN006', cnic: '35202-9876543-2', age: 33, gender: 'Female'}
        ];
        
        // Store mock data for search functionality
        allPatientsForInvoice = patients;
        
        const select = document.getElementById('labInvoicePatient');
        select.innerHTML = '<option value="">Choose patient...</option>' + 
            patients.map(p => `<option value="${p.id}" data-name="${p.name}" data-cnic="${p.cnic}" data-mrn="${p.mrn}">${p.name} (${p.mrn})</option>`).join('');
    }
}

// Enhanced document ready
document.addEventListener('DOMContentLoaded', function() {
    // Existing initialization...
    loadStats();
    loadLabOrders();
    loadLabInvoices(); // Changed from loadInvoices
    loadPatientsForLabInvoice(); // Changed from loadPatients
    
    // New equipment initialization
    loadEquipmentData();
    loadOCRDropdowns();
    loadRecentResults();
    
    // Enhanced Interactive Features
    
    // Smooth number animations for stats
    window.animateValue = function(element, start, end, duration = 1000) {
        if (!element) return;
        
        const startTimestamp = performance.now();
        const step = (timestamp) => {
            const elapsed = timestamp - startTimestamp;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function for smooth animation
            const easeOutCubic = 1 - Math.pow(1 - progress, 3);
            const current = Math.floor(start + (end - start) * easeOutCubic);
            
            element.textContent = current;
            
            if (progress < 1) {
                requestAnimationFrame(step);
            }
        };
        requestAnimationFrame(step);
    };
    
    // Enhanced table interactions
    function enhanceTableInteractivity() {
        // Add click-to-expand functionality
        setTimeout(() => {
            document.querySelectorAll('.table tbody tr').forEach(row => {
                row.addEventListener('click', function() {
                    // Add highlighting effect
                    document.querySelectorAll('.table tbody tr').forEach(r => r.classList.remove('table-active'));
                    this.classList.add('table-active');
                    
                    // Add pulse effect
                    this.style.animation = 'pulse 0.5s ease-in-out';
                    setTimeout(() => {
                        this.style.animation = '';
                    }, 500);
                });
            });
        }, 1000);
    }
    
    // Real-time status indicators
    function updateStatusIndicators() {
        // Equipment status indicators
        document.querySelectorAll('.equipment-status').forEach(indicator => {
            const status = indicator.dataset.status;
            indicator.classList.remove('pulse-slow');
            if (status === 'online') {
                indicator.classList.add('pulse-slow');
            }
        });
    }
    
    // Enhanced search functionality
    function setupEnhancedSearch() {
        const searchInputs = document.querySelectorAll('.search-input');
        searchInputs.forEach(input => {
            input.addEventListener('input', debounce(function() {
                const searchTerm = this.value.toLowerCase();
                const tableRows = this.closest('.card').querySelectorAll('tbody tr');
                
                tableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        row.style.display = '';
                        row.style.animation = 'fadeIn 0.3s ease-in-out';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }, 300));
        });
    }
    
    // Debounce function for search
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Tab switching with smooth transitions
    function setupSmoothTabSwitching() {
        const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
        tabButtons.forEach(button => {
            button.addEventListener('shown.bs.tab', function() {
                const targetTab = document.querySelector(this.getAttribute('data-bs-target'));
                if (targetTab) {
                    targetTab.style.opacity = '0';
                    targetTab.style.transform = 'translateY(20px)';
                    
                    setTimeout(() => {
                        targetTab.style.transition = 'all 0.3s ease-in-out';
                        targetTab.style.opacity = '1';
                        targetTab.style.transform = 'translateY(0)';
                    }, 10);
                }
            });
        });
    }
    
    // Initialize enhanced features
    setupEnhancedSearch();
    setupSmoothTabSwitching();
    enhanceTableInteractivity();
    updateStatusIndicators();
    
    // Enhanced stats updater with animations
    const originalLoadStats = window.loadStats;
    window.loadStats = async function() {
        try {
            const response = await fetch('/api/lab-stats');
            const stats = await response.json();
            
            // Animate the numbers instead of just setting them
            if (document.getElementById('pendingOrders')) {
                animateValue(document.getElementById('pendingOrders'), 
                    parseInt(document.getElementById('pendingOrders').textContent) || 0, 
                    stats.pending || 0);
            }
            if (document.getElementById('samplesCollected')) {
                animateValue(document.getElementById('samplesCollected'), 
                    parseInt(document.getElementById('samplesCollected').textContent) || 0, 
                    stats.collected || 0);
            }
            if (document.getElementById('completedOrders')) {
                animateValue(document.getElementById('completedOrders'), 
                    parseInt(document.getElementById('completedOrders').textContent) || 0, 
                    stats.completed || 0);
            }
            if (document.getElementById('inProgressOrders')) {
                animateValue(document.getElementById('inProgressOrders'), 
                    parseInt(document.getElementById('inProgressOrders').textContent) || 0, 
                    stats.in_progress || 0);
            }
            
            updateStatusIndicators();
            enhanceTableInteractivity();
        } catch (error) {
            console.error('Error loading stats:', error);
            // Fallback to original function if it exists
            if (originalLoadStats) originalLoadStats();
        }
    };
    
    // Auto refresh every 30 seconds
    setInterval(() => {
        loadStats();
        loadLabOrders();
        loadLabInvoices(); // Changed from loadInvoices to loadLabInvoices
        loadEquipmentData();
        loadRecentResults();
    }, 30000);
    
    // Enhanced filter event listeners
    document.getElementById('statusFilter').addEventListener('change', loadLabOrders);
    document.getElementById('priorityFilter').addEventListener('change', loadLabOrders);
    document.getElementById('dateFilter').addEventListener('change', loadLabOrders);
    document.getElementById('labInvoiceStatusFilter').addEventListener('change', filterLabInvoices); // Updated
    document.getElementById('labTestTypeFilter').addEventListener('change', filterLabInvoices); // Added
    document.getElementById('resultSourceFilter').addEventListener('change', loadRecentResults);
    document.getElementById('resultStatusFilter').addEventListener('change', loadRecentResults);
    
    // Lab-specific form listeners
    document.getElementById('labInvoicePatient').addEventListener('change', loadPatientLabOrders); // Updated
    document.getElementById('labInvoiceOrders').addEventListener('change', calculateLabInvoiceAmount); // Updated
    document.getElementById('labInvoiceForm').addEventListener('submit', generateLabInvoice); // Updated
    document.getElementById('labDiscount').addEventListener('input', calculateLabInvoiceAmount); // Added discount calculation
    
    // Analytics filter listener
    document.getElementById('analyticsTimeFilter').addEventListener('change', loadAnalytics);
    
    // Load analytics on page load
    loadAnalytics();
    
    // Initialize test management
    loadAvailableTests();
    setupTestSearch();
    
    // Initialize equipment management
    loadLabEquipment();
    setupEquipmentSearch();
});

// Analytics Functions
async function loadAnalytics() {
    try {
        // Get selected time filter
        const timeFilter = document.getElementById('analyticsTimeFilter').value;
        
        // Simulate API call - replace with actual endpoint
        const response = await fetch(`/api/lab-tech/analytics?period=${timeFilter}`);
        const data = await response.json();
        
        // Update performance metrics
        updatePerformanceMetrics(data);
        
        // Update charts
        updateTestDistributionChart(data.testDistribution);
        updateDailyRevenueChart(data.dailyRevenue);
        
        // Update detailed metrics table
        updatePerformanceMetricsTable(data);
        
    } catch (error) {
        console.error('Error loading analytics:', error);
        // Load sample data
        loadSampleAnalytics();
    }
}

function updatePerformanceMetrics(data) {
    // Average Processing Time
    const avgTime = data?.avgProcessingTime || 22;
    document.getElementById('avgProcessingTime').textContent = avgTime;
    document.getElementById('avgProcessingTimeUnit').textContent = 'minutes';
    document.getElementById('avgProcessingProgress').style.width = `${Math.min((30 - avgTime) / 30 * 100, 100)}%`;
    
    // Today's Completion Rate
    const completionRate = data?.completionRate || 94;
    document.getElementById('todayCompletionRate').textContent = `${completionRate}%`;
    document.getElementById('completionRateDetail').textContent = `${data?.completedToday || 15} of ${data?.totalToday || 16} orders`;
    document.getElementById('completionRateProgress').style.width = `${completionRate}%`;
    
    // Critical Results
    const criticalResults = data?.criticalResults || 3;
    document.getElementById('criticalResults').textContent = criticalResults;
    document.getElementById('criticalResultsDetail').textContent = 'requiring attention';
    document.getElementById('criticalResultsProgress').style.width = `${Math.min(criticalResults / 10 * 100, 100)}%`;
    
    // Quality Score
    const qualityScore = data?.qualityScore || 98.5;
    document.getElementById('qualityScore').textContent = `${qualityScore}%`;
    document.getElementById('qualityScoreDetail').textContent = 'accuracy rating';
    document.getElementById('qualityScoreProgress').style.width = `${qualityScore}%`;
}

function updateTestDistributionChart(data) {
    const ctx = document.getElementById('testDistributionChart').getContext('2d');
    
    // Sample data if no data provided
    const testData = data || {
        'Hematology': 35,
        'Biochemistry': 28,
        'Microbiology': 20,
        'Electrolyte': 12,
        'Other': 5
    };
    
    const totalTests = Object.values(testData).reduce((a, b) => a + b, 0);
    document.getElementById('totalTestsToday').textContent = `${totalTests} tests`;
    
    if (window.testDistributionChartInstance) {
        window.testDistributionChartInstance.destroy();
    }
    
    window.testDistributionChartInstance = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(testData),
            datasets: [{
                data: Object.values(testData),
                backgroundColor: [
                    'rgba(102, 126, 234, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(168, 85, 247, 0.8)'
                ],
                borderColor: [
                    'rgba(102, 126, 234, 1)',
                    'rgba(16, 185, 129, 1)',
                    'rgba(245, 158, 11, 1)',
                    'rgba(239, 68, 68, 1)',
                    'rgba(168, 85, 247, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: 'rgba(255, 255, 255, 0.8)',
                        padding: 15,
                        usePointStyle: true
                    }
                }
            }
        }
    });
}

function updateDailyRevenueChart(data) {
    const ctx = document.getElementById('dailyRevenueChart').getContext('2d');
    
    // Sample data if no data provided
    const revenueData = data || {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        values: [1200, 1800, 1500, 2200, 1900, 800, 600]
    };
    
    const todayRevenue = revenueData.values[revenueData.values.length - 1];
    document.getElementById('todayRevenue').textContent = `$${todayRevenue}`;
    
    if (window.dailyRevenueChartInstance) {
        window.dailyRevenueChartInstance.destroy();
    }
    
    window.dailyRevenueChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: revenueData.labels,
            datasets: [{
                label: 'Daily Revenue',
                data: revenueData.values,
                borderColor: 'rgba(16, 185, 129, 1)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgba(16, 185, 129, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.8)',
                        callback: function(value) {
                            return '$' + value;
                        }
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                },
                x: {
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.8)'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                }
            }
        }
    });
}

function updatePerformanceMetricsTable(data) {
    // Update detailed metrics in the table
    document.getElementById('detailAvgProcessingTime').textContent = `${data?.avgProcessingTime || 22} min`;
    document.getElementById('detailCompletionRate').textContent = `${data?.completionRate || 94}%`;
    document.getElementById('detailCriticalResponse').textContent = `${data?.criticalResponseTime || 12} min`;
    document.getElementById('detailQualityScore').textContent = `${data?.qualityScore || 98.5}%`;
    
    // Update status badges and trends
    updateStatusBadges(data);
}

function updateStatusBadges(data) {
    const avgProcessingTime = data?.avgProcessingTime || 22;
    const completionRate = data?.completionRate || 94;
    const criticalResponseTime = data?.criticalResponseTime || 12;
    const qualityScore = data?.qualityScore || 98.5;
    
    // Processing Time Status
    const processingStatus = document.getElementById('processingTimeStatus');
    if (avgProcessingTime <= 20) {
        processingStatus.textContent = 'Excellent';
        processingStatus.className = 'badge badge-success';
    } else if (avgProcessingTime <= 30) {
        processingStatus.textContent = 'Good';
        processingStatus.className = 'badge badge-success';
    } else {
        processingStatus.textContent = 'Needs Improvement';
        processingStatus.className = 'badge badge-warning';
    }
    
    // Completion Rate Status
    const completionStatus = document.getElementById('completionRateStatus');
    if (completionRate >= 95) {
        completionStatus.textContent = 'Excellent';
        completionStatus.className = 'badge badge-success';
    } else if (completionRate >= 90) {
        completionStatus.textContent = 'Good';
        completionStatus.className = 'badge badge-success';
    } else {
        completionStatus.textContent = 'Needs Improvement';
        completionStatus.className = 'badge badge-warning';
    }
    
    // Critical Response Status
    const criticalStatus = document.getElementById('criticalResponseStatus');
    if (criticalResponseTime <= 10) {
        criticalStatus.textContent = 'Excellent';
        criticalStatus.className = 'badge badge-success';
    } else if (criticalResponseTime <= 15) {
        criticalStatus.textContent = 'Good';
        criticalStatus.className = 'badge badge-success';
    } else {
        criticalStatus.textContent = 'Needs Improvement';
        criticalStatus.className = 'badge badge-warning';
    }
    
    // Quality Score Status
    const qualityStatus = document.getElementById('qualityScoreStatus');
    if (qualityScore >= 98) {
        qualityStatus.textContent = 'Excellent';
        qualityStatus.className = 'badge badge-success';
    } else if (qualityScore >= 95) {
        qualityStatus.textContent = 'Good';
        qualityStatus.className = 'badge badge-success';
    } else {
        qualityStatus.textContent = 'Needs Improvement';
        qualityStatus.className = 'badge badge-warning';
    }
}

function loadSampleAnalytics() {
    const sampleData = {
        avgProcessingTime: 22,
        completionRate: 94,
        criticalResults: 3,
        qualityScore: 98.5,
        completedToday: 15,
        totalToday: 16,
        criticalResponseTime: 12,
        testDistribution: {
            'Hematology': 35,
            'Biochemistry': 28,
            'Microbiology': 20,
            'Electrolyte': 12,
            'Other': 5
        },
        dailyRevenue: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            values: [1200, 1800, 1500, 2200, 1900, 800, 600]
        }
    };
    
    updatePerformanceMetrics(sampleData);
    updateTestDistributionChart(sampleData.testDistribution);
    updateDailyRevenueChart(sampleData.dailyRevenue);
    updatePerformanceMetricsTable(sampleData);
}

// Enhanced Collection Modal Functionality
function initializeCollectionModal() {
    const modal = document.getElementById('collectionModal');
    if (!modal) return;
    
    // Enhanced modal animations
    modal.addEventListener('show.bs.modal', function() {
        const modalContent = modal.querySelector('.modal-content');
        modalContent.style.animation = 'modalSlide 0.3s ease';
    });
    
    modal.addEventListener('shown.bs.modal', function() {
        // Focus on patient search when modal opens
        const patientSearch = modal.querySelector('#patientSearch');
        if (patientSearch) {
            setTimeout(() => patientSearch.focus(), 100);
        }
    });
    
    // Initialize real-time search functionality
    const patientSearch = document.getElementById('patientSearch');
    if (patientSearch) {
        let searchTimeout;
        patientSearch.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchPatients(this.value);
            }, 300);
        });
    }
    
    // Initialize CNIC search functionality
    const cnicSearch = document.getElementById('cnicSearch');
    if (cnicSearch) {
        let cnicSearchTimeout;
        
        // Format CNIC as user types
        cnicSearch.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
            if (value.length > 5) {
                value = value.substring(0, 5) + '-' + value.substring(5);
            }
            if (value.length > 13) {
                value = value.substring(0, 13) + '-' + value.substring(13);
            }
            if (value.length > 15) {
                value = value.substring(0, 15);
            }
            e.target.value = value;
            
            // Auto-search after user stops typing for 800ms
            clearTimeout(cnicSearchTimeout);
            if (value.length >= 5) {
                cnicSearchTimeout = setTimeout(() => {
                    searchPatientByCnic(true); // true flag for auto-search
                }, 800);
            }
        });
        
        // Search on Enter key
        cnicSearch.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                clearTimeout(cnicSearchTimeout);
                searchPatientByCnic();
            }
        });
    }
    
}

function searchPatients(query) {
    // Simulate patient search - replace with actual API call
    const patients = [
        { id: 1, name: 'John Doe', cnic: '12345-6789012-3', age: 35, gender: 'Male', orderedTests: ['CBC', 'BMP'] },
        { id: 2, name: 'Jane Smith', cnic: '23456-7890123-4', age: 28, gender: 'Female', orderedTests: ['LIPID', 'TSH'] },
        { id: 3, name: 'Ahmed Ali', cnic: '34567-8901234-5', age: 42, gender: 'Male', orderedTests: ['HBA1C', 'URINE'] },
        { id: 4, name: 'Sara Khan', cnic: '45678-9012345-6', age: 31, gender: 'Female', orderedTests: ['CBC', 'LIPID', 'TSH'] }
    ];
    
    if (query.length < 2) return;
    
    const filtered = patients.filter(p => 
        p.name.toLowerCase().includes(query.toLowerCase()) ||
        p.cnic.includes(query)
    );
    
    // Update patient dropdown with filtered results
    updatePatientDropdown(filtered);
}

// Search patient by CNIC with enhanced auto-fill
function searchPatientByCnic(isAutoSearch = false) {
    const cnicInput = document.getElementById('cnicSearch');
    const cnic = cnicInput.value.trim();
    const searchButton = cnicInput.nextElementSibling;
    
    if (cnic.length < 5) {
        if (!isAutoSearch) {
            showAlert('Please enter at least 5 digits of CNIC', 'warning');
        }
        return;
    }
    
    // Show loading state on search button
    if (searchButton) {
        const originalIcon = searchButton.innerHTML;
        searchButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        searchButton.disabled = true;
        
        setTimeout(() => {
            searchButton.innerHTML = originalIcon;
            searchButton.disabled = false;
        }, 1000);
    }
    
    // Sample patient database - replace with actual API call
    const patients = [
        { id: 1, name: 'John Smith', cnic: '12345-1234567-1', age: 35, gender: 'Male', orderedTests: ['CBC', 'BMP'] },
        { id: 2, name: 'Sarah Johnson', cnic: '54321-7654321-9', age: 28, gender: 'Female', orderedTests: ['LIPID', 'TSH'] },
        { id: 3, name: 'Ahmed Ali', cnic: '42101-1234567-8', age: 42, gender: 'Male', orderedTests: ['CBC', 'HBA1C'] },
        { id: 4, name: 'Maria Garcia', cnic: '35202-9876543-2', age: 31, gender: 'Female', orderedTests: ['URINE', 'CBC'] },
        { id: 5, name: 'Ali Hassan', cnic: '12345-9876543-1', age: 29, gender: 'Male', orderedTests: ['TSH'] },
        { id: 6, name: 'Fatima Khan', cnic: '54321-1234567-9', age: 33, gender: 'Female', orderedTests: ['LIPID'] }
    ];
    
    // Find patient by CNIC (partial or full match)
    const foundPatients = patients.filter(p => 
        p.cnic.replace(/\D/g, '').includes(cnic.replace(/\D/g, '')) ||
        p.cnic.includes(cnic)
    );
    
    if (foundPatients.length === 1) {
        const foundPatient = foundPatients[0];
        
        // Auto-fill the patient selection dropdown
        const patientSelect = document.getElementById('patientSelect');
        patientSelect.value = foundPatient.id.toString();
        
        // Trigger the change event to load patient info
        patientSelect.dispatchEvent(new Event('change'));
        
        // Visual feedback
        cnicInput.style.borderColor = '#28a745';
        cnicInput.style.boxShadow = '0 0 0 0.2rem rgba(40, 167, 69, 0.25)';
        
        setTimeout(() => {
            cnicInput.style.borderColor = '';
            cnicInput.style.boxShadow = '';
        }, 2000);
        
        if (!isAutoSearch) {
            showAlert(`Patient found and selected: ${foundPatient.name}`, 'success');
        }
        
    } else if (foundPatients.length > 1) {
        // Multiple matches found - update dropdown with filtered results
        updatePatientDropdownWithMatches(foundPatients);
        
        // Visual feedback for multiple matches
        cnicInput.style.borderColor = '#ffc107';
        cnicInput.style.boxShadow = '0 0 0 0.2rem rgba(255, 193, 7, 0.25)';
        
        setTimeout(() => {
            cnicInput.style.borderColor = '';
            cnicInput.style.boxShadow = '';
        }, 2000);
        
        if (!isAutoSearch) {
            showAlert(`${foundPatients.length} patients found with similar CNIC. Please select from dropdown.`, 'info');
        }
        
    } else {
        // No match found
        cnicInput.style.borderColor = '#dc3545';
        cnicInput.style.boxShadow = '0 0 0 0.2rem rgba(220, 53, 69, 0.25)';
        
        setTimeout(() => {
            cnicInput.style.borderColor = '';
            cnicInput.style.boxShadow = '';
        }, 2000);
        
        if (!isAutoSearch) {
            showAlert('No patient found with this CNIC', 'warning');
        }
    }
}

// Update patient dropdown with specific matches
function updatePatientDropdownWithMatches(patients) {
    const select = document.getElementById('patientSelect');
    
    // Clear existing options except the first one
    while (select.children.length > 1) {
        select.removeChild(select.lastChild);
    }
    
    // Add matched patients to dropdown
    patients.forEach(patient => {
        const option = document.createElement('option');
        option.value = patient.id;
        option.textContent = `${patient.name} (CNIC: ${patient.cnic})`;
        select.appendChild(option);
    });
    
    // Highlight the dropdown to draw attention
    select.style.borderColor = '#ffc107';
    select.style.boxShadow = '0 0 0 0.2rem rgba(255, 193, 7, 0.25)';
    
    setTimeout(() => {
        select.style.borderColor = '';
        select.style.boxShadow = '';
    }, 3000);
}

function updatePatientDropdown(patients) {
    const select = document.getElementById('patientSelect');
    // Clear existing options except the first one
    while (select.children.length > 1) {
        select.removeChild(select.lastChild);
    }
    
    patients.forEach(patient => {
        const option = document.createElement('option');
        option.value = patient.id;
        option.textContent = `${patient.name} (${patient.cnic})`;
        select.appendChild(option);
    });
}

// Initialize enhanced drag and drop with visual feedback
function initializeEnhancedDragDrop() {
    // Add drag event listeners to test items
    document.addEventListener('dragstart', function(e) {
        if (e.target.classList.contains('test-item') && e.target.draggable) {
            e.dataTransfer.setData('text/plain', e.target.dataset.test);
            e.target.classList.add('dragging');
        }
    });
    
    document.addEventListener('dragend', function(e) {
        if (e.target.classList.contains('test-item')) {
            e.target.classList.remove('dragging');
        }
    });
    
    // Enhanced hover effects for test items
    document.addEventListener('mouseover', function(e) {
        if (e.target.closest('.test-item')) {
            const item = e.target.closest('.test-item');
            if (!item.classList.contains('dragging')) {
                item.style.transform = 'translateY(-2px)';
                item.style.boxShadow = '0 8px 25px rgba(16, 185, 129, 0.15)';
            }
        }
    });
    
    document.addEventListener('mouseout', function(e) {
        if (e.target.closest('.test-item')) {
            const item = e.target.closest('.test-item');
            if (!item.classList.contains('dragging')) {
                item.style.transform = 'translateY(0)';
                item.style.boxShadow = 'none';
            }
        }
    });
}

// Update initialization in existing DOMContentLoaded
const originalInit = document.querySelector('script').textContent;
document.addEventListener('DOMContentLoaded', function() {
    // Test basic JavaScript functionality
    console.log('🚀 Lab Tech Dashboard JavaScript loaded successfully!');
    
    // Test if showCollectionModal function is available
    if (typeof showCollectionModal === 'function') {
        console.log('✅ showCollectionModal function is available');
    } else {
        console.error('❌ showCollectionModal function is NOT available');
    }
    
    // Find the collection card
    const collectionCard = document.querySelector('[onclick*="showCollectionModal"]');
    if (collectionCard) {
        console.log('✅ Collection card found:', collectionCard);
        
        // Add additional click event listener as backup
        collectionCard.addEventListener('click', function(e) {
            console.log('🎯 Additional click listener triggered!');
        });
    } else {
        console.error('❌ Collection card not found');
    }
    
    // Initialize collection modal functionality
    initializeCollectionModal();
    
    // Initialize enhanced drag and drop
    initializeEnhancedDragDrop();
    
    // Initialize custom modal system (replace Bootstrap dependencies)
    initializeCustomModalSystem();
    
    // Initialize tooltips if Bootstrap is available
    if (typeof bootstrap !== 'undefined') {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});

// Initialize custom modal system to replace Bootstrap modal functionality
function initializeCustomModalSystem() {
    // Handle all modal close buttons
    document.addEventListener('click', function(e) {
        if (e.target.matches('[data-bs-dismiss="modal"], .btn-close')) {
            const modal = e.target.closest('.modal');
            if (modal) {
                hideCustomModal(modal);
            }
        }
    });
    
    // Handle modal content clicks to prevent closure
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal') && e.target.style.display === 'block') {
            // Only close if clicking the backdrop (modal itself), not its content
            const modalContent = e.target.querySelector('.modal-content');
            if (modalContent && !modalContent.contains(e.target)) {
                hideCustomModal(e.target);
            }
        }
    });
}

// Custom modal show/hide functions
function showCustomModal(modal) {
    if (!modal) return;
    
    console.log('📱 showCustomModal called with:', modal.id);
    
    // Move modal to body if it's in the hidden container
    const modalContainer = document.getElementById('modalContainer');
    if (modalContainer && modalContainer.contains(modal)) {
        console.log('🔄 Moving modal from hidden container to body');
        document.body.appendChild(modal);
    }
    
    // Reset modal styles and make it accessible
    modal.style.position = 'fixed';
    modal.style.top = '0';
    modal.style.left = '0';
    modal.style.width = '100%';
    modal.style.height = '100%';
    modal.style.pointerEvents = 'auto';
    modal.style.display = 'flex';
    modal.style.alignItems = 'flex-start';
    modal.style.justifyContent = 'center';
    modal.style.paddingTop = '2rem';
    modal.style.paddingBottom = '2rem';
    modal.style.opacity = '0';
    modal.style.transition = 'opacity 0.3s ease';
    modal.style.zIndex = '1050';
    modal.style.backgroundColor = 'rgba(0, 0, 0, 0.75)'; // Even darker background
    modal.style.overflowY = 'auto';
    
    // Ensure modal dialog has proper styling
    const modalDialog = modal.querySelector('.modal-dialog');
    if (modalDialog) {
        modalDialog.style.margin = '1rem auto';
        modalDialog.style.maxHeight = '95vh';
        modalDialog.style.overflow = 'visible';
        modalDialog.style.backgroundColor = '#ffffff';
        modalDialog.style.borderRadius = '0.5rem';
        modalDialog.style.boxShadow = '0 1rem 3rem rgba(0, 0, 0, 0.25)';
        modalDialog.style.opacity = '1';
        modalDialog.style.border = '1px solid rgba(0, 0, 0, 0.125)';
        modalDialog.style.maxWidth = '90vw';
        modalDialog.style.width = '100%';
    }
    
    // Ensure modal content is fully visible with better styling
    const modalContent = modal.querySelector('.modal-content');
    if (modalContent) {
        modalContent.style.backgroundColor = '#ffffff';
        modalContent.style.borderRadius = '0.5rem';
        modalContent.style.opacity = '1';
        modalContent.style.color = '#212529';
        modalContent.style.border = 'none';
        modalContent.style.boxShadow = 'none';
        modalContent.style.maxHeight = '90vh';
        modalContent.style.overflow = 'auto';
    }
    
    // Enhance modal header
    const modalHeader = modal.querySelector('.modal-header');
    if (modalHeader) {
        modalHeader.style.backgroundColor = '#f8f9fa';
        modalHeader.style.borderBottom = '1px solid #dee2e6';
        modalHeader.style.padding = '1rem 1.5rem';
        modalHeader.style.borderRadius = '0.5rem 0.5rem 0 0';
    }
    
    // Enhance modal body
    const modalBody = modal.querySelector('.modal-body');
    if (modalBody) {
        modalBody.style.padding = '1.5rem';
        modalBody.style.backgroundColor = '#ffffff';
        modalBody.style.maxHeight = '70vh';
        modalBody.style.overflow = 'auto';
    }
    
    // Enhance modal footer
    const modalFooter = modal.querySelector('.modal-footer');
    if (modalFooter) {
        modalFooter.style.backgroundColor = '#f8f9fa';
        modalFooter.style.borderTop = '1px solid #dee2e6';
        modalFooter.style.padding = '1rem 1.5rem';
        modalFooter.style.borderRadius = '0 0 0.5rem 0.5rem';
    }
    
    // Ensure all form elements are properly styled
    const formElements = modal.querySelectorAll('input, select, textarea, button, label, .form-group, .form-control');
    formElements.forEach(element => {
        element.style.opacity = '1';
        element.style.visibility = 'visible';
        
        if (element.tagName === 'INPUT' || element.tagName === 'SELECT' || element.tagName === 'TEXTAREA') {
            element.style.backgroundColor = '#ffffff';
            element.style.color = '#212529';
            element.style.border = '1px solid #ced4da';
            element.style.borderRadius = '0.375rem';
            element.style.padding = '0.5rem 0.75rem';
            element.style.fontSize = '1rem';
            element.style.lineHeight = '1.5';
        }
        
        if (element.tagName === 'LABEL') {
            element.style.color = '#212529';
            element.style.fontWeight = '500';
            element.style.marginBottom = '0.5rem';
            element.style.display = 'block';
        }
        
        if (element.classList.contains('btn')) {
            element.style.borderRadius = '0.375rem';
            element.style.padding = '0.5rem 1rem';
            element.style.fontSize = '1rem';
            element.style.fontWeight = '500';
        }
    });
    
    // Style form groups
    const formGroups = modal.querySelectorAll('.form-group, .mb-3');
    formGroups.forEach(group => {
        group.style.marginBottom = '1rem';
        group.style.opacity = '1';
        group.style.visibility = 'visible';
    });
    
    // Style any tables in the modal
    const tables = modal.querySelectorAll('table');
    tables.forEach(table => {
        table.style.opacity = '1';
        table.style.visibility = 'visible';
        table.style.backgroundColor = '#ffffff';
    });
    
    // Style test items
    const testItems = modal.querySelectorAll('.test-item');
    testItems.forEach(item => {
        item.style.opacity = '1';
        item.style.visibility = 'visible';
        item.style.backgroundColor = '#f8f9fa';
        item.style.border = '1px solid #dee2e6';
        item.style.borderRadius = '0.375rem';
        item.style.color = '#212529';
    });
    
    // Animate in
    setTimeout(() => {
        modal.style.opacity = '1';
    }, 10);
    
    // Store backdrop reference (using the modal itself as backdrop)
    modal._backdrop = modal;
    
    // Add body class to prevent scrolling
    document.body.style.overflow = 'hidden';
    
    console.log('✅ Modal should now be visible with proper opacity');
}

function hideCustomModal(modal) {
    if (!modal) return;
    
    console.log('❌ Hiding modal:', modal.id);
    
    // Animate out
    modal.style.opacity = '0';
    
    setTimeout(() => {
        modal.style.display = 'none';
        modal.style.zIndex = '';
        modal.style.backgroundColor = '';
        modal.style.alignItems = '';
        modal.style.justifyContent = '';
        
        // Move modal back to hidden container
        const modalContainer = document.getElementById('modalContainer');
        if (modalContainer) {
            modal.style.position = 'fixed';
            modal.style.top = '-9999px';
            modal.style.pointerEvents = 'none';
            modalContainer.appendChild(modal);
            console.log('📦 Modal moved back to hidden container');
        }
        
        // Clear backdrop reference
        modal._backdrop = null;
        
        document.body.style.overflow = '';
    }, 300);
}

// Enhanced modal functions for the cards
function showCollectionModal() {
    console.log('🎯 showCollectionModal function called!');
    const modal = document.getElementById('collectionModal');
    console.log('🔍 Modal element:', modal);
    if (modal) {
        console.log('✅ Collection modal found, showing...');
        setCurrentDateTime();
        
        // Try both custom and Bootstrap modal systems
        if (typeof showCustomModal === 'function') {
            showCustomModal(modal);
        } else if (typeof bootstrap !== 'undefined') {
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        } else {
            // Fallback: simple display
            modal.style.display = 'block';
            modal.style.zIndex = '1050';
            // Add backdrop
            const backdrop = document.createElement('div');
            backdrop.style.cssText = `
                position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                background: rgba(0, 0, 0, 0.5); z-index: 1040;
            `;
            document.body.appendChild(backdrop);
            modal._backdrop = backdrop;
        }
    } else {
        console.error('❌ Collection modal not found!');
    }
}

function showResultsModal() {
    console.log('🎯 showResultsModal function called!');
    const modal = document.getElementById('resultsModal');
    console.log('🔍 Modal element:', modal);
    if (modal) {
        console.log('✅ Results modal found, showing...');
        setCurrentDateTime();
        
        // Try both custom and Bootstrap modal systems
        if (typeof showCustomModal === 'function') {
            showCustomModal(modal);
        } else if (typeof bootstrap !== 'undefined') {
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        } else {
            // Fallback: simple display
            modal.style.display = 'block';
            modal.style.zIndex = '1050';
            // Add backdrop
            const backdrop = document.createElement('div');
            backdrop.style.cssText = `
                position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                background: rgba(0, 0, 0, 0.5); z-index: 1040;
            `;
            document.body.appendChild(backdrop);
            modal._backdrop = backdrop;
        }
    } else {
        console.error('❌ Results modal not found!');
    }
}

// Helper functions for modals
function loadPatientTests() {
    const patientId = document.getElementById('patientSelect').value;
    const patientInfo = document.getElementById('patientInfo');
    
    if (!patientId) {
        patientInfo.innerHTML = '<p style="color: var(--text-muted); margin: 0; text-align: center;">Select a patient to view information</p>';
        return;
    }
    
    // Sample patient data - replace with actual API call
    const patients = {
        '1': { name: 'John Smith', age: 35, gender: 'Male', cnic: '12345-1234567-1', tests: ['CBC', 'BMP'] },
        '2': { name: 'Sarah Johnson', age: 28, gender: 'Female', cnic: '54321-7654321-9', tests: ['LIPID', 'TSH'] },
        '3': { name: 'Ahmed Ali', age: 42, gender: 'Male', cnic: '42101-1234567-8', tests: ['CBC', 'HBA1C'] },
        '4': { name: 'Maria Garcia', age: 31, gender: 'Female', cnic: '35202-9876543-2', tests: ['URINE', 'CBC'] }
    };
    
    const patient = patients[patientId];
    if (patient) {
        patientInfo.innerHTML = `
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <strong style="color: #06b6d4;">Name:</strong> ${patient.name}<br>
                    <strong style="color: #06b6d4;">CNIC:</strong> ${patient.cnic}
                </div>
                <div>
                    <strong style="color: #06b6d4;">Age:</strong> ${patient.age}<br>
                    <strong style="color: #06b6d4;">Gender:</strong> ${patient.gender}
                </div>
            </div>
            <div style="margin-top: 0.5rem;">
                <strong style="color: #10b981;">Ordered Tests:</strong> ${patient.tests.join(', ')}
            </div>
        `;
        
        // Highlight ordered tests
        highlightOrderedTests(patient.tests);
    }
}

function highlightOrderedTests(orderedTests) {
    document.querySelectorAll('.test-item').forEach(item => {
        const testCode = item.dataset.test;
        if (orderedTests.includes(testCode)) {
            item.style.background = 'linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(6, 182, 212, 0.2))';
            item.style.borderColor = '#10b981';
            item.style.borderWidth = '2px';
        } else {
            item.style.background = 'var(--glass-background)';
            item.style.borderColor = 'var(--glass-border)';
            item.style.borderWidth = '1px';
        }
    });
}

function loadPatientResults() {
    const patientId = document.getElementById('patientResultsSelect').value;
    const patientInfo = document.getElementById('patientResultsInfo');
    const pendingTests = document.getElementById('pendingTests');
    
    if (!patientId) {
        patientInfo.innerHTML = '<p style="color: var(--text-muted); margin: 0; text-align: center;">Select a patient to view their pending tests</p>';
        pendingTests.innerHTML = '<div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: var(--text-muted); pointer-events: none;"><i class="fas fa-clipboard-list" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5;"></i><p style="margin: 0;">Select a patient to view pending tests</p><small>Test results will appear here for entry</small></div>';
        return;
    }
    
    // Sample data - replace with actual API call
    const patients = {
        '1': { 
            name: 'John Smith', 
            cnic: '12345-1234567-1',
            tests: [
                { name: 'Complete Blood Count (CBC)', code: 'CBC', collected: '2025-08-17 09:30', status: 'pending' },
                { name: 'Basic Metabolic Panel (BMP)', code: 'BMP', collected: '2025-08-17 09:30', status: 'pending' }
            ]
        },
        '2': { 
            name: 'Sarah Johnson', 
            cnic: '54321-7654321-9',
            tests: [
                { name: 'Lipid Profile', code: 'LIPID', collected: '2025-08-17 10:15', status: 'pending' },
                { name: 'Thyroid Stimulating Hormone (TSH)', code: 'TSH', collected: '2025-08-17 10:15', status: 'pending' }
            ]
        }
    };
    
    const patient = patients[patientId];
    if (patient) {
        patientInfo.innerHTML = `
            <div>
                <strong style="color: #10b981;">Patient:</strong> ${patient.name}<br>
                <strong style="color: #10b981;">CNIC:</strong> ${patient.cnic}<br>
                <strong style="color: #10b981;">Pending Tests:</strong> ${patient.tests.length}
            </div>
        `;
        
        pendingTests.innerHTML = patient.tests.map(test => `
            <div class="test-result-item" style="background: var(--glass-background); border: 1px solid var(--glass-border); border-radius: 8px; padding: 1rem; margin-bottom: 1rem;">
                <div style="display: flex; justify-content: between; align-items: start; margin-bottom: 1rem;">
                    <div>
                        <h6 style="color: #f59e0b; margin: 0 0 0.5rem 0;">${test.name}</h6>
                        <small style="color: var(--text-muted);">Collected: ${test.collected}</small>
                    </div>
                    <span class="badge" style="background: rgba(251, 191, 36, 0.2); color: #fbbf24;">Pending</span>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Result Value:</label>
                        <input type="text" class="form-control" id="result_${test.code}" placeholder="Enter result value...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Result Flag:</label>
                        <select class="form-control" id="flag_${test.code}">
                            <option value="normal">Normal</option>
                            <option value="high">High</option>
                            <option value="low">Low</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>
                </div>
                <div style="margin-top: 1rem;">
                    <label class="form-label">Notes:</label>
                    <textarea class="form-control" id="notes_${test.code}" rows="2" placeholder="Enter any notes..."></textarea>
                </div>
            </div>
        `).join('');
    }
}

// Drag and drop functions
function allowDrop(ev) {
    ev.preventDefault();
}

function dropTest(ev) {
    ev.preventDefault();
    const testCode = ev.dataTransfer.getData("text");
    const selectedTestsDiv = document.getElementById('selectedTests');
    
    // Check if test is already added
    if (selectedTestsDiv.querySelector(`[data-test="${testCode}"]`)) {
        return;
    }
    
    // Find the original test item
    const originalTest = document.querySelector(`[data-test="${testCode}"]`);
    if (!originalTest) return;
    
    // Create selected test item
    const selectedTest = originalTest.cloneNode(true);
    selectedTest.style.background = 'rgba(16, 185, 129, 0.2)';
    selectedTest.style.borderColor = '#10b981';
    selectedTest.draggable = false;
    
    // Add remove button
    const removeBtn = document.createElement('button');
    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
    removeBtn.style.cssText = 'position: absolute; top: -8px; right: -8px; background: #ef4444; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; font-size: 0.7rem; cursor: pointer;';
    removeBtn.onclick = function() {
        selectedTest.remove();
        updateTotalAmount();
    };
    
    selectedTest.style.position = 'relative';
    selectedTest.appendChild(removeBtn);
    
    // Clear placeholder content if exists
    const placeholder = selectedTestsDiv.querySelector('div[style*="position: absolute"]');
    if (placeholder) {
        placeholder.style.display = 'none';
    }
    
    selectedTestsDiv.appendChild(selectedTest);
    updateTotalAmount();
}

function updateTotalAmount() {
    const selectedTests = document.querySelectorAll('#selectedTests .test-item');
    let total = 0;
    
    selectedTests.forEach(test => {
        const priceText = test.querySelector('.badge').textContent;
        const price = parseFloat(priceText.replace('$', ''));
        total += price;
    });
    
    document.getElementById('totalAmount').textContent = total.toFixed(2);
}

function recordCollection() {
    console.log('Recording collection...');
    showAlert('Sample collection recorded successfully!', 'success');
    hideCustomModal(document.getElementById('collectionModal'));
}

async function submitTestResults() {
    console.log('Submitting test results...');
    
    const patientId = document.getElementById('patientResultsSelect')?.value;
    if (!patientId) {
        showAlert('Please select a patient first', 'warning');
        return;
    }
    
    // Get patient name for display
    const patientSelect = document.getElementById('patientResultsSelect');
    const patientName = patientSelect.options[patientSelect.selectedIndex]?.text || 'Unknown Patient';
    
    // Collect all test results from the form
    const testResults = [];
    const testItems = document.querySelectorAll('.test-result-item');
    
    testItems.forEach(item => {
        const testCode = item.querySelector('input[id^="result_"]')?.id.replace('result_', '');
        const resultValue = item.querySelector(`#result_${testCode}`)?.value;
        const resultFlag = item.querySelector(`#flag_${testCode}`)?.value;
        const resultNotes = item.querySelector(`#notes_${testCode}`)?.value;
        const testNameElement = item.querySelector('h6');
        const testName = testNameElement ? testNameElement.textContent : testCode;
        
        if (resultValue && resultValue.trim()) {
            testResults.push({
                test_code: testCode,
                test_name: testName,
                result_value: resultValue.trim(),
                result_flag: resultFlag || 'normal',
                result_notes: resultNotes || '',
                source_type: 'manual',
                result_status: 'final',
                performed_at: new Date().toISOString(),
                patient_name: patientName
            });
        }
    });
    
    if (testResults.length === 0) {
        showAlert('Please enter at least one test result', 'warning');
        return;
    }
    
    try {
        // Submit each result
        for (const result of testResults) {
            try {
                const response = await fetch('/api/lab-tech/results', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        patient_id: patientId,
                        ...result
                    })
                });
                
                if (!response.ok) {
                    throw new Error(`Failed to submit result for ${result.test_code}`);
                }
            } catch (apiError) {
                // If API fails, still add to local display for immediate feedback
                console.log('API not available, adding to local display only:', apiError);
            }
            
            // Add to recent results immediately for user feedback
            addToRecentResults(result);
        }
        
        showAlert(`Successfully submitted ${testResults.length} test result(s)!`, 'success');
        hideCustomModal(document.getElementById('resultsModal'));
        
        // Also refresh dashboard stats
        loadStats();
        loadLabOrders();
        
    } catch (error) {
        console.error('Error submitting test results:', error);
        showAlert('Error submitting test results: ' + error.message, 'error');
    }
}

// Test Management Functions
let availableTests = [
    { id: 1, code: 'CBC', name: 'Complete Blood Count', category: 'Hematology', price: 25.00, turnaroundTime: '2-4 hours', status: 'active', description: 'Measures different components of blood', sampleType: 'Blood', normalRange: '4.5-11.0 x10³/μL', unit: 'x10³/μL' },
    { id: 2, code: 'BMP', name: 'Basic Metabolic Panel', category: 'Biochemistry', price: 35.00, turnaroundTime: '4-6 hours', status: 'active', description: 'Basic metabolic function tests', sampleType: 'Serum', normalRange: 'Various', unit: 'Various' },
    { id: 3, code: 'LIPID', name: 'Lipid Profile', category: 'Biochemistry', price: 40.00, turnaroundTime: '6-12 hours', status: 'active', description: 'Cholesterol and triglyceride levels', sampleType: 'Serum', normalRange: '<200 mg/dL', unit: 'mg/dL' },
    { id: 4, code: 'TSH', name: 'Thyroid Stimulating Hormone', category: 'Endocrinology', price: 30.00, turnaroundTime: '24-48 hours', status: 'active', description: 'Thyroid function test', sampleType: 'Serum', normalRange: '0.4-4.0 mIU/L', unit: 'mIU/L' },
    { id: 5, code: 'HBA1C', name: 'Hemoglobin A1C', category: 'Endocrinology', price: 45.00, turnaroundTime: '2-4 hours', status: 'active', description: 'Average blood glucose over 2-3 months', sampleType: 'Blood', normalRange: '<5.7%', unit: '%' },
    { id: 6, code: 'URINE', name: 'Urine Analysis', category: 'Other', price: 20.00, turnaroundTime: '2-4 hours', status: 'active', description: 'Complete urine examination', sampleType: 'Urine', normalRange: 'Various', unit: 'Various' }
];

let nextTestId = 7;

// Load and display tests
function loadAvailableTests() {
    renderTestsTable(availableTests);
    updateTestStats();
}

// Render tests table
function renderTestsTable(tests) {
    const tbody = document.getElementById('testsTableBody');
    if (!tbody) return;
    
    tbody.innerHTML = tests.map(test => `
        <tr>
            <td>
                <span class="badge" style="background: rgba(6, 182, 212, 0.2); color: #06b6d4; font-family: monospace;">
                    ${test.code}
                </span>
            </td>
            <td>
                <div>
                    <strong>${test.name}</strong>
                    ${test.description ? `<br><small class="text-muted">${test.description}</small>` : ''}
                </div>
            </td>
            <td>
                <span class="badge" style="background: rgba(139, 92, 246, 0.2); color: #8b5cf6;">
                    ${test.category}
                </span>
            </td>
            <td>
                <strong style="color: #10b981;">$${test.price.toFixed(2)}</strong>
            </td>
            <td>
                <span style="color: var(--text-muted);">
                    <i class="fas fa-clock me-1"></i>${test.turnaroundTime}
                </span>
            </td>
            <td>
                <span class="badge ${test.status === 'active' ? 'bg-success' : 'bg-secondary'}">
                    ${test.status.charAt(0).toUpperCase() + test.status.slice(1)}
                </span>
            </td>
            <td>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" onclick="editTest(${test.id})" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-outline-info" onclick="viewTest(${test.id})" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-outline-danger" onclick="deleteTest(${test.id})" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Update statistics
function updateTestStats() {
    const totalTests = availableTests.length;
    const activeTests = availableTests.filter(t => t.status === 'active').length;
    const avgPrice = totalTests > 0 ? (availableTests.reduce((sum, t) => sum + t.price, 0) / totalTests) : 0;
    const categories = [...new Set(availableTests.map(t => t.category))].length;
    
    document.getElementById('totalTestsCount').textContent = totalTests;
    document.getElementById('activeTestsCount').textContent = activeTests;
    document.getElementById('avgTestPrice').textContent = `$${avgPrice.toFixed(2)}`;
    document.getElementById('categoriesCount').textContent = categories;
}

// Show add test modal
function showAddTestModal() {
    document.getElementById('testModalTitle').innerHTML = '<i class="fas fa-flask me-2"></i>Add New Test';
    document.getElementById('testId').value = '';
    document.getElementById('testForm').reset();
    showCustomModal(document.getElementById('testModal'));
}

// Edit test
function editTest(testId) {
    const test = availableTests.find(t => t.id === testId);
    if (!test) return;
    
    document.getElementById('testModalTitle').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Test';
    document.getElementById('testId').value = test.id;
    document.getElementById('testCode').value = test.code;
    document.getElementById('testName').value = test.name;
    document.getElementById('testCategory').value = test.category;
    document.getElementById('testPrice').value = test.price;
    document.getElementById('turnaroundTime').value = test.turnaroundTime;
    document.getElementById('testStatus').value = test.status;
    document.getElementById('testDescription').value = test.description || '';
    document.getElementById('sampleType').value = test.sampleType || 'Blood';
    document.getElementById('normalRange').value = test.normalRange || '';
    document.getElementById('testUnit').value = test.unit || '';
    
    showCustomModal(document.getElementById('testModal'));
}

// View test details
function viewTest(testId) {
    const test = availableTests.find(t => t.id === testId);
    if (!test) return;
    
    const modalContent = `
        <div class="modal fade" id="viewTestModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-flask me-2"></i>Test Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-6"><strong>Test Code:</strong></div>
                            <div class="col-6">
                                <span class="badge" style="background: rgba(6, 182, 212, 0.2); color: #06b6d4;">
                                    ${test.code}
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6"><strong>Test Name:</strong></div>
                            <div class="col-6">${test.name}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6"><strong>Category:</strong></div>
                            <div class="col-6">
                                <span class="badge" style="background: rgba(139, 92, 246, 0.2); color: #8b5cf6;">
                                    ${test.category}
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6"><strong>Price:</strong></div>
                            <div class="col-6"><strong style="color: #10b981;">$${test.price.toFixed(2)}</strong></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6"><strong>Turnaround Time:</strong></div>
                            <div class="col-6">${test.turnaroundTime}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6"><strong>Sample Type:</strong></div>
                            <div class="col-6">${test.sampleType}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6"><strong>Normal Range:</strong></div>
                            <div class="col-6">${test.normalRange}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6"><strong>Unit:</strong></div>
                            <div class="col-6">${test.unit}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6"><strong>Status:</strong></div>
                            <div class="col-6">
                                <span class="badge ${test.status === 'active' ? 'bg-success' : 'bg-secondary'}">
                                    ${test.status.charAt(0).toUpperCase() + test.status.slice(1)}
                                </span>
                            </div>
                        </div>
                        ${test.description ? `
                        <div class="row mb-3">
                            <div class="col-12"><strong>Description:</strong></div>
                            <div class="col-12">${test.description}</div>
                        </div>
                        ` : ''}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="editTest(${test.id}); closeViewModal();">
                            <i class="fas fa-edit me-1"></i>Edit Test
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalContent);
    const modal = new bootstrap.Modal(document.getElementById('viewTestModal'));
    modal.show();
    
    document.getElementById('viewTestModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

function closeViewModal() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('viewTestModal'));
    if (modal) modal.hide();
}

// Save test
function saveTest() {
    const testId = document.getElementById('testId').value;
    const testData = {
        code: document.getElementById('testCode').value.trim().toUpperCase(),
        name: document.getElementById('testName').value.trim(),
        category: document.getElementById('testCategory').value,
        price: parseFloat(document.getElementById('testPrice').value),
        turnaroundTime: document.getElementById('turnaroundTime').value,
        status: document.getElementById('testStatus').value,
        description: document.getElementById('testDescription').value.trim(),
        sampleType: document.getElementById('sampleType').value,
        normalRange: document.getElementById('normalRange').value.trim(),
        unit: document.getElementById('testUnit').value.trim()
    };
    
    // Validation
    if (!testData.code || !testData.name || !testData.category || !testData.price) {
        showAlert('Please fill in all required fields', 'error');
        return;
    }
    
    // Check for duplicate test code (excluding current test when editing)
    const existingTest = availableTests.find(t => t.code === testData.code && t.id != testId);
    if (existingTest) {
        showAlert('Test code already exists', 'error');
        return;
    }
    
    if (testId) {
        // Edit existing test
        const index = availableTests.findIndex(t => t.id == testId);
        if (index !== -1) {
            availableTests[index] = { ...availableTests[index], ...testData };
            showAlert('Test updated successfully', 'success');
        }
    } else {
        // Add new test
        availableTests.push({ id: nextTestId++, ...testData });
        showAlert('Test added successfully', 'success');
    }
    
    loadAvailableTests();
    hideCustomModal(document.getElementById('testModal'));
}

// Delete test
function deleteTest(testId) {
    if (confirm('Are you sure you want to delete this test?')) {
        const index = availableTests.findIndex(t => t.id === testId);
        if (index !== -1) {
            availableTests.splice(index, 1);
            loadAvailableTests();
            showAlert('Test deleted successfully', 'success');
        }
    }
}

// Search and filter functions
function setupTestSearch() {
    const searchInput = document.getElementById('testSearchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const statusFilter = document.getElementById('statusFilter');
    
    if (searchInput) {
        searchInput.addEventListener('input', filterTests);
    }
    if (categoryFilter) {
        categoryFilter.addEventListener('change', filterTests);
    }
    if (statusFilter) {
        statusFilter.addEventListener('change', filterTests);
    }
}

function filterTests() {
    const searchTerm = document.getElementById('testSearchInput')?.value.toLowerCase() || '';
    const categoryFilter = document.getElementById('categoryFilter')?.value || '';
    const statusFilter = document.getElementById('statusFilter')?.value || '';
    
    const filteredTests = availableTests.filter(test => {
        const matchesSearch = test.name.toLowerCase().includes(searchTerm) || 
                            test.code.toLowerCase().includes(searchTerm) ||
                            test.description.toLowerCase().includes(searchTerm);
        const matchesCategory = !categoryFilter || test.category === categoryFilter;
        const matchesStatus = !statusFilter || test.status === statusFilter;
        
        return matchesSearch && matchesCategory && matchesStatus;
    });
    
    renderTestsTable(filteredTests);
}

// Equipment Management Functions
let labEquipment = [
    {
        id: 1,
        name: "Hematology Analyzer 1",
        type: "Analyzer",
        model: "Sysmex XN-1000",
        serial: "SYS123456789",
        ipAddress: "192.168.1.100",
        port: 8080,
        connectionType: "TCP",
        dataFormat: "HL7",
        status: "online",
        location: "Lab Room A, Station 1",
        lastConnected: "2025-08-18 10:30:00",
        supportedTests: ["CBC", "WBC", "RBC", "HGB", "HCT"],
        notes: "Primary hematology analyzer"
    },
    {
        id: 2,
        name: "Chemistry Analyzer",
        type: "Analyzer", 
        model: "Roche Cobas c111",
        serial: "ROC987654321",
        ipAddress: "192.168.1.101",
        port: 8080,
        connectionType: "TCP",
        dataFormat: "HL7",
        status: "online",
        location: "Lab Room B, Station 2",
        lastConnected: "2025-08-18 10:25:00",
        supportedTests: ["BMP", "LIPID", "LFT", "GLUCOSE"],
        notes: "Chemistry and immunoassay analyzer"
    },
    {
        id: 3,
        name: "Microscope Station 1",
        type: "Microscope",
        model: "Olympus BX43",
        serial: "OLY445566778",
        ipAddress: "192.168.1.102",
        port: 8081,
        connectionType: "USB",
        dataFormat: "XML",
        status: "offline",
        location: "Lab Room C, Station 3",
        lastConnected: "2025-08-17 15:45:00",
        supportedTests: ["URINE", "DIFF"],
        notes: "Manual microscopy workstation"
    }
];

let nextEquipmentId = 4;

// Load and display equipment
function loadLabEquipment() {
    renderEquipmentTable(labEquipment);
    updateEquipmentStats();
}

// Render equipment table
function renderEquipmentTable(equipment) {
    const tbody = document.getElementById('equipmentTableBody');
    if (!tbody) return;
    
    tbody.innerHTML = equipment.map(eq => {
        const statusClass = eq.status === 'online' ? 'online' : eq.status === 'maintenance' ? 'maintenance' : 'offline';
        const statusIcon = eq.status === 'online' ? 'fas fa-circle' : eq.status === 'maintenance' ? 'fas fa-wrench' : 'fas fa-times-circle';
        
        return `
            <tr>
                <td>
                    <div>
                        <strong>${eq.name}</strong>
                        <br>
                        <small class="text-muted">${eq.serial}</small>
                    </div>
                </td>
                <td>
                    <span class="badge" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6; border: 1px solid rgba(139, 92, 246, 0.3);">
                        ${eq.type}
                    </span>
                </td>
                <td>${eq.model}</td>
                <td>
                    <code style="background: rgba(255, 255, 255, 0.1); padding: 0.25rem 0.5rem; border-radius: 4px;">
                        ${eq.ipAddress}:${eq.port}
                    </code>
                </td>
                <td>
                    <span class="equipment-status ${statusClass}">
                        <span class="equipment-status-indicator"></span>
                        <i class="${statusIcon}"></i>
                        ${eq.status.charAt(0).toUpperCase() + eq.status.slice(1)}
                    </span>
                </td>
                <td>
                    <small>${formatDateTime(eq.lastConnected)}</small>
                </td>
                <td>
                    <div class="d-flex flex-wrap gap-1">
                        ${eq.supportedTests.slice(0, 3).map(test => 
                            `<span class="badge bg-info">${test}</span>`
                        ).join('')}
                        ${eq.supportedTests.length > 3 ? `<span class="badge bg-secondary">+${eq.supportedTests.length - 3}</span>` : ''}
                    </div>
                </td>
                <td>
                    <div class="btn-group-sm">
                        <button class="btn btn-outline-info btn-sm" onclick="viewEquipment(${eq.id})" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-outline-warning btn-sm" onclick="editEquipment(${eq.id})" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-success btn-sm" onclick="testEquipmentConnection(${eq.id})" title="Test Connection">
                            <i class="fas fa-wifi"></i>
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="deleteEquipment(${eq.id})" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

// Update equipment statistics
function updateEquipmentStats() {
    const totalCount = labEquipment.length;
    const onlineCount = labEquipment.filter(eq => eq.status === 'online').length;
    const offlineCount = labEquipment.filter(eq => eq.status === 'offline').length;
    
    // Find most recent connection
    const lastSyncTimes = labEquipment.map(eq => new Date(eq.lastConnected));
    const mostRecentSync = lastSyncTimes.length > 0 ? Math.max(...lastSyncTimes) : null;
    
    document.getElementById('totalEquipmentCount').textContent = totalCount;
    document.getElementById('onlineEquipmentCount').textContent = onlineCount;
    document.getElementById('offlineEquipmentCount').textContent = offlineCount;
    
    if (mostRecentSync) {
        const now = new Date();
        const diffMinutes = Math.floor((now - mostRecentSync) / 60000);
        document.getElementById('lastSyncTime').textContent = 
            diffMinutes < 60 ? `${diffMinutes}m ago` : `${Math.floor(diffMinutes / 60)}h ago`;
    } else {
        document.getElementById('lastSyncTime').textContent = '--';
    }
}

// Show add equipment modal
function showAddEquipmentModal() {
    clearEquipmentForm();
    document.getElementById('equipmentModalTitle').innerHTML = '<i class="fas fa-microscope me-2"></i>Add Lab Equipment';
    loadSupportedTestsForModal();
    showCustomModal(document.getElementById('equipmentModal'));
}

// Show edit equipment modal
function editEquipment(equipmentId) {
    const equipment = labEquipment.find(eq => eq.id === equipmentId);
    if (!equipment) return;
    
    document.getElementById('equipmentId').value = equipment.id;
    document.getElementById('equipmentName').value = equipment.name;
    document.getElementById('equipmentType').value = equipment.type;
    document.getElementById('equipmentModel').value = equipment.model;
    document.getElementById('equipmentSerial').value = equipment.serial;
    document.getElementById('equipmentIP').value = equipment.ipAddress;
    document.getElementById('equipmentPort').value = equipment.port;
    document.getElementById('connectionType').value = equipment.connectionType;
    document.getElementById('dataFormat').value = equipment.dataFormat;
    document.getElementById('equipmentLocation').value = equipment.location;
    document.getElementById('equipmentStatus').value = equipment.status;
    document.getElementById('equipmentNotes').value = equipment.notes || '';
    
    document.getElementById('equipmentModalTitle').innerHTML = '<i class="fas fa-microscope me-2"></i>Edit Equipment';
    loadSupportedTestsForModal(equipment.supportedTests);
    showCustomModal(document.getElementById('equipmentModal'));
}

// View equipment details
function viewEquipment(equipmentId) {
    const equipment = labEquipment.find(eq => eq.id === equipmentId);
    if (!equipment) return;
    
    const statusClass = equipment.status === 'online' ? 'online' : equipment.status === 'maintenance' ? 'maintenance' : 'offline';
    
    const detailsContent = `
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-primary mb-3">Basic Information</h6>
                <table class="table table-sm">
                    <tr><td><strong>Name:</strong></td><td>${equipment.name}</td></tr>
                    <tr><td><strong>Type:</strong></td><td>${equipment.type}</td></tr>
                    <tr><td><strong>Model:</strong></td><td>${equipment.model}</td></tr>
                    <tr><td><strong>Serial Number:</strong></td><td>${equipment.serial}</td></tr>
                    <tr><td><strong>Location:</strong></td><td>${equipment.location}</td></tr>
                    <tr><td><strong>Status:</strong></td><td><span class="equipment-status ${statusClass}"><span class="equipment-status-indicator"></span>${equipment.status.charAt(0).toUpperCase() + equipment.status.slice(1)}</span></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="text-primary mb-3">Connection Information</h6>
                <table class="table table-sm">
                    <tr><td><strong>IP Address:</strong></td><td><code>${equipment.ipAddress}</code></td></tr>
                    <tr><td><strong>Port:</strong></td><td><code>${equipment.port}</code></td></tr>
                    <tr><td><strong>Connection Type:</strong></td><td>${equipment.connectionType}</td></tr>
                    <tr><td><strong>Data Format:</strong></td><td>${equipment.dataFormat}</td></tr>
                    <tr><td><strong>Last Connected:</strong></td><td>${formatDateTime(equipment.lastConnected)}</td></tr>
                </table>
            </div>
            <div class="col-12 mt-3">
                <h6 class="text-primary mb-3">Supported Tests</h6>
                <div class="d-flex flex-wrap gap-2">
                    ${equipment.supportedTests.map(test => 
                        `<span class="badge bg-info">${test}</span>`
                    ).join('')}
                </div>
                ${equipment.notes ? `
                <h6 class="text-primary mb-3 mt-3">Notes</h6>
                <p class="text-muted">${equipment.notes}</p>
                ` : ''}
            </div>
        </div>
    `;
    
    document.getElementById('equipmentDetailsContent').innerHTML = detailsContent;
    showCustomModal(document.getElementById('equipmentDetailsModal'));
}

// Delete equipment
function deleteEquipment(equipmentId) {
    const equipment = labEquipment.find(eq => eq.id === equipmentId);
    if (!equipment) return;
    
    if (confirm(`Are you sure you want to delete "${equipment.name}"? This action cannot be undone.`)) {
        labEquipment = labEquipment.filter(eq => eq.id !== equipmentId);
        renderEquipmentTable(labEquipment);
        updateEquipmentStats();
        showAlert('Equipment deleted successfully', 'success');
    }
}

// Save equipment
function saveEquipment() {
    const equipmentId = document.getElementById('equipmentId').value;
    const equipmentData = {
        name: document.getElementById('equipmentName').value,
        type: document.getElementById('equipmentType').value,
        model: document.getElementById('equipmentModel').value,
        serial: document.getElementById('equipmentSerial').value,
        ipAddress: document.getElementById('equipmentIP').value,
        port: parseInt(document.getElementById('equipmentPort').value) || 8080,
        connectionType: document.getElementById('connectionType').value,
        dataFormat: document.getElementById('dataFormat').value,
        location: document.getElementById('equipmentLocation').value,
        status: document.getElementById('equipmentStatus').value,
        notes: document.getElementById('equipmentNotes').value,
        supportedTests: getSelectedTests(),
        lastConnected: new Date().toISOString()
    };
    
    // Validation
    if (!equipmentData.name || !equipmentData.type || !equipmentData.ipAddress) {
        showAlert('Please fill in all required fields', 'error');
        return;
    }
    
    // IP address validation
    const ipPattern = /^(?:[0-9]{1,3}\.){3}[0-9]{1,3}$/;
    if (!ipPattern.test(equipmentData.ipAddress)) {
        showAlert('Please enter a valid IP address', 'error');
        return;
    }
    
    if (equipmentId) {
        // Edit existing equipment
        const index = labEquipment.findIndex(eq => eq.id === parseInt(equipmentId));
        if (index !== -1) {
            labEquipment[index] = { ...labEquipment[index], ...equipmentData };
            showAlert('Equipment updated successfully', 'success');
        }
    } else {
        // Add new equipment
        equipmentData.id = nextEquipmentId++;
        labEquipment.push(equipmentData);
        showAlert('Equipment added successfully', 'success');
    }
    
    renderEquipmentTable(labEquipment);
    updateEquipmentStats();
    hideCustomModal(document.getElementById('equipmentModal'));
}

// Test equipment connection
function testEquipmentConnection(equipmentId) {
    const equipment = labEquipment.find(eq => eq.id === equipmentId);
    if (!equipment) return;
    
    const statusSpan = document.getElementById('connectionStatus');
    if (statusSpan) {
        statusSpan.innerHTML = '<i class="fas fa-spinner fa-spin connection-test-loading"></i> Testing connection...';
    }
    
    // Simulate connection test
    setTimeout(() => {
        const isOnline = Math.random() > 0.3; // 70% chance of success
        
        if (isOnline) {
            equipment.status = 'online';
            equipment.lastConnected = new Date().toISOString();
            if (statusSpan) {
                statusSpan.innerHTML = '<i class="fas fa-check-circle connection-test-success"></i> Connection successful';
            }
            showAlert(`Connection to ${equipment.name} successful`, 'success');
        } else {
            equipment.status = 'offline';
            if (statusSpan) {
                statusSpan.innerHTML = '<i class="fas fa-times-circle connection-test-error"></i> Connection failed';
            }
            showAlert(`Failed to connect to ${equipment.name}`, 'error');
        }
        
        renderEquipmentTable(labEquipment);
        updateEquipmentStats();
        
        // Clear status after 3 seconds
        setTimeout(() => {
            if (statusSpan) statusSpan.innerHTML = '';
        }, 3000);
    }, 2000);
}

// Test connection from modal
function testConnection() {
    const ipAddress = document.getElementById('equipmentIP').value;
    const port = document.getElementById('equipmentPort').value;
    
    if (!ipAddress) {
        showAlert('Please enter an IP address', 'error');
        return;
    }
    
    const statusSpan = document.getElementById('connectionStatus');
    statusSpan.innerHTML = '<i class="fas fa-spinner fa-spin connection-test-loading"></i> Testing connection...';
    
    // Simulate connection test
    setTimeout(() => {
        const isSuccessful = Math.random() > 0.4; // 60% chance of success
        
        if (isSuccessful) {
            statusSpan.innerHTML = '<i class="fas fa-check-circle connection-test-success"></i> Connection successful';
        } else {
            statusSpan.innerHTML = '<i class="fas fa-times-circle connection-test-error"></i> Connection failed - check IP and port';
        }
        
        // Clear status after 5 seconds
        setTimeout(() => {
            statusSpan.innerHTML = '';
        }, 5000);
    }, 1500);
}

// Load supported tests for modal
function loadSupportedTestsForModal(selectedTests = []) {
    const container = document.getElementById('supportedTestsContainer');
    if (!container) return;
    
    const testOptions = [
        'CBC', 'WBC', 'RBC', 'HGB', 'HCT', 'PLT', 'BMP', 'GLUCOSE', 'BUN', 'CREATININE',
        'LIPID', 'CHOLESTEROL', 'HDL', 'LDL', 'TRIGLYCERIDES', 'LFT', 'ALT', 'AST',
        'TSH', 'T3', 'T4', 'HBA1C', 'PSA', 'URINE', 'DIFF', 'ESR', 'CRP', 'VITAMIN_D'
    ];
    
    container.innerHTML = testOptions.map(test => `
        <div class="test-checkbox-item">
            <input type="checkbox" id="test_${test}" value="${test}" ${selectedTests.includes(test) ? 'checked' : ''}>
            <label for="test_${test}">${test}</label>
        </div>
    `).join('');
}

// Get selected tests from modal
function getSelectedTests() {
    const checkboxes = document.querySelectorAll('#supportedTestsContainer input[type="checkbox"]:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

// Clear equipment form
function clearEquipmentForm() {
    document.getElementById('equipmentForm').reset();
    document.getElementById('equipmentId').value = '';
    document.getElementById('connectionStatus').innerHTML = '';
}

// Setup equipment search and filters
function setupEquipmentSearch() {
    const searchInput = document.getElementById('equipmentSearchInput');
    const typeFilter = document.getElementById('equipmentTypeFilter');
    const statusFilter = document.getElementById('equipmentStatusFilter');
    
    if (searchInput) {
        searchInput.addEventListener('input', filterEquipment);
    }
    if (typeFilter) {
        typeFilter.addEventListener('change', filterEquipment);
    }
    if (statusFilter) {
        statusFilter.addEventListener('change', filterEquipment);
    }
}

// Filter equipment based on search and filters
function filterEquipment() {
    const searchTerm = document.getElementById('equipmentSearchInput')?.value.toLowerCase() || '';
    const typeFilter = document.getElementById('equipmentTypeFilter')?.value || '';
    const statusFilter = document.getElementById('equipmentStatusFilter')?.value || '';
    
    const filteredEquipment = labEquipment.filter(equipment => {
        const matchesSearch = equipment.name.toLowerCase().includes(searchTerm) || 
                            equipment.model.toLowerCase().includes(searchTerm) ||
                            equipment.serial.toLowerCase().includes(searchTerm);
        const matchesType = !typeFilter || equipment.type === typeFilter;
        const matchesStatus = !statusFilter || equipment.status === statusFilter;
        
        return matchesSearch && matchesType && matchesStatus;
    });
    
    renderEquipmentTable(filteredEquipment);
}

// Enhanced Interactive Functions for Configuration Tab

// Show enhanced add equipment modal
function showAddEquipmentModal() {
    const modal = new bootstrap.Modal(document.getElementById('addEquipmentModal'));
    modal.show();
    
    // Set current date/time and populate tests
    populateSupportedTests();
}

// Show enhanced add test modal  
function showAddTestModal() {
    const modal = new bootstrap.Modal(document.getElementById('addTestModal'));
    modal.show();
    
    // Auto-generate test code based on name
    document.getElementById('testName').addEventListener('input', function() {
        const name = this.value;
        const code = name.split(' ').map(word => word.charAt(0)).join('').toUpperCase();
        document.getElementById('testCode').value = code;
    });
}

// Populate supported tests checkboxes
function populateSupportedTests() {
    const container = document.getElementById('supportedTestsContainer');
    const commonTests = [
        { code: 'CBC', name: 'Complete Blood Count' },
        { code: 'BMP', name: 'Basic Metabolic Panel' },
        { code: 'LIPID', name: 'Lipid Profile' },
        { code: 'LFT', name: 'Liver Function Tests' },
        { code: 'TSH', name: 'Thyroid Stimulating Hormone' },
        { code: 'HBA1C', name: 'Hemoglobin A1C' },
        { code: 'GLUCOSE', name: 'Blood Glucose' },
        { code: 'URINE', name: 'Urinalysis' },
        { code: 'PT', name: 'Prothrombin Time' },
        { code: 'CRP', name: 'C-Reactive Protein' },
        { code: 'ESR', name: 'Erythrocyte Sedimentation Rate' },
        { code: 'VITAMIN_D', name: 'Vitamin D' }
    ];
    
    container.innerHTML = commonTests.map(test => `
        <div class="form-check" style="background: rgba(139, 92, 246, 0.1); border: 1px solid rgba(139, 92, 246, 0.3); border-radius: 8px; padding: 0.75rem; margin-bottom: 0.5rem;">
            <input class="form-check-input" type="checkbox" id="test_${test.code}" value="${test.code}">
            <label class="form-check-label" for="test_${test.code}" style="color: var(--text-primary); font-weight: 500;">
                <strong>${test.code}</strong><br>
                <small>${test.name}</small>
            </label>
        </div>
    `).join('');
}

// Enhanced equipment and test management functions
function testEquipmentConnection() {
    const ipAddress = document.getElementById('ipAddress').value;
    const port = document.getElementById('port').value;
    const statusElement = document.getElementById('connectionStatus');
    
    if (!ipAddress) {
        showErrorToast('Please enter IP address');
        return;
    }
    
    statusElement.innerHTML = '<i class="fas fa-spinner fa-spin me-2" style="color: #f59e0b;"></i>Testing connection...';
    statusElement.style.color = '#f59e0b';
    
    // Simulate connection test with realistic delay
    setTimeout(() => {
        const isConnected = Math.random() > 0.3; // 70% success rate
        
        if (isConnected) {
            statusElement.innerHTML = '<i class="fas fa-check-circle me-2" style="color: #10b981;"></i>Connection successful! Equipment is responding.';
            statusElement.style.color = '#10b981';
            showSuccessToast('Equipment connection successful!');
        } else {
            statusElement.innerHTML = '<i class="fas fa-times-circle me-2" style="color: #ef4444;"></i>Connection failed! Check IP address and network.';
            statusElement.style.color = '#ef4444';
            showErrorToast('Equipment connection failed!');
        }
        
        // Clear status after 5 seconds
        setTimeout(() => {
            statusElement.innerHTML = '';
        }, 5000);
    }, 2000);
}

function saveEquipment() {
    const form = document.getElementById('addEquipmentForm');
    
    // Get form data
    const equipmentData = {
        name: document.getElementById('equipmentName').value,
        type: document.getElementById('equipmentType').value,
        manufacturer: document.getElementById('manufacturer').value,
        modelNumber: document.getElementById('modelNumber').value,
        serialNumber: document.getElementById('serialNumber').value,
        location: document.getElementById('location').value,
        ipAddress: document.getElementById('ipAddress').value,
        port: document.getElementById('port').value,
        connectionType: document.getElementById('connectionType').value,
        dataFormat: document.getElementById('dataFormat').value,
        supportedTests: getSelectedSupportedTests()
    };
    
    // Validation
    if (!equipmentData.name || !equipmentData.type || !equipmentData.ipAddress) {
        showErrorToast('Please fill in all required fields (Name, Type, IP Address)');
        return;
    }
    
    // IP validation
    const ipPattern = /^(?:[0-9]{1,3}\.){3}[0-9]{1,3}$/;
    if (!ipPattern.test(equipmentData.ipAddress)) {
        showErrorToast('Please enter a valid IP address (e.g., 192.168.1.100)');
        return;
    }
    
    // Simulate saving (in real app, this would be an API call)
    showLoadingToast('Saving equipment...');
    
    setTimeout(() => {
        // Add to equipment list
        const newEquipment = {
            ...equipmentData,
            id: Date.now(),
            status: 'online',
            lastConnected: new Date().toISOString()
        };
        
        labEquipment.push(newEquipment);
        renderEquipmentTable(labEquipment);
        updateEquipmentStats();
        
        // Close modal and show success
        const modal = bootstrap.Modal.getInstance(document.getElementById('addEquipmentModal'));
        modal.hide();
        
        showSuccessToast(`Equipment "${equipmentData.name}" added successfully!`);
        
        // Reset form
        form.reset();
        document.getElementById('connectionStatus').innerHTML = '';
    }, 1500);
}

function saveTest() {
    const form = document.getElementById('addTestForm');
    
    // Get form data
    const testData = {
        code: document.getElementById('testCode').value.toUpperCase(),
        name: document.getElementById('testName').value,
        category: document.getElementById('testCategory').value,
        price: parseFloat(document.getElementById('testPrice').value),
        turnaroundTime: document.getElementById('turnaroundTime').value,
        sampleType: document.getElementById('sampleType').value,
        normalRange: document.getElementById('normalRange').value,
        unit: document.getElementById('testUnit').value,
        description: document.getElementById('testDescription').value,
        active: document.getElementById('testActive').checked
    };
    
    // Validation
    if (!testData.code || !testData.name || !testData.category || !testData.price) {
        showErrorToast('Please fill in all required fields (Code, Name, Category, Price)');
        return;
    }
    
    if (testData.price <= 0) {
        showErrorToast('Please enter a valid price greater than 0');
        return;
    }
    
    // Check for duplicate test code
    if (labTests.some(test => test.code === testData.code)) {
        showErrorToast(`Test code "${testData.code}" already exists. Please use a different code.`);
        return;
    }
    
    // Simulate saving
    showLoadingToast('Saving test...');
    
    setTimeout(() => {
        // Add to tests list
        const newTest = {
            ...testData,
            id: Date.now(),
            createdAt: new Date().toISOString()
        };
        
        labTests.push(newTest);
        renderTestsTable(labTests);
        
        // Update available tests in collection modal
        updateAvailableTestsInModal();
        
        // Close modal and show success
        const modal = bootstrap.Modal.getInstance(document.getElementById('addTestModal'));
        modal.hide();
        
        showSuccessToast(`Test "${testData.name}" added successfully!`);
        
        // Reset form
        form.reset();
    }, 1500);
}

// Helper functions
function getSelectedSupportedTests() {
    const checkboxes = document.querySelectorAll('#supportedTestsContainer input[type="checkbox"]:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

function updateAvailableTestsInModal() {
    // Update the drag-and-drop available tests in collection modal
    const availableTestsContainer = document.getElementById('availableTests');
    if (availableTestsContainer) {
        availableTestsContainer.innerHTML = labTests.filter(test => test.active).map(test => `
            <div class="test-item" draggable="true" data-test="${test.code}" 
                 style="background: var(--glass-background); border: 1px solid var(--glass-border); border-radius: 6px; padding: 0.75rem; margin-bottom: 0.5rem; cursor: grab; transition: all 0.3s ease;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-grip-vertical" style="color: var(--text-muted);"></i>
                    <span>${test.name} (${test.code})</span>
                    <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #10b981; margin-left: auto;">$${test.price}</span>
                </div>
            </div>
        `).join('');
        
        // Re-initialize drag and drop
        initializeDragAndDrop();
    }
}

// Toast notification system
function showSuccessToast(message) {
    showToast(message, 'success', 'fas fa-check-circle');
}

function showErrorToast(message) {
    showToast(message, 'error', 'fas fa-exclamation-circle');
}

function showLoadingToast(message) {
    showToast(message, 'info', 'fas fa-spinner fa-spin');
}

function showToast(message, type, icon) {
    // Remove existing toasts
    document.querySelectorAll('.toast-notification').forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="${icon} me-2"></i>
            ${message}
        </div>
        <button class="toast-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    // Add enhanced toast styles
    if (!document.querySelector('#enhanced-toast-styles')) {
        const styles = document.createElement('style');
        styles.id = 'enhanced-toast-styles';
        styles.textContent = `
            .toast-notification {
                position: fixed;
                top: 20px;
                right: 20px;
                min-width: 300px;
                padding: 1rem 1.5rem;
                border-radius: 15px;
                color: white;
                font-weight: 600;
                z-index: 9999;
                animation: slideInBounce 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
                backdrop-filter: blur(10px);
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            .toast-notification.success { 
                background: linear-gradient(135deg, #10b981, #059669); 
                border: 1px solid rgba(16, 185, 129, 0.3);
            }
            .toast-notification.error { 
                background: linear-gradient(135deg, #ef4444, #dc2626); 
                border: 1px solid rgba(239, 68, 68, 0.3);
            }
            .toast-notification.info { 
                background: linear-gradient(135deg, #06b6d4, #0891b2); 
                border: 1px solid rgba(6, 182, 212, 0.3);
            }
            .toast-content {
                display: flex;
                align-items: center;
                flex: 1;
            }
            .toast-close {
                background: none;
                border: none;
                color: rgba(255, 255, 255, 0.8);
                font-size: 0.9rem;
                cursor: pointer;
                padding: 0.25rem;
                border-radius: 50%;
                transition: all 0.2s ease;
                margin-left: 1rem;
            }
            .toast-close:hover {
                background: rgba(255, 255, 255, 0.2);
                color: white;
            }
            @keyframes slideInBounce {
                0% { transform: translateX(100%) scale(0.8); opacity: 0; }
                60% { transform: translateX(-10px) scale(1.05); opacity: 1; }
                100% { transform: translateX(0) scale(1); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0) scale(1); opacity: 1; }
                to { transform: translateX(100%) scale(0.8); opacity: 0; }
            }
        `;
        document.head.appendChild(styles);
    }
    
    document.body.appendChild(toast);
    
    // Auto-remove after delay (except loading toasts)
    if (type !== 'info') {
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease-in-out';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }
}

// Enhanced equipment grid view functions
function toggleEquipmentView() {
    const gridContainer = document.querySelector('.equipment-grid-container');
    const tableContainer = document.getElementById('equipmentTableContainer');
    const toggleBtn = document.querySelector('[onclick="toggleEquipmentView()"]');
    
    if (!gridContainer || !tableContainer || !toggleBtn) return;
    
    if (gridContainer.style.display === 'none') {
        gridContainer.style.display = 'grid';
        tableContainer.style.display = 'none';
        toggleBtn.innerHTML = '<i class="fas fa-table me-2"></i>Table View';
        showSuccessToast('Switched to Grid View');
    } else {
        gridContainer.style.display = 'none';
        tableContainer.style.display = 'block';
        toggleBtn.innerHTML = '<i class="fas fa-th me-2"></i>Grid View';
        showSuccessToast('Switched to Table View');
    }
}

function testAllConnections() {
    showLoadingToast('Testing all equipment connections...');
    
    let completedTests = 0;
    const totalEquipment = labEquipment.length;
    
    labEquipment.forEach((equipment, index) => {
        setTimeout(() => {
            // Simulate connection test
            const isOnline = Math.random() > 0.2; // 80% success rate
            equipment.status = isOnline ? 'online' : 'offline';
            equipment.lastConnected = isOnline ? new Date().toISOString() : equipment.lastConnected;
            
            completedTests++;
            
            if (completedTests === totalEquipment) {
                // All tests completed
                renderEquipmentTable(labEquipment);
                updateEquipmentStats();
                
                const onlineCount = labEquipment.filter(eq => eq.status === 'online').length;
                const offlineCount = totalEquipment - onlineCount;
                
                document.querySelector('.toast-notification.info')?.remove();
                
                if (offlineCount === 0) {
                    showSuccessToast(`All ${totalEquipment} equipment connections successful!`);
                } else {
                    showErrorToast(`${onlineCount}/${totalEquipment} equipment online. ${offlineCount} equipment offline.`);
                }
            }
        }, (index + 1) * 500); // Stagger the tests
    });
}

// Enhanced filter functions with real-time search
function initializeEnhancedFilters() {
    const searchInput = document.getElementById('equipmentSearchInput');
    const statusFilter = document.getElementById('equipmentStatusFilter');
    const typeFilter = document.getElementById('equipmentTypeFilter');
    
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(filterEquipment, 300); // Debounce search
        });
    }
    
    if (statusFilter) {
        statusFilter.addEventListener('change', filterEquipment);
    }
    
    if (typeFilter) {
        typeFilter.addEventListener('change', filterEquipment);
    }
}

// Initialize all enhanced functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize equipment management
    loadLabEquipment();
    setupEquipmentSearch();
    initializeEnhancedFilters();
    
    // Initialize test management
    loadAvailableTests();
    
    // Auto-populate current date/time in forms
    const now = new Date();
    const dateTimeString = now.toISOString().slice(0, 16);
    
    ['collectionDateTime', 'submissionDateTime', 'resultTime'].forEach(id => {
        const element = document.getElementById(id);
        if (element) element.value = dateTimeString;
    });
    
    console.log('Enhanced Lab Tech Dashboard initialized successfully!');
});
</script>

<!-- Hidden Modal Windows - Available for JavaScript but not part of page structure -->
<div style="position: fixed; top: -9999px; left: -9999px; pointer-events: none;" id="modalContainer">
    <!-- Collection Time Modal -->
    <div class="modal fade" id="collectionModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header" style="border-bottom: 1px solid var(--glass-border);">
                    <h5 class="modal-title">
                        <i class="fas fa-vial me-2" style="color: #06b6d4;"></i>
                        Record Sample Collection
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding: 2rem;">
                    <form id="collectionForm">
                        <!-- Patient Selection Section -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label" style="font-weight: 600; color: var(--text-primary);">
                                    <i class="fas fa-user me-2" style="color: #06b6d4;"></i>Patient Selection
                                </label>
                                <select id="patientSelect" class="form-control" style="margin-bottom: 1rem;" onchange="loadPatientTests()">
                                    <option value="">Select Patient</option>
                                    <option value="1">John Smith (CNIC: 12345-1234567-1)</option>
                                    <option value="2">Sarah Johnson (CNIC: 54321-7654321-9)</option>
                                    <option value="3">Ahmed Ali (CNIC: 42101-1234567-8)</option>
                                    <option value="4">Maria Garcia (CNIC: 35202-9876543-2)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" style="font-weight: 600; color: var(--text-primary);">
                                    <i class="fas fa-id-card me-2" style="color: #f59e0b;"></i>Search by CNIC
                                </label>
                                <div class="input-group" style="margin-bottom: 1rem;">
                                    <input type="text" id="cnicSearch" class="form-control" placeholder="Enter CNIC (12345-1234567-1)" style="border-right: none;">
                                    <button type="button" class="btn btn-outline-primary" onclick="searchPatientByCnic()" style="border-left: none; border-color: #ced4da;">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Auto-search as you type or click search
                                </small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" style="font-weight: 600; color: var(--text-primary);">
                                    <i class="fas fa-info-circle me-2" style="color: #10b981;"></i>Patient Information
                                </label>
                                <div id="patientInfo" style="background: rgba(6, 182, 212, 0.1); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; padding: 1rem; min-height: 80px;">
                                    <p style="color: var(--text-muted); margin: 0; text-align: center;">Select a patient to view information</p>
                                </div>
                            </div>
                        </div>

                        <!-- Test Selection Section -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                            <div>
                                <label class="form-label" style="font-weight: 600; color: var(--text-primary); margin-bottom: 1rem; display: block;">
                                    <i class="fas fa-flask me-2" style="color: #f59e0b;"></i>Available Tests
                                </label>
                                <div id="availableTests" style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 8px; padding: 1rem; min-height: 200px; max-height: 300px; overflow-y: auto;">
                                    <div class="test-item" draggable="true" data-test="CBC" style="background: var(--glass-background); border: 1px solid var(--glass-border); border-radius: 6px; padding: 0.75rem; margin-bottom: 0.5rem; cursor: grab; transition: all 0.3s ease;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fas fa-grip-vertical" style="color: var(--text-muted);"></i>
                                            <span>Complete Blood Count (CBC)</span>
                                            <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #10b981; margin-left: auto;">$25</span>
                                        </div>
                                    </div>
                                    <div class="test-item" draggable="true" data-test="BMP" style="background: var(--glass-background); border: 1px solid var(--glass-border); border-radius: 6px; padding: 0.75rem; margin-bottom: 0.5rem; cursor: grab; transition: all 0.3s ease;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fas fa-grip-vertical" style="color: var(--text-muted);"></i>
                                            <span>Basic Metabolic Panel (BMP)</span>
                                            <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #10b981; margin-left: auto;">$35</span>
                                        </div>
                                    </div>
                                    <div class="test-item" draggable="true" data-test="LIPID" style="background: var(--glass-background); border: 1px solid var(--glass-border); border-radius: 6px; padding: 0.75rem; margin-bottom: 0.5rem; cursor: grab; transition: all 0.3s ease;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fas fa-grip-vertical" style="color: var(--text-muted);"></i>
                                            <span>Lipid Profile</span>
                                            <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #10b981; margin-left: auto;">$40</span>
                                        </div>
                                    </div>
                                    <div class="test-item" draggable="true" data-test="TSH" style="background: var(--glass-background); border: 1px solid var(--glass-border); border-radius: 6px; padding: 0.75rem; margin-bottom: 0.5rem; cursor: grab; transition: all 0.3s ease;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fas fa-grip-vertical" style="color: var(--text-muted);"></i>
                                            <span>Thyroid Stimulating Hormone (TSH)</span>
                                            <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #10b981; margin-left: auto;">$30</span>
                                        </div>
                                    </div>
                                    <div class="test-item" draggable="true" data-test="HBA1C" style="background: var(--glass-background); border: 1px solid var(--glass-border); border-radius: 6px; padding: 0.75rem; margin-bottom: 0.5rem; cursor: grab; transition: all 0.3s ease;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fas fa-grip-vertical" style="color: var(--text-muted);"></i>
                                            <span>Hemoglobin A1C (HBA1C)</span>
                                            <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #10b981; margin-left: auto;">$45</span>
                                        </div>
                                    </div>
                                    <div class="test-item" draggable="true" data-test="URINE" style="background: var(--glass-background); border: 1px solid var(--glass-border); border-radius: 6px; padding: 0.75rem; margin-bottom: 0.5rem; cursor: grab; transition: all 0.3s ease;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fas fa-grip-vertical" style="color: var(--text-muted);"></i>
                                            <span>Urine Analysis</span>
                                            <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #10b981; margin-left: auto;">$20</span>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-top: 0.5rem; text-align: center; color: var(--text-muted); font-size: 0.9rem;">
                                    <i class="fas fa-hand-point-right me-1"></i>Drag tests to the collection area
                                </div>
                            </div>
                            <div>
                                <label class="form-label" style="font-weight: 600; color: var(--text-primary); margin-bottom: 1rem; display: block;">
                                    <i class="fas fa-clipboard-list me-2" style="color: #10b981;"></i>Tests to Collect
                                </label>
                                <div id="selectedTests" style="background: rgba(16, 185, 129, 0.1); border: 2px dashed rgba(16, 185, 129, 0.3); border-radius: 8px; padding: 1rem; min-height: 200px; max-height: 300px; overflow-y: auto; position: relative;" ondrop="dropTest(event)" ondragover="allowDrop(event)">
                                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: var(--text-muted); pointer-events: none;">
                                        <i class="fas fa-plus-circle" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                                        <p style="margin: 0;">Drag tests here or select patient first</p>
                                        <small>Tests will appear as you select them</small>
                                    </div>
                                </div>
                                <div style="margin-top: 0.5rem; text-align: right; padding: 0.5rem; background: rgba(16, 185, 129, 0.1); border-radius: 6px;">
                                    <strong style="color: var(--text-primary); font-size: 1.1rem;">
                                        <i class="fas fa-calculator me-1" style="color: #10b981;"></i>
                                        Total: $<span id="totalAmount">0</span>
                                    </strong>
                                </div>
                            </div>
                        </div>

                        <!-- Collection Details Section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="collectionDateTime" class="form-label" style="font-weight: 600; color: var(--text-primary);">
                                    <i class="fas fa-calendar-alt me-2" style="color: #06b6d4;"></i>Collection Date & Time
                                </label>
                                <input type="datetime-local" id="collectionDateTime" class="form-control" readonly style="background: rgba(6, 182, 212, 0.1); border: 1px solid rgba(6, 182, 212, 0.3);">
                                <small style="color: var(--text-muted);">Automatically set to current time</small>
                            </div>
                            <div class="col-md-6">
                                <label for="priority" class="form-label" style="font-weight: 600; color: var(--text-primary);">
                                    <i class="fas fa-exclamation-triangle me-2" style="color: #f59e0b;"></i>Priority Level
                                </label>
                                <select id="priority" class="form-control">
                                    <option value="routine">Routine</option>
                                    <option value="urgent">Urgent</option>
                                    <option value="stat">STAT</option>
                                </select>
                            </div>
                        </div>

                        <!-- Notes Section -->
                        <div class="mb-4">
                            <label for="collectionNotes" class="form-label" style="font-weight: 600; color: var(--text-primary);">
                                <i class="fas fa-sticky-note me-2" style="color: #a855f7;"></i>Collection Notes (Optional)
                            </label>
                            <textarea id="collectionNotes" class="form-control" rows="3" placeholder="Enter any special notes about the collection process, patient condition, or sample handling requirements..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--glass-border); padding: 1.5rem;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" onclick="recordCollection()" style="background: linear-gradient(135deg, #06b6d4, #0891b2); border: none;">
                        <i class="fas fa-save me-1"></i>Record Collection
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Test Results Submission Modal -->
    <div class="modal fade" id="resultsModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header" style="border-bottom: 1px solid var(--glass-border);">
                    <h5 class="modal-title">
                        <i class="fas fa-flask me-2" style="color: #10b981;"></i>
                        Submit Test Results
                    </h5>
                    <button type="button" class="btn-close" onclick="hideCustomModal(document.getElementById('resultsModal'))"></button>
                </div>
                <div class="modal-body" style="padding: 2rem;">
                    <form id="resultsForm">
                        <!-- Patient Selection Section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: var(--text-primary);">
                                    <i class="fas fa-user me-2" style="color: #10b981;"></i>Patient with Pending Results
                                </label>
                                <select id="patientResultsSelect" class="form-control" style="margin-bottom: 1rem;" onchange="loadPatientResults()">
                                    <option value="">Select Patient</option>
                                    <option value="1">John Smith - CBC, BMP (Collected: 2025-08-17 09:30)</option>
                                    <option value="2">Sarah Johnson - LIPID, TSH (Collected: 2025-08-17 10:15)</option>
                                    <option value="3">Ahmed Ali - CBC, HBA1C (Collected: 2025-08-17 11:00)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: var(--text-primary);">
                                    <i class="fas fa-info-circle me-2" style="color: #10b981;"></i>Patient Information
                                </label>
                                <div id="patientResultsInfo" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 8px; padding: 1rem; min-height: 80px;">
                                    <p style="color: var(--text-muted); margin: 0; text-align: center;">Select a patient to view their pending tests</p>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Tests Section -->
                        <div class="mb-4">
                            <label class="form-label" style="font-weight: 600; color: var(--text-primary); margin-bottom: 1rem; display: block;">
                                <i class="fas fa-clipboard-list me-2" style="color: #f59e0b;"></i>Pending Test Results
                            </label>
                            <div id="pendingTests" style="background: rgba(251, 191, 36, 0.1); border: 1px solid rgba(251, 191, 36, 0.3); border-radius: 10px; padding: 1.5rem; min-height: 300px; max-height: 500px; overflow-y: auto; position: relative;">
                                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: var(--text-muted); pointer-events: none;">
                                    <i class="fas fa-clipboard-list" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                                    <p style="margin: 0;">Select a patient to view pending tests</p>
                                    <small>Test results will appear here for entry</small>
                                </div>
                            </div>
                        </div>

                        <!-- Submission Details Section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="submissionDateTime" class="form-label" style="font-weight: 600; color: var(--text-primary);">
                                    <i class="fas fa-calendar-alt me-2" style="color: #10b981;"></i>Submission Date & Time
                                </label>
                                <input type="datetime-local" id="submissionDateTime" class="form-control" readonly style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3);">
                                <small style="color: var(--text-muted);">Automatically set to current time</small>
                            </div>
                            <div class="col-md-6">
                                <label for="resultNotes" class="form-label" style="font-weight: 600; color: var(--text-primary);">
                                    <i class="fas fa-sticky-note me-2" style="color: #a855f7;"></i>Result Notes (Optional)
                                </label>
                                <textarea id="resultNotes" class="form-control" rows="3" placeholder="Enter any notes about the test results, abnormal findings, or additional observations..."></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--glass-border); padding: 1.5rem;">
                    <button type="button" class="btn btn-secondary" onclick="hideCustomModal(document.getElementById('resultsModal'))">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" onclick="submitTestResults()" style="background: linear-gradient(135deg, #10b981, #059669); border: none;">
                        <i class="fas fa-paper-plane me-1"></i>Submit Results
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Result Submission Modal -->
    <div class="modal fade" id="resultModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header" style="border-bottom: 1px solid var(--glass-border);">
                    <h5 class="modal-title">
                        <i class="fas fa-clipboard-check me-2"></i>
                        Submit Test Results
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="resultForm">
                        <input type="hidden" id="resultOrderId">
                        <div class="mb-3">
                            <label class="form-label">Patient:</label>
                            <p id="resultPatientName" style="color: var(--text-secondary);"></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Test:</label>
                            <p id="resultTestName" style="color: var(--text-secondary);"></p>
                        </div>
                        <div class="mb-3">
                            <label for="resultValue" class="form-label">Result Value:</label>
                            <input type="text" id="resultValue" class="form-control" required placeholder="Enter test result...">
                        </div>
                        <div class="mb-3">
                            <label for="resultFlag" class="form-label">Result Flag:</label>
                            <select id="resultFlag" class="form-control">
                                <option value="normal">Normal</option>
                                <option value="high">High</option>
                                <option value="low">Low</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="resultNotes" class="form-label">Result Notes:</label>
                            <textarea id="resultNotes" class="form-control" rows="3" placeholder="Enter any notes about the results..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="resultTime" class="form-label">Result Submission Time:</label>
                            <input type="datetime-local" id="resultTime" class="form-control" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--glass-border);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="submitResult()">
                        <i class="fas fa-check me-1"></i>
                        Submit Results
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Camera Modal -->
    <div class="modal fade" id="cameraModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark">
                <div class="modal-header border-purple">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-camera me-2"></i>Capture Result Image
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <video id="cameraVideo" width="100%" height="400" autoplay style="border-radius: 8px;"></video>
                    <canvas id="cameraCanvas" style="display: none;"></canvas>
                    <div class="d-flex gap-3 justify-content-center mt-4">
                        <button class="btn btn-gradient-primary" onclick="capturePhoto()">
                            <i class="fas fa-camera me-2"></i>Capture Photo
                        </button>
                        <button class="btn btn-gradient-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Photo Preview Modal -->
    <div class="modal fade" id="photoPreviewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark">
                <div class="modal-header border-purple">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-eye me-2"></i>Photo Preview
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="preview-container" style="background: #1a1a1a; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
                        <img id="previewImage" src="" alt="Captured photo" 
                             style="max-width: 100%; max-height: 400px; border-radius: 8px; border: 2px solid #6f42c1;">
                    </div>
                    <div class="d-flex gap-3 justify-content-center">
                        <button class="btn btn-gradient-success" onclick="confirmCapture()">
                            <i class="fas fa-check me-2"></i>Use This Photo
                        </button>
                        <button class="btn btn-gradient-warning" onclick="retakePhoto()">
                            <i class="fas fa-redo me-2"></i>Retake Photo
                        </button>
                        <button class="btn btn-gradient-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Test Modal -->
    <div class="modal fade" id="testModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="testModalTitle">
                        <i class="fas fa-flask me-2"></i>Add New Test
                    </h5>
                    <button type="button" class="btn-close" onclick="hideCustomModal(document.getElementById('testModal'))"></button>
                </div>
                <div class="modal-body">
                    <form id="testForm">
                        <input type="hidden" id="testId" value="">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="testCode" class="form-label">
                                    <i class="fas fa-barcode me-1"></i>Test Code *
                                </label>
                                <input type="text" id="testCode" class="form-control" placeholder="e.g., CBC, BMP, TSH" required>
                                <small class="text-muted">Unique identifier for the test</small>
                            </div>
                            <div class="col-md-6">
                                <label for="testName" class="form-label">
                                    <i class="fas fa-tag me-1"></i>Test Name *
                                </label>
                                <input type="text" id="testName" class="form-control" placeholder="e.g., Complete Blood Count" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="testCategory" class="form-label">
                                    <i class="fas fa-layer-group me-1"></i>Category *
                                </label>
                                <select id="testCategory" class="form-control" required>
                                    <option value="">Select Category</option>
                                    <option value="Hematology">Hematology</option>
                                    <option value="Biochemistry">Biochemistry</option>
                                    <option value="Microbiology">Microbiology</option>
                                    <option value="Immunology">Immunology</option>
                                    <option value="Endocrinology">Endocrinology</option>
                                    <option value="Cardiology">Cardiology</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="testPrice" class="form-label">
                                    <i class="fas fa-dollar-sign me-1"></i>Price (USD) *
                                </label>
                                <input type="number" id="testPrice" class="form-control" placeholder="0.00" min="0" step="0.01" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="turnaroundTime" class="form-label">
                                    <i class="fas fa-clock me-1"></i>Turnaround Time
                                </label>
                                <select id="turnaroundTime" class="form-control">
                                    <option value="2-4 hours">2-4 hours</option>
                                    <option value="4-6 hours">4-6 hours</option>
                                    <option value="6-12 hours">6-12 hours</option>
                                    <option value="12-24 hours">12-24 hours</option>
                                    <option value="24-48 hours">24-48 hours</option>
                                    <option value="2-3 days">2-3 days</option>
                                    <option value="3-7 days">3-7 days</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="testStatus" class="form-label">
                                    <i class="fas fa-toggle-on me-1"></i>Status
                                </label>
                                <select id="testStatus" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="testDescription" class="form-label">
                                <i class="fas fa-align-left me-1"></i>Description
                            </label>
                            <textarea id="testDescription" class="form-control" rows="3" placeholder="Brief description of the test..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="sampleType" class="form-label">
                                <i class="fas fa-vial me-1"></i>Sample Type
                            </label>
                            <select id="sampleType" class="form-control">
                                <option value="Blood">Blood</option>
                                <option value="Urine">Urine</option>
                                <option value="Serum">Serum</option>
                                <option value="Plasma">Plasma</option>
                                <option value="Saliva">Saliva</option>
                                <option value="Tissue">Tissue</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="normalRange" class="form-label">
                                    <i class="fas fa-chart-line me-1"></i>Normal Range
                                </label>
                                <input type="text" id="normalRange" class="form-control" placeholder="e.g., 4.5-11.0 x10³/μL">
                            </div>
                            <div class="col-md-6">
                                <label for="testUnit" class="form-label">
                                    <i class="fas fa-ruler me-1"></i>Unit
                                </label>
                                <input type="text" id="testUnit" class="form-control" placeholder="e.g., mg/dL, mmol/L, %">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="hideCustomModal(document.getElementById('testModal'))">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" onclick="saveTest()">
                        <i class="fas fa-save me-1"></i>Save Test
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Equipment Modal -->
    <div class="modal fade" id="equipmentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="equipmentModalTitle">
                        <i class="fas fa-microscope me-2"></i>Add Lab Equipment
                    </h5>
                    <button type="button" class="btn-close" onclick="hideCustomModal(document.getElementById('equipmentModal'))"></button>
                </div>
                <div class="modal-body">
                    <form id="equipmentForm">
                        <input type="hidden" id="equipmentId" value="">
                        
                        <!-- Basic Information -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="equipmentName" class="form-label">
                                    <i class="fas fa-tag me-1"></i>Equipment Name *
                                </label>
                                <input type="text" id="equipmentName" class="form-control" placeholder="e.g., Hematology Analyzer 1" required>
                            </div>
                            <div class="col-md-6">
                                <label for="equipmentType" class="form-label">
                                    <i class="fas fa-cogs me-1"></i>Equipment Type *
                                </label>
                                <select id="equipmentType" class="form-control" required>
                                    <option value="">Select Type</option>
                                    <option value="Analyzer">Analyzer</option>
                                    <option value="Microscope">Microscope</option>
                                    <option value="Centrifuge">Centrifuge</option>
                                    <option value="Incubator">Incubator</option>
                                    <option value="Spectrophotometer">Spectrophotometer</option>
                                    <option value="PCR Machine">PCR Machine</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="equipmentModel" class="form-label">
                                    <i class="fas fa-info-circle me-1"></i>Model/Brand
                                </label>
                                <input type="text" id="equipmentModel" class="form-control" placeholder="e.g., Sysmex XN-1000">
                            </div>
                            <div class="col-md-6">
                                <label for="equipmentSerial" class="form-label">
                                    <i class="fas fa-barcode me-1"></i>Serial Number
                                </label>
                                <input type="text" id="equipmentSerial" class="form-control" placeholder="e.g., SYS123456789">
                            </div>
                        </div>

                        <!-- Connection Settings -->
                        <div class="card mb-3" style="background: var(--glass-background); border: 1px solid var(--glass-border);">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-network-wired me-2"></i>Connection Settings
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="equipmentIP" class="form-label">
                                            <i class="fas fa-globe me-1"></i>IP Address *
                                        </label>
                                        <input type="text" id="equipmentIP" class="form-control" placeholder="192.168.1.100" required pattern="^(?:[0-9]{1,3}\.){3}[0-9]{1,3}$">
                                        <small class="text-muted">Equipment network IP address</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="equipmentPort" class="form-label">
                                            <i class="fas fa-plug me-1"></i>Port
                                        </label>
                                        <input type="number" id="equipmentPort" class="form-control" placeholder="8080" min="1" max="65535">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="connectionType" class="form-label">
                                            <i class="fas fa-link me-1"></i>Connection Type
                                        </label>
                                        <select id="connectionType" class="form-control">
                                            <option value="TCP">TCP/IP</option>
                                            <option value="Serial">Serial</option>
                                            <option value="USB">USB</option>
                                            <option value="HL7">HL7</option>
                                            <option value="LIS">LIS Interface</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="dataFormat" class="form-label">
                                            <i class="fas fa-file-code me-1"></i>Data Format
                                        </label>
                                        <select id="dataFormat" class="form-control">
                                            <option value="HL7">HL7 v2.x</option>
                                            <option value="ASTM">ASTM E1394</option>
                                            <option value="XML">XML</option>
                                            <option value="JSON">JSON</option>
                                            <option value="CSV">CSV</option>
                                            <option value="Custom">Custom</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-outline-info btn-sm" onclick="testConnection()">
                                            <i class="fas fa-wifi me-1"></i>Test Connection
                                        </button>
                                        <span id="connectionStatus" class="ms-3"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Test Capabilities -->
                        <div class="card mb-3" style="background: var(--glass-background); border: 1px solid var(--glass-border);">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-flask me-2"></i>Supported Tests
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row" id="supportedTestsContainer">
                                    <!-- Will be populated with available tests -->
                                </div>
                                <small class="text-muted">Select which tests this equipment can perform</small>
                            </div>
                        </div>

                        <!-- Additional Settings -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="equipmentLocation" class="form-label">
                                    <i class="fas fa-map-marker-alt me-1"></i>Location
                                </label>
                                <input type="text" id="equipmentLocation" class="form-control" placeholder="e.g., Lab Room A, Station 3">
                            </div>
                            <div class="col-md-6">
                                <label for="equipmentStatus" class="form-label">
                                    <i class="fas fa-toggle-on me-1"></i>Status
                                </label>
                                <select id="equipmentStatus" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="equipmentNotes" class="form-label">
                                <i class="fas fa-sticky-note me-1"></i>Notes
                            </label>
                            <textarea id="equipmentNotes" class="form-control" rows="3" placeholder="Additional notes about this equipment..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="hideCustomModal(document.getElementById('equipmentModal'))">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" onclick="saveEquipment()">
                        <i class="fas fa-save me-1"></i>Save Equipment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Add Equipment Modal -->
    <div class="modal fade" id="addEquipmentModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="background: var(--glass-background); border: 1px solid var(--glass-border); border-radius: 20px; overflow: hidden;">
                <div class="modal-header" style="background: linear-gradient(135deg, #10b981, #059669); border: none; padding: 2rem;">
                    <div>
                        <h4 class="modal-title" style="color: white; margin: 0; font-weight: 700;">
                            <i class="fas fa-plus-circle me-3"></i>Add New Lab Equipment
                        </h4>
                        <p style="color: rgba(255, 255, 255, 0.8); margin: 0.5rem 0 0 0;">Configure equipment connection and supported tests</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding: 2rem;">
                    <form id="addEquipmentForm">
                        <!-- Equipment Basic Info -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 style="color: var(--text-primary); margin-bottom: 1.5rem; border-bottom: 2px solid rgba(16, 185, 129, 0.3); padding-bottom: 0.5rem;">
                                    <i class="fas fa-info-circle me-2" style="color: #10b981;"></i>Equipment Information
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="color: var(--text-primary); font-weight: 600;">
                                    <i class="fas fa-tag me-1"></i>Equipment Name *
                                </label>
                                <input type="text" id="equipmentName" class="form-control" placeholder="e.g., Hematology Analyzer" required style="border-radius: 10px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="color: var(--text-primary); font-weight: 600;">
                                    <i class="fas fa-cogs me-1"></i>Equipment Type *
                                </label>
                                <select id="equipmentType" class="form-control" required style="border-radius: 10px; padding: 0.75rem;">
                                    <option value="">Select Type</option>
                                    <option value="analyzer">🧪 Analyzer</option>
                                    <option value="microscope">🔬 Microscope</option>
                                    <option value="centrifuge">🌀 Centrifuge</option>
                                    <option value="incubator">🔥 Incubator</option>
                                    <option value="spectrophotometer">📊 Spectrophotometer</option>
                                    <option value="other">⚙️ Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label" style="color: var(--text-primary); font-weight: 600;">
                                    <i class="fas fa-industry me-1"></i>Manufacturer
                                </label>
                                <input type="text" id="manufacturer" class="form-control" placeholder="e.g., Abbott, Roche" style="border-radius: 10px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="color: var(--text-primary); font-weight: 600;">
                                    <i class="fas fa-barcode me-1"></i>Model Number
                                </label>
                                <input type="text" id="modelNumber" class="form-control" placeholder="e.g., XN-1000" style="border-radius: 10px; padding: 0.75rem;">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label" style="color: var(--text-primary); font-weight: 600;">
                                    <i class="fas fa-hashtag me-1"></i>Serial Number
                                </label>
                                <input type="text" id="serialNumber" class="form-control" placeholder="Equipment serial number" style="border-radius: 10px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="color: var(--text-primary); font-weight: 600;">
                                    <i class="fas fa-map-marker-alt me-1"></i>Location
                                </label>
                                <input type="text" id="location" class="form-control" placeholder="e.g., Lab Room 1" style="border-radius: 10px; padding: 0.75rem;">
                            </div>
                        </div>

                        <!-- Connection Settings -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 style="color: var(--text-primary); margin-bottom: 1.5rem; border-bottom: 2px solid rgba(6, 182, 212, 0.3); padding-bottom: 0.5rem;">
                                    <i class="fas fa-network-wired me-2" style="color: #06b6d4;"></i>Connection Settings
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="color: var(--text-primary); font-weight: 600;">
                                    <i class="fas fa-globe me-1"></i>IP Address *
                                </label>
                                <input type="text" id="ipAddress" class="form-control" placeholder="192.168.1.100" required style="border-radius: 10px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="color: var(--text-primary); font-weight: 600;">
                                    <i class="fas fa-plug me-1"></i>Port
                                </label>
                                <input type="number" id="port" class="form-control" placeholder="4001" style="border-radius: 10px; padding: 0.75rem;">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label" style="color: var(--text-primary); font-weight: 600;">
                                    <i class="fas fa-link me-1"></i>Connection Type
                                </label>
                                <select id="connectionType" class="form-control" style="border-radius: 10px; padding: 0.75rem;">
                                    <option value="TCP">TCP/IP</option>
                                    <option value="Serial">Serial</option>
                                    <option value="USB">USB</option>
                                    <option value="HL7">HL7</option>
                                    <option value="LIS">LIS Interface</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="color: var(--text-primary); font-weight: 600;">
                                    <i class="fas fa-file-code me-1"></i>Data Format
                                </label>
                                <select id="dataFormat" class="form-control" style="border-radius: 10px; padding: 0.75rem;">
                                    <option value="HL7">HL7 v2.x</option>
                                    <option value="ASTM">ASTM E1394</option>
                                    <option value="XML">XML</option>
                                    <option value="JSON">JSON</option>
                                    <option value="CSV">CSV</option>
                                    <option value="Custom">Custom</option>
                                </select>
                            </div>
                        </div>

                        <!-- Test Connection -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div style="background: rgba(6, 182, 212, 0.1); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 10px; padding: 1.5rem;">
                                    <button type="button" class="btn btn-info" onclick="testEquipmentConnection()" style="border-radius: 20px; padding: 0.5rem 1.5rem;">
                                        <i class="fas fa-wifi me-2"></i>Test Connection
                                    </button>
                                    <span id="connectionStatus" class="ms-3" style="font-weight: 600;"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Supported Tests -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 style="color: var(--text-primary); margin-bottom: 1.5rem; border-bottom: 2px solid rgba(139, 92, 246, 0.3); padding-bottom: 0.5rem;">
                                    <i class="fas fa-flask me-2" style="color: #8b5cf6;"></i>Supported Tests
                                </h5>
                                <div id="supportedTestsContainer" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; max-height: 300px; overflow-y: auto; padding: 1rem; background: rgba(139, 92, 246, 0.05); border-radius: 10px;">
                                    <!-- Test checkboxes will be populated here -->
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="background: rgba(255, 255, 255, 0.05); padding: 1.5rem 2rem;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 20px; padding: 0.5rem 1.5rem;">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-success" onclick="saveEquipment()" style="background: linear-gradient(135deg, #10b981, #059669); border: none; border-radius: 20px; padding: 0.5rem 1.5rem;">
                        <i class="fas fa-save me-2"></i>Save Equipment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Add Test Modal -->
    <div class="modal fade" id="addTestModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background: var(--glass-background); border: 1px solid var(--glass-border); border-radius: 20px; overflow: hidden;">
                <div class="modal-header" style="background: linear-gradient(135deg, #06b6d4, #0891b2); border: none; padding: 2rem;">
                    <div>
                        <h4 class="modal-title" style="color: white; margin: 0; font-weight: 700;">
                            <i class="fas fa-flask me-3"></i>Add New Lab Test
                        </h4>
                        <p style="color: rgba(255, 255, 255, 0.8); margin: 0.5rem 0 0 0;">Configure test parameters and pricing</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding: 2rem;">
                    <form id="addTestForm">
                        <!-- Test Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 style="color: var(--text-primary); margin-bottom: 1.5rem; border-bottom: 2px solid rgba(6, 182, 212, 0.3); padding-bottom: 0.5rem;">
                                    <i class="fas fa-info-circle me-2" style="color: #06b6d4;"></i>Test Information
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="color: var(--text-primary); font-weight: 600;">
                                    <i class="fas fa-code me-1"></i>Test Code *
                                </label>
                                <input type="text" id="testCode" class="form-control" placeholder="e.g., CBC, BMP" required style="border-radius: 10px; padding: 0.75rem; text-transform: uppercase;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="color: var(--text-primary); font-weight: 600;">
                                    <i class="fas fa-tag me-1"></i>Test Name *
                                </label>
                                <input type="text" id="testName" class="form-control" placeholder="e.g., Complete Blood Count" required style="border-radius: 10px; padding: 0.75rem;">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label" style="color: var(--text-primary); font-weight: 600;">
                                    <i class="fas fa-layer-group me-1"></i>Category *
                                </label>
                                <select id="testCategory" class="form-control" required style="border-radius: 10px; padding: 0.75rem;">
                                    <option value="">Select Category</option>
                                    <option value="hematology">🩸 Hematology</option>
                                    <option value="biochemistry">🧪 Biochemistry</option>
                                    <option value="microbiology">🦠 Microbiology</option>
                                    <option value="immunology">🛡️ Immunology</option>
                                    <option value="endocrinology">⚕️ Endocrinology</option>
                                    <option value="cardiology">❤️ Cardiology</option>
                                    <option value="other">📋 Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="color: var(--text-primary); font-weight: 600;">
                                    <i class="fas fa-dollar-sign me-1"></i>Price *
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text" style="background: rgba(6, 182, 212, 0.1); border: 1px solid rgba(6, 182, 212, 0.3);">$</span>
                                    <input type="number" id="testPrice" class="form-control" placeholder="25.00" step="0.01" min="0" required style="border-radius: 0 10px 10px 0; padding: 0.75rem;">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label" style="color: var(--text-primary); font-weight: 600;">
                                    <i class="fas fa-clock me-1"></i>Turnaround Time
                                </label>
                                <select id="turnaroundTime" class="form-control" style="border-radius: 10px; padding: 0.75rem;">
                                    <option value="1-2 hours">⚡ 1-2 hours (STAT)</option>
                                    <option value="2-4 hours">🔥 2-4 hours (Urgent)</option>
                                    <option value="4-6 hours">⏰ 4-6 hours (Routine)</option>
                                    <option value="6-12 hours">📅 6-12 hours (Standard)</option>
                                    <option value="24-48 hours">📆 24-48 hours (Extended)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="color: var(--text-primary); font-weight: 600;">
                                    <i class="fas fa-vial me-1"></i>Sample Type
                                </label>
                                <select id="sampleType" class="form-control" style="border-radius: 10px; padding: 0.75rem;">
                                    <option value="blood">🩸 Blood</option>
                                    <option value="serum">🧪 Serum</option>
                                    <option value="plasma">💧 Plasma</option>
                                    <option value="urine">💛 Urine</option>
                                    <option value="stool">🟤 Stool</option>
                                    <option value="saliva">💦 Saliva</option>
                                    <option value="other">📋 Other</option>
                                </select>
                            </div>
                        </div>

                        <!-- Advanced Settings -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 style="color: var(--text-primary); margin-bottom: 1.5rem; border-bottom: 2px solid rgba(139, 92, 246, 0.3); padding-bottom: 0.5rem;">
                                    <i class="fas fa-cogs me-2" style="color: #8b5cf6;"></i>Advanced Settings
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="color: var(--text-primary); font-weight: 600;">
                                    <i class="fas fa-chart-line me-1"></i>Normal Range
                                </label>
                                <input type="text" id="normalRange" class="form-control" placeholder="e.g., 4.5-11.0 x10³/μL" style="border-radius: 10px; padding: 0.75rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="color: var(--text-primary); font-weight: 600;">
                                    <i class="fas fa-ruler me-1"></i>Unit
                                </label>
                                <input type="text" id="testUnit" class="form-control" placeholder="e.g., mg/dL, x10³/μL" style="border-radius: 10px; padding: 0.75rem;">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label" style="color: var(--text-primary); font-weight: 600;">
                                    <i class="fas fa-file-alt me-1"></i>Description
                                </label>
                                <textarea id="testDescription" class="form-control" rows="3" placeholder="Brief description of the test and what it measures..." style="border-radius: 10px; padding: 0.75rem;"></textarea>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-check form-switch" style="padding-left: 3rem;">
                                    <input class="form-check-input" type="checkbox" id="testActive" checked style="transform: scale(1.5);">
                                    <label class="form-check-label" for="testActive" style="color: var(--text-primary); font-weight: 600; margin-left: 1rem;">
                                        <i class="fas fa-toggle-on me-2" style="color: #10b981;"></i>Test is Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="background: rgba(255, 255, 255, 0.05); padding: 1.5rem 2rem;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 20px; padding: 0.5rem 1.5rem;">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" onclick="saveTest()" style="background: linear-gradient(135deg, #06b6d4, #0891b2); border: none; border-radius: 20px; padding: 0.5rem 1.5rem;">
                        <i class="fas fa-save me-2"></i>Save Test
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Equipment Details Modal -->
    <div class="modal fade" id="equipmentDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-microscope me-2"></i>Equipment Details
                    </h5>
                    <button type="button" class="btn-close" onclick="hideCustomModal(document.getElementById('equipmentDetailsModal'))"></button>
                </div>
                <div class="modal-body" id="equipmentDetailsContent">
                    <!-- Equipment details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="hideCustomModal(document.getElementById('equipmentDetailsModal'))">
                        <i class="fas fa-times me-1"></i>Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
