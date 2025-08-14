@extends('layouts.main')

@section('title', 'Doctor Financial Dashboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h1 class="gradient-text mb-1">Financial Dashboard</h1>
                            <p class="text-light-gray mb-0">Track your earnings and performance</p>
                        </div>
                        <div class="d-flex gap-3 align-items-center">
                            <input type="date" id="dateFilter" class="form-control bg-dark text-white border-purple" 
                                   value="{{ $date }}" style="max-width: 200px;">
                            <div class="badge bg-gradient-purple px-3 py-2">{{ $revenuePercentage }}% Revenue Share</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="glass-card h-100">
                <div class="card-body text-center">
                    <div class="icon-circle bg-gradient-purple mb-3 mx-auto">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <h3 class="gradient-text mb-2">${{ number_format($todayEarnings->doctor_share ?? 0, 2) }}</h3>
                    <p class="text-light-gray mb-0">Today's Earnings</p>
                    @if($todayEarnings)
                        <small class="text-muted">From {{ $todayEarnings->total_consultations }} consultations</small>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="glass-card h-100">
                <div class="card-body text-center">
                    <div class="icon-circle bg-gradient-success mb-3 mx-auto">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="text-success mb-2">{{ $todayPatients }}</h3>
                    <p class="text-light-gray mb-0">Patients Today</p>
                    <small class="text-muted">{{ $todayAppointments }} appointments</small>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="glass-card h-100">
                <div class="card-body text-center">
                    <div class="icon-circle bg-gradient-info mb-3 mx-auto">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                    <h3 class="text-info mb-2">${{ number_format($weeklyEarnings, 2) }}</h3>
                    <p class="text-light-gray mb-0">This Week</p>
                    <small class="text-muted">Weekly earnings</small>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="glass-card h-100">
                <div class="card-body text-center">
                    <div class="icon-circle bg-gradient-warning mb-3 mx-auto">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3 class="text-warning mb-2">${{ number_format($monthlyEarnings, 2) }}</h3>
                    <p class="text-light-gray mb-0">This Month</p>
                    <small class="text-muted">Monthly earnings</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="glass-card h-100">
                <div class="card-header border-0 bg-transparent">
                    <h4 class="gradient-text mb-0">Earnings Trend (Last 7 Days)</h4>
                </div>
                <div class="card-body">
                    <canvas id="earningsChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
            <div class="glass-card h-100">
                <div class="card-header border-0 bg-transparent">
                    <h4 class="gradient-text mb-0">Performance Summary</h4>
                </div>
                <div class="card-body">
                    <div class="performance-metric mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-light-gray">Average per Patient</span>
                            <span class="fw-bold text-white">
                                ${{ $todayPatients > 0 ? number_format(($todayEarnings->doctor_share ?? 0) / $todayPatients, 2) : '0.00' }}
                            </span>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar bg-gradient-purple" style="width: 75%"></div>
                        </div>
                    </div>
                    
                    <div class="performance-metric mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-light-gray">Daily Target</span>
                            <span class="fw-bold text-white">
                                {{ $todayEarnings && $todayEarnings->doctor_share >= 500 ? '✅' : '⏳' }}
                                ${{ number_format($todayEarnings->doctor_share ?? 0, 0) }}/500
                            </span>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            @php
                                $progress = min(100, (($todayEarnings->doctor_share ?? 0) / 500) * 100);
                            @endphp
                            <div class="progress-bar bg-gradient-success" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                    
                    <div class="performance-metric">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-light-gray">Patient Satisfaction</span>
                            <span class="fw-bold text-white">95%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-gradient-warning" style="width: 95%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    @if($todayEarnings && $todayEarnings->total_appointments > 0)
    <div class="row">
        <div class="col-12">
            <div class="glass-card">
                <div class="card-header border-0 bg-transparent">
                    <h4 class="gradient-text mb-0">Today's Activity Summary</h4>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="stat-item">
                                <h5 class="text-purple mb-1">{{ $todayEarnings->total_consultations ?? 0 }}</h5>
                                <p class="text-light-gray mb-0">Total Appointments</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <h5 class="text-success mb-1">{{ $todayEarnings->patients_attended ?? 0 }}</h5>
                                <p class="text-light-gray mb-0">Unique Patients</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <h5 class="text-info mb-1">${{ number_format(($todayEarnings->doctor_share ?? 0) + ($todayEarnings->admin_share ?? 0), 2) }}</h5>
                                <p class="text-light-gray mb-0">Total Revenue Generated</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <h5 class="text-warning mb-1">{{ $revenuePercentage }}%</h5>
                                <p class="text-light-gray mb-0">Your Share</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="glass-card text-center py-5">
                <div class="icon-circle bg-gradient-purple mb-3 mx-auto" style="width: 80px; height: 80px;">
                    <i class="fas fa-calendar-plus" style="font-size: 2rem;"></i>
                </div>
                <h4 class="gradient-text mb-3">No Activity Today</h4>
                <p class="text-light-gray mb-0">You haven't seen any patients today. Your earnings will appear here once you start consultations.</p>
            </div>
        </div>
    </div>
    @endif
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
    font-size: 1.5rem;
}

.performance-metric {
    border-left: 3px solid var(--purple-gradient);
    padding-left: 1rem;
}

.stat-item {
    padding: 1rem;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.05);
    margin-bottom: 1rem;
}

.progress {
    background-color: rgba(255, 255, 255, 0.1);
}

@media (max-width: 768px) {
    .d-flex.gap-3 {
        flex-direction: column;
        gap: 1rem !important;
    }
    
    .d-flex.gap-3 input {
        max-width: 100% !important;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Date filter functionality
    document.getElementById('dateFilter').addEventListener('change', function() {
        window.location.href = `?date=${this.value}`;
    });

    // Earnings Chart
    const ctx = document.getElementById('earningsChart').getContext('2d');
    const earningsData = {!! json_encode($recentEarnings) !!};
    
    const chartData = {
        labels: earningsData.map(item => new Date(item.earning_date).toLocaleDateString()),
        datasets: [{
            label: 'Daily Earnings',
            data: earningsData.map(item => item.doctor_share),
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    };

    new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#ffffff'
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
                        color: '#ffffff',
                        callback: function(value) {
                            return '$' + value.toFixed(2);
                        }
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#ffffff'
                    }
                }
            }
        }
    });
});
</script>
@endsection
