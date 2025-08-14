@extends('layouts.main')

@section('title', 'Admin Financial Dashboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h1 class="gradient-text mb-1">Admin Financial Dashboard</h1>
                            <p class="text-light-gray mb-0">Complete business overview and financial analytics</p>
                        </div>
                        <div class="d-flex gap-3 align-items-center">
                            <select id="periodFilter" class="form-select bg-dark text-white border-purple" style="max-width: 150px;">
                                <option value="day" {{ $period == 'day' ? 'selected' : '' }}>Daily</option>
                                <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Weekly</option>
                                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Monthly</option>
                                <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Yearly</option>
                            </select>
                            <input type="date" id="dateFilter" class="form-control bg-dark text-white border-purple" 
                                   value="{{ $date }}" style="max-width: 200px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Overview Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="glass-card h-100">
                <div class="card-body text-center">
                    <div class="icon-circle bg-gradient-success mb-3 mx-auto">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <h3 class="text-success mb-2">${{ number_format($totalRevenue, 2) }}</h3>
                    <p class="text-light-gray mb-0">Total Revenue</p>
                    <small class="text-muted">{{ ucfirst($period) }}ly income</small>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="glass-card h-100">
                <div class="card-body text-center">
                    <div class="icon-circle bg-gradient-purple mb-3 mx-auto">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h3 class="gradient-text mb-2">${{ number_format($totalDoctorEarnings, 2) }}</h3>
                    <p class="text-light-gray mb-0">Doctor Earnings</p>
                    <small class="text-muted">Total paid to doctors</small>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="glass-card h-100">
                <div class="card-body text-center">
                    <div class="icon-circle bg-gradient-info mb-3 mx-auto">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="text-info mb-2">${{ number_format($adminEarnings, 2) }}</h3>
                    <p class="text-light-gray mb-0">Admin Revenue</p>
                    <small class="text-muted">Your share (30-40%)</small>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="glass-card h-100">
                <div class="card-body text-center">
                    <div class="icon-circle {{ $netProfit >= 0 ? 'bg-gradient-success' : 'bg-gradient-danger' }} mb-3 mx-auto">
                        <i class="fas {{ $netProfit >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                    </div>
                    <h3 class="{{ $netProfit >= 0 ? 'text-success' : 'text-danger' }} mb-2">${{ number_format($netProfit, 2) }}</h3>
                    <p class="text-light-gray mb-0">Net Profit</p>
                    <small class="text-muted">After expenses: ${{ number_format($totalExpenses, 2) }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="glass-card h-100">
                <div class="card-header border-0 bg-transparent">
                    <h4 class="gradient-text mb-0">Revenue Trend</h4>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
            <div class="glass-card h-100">
                <div class="card-header border-0 bg-transparent">
                    <h4 class="gradient-text mb-0">Expense Breakdown</h4>
                </div>
                <div class="card-body">
                    <canvas id="expenseChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Doctors & Recent Activity -->
    <div class="row g-4">
        <div class="col-xl-6">
            <div class="glass-card h-100">
                <div class="card-header border-0 bg-transparent">
                    <h4 class="gradient-text mb-0">Top Performing Doctors</h4>
                </div>
                <div class="card-body">
                    @forelse($topDoctors as $doctor)
                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-secondary">
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle bg-gradient-purple me-3">
                                {{ substr($doctor->doctor->name ?? 'N/A', 0, 2) }}
                            </div>
                            <div>
                                <h6 class="text-white mb-1">{{ $doctor->doctor->name ?? 'Unknown Doctor' }}</h6>
                                <small class="text-light-gray">Doctor</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-success">${{ number_format($doctor->total, 2) }}</div>
                            <small class="text-muted">Earned</small>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fas fa-user-md text-muted fa-3x mb-3"></i>
                        <p class="text-light-gray">No doctor earnings data available</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <div class="col-xl-6">
            <div class="glass-card h-100">
                <div class="card-header border-0 bg-transparent">
                    <h4 class="gradient-text mb-0">Recent Invoices</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentInvoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->patient->first_name ?? 'N/A' }} {{ $invoice->patient->last_name ?? '' }}</td>
                                    <td>{{ $invoice->doctor->name ?? 'N/A' }}</td>
                                    <td class="text-success">${{ number_format($invoice->amount, 2) }}</td>
                                    <td>{{ $invoice->created_at->format('M j, Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-light-gray py-4">
                                        No recent invoices found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
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
    font-size: 1.5rem;
}

.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 0.9rem;
}

.table-dark {
    --bs-table-bg: transparent;
}

.table-dark th,
.table-dark td {
    border-color: rgba(255, 255, 255, 0.1);
    color: #ffffff;
}

.table-hover tbody tr:hover {
    background-color: rgba(255, 255, 255, 0.05);
}

@media (max-width: 768px) {
    .d-flex.gap-3 {
        flex-direction: column;
        gap: 1rem !important;
    }
    
    .d-flex.gap-3 select,
    .d-flex.gap-3 input {
        max-width: 100% !important;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const periodFilter = document.getElementById('periodFilter');
    const dateFilter = document.getElementById('dateFilter');
    
    function updateFilters() {
        const period = periodFilter.value;
        const date = dateFilter.value;
        window.location.href = `?period=${period}&date=${date}`;
    }
    
    periodFilter.addEventListener('change', updateFilters);
    dateFilter.addEventListener('change', updateFilters);

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueData = {!! json_encode($dailyRevenue) !!};
    
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueData.map(item => new Date(item.date).toLocaleDateString()),
            datasets: [{
                label: 'Revenue',
                data: revenueData.map(item => item.revenue),
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }, {
                label: 'Appointments',
                data: revenueData.map(item => item.appointments),
                borderColor: '#764ba2',
                backgroundColor: 'rgba(118, 75, 162, 0.1)',
                borderWidth: 3,
                fill: false,
                tension: 0.4,
                yAxisID: 'y1'
            }]
        },
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
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#ffffff',
                        callback: function(value) {
                            return '$' + value.toFixed(0);
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    grid: {
                        drawOnChartArea: false,
                    },
                    ticks: {
                        color: '#ffffff'
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

    // Expense Chart
    const expenseCtx = document.getElementById('expenseChart').getContext('2d');
    const expenseData = {!! json_encode($expenseBreakdown) !!};
    
    if (expenseData.length > 0) {
        new Chart(expenseCtx, {
            type: 'doughnut',
            data: {
                labels: expenseData.map(item => item.category ? item.category.name : 'Uncategorized'),
                datasets: [{
                    data: expenseData.map(item => item.total),
                    backgroundColor: [
                        '#667eea',
                        '#764ba2',
                        '#f093fb',
                        '#f5576c',
                        '#4facfe',
                        '#00f2fe'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#ffffff',
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    } else {
        // Show no data message
        expenseCtx.canvas.style.display = 'none';
        expenseCtx.canvas.insertAdjacentHTML('afterend', 
            '<div class="text-center py-4"><i class="fas fa-chart-pie text-muted fa-3x mb-3"></i><p class="text-light-gray">No expense data available</p></div>'
        );
    }
});
</script>
@endsection
