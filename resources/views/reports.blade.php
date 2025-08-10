@extends('layouts.app')
@section('title', 'Reports')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Reports</h2>
    <button class="btn btn-outline-primary" id="refresh-btn">Refresh</button>
</div>
<div class="card mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="reports-table">
                <thead class="table-light">
                    <tr>
                        <th>Patient</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Summary</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal for detailed report -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">Report Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="report-details">
                <!-- Details will be loaded here -->
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
function fetchReports() {
    fetch('/api/reports')
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector('#reports-table tbody');
            tbody.innerHTML = '';
            data.forEach(r => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${r.patient_name}</td>
                    <td>${r.type}</td>
                    <td>${r.date}</td>
                    <td>${r.summary}</td>
                    <td><button class='btn btn-sm btn-outline-info' onclick='showReport(${JSON.stringify(r)})'>View</button></td>
                `;
                tbody.appendChild(tr);
            });
        });
}
fetchReports();
document.getElementById('refresh-btn').addEventListener('click', fetchReports);
window.showReport = function(r) {
    let html = `<div class='mb-2'><strong>Patient:</strong> ${r.patient_name}</div>`;
    html += `<div class='mb-2'><strong>Type:</strong> ${r.type}</div>`;
    html += `<div class='mb-2'><strong>Date:</strong> ${r.date}</div>`;
    html += `<div class='mb-2'><strong>Summary:</strong> ${r.summary}</div>`;
    if (r.details) {
        html += `<div class='mb-2'><strong>Details:</strong><pre>${r.details}</pre></div>`;
    }
    document.getElementById('report-details').innerHTML = html;
    var modal = new bootstrap.Modal(document.getElementById('reportModal'));
    modal.show();
}
</script>
@endpush
@endsection

