<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Equipment Management - Lab Technician</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%); 
            min-height: 100vh; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .glass-card { 
            background: rgba(255, 255, 255, 0.1); 
            backdrop-filter: blur(10px); 
            border: 1px solid rgba(255, 255, 255, 0.2); 
            border-radius: 15px; 
            color: white; 
        }
        .navbar { 
            background: rgba(255, 255, 255, 0.1); 
            backdrop-filter: blur(10px); 
        }
        .equipment-item { 
            background: rgba(255, 255, 255, 0.05); 
            border-radius: 8px; 
            padding: 15px; 
            margin-bottom: 15px; 
            border-left: 4px solid #28a745; 
        }
        .status-online { border-left-color: #28a745; }
        .status-maintenance { border-left-color: #ffc107; }
        .status-offline { border-left-color: #dc3545; }
        .btn-primary {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
        }
        .equipment-stats {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
        }
        .modal-content {
            background: rgba(25, 25, 25, 0.95);
            backdrop-filter: blur(10px);
        }
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
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
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="/lab-tech-dashboard">
                <i class="fas fa-cogs me-2"></i>Equipment Management
            </a>
            <div class="ms-auto">
                <button class="btn btn-outline-light btn-sm me-2" onclick="refreshEquipment()">
                    <i class="fas fa-sync-alt me-1"></i>Refresh
                </button>
                <button class="btn btn-primary btn-sm me-2" onclick="showMaintenanceModal()">
                    <i class="fas fa-wrench me-1"></i>Schedule Maintenance
                </button>
                <a href="/lab-tech-dashboard" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <!-- Equipment Overview Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="equipment-stats">
                        <i class="fas fa-circle fa-2x mb-2 text-success"></i>
                        <h4 id="onlineCount">0</h4>
                        <small>Online</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="equipment-stats">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2 text-warning"></i>
                        <h4 id="maintenanceCount">0</h4>
                        <small>Maintenance</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="equipment-stats">
                        <i class="fas fa-times-circle fa-2x mb-2 text-danger"></i>
                        <h4 id="offlineCount">0</h4>
                        <small>Offline</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3">
                    <div class="equipment-stats">
                        <i class="fas fa-percentage fa-2x mb-2 text-info"></i>
                        <h4 id="uptime">0%</h4>
                        <small>Uptime</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Equipment List -->
        <div class="row">
            <div class="col-lg-8">
                <div class="glass-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5><i class="fas fa-list me-2"></i>Laboratory Equipment Status</h5>
                        <div>
                            <select class="form-select form-select-sm" id="statusFilter" onchange="filterEquipment()">
                                <option value="">All Equipment</option>
                                <option value="online">Online</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="offline">Offline</option>
                            </select>
                        </div>
                    </div>
                    <div id="equipmentList">
                        <div class="text-center p-4">
                            <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                            <p>Loading equipment...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Maintenance Schedule -->
            <div class="col-lg-4">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-calendar-alt me-2"></i>Maintenance Schedule</h5>
                    <div id="maintenanceSchedule">
                        <!-- Dynamic content -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Equipment Details -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="glass-card p-4">
                    <h5 class="mb-3"><i class="fas fa-chart-line me-2"></i>Equipment Usage Analytics</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <canvas id="usageChart" height="200"></canvas>
                        </div>
                        <div class="col-md-6">
                            <div id="equipmentDetails">
                                <p class="text-muted">Select equipment to view details</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Maintenance Modal -->
    <div class="modal fade" id="maintenanceModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-wrench me-2"></i>Schedule Maintenance
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="maintenanceForm">
                        <div class="mb-3">
                            <label class="form-label">Equipment *</label>
                            <select class="form-select" id="equipmentSelect" required>
                                <option value="">Select Equipment</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Maintenance Type *</label>
                            <select class="form-select" id="maintenanceType" required>
                                <option value="">Select Type</option>
                                <option value="preventive">Preventive Maintenance</option>
                                <option value="corrective">Corrective Maintenance</option>
                                <option value="calibration">Calibration</option>
                                <option value="cleaning">Cleaning</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Scheduled Date *</label>
                            <input type="datetime-local" class="form-control" id="scheduledDate" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Priority *</label>
                            <select class="form-select" id="priority" required>
                                <option value="">Select Priority</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="description" rows="3" placeholder="Describe the maintenance work needed..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="scheduleMaintenance()">
                        <i class="fas fa-save me-2"></i>Schedule
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let equipmentData = [];
        let maintenanceScheduleData = [];

        document.addEventListener('DOMContentLoaded', function() {
            loadEquipment();
            loadMaintenanceSchedule();
            initializeChart();
        });

        function loadEquipment() {
            // Mock equipment data - in production this would come from API
            equipmentData = [
                {
                    id: 1,
                    name: 'Hematology Analyzer A1',
                    model: 'HA-2000',
                    serial: 'HA001',
                    status: 'online',
                    location: 'Lab Room 1',
                    last_maintenance: '2025-08-01',
                    next_maintenance: '2025-09-01',
                    usage_hours: 1250,
                    tests_completed: 4520
                },
                {
                    id: 2,
                    name: 'Chemistry Analyzer C1',
                    model: 'CA-3000',
                    serial: 'CA001',
                    status: 'online',
                    location: 'Lab Room 2',
                    last_maintenance: '2025-07-15',
                    next_maintenance: '2025-08-30',
                    usage_hours: 1580,
                    tests_completed: 6240
                },
                {
                    id: 3,
                    name: 'Centrifuge CF1',
                    model: 'CF-500',
                    serial: 'CF001',
                    status: 'maintenance',
                    location: 'Lab Room 1',
                    last_maintenance: '2025-08-20',
                    next_maintenance: '2025-09-20',
                    usage_hours: 890,
                    tests_completed: 2100
                },
                {
                    id: 4,
                    name: 'Microscope M1',
                    model: 'MS-Pro',
                    serial: 'MS001',
                    status: 'online',
                    location: 'Lab Room 3',
                    last_maintenance: '2025-08-10',
                    next_maintenance: '2025-09-10',
                    usage_hours: 720,
                    tests_completed: 850
                },
                {
                    id: 5,
                    name: 'PCR Machine PCR1',
                    model: 'PCR-200',
                    serial: 'PCR001',
                    status: 'offline',
                    location: 'Lab Room 4',
                    last_maintenance: '2025-08-05',
                    next_maintenance: '2025-08-25',
                    usage_hours: 320,
                    tests_completed: 145
                }
            ];

            displayEquipment(equipmentData);
            updateStats();
            populateEquipmentSelect();
        }

        function displayEquipment(equipment) {
            const container = document.getElementById('equipmentList');
            
            container.innerHTML = equipment.map(eq => `
                <div class="equipment-item status-${eq.status}" onclick="selectEquipment(${eq.id})">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <h6 class="mb-1">${eq.name}</h6>
                            <small class="text-info">Model: ${eq.model} | Serial: ${eq.serial}</small>
                        </div>
                        <div class="col-md-2">
                            <span class="badge bg-${getStatusColor(eq.status)}">${eq.status.toUpperCase()}</span>
                        </div>
                        <div class="col-md-2">
                            <small class="text-muted">Location: ${eq.location}</small>
                        </div>
                        <div class="col-md-2">
                            <small class="text-muted">Last: ${eq.last_maintenance}</small>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-light btn-sm" onclick="event.stopPropagation(); scheduleMaintenance(${eq.id})">
                                <i class="fas fa-wrench"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function getStatusColor(status) {
            const colors = {
                'online': 'success',
                'maintenance': 'warning',
                'offline': 'danger'
            };
            return colors[status] || 'secondary';
        }

        function updateStats() {
            const onlineCount = equipmentData.filter(eq => eq.status === 'online').length;
            const maintenanceCount = equipmentData.filter(eq => eq.status === 'maintenance').length;
            const offlineCount = equipmentData.filter(eq => eq.status === 'offline').length;
            const uptime = Math.round((onlineCount / equipmentData.length) * 100);

            document.getElementById('onlineCount').textContent = onlineCount;
            document.getElementById('maintenanceCount').textContent = maintenanceCount;
            document.getElementById('offlineCount').textContent = offlineCount;
            document.getElementById('uptime').textContent = uptime + '%';
        }

        function filterEquipment() {
            const filter = document.getElementById('statusFilter').value;
            const filtered = filter ? equipmentData.filter(eq => eq.status === filter) : equipmentData;
            displayEquipment(filtered);
        }

        function refreshEquipment() {
            loadEquipment();
            loadMaintenanceSchedule();
            showAlert('Equipment status refreshed!', 'info');
        }

        function selectEquipment(equipmentId) {
            const equipment = equipmentData.find(eq => eq.id === equipmentId);
            if (!equipment) return;

            document.getElementById('equipmentDetails').innerHTML = `
                <h6>${equipment.name}</h6>
                <div class="row">
                    <div class="col-6">
                        <small class="text-muted">Model:</small><br>
                        <strong>${equipment.model}</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Serial:</small><br>
                        <strong>${equipment.serial}</strong>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-6">
                        <small class="text-muted">Usage Hours:</small><br>
                        <strong>${equipment.usage_hours}</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Tests Completed:</small><br>
                        <strong>${equipment.tests_completed}</strong>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <small class="text-muted">Next Maintenance:</small><br>
                        <strong>${equipment.next_maintenance}</strong>
                    </div>
                </div>
            `;
        }

        function populateEquipmentSelect() {
            const select = document.getElementById('equipmentSelect');
            select.innerHTML = '<option value="">Select Equipment</option>' +
                equipmentData.map(eq => `<option value="${eq.id}">${eq.name}</option>`).join('');
        }

        function loadMaintenanceSchedule() {
            maintenanceScheduleData = [
                { equipment: 'Chemistry Analyzer C1', date: '2025-08-30', type: 'Preventive' },
                { equipment: 'Microscope M1', date: '2025-09-10', type: 'Calibration' },
                { equipment: 'Centrifuge CF1', date: '2025-09-20', type: 'Repair' }
            ];

            const container = document.getElementById('maintenanceSchedule');
            container.innerHTML = maintenanceScheduleData.map(item => `
                <div class="mb-3 p-2" style="background: rgba(255,255,255,0.05); border-radius: 8px;">
                    <strong>${item.equipment}</strong>
                    <small class="d-block text-info">${item.type} - ${item.date}</small>
                </div>
            `).join('');
        }

        function showMaintenanceModal() {
            new bootstrap.Modal(document.getElementById('maintenanceModal')).show();
        }

        function scheduleMaintenance(equipmentId = null) {
            if (equipmentId) {
                document.getElementById('equipmentSelect').value = equipmentId;
                showMaintenanceModal();
                return;
            }

            const form = document.getElementById('maintenanceForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // In production, this would submit to the API
            showAlert('Maintenance scheduled successfully!', 'success');
            bootstrap.Modal.getInstance(document.getElementById('maintenanceModal')).hide();
            form.reset();
            loadMaintenanceSchedule();
        }

        function initializeChart() {
            const ctx = document.getElementById('usageChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: equipmentData.map(eq => eq.name.split(' ')[0]),
                    datasets: [{
                        label: 'Usage Hours',
                        data: equipmentData.map(eq => eq.usage_hours),
                        backgroundColor: 'rgba(40, 167, 69, 0.7)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: { color: 'white' }
                        }
                    },
                    scales: {
                        y: {
                            ticks: { color: 'white' },
                            grid: { color: 'rgba(255, 255, 255, 0.1)' }
                        },
                        x: {
                            ticks: { color: 'white' },
                            grid: { color: 'rgba(255, 255, 255, 0.1)' }
                        }
                    }
                }
            });
        }

        function showAlert(message, type = 'info') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
            alertDiv.style.zIndex = '9999';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 5000);
        }
    </script>
</body>
</html>
