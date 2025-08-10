@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h1 class="display-5">Welcome to MedGemma Healthcare</h1>
        <p class="lead">Your secure, AI-powered healthcare management platform.</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="/patients" class="btn btn-outline-primary btn-lg">Manage Patients</a>
        <a href="/medgemma" class="btn btn-primary btn-lg ms-2">Run MedGemma AI</a>
    </div>
</div>
<div class="row g-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h5 class="card-title">Patients</h5>
                <p class="display-6 fw-bold" id="patients-count">--</p>
                <a href="/patients" class="btn btn-link">View All</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h5 class="card-title">Imaging Studies</h5>
                <p class="display-6 fw-bold" id="studies-count">--</p>
                <a href="/medgemma" class="btn btn-link">Analyze</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h5 class="card-title">Lab Orders</h5>
                <p class="display-6 fw-bold" id="labs-count">--</p>
                <a href="/reports" class="btn btn-link">View Reports</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <h5 class="card-title">AI Results</h5>
                <p class="display-6 fw-bold" id="ai-count">--</p>
                <a href="/medgemma" class="btn btn-link">See Results</a>
            </div>
        </div>
    </div>
</div>
<div class="alert alert-info mt-5" role="alert">
    <strong>Tip:</strong> Use the navigation bar to access all features. For help, click the Help link above.
</div>
@push('scripts')
<script>
// Example: Fetch dashboard stats via AJAX (replace with real endpoints)
document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/dashboard-stats')
        .then(res => res.json())
        .then(data => {
            document.getElementById('patients-count').textContent = data.patients ?? '--';
            document.getElementById('studies-count').textContent = data.studies ?? '--';
            document.getElementById('labs-count').textContent = data.labs ?? '--';
            document.getElementById('ai-count').textContent = data.ai ?? '--';
        });
});
</script>
@endpush
@endsection

