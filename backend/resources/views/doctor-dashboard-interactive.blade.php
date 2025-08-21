@extends('layouts.app')

@section('title', 'Doctor Dashboard - MedGemma Healthcare')

@section('content')
<div class="container-fluid px-4">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-card p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="mb-2 text-white">
                            <i class="fas fa-user-md me-3"></i>Welcome, Dr. {{ Auth::user()->name ?? 'Doctor' }}
                        </h1>
                        <p class="text-light mb-0">
                            <i class="fas fa-calendar me-2"></i>{{ date('l, F j, Y') }}
                            <span class="ms-3"><i class="fas fa-clock me-2"></i><span id="current-time">{{ date('g:i A') }}</span></span>
                        </p>
                    </div>
                    <div class="text-end">
                        <div class="badge bg-success fs-6 px-3 py-2">
                            <i class="fas fa-circle me-2"></i>Online
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="glass-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-light-gray mb-2">Today's Patients</h6>
                        <h2 class="text-white mb-0 counter" data-target="12">0</h2>
                        <small class="text-success">
                            <i class="fas fa-arrow-up me-1"></i>+2 from yesterday
                        </small>
                    </div>
                    <div class="text-info">
                        <i class="fas fa-user-injured fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="glass-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-light-gray mb-2">Pending Reviews</h6>
                        <h2 class="text-white mb-0 counter" data-target="8">0</h2>
                        <small class="text-warning">
                            <i class="fas fa-clock me-1"></i>3 urgent
                        </small>
                    </div>
                    <div class="text-warning">
                        <i class="fas fa-file-medical fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="glass-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-light-gray mb-2">Lab Results</h6>
                        <h2 class="text-white mb-0 counter" data-target="15">0</h2>
                        <small class="text-info">
                            <i class="fas fa-flask me-1"></i>5 new today
                        </small>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-vial fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="glass-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-light-gray mb-2">AI Analyses</h6>
                        <h2 class="text-white mb-0 counter" data-target="24">0</h2>
                        <small class="text-primary">
                            <i class="fas fa-brain me-1"></i>6 completed today
                        </small>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-robot fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Patient Queue -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="glass-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="text-white mb-0">
                        <i class="fas fa-users me-2"></i>Today's Patient Queue
                    </h5>
                    <button class="btn btn-outline-primary btn-sm" onclick="refreshPatientQueue()">
                        <i class="fas fa-sync-alt me-1"></i>Refresh
                    </button>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-dark table-hover">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Patient</th>
                                <th>Condition</th>
                                <th>Priority</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="patient-queue">
                            <tr>
                                <td>09:00 AM</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-primary me-2">JD</div>
                                        <div>
                                            <div class="fw-bold">John Doe</div>
                                            <small class="text-muted">MRN001</small>
                                        </div>
                                    </div>
                                </td>
                                <td>Annual Checkup</td>
                                <td><span class="badge bg-success">Normal</span></td>
                                <td>
                                    <button class="btn btn-sm btn-primary me-1" onclick="viewPatient('MRN001')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" onclick="startConsultation('MRN001')">
                                        <i class="fas fa-play"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>10:30 AM</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-info me-2">JS</div>
                                        <div>
                                            <div class="fw-bold">Jane Smith</div>
                                            <small class="text-muted">MRN002</small>
                                        </div>
                                    </div>
                                </td>
                                <td>Hypertension Follow-up</td>
                                <td><span class="badge bg-warning">Medium</span></td>
                                <td>
                                    <button class="btn btn-sm btn-primary me-1" onclick="viewPatient('MRN002')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" onclick="startConsultation('MRN002')">
                                        <i class="fas fa-play"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>11:15 AM</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-danger me-2">MJ</div>
                                        <div>
                                            <div class="fw-bold">Michael Johnson</div>
                                            <small class="text-muted">MRN003</small>
                                        </div>
                                    </div>
                                </td>
                                <td>Chest Pain Evaluation</td>
                                <td><span class="badge bg-danger">High</span></td>
                                <td>
                                    <button class="btn btn-sm btn-primary me-1" onclick="viewPatient('MRN003')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" onclick="startConsultation('MRN003')">
                                        <i class="fas fa-play"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Alerts -->
        <div class="col-xl-4 col-lg-5">
            <!-- Quick Actions -->
            <div class="glass-card p-4 mb-4">
                <h5 class="text-white mb-3">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
                <div class="row g-2">
                    <div class="col-6">
                        <button class="btn btn-outline-primary w-100" onclick="openNewPatient()">
                            <i class="fas fa-user-plus d-block mb-1"></i>
                            <small>New Patient</small>
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-outline-primary w-100" onclick="openPrescription()">
                            <i class="fas fa-prescription d-block mb-1"></i>
                            <small>Prescription</small>
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-outline-primary w-100" onclick="openLabOrder()">
                            <i class="fas fa-flask d-block mb-1"></i>
                            <small>Lab Order</small>
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-outline-primary w-100" onclick="openAIAnalysis()">
                            <i class="fas fa-brain d-block mb-1"></i>
                            <small>AI Analysis</small>
                        </button>
                    </div>
                </div>
            </div>

            <!-- System Alerts -->
            <div class="glass-card p-4 mb-4">
                <h5 class="text-white mb-3">
                    <i class="fas fa-bell me-2"></i>System Alerts
                </h5>
                <div class="alert-list" id="system-alerts">
                    <div class="alert alert-warning alert-sm mb-2">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Lab Results:</strong> 3 critical values require review
                    </div>
                    <div class="alert alert-info alert-sm mb-2">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>AI Analysis:</strong> 2 scans completed, ready for review
                    </div>
                    <div class="alert alert-success alert-sm mb-0">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>System:</strong> All services running normally
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="glass-card p-4">
                <h5 class="text-white mb-3">
                    <i class="fas fa-history me-2"></i>Recent Activity
                </h5>
                <div class="activity-timeline">
                    <div class="activity-item">
                        <div class="activity-dot bg-success"></div>
                        <div class="activity-content">
                            <small class="text-muted">5 minutes ago</small>
                            <p class="mb-1">Completed consultation for John Doe</p>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-dot bg-primary"></div>
                        <div class="activity-content">
                            <small class="text-muted">15 minutes ago</small>
                            <p class="mb-1">Reviewed AI analysis for chest X-ray</p>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-dot bg-warning"></div>
                        <div class="activity-content">
                            <small class="text-muted">30 minutes ago</small>
                            <p class="mb-1">Prescribed medication for Jane Smith</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Row -->
    <div class="row mt-4">
        <!-- Patient Statistics Chart -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="glass-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="text-white mb-0">
                        <i class="fas fa-chart-line me-2"></i>Patient Statistics
                    </h5>
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="chartPeriod" id="week" autocomplete="off" checked>
                        <label class="btn btn-outline-primary btn-sm" for="week">Week</label>
                        <input type="radio" class="btn-check" name="chartPeriod" id="month" autocomplete="off">
                        <label class="btn btn-outline-primary btn-sm" for="month">Month</label>
                        <input type="radio" class="btn-check" name="chartPeriod" id="year" autocomplete="off">
                        <label class="btn btn-outline-primary btn-sm" for="year">Year</label>
                    </div>
                </div>
                <canvas id="patientChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Medical Specialty Breakdown -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="glass-card p-4">
                <h5 class="text-white mb-3">
                    <i class="fas fa-chart-pie me-2"></i>Specialty Breakdown
                </h5>
                <canvas id="specialtyChart" width="300" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Patient Detail Modal -->
<div class="modal fade" id="patientModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h5 class="modal-title text-white">Patient Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="patientModalBody">
                <!-- Patient details will be loaded here -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 14px;
    }

    .activity-timeline {
        position: relative;
    }

    .activity-item {
        display: flex;
        margin-bottom: 1rem;
        position: relative;
    }

    .activity-item:not(:last-child):before {
        content: '';
        position: absolute;
        left: 7px;
        top: 20px;
        width: 2px;
        height: calc(100% + 1rem);
        background: rgba(255, 255, 255, 0.2);
    }

    .activity-dot {
        width: 15px;
        height: 15px;
        border-radius: 50%;
        margin-right: 1rem;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .activity-content {
        flex: 1;
    }

    .alert-sm {
        padding: 0.5rem;
        font-size: 0.875rem;
    }

    .counter {
        transition: all 0.3s ease;
    }

    .glass-card:hover {
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
        transform: translateY(-1px);
    }

    .table-dark th {
        border-color: rgba(255, 255, 255, 0.2);
        color: rgba(255, 255, 255, 0.9);
    }

    .table-dark td {
        border-color: rgba(255, 255, 255, 0.1);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.05);
    }
</style>
@endpush

@push('scripts')
<script>
// Real-time clock update
function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', { 
        hour: 'numeric', 
        minute: '2-digit',
        hour12: true 
    });
    document.getElementById('current-time').textContent = timeString;
}

// Update time every minute
setInterval(updateTime, 60000);

// Counter animation
function animateCounters() {
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const increment = target / 50;
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                counter.textContent = target;
                clearInterval(timer);
            } else {
                counter.textContent = Math.floor(current);
            }
        }, 20);
    });
}

// Initialize charts
let patientChart, specialtyChart;

function initCharts() {
    // Patient Statistics Chart
    const ctx1 = document.getElementById('patientChart').getContext('2d');
    patientChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Patients Seen',
                data: [12, 15, 8, 18, 22, 6, 3],
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: 'rgba(255, 255, 255, 0.8)'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.8)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.8)'
                    }
                }
            }
        }
    });

    // Specialty Breakdown Chart
    const ctx2 = document.getElementById('specialtyChart').getContext('2d');
    specialtyChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['General Medicine', 'Cardiology', 'Orthopedics', 'Neurology', 'Other'],
            datasets: [{
                data: [35, 25, 20, 15, 5],
                backgroundColor: [
                    '#667eea',
                    '#764ba2',
                    '#f093fb',
                    '#f5576c',
                    '#4facfe'
                ],
                borderWidth: 0
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
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });
}

// Quick action functions
function openNewPatient() {
    window.location.href = '/patients?action=new';
}

function openPrescription() {
    window.location.href = '/prescriptions/new';
}

function openLabOrder() {
    window.location.href = '/lab-orders/new';
}

function openAIAnalysis() {
    window.location.href = '/medgemma';
}

function refreshPatientQueue() {
    // Add loading animation
    const button = event.target.closest('button');
    const icon = button.querySelector('i');
    icon.classList.add('fa-spin');
    
    // Simulate refresh
    setTimeout(() => {
        icon.classList.remove('fa-spin');
        // Here you would typically make an AJAX call to refresh the data
    }, 1000);
}

function viewPatient(mrn) {
    // Load patient details in modal
    fetch(`/api/patients/${mrn}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('patientModalBody').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Patient Information</h6>
                        <p><strong>Name:</strong> ${data.full_name}</p>
                        <p><strong>MRN:</strong> ${data.mrn}</p>
                        <p><strong>DOB:</strong> ${data.dob}</p>
                        <p><strong>Sex:</strong> ${data.sex}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Contact Information</h6>
                        <p><strong>Phone:</strong> ${data.phone}</p>
                        <p><strong>Email:</strong> ${data.email}</p>
                        <p><strong>Address:</strong> ${data.address}</p>
                    </div>
                </div>
            `;
            const modal = new bootstrap.Modal(document.getElementById('patientModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error loading patient data:', error);
        });
}

function startConsultation(mrn) {
    if (confirm(`Start consultation for patient ${mrn}?`)) {
        window.location.href = `/consultations/new?patient=${mrn}`;
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    updateTime();
    animateCounters();
    initCharts();
});
</script>
@endpush
