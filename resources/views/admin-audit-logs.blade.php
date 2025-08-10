@extends('layouts.app')
@section('title', 'Audit Log Viewer')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Audit Log Viewer</h2>
    <button class="btn btn-outline-primary" id="refresh-audit-btn">Refresh</button>
    <button class="btn btn-outline-secondary" onclick="window.print()">Print</button>
    <button class="btn btn-outline-success" id="export-csv-btn">Export CSV</button>
    <button class="btn btn-dark" id="toggle-darkmode-btn" aria-pressed="false" aria-label="Toggle dark mode">Dark Mode</button>
</div>
<div class="card mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="audit-table" aria-label="Audit log entries">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">User</th>
                        <th scope="col">Action</th>
                        <th scope="col">Subject</th>
                        <th scope="col">IP</th>
                        <th scope="col">Details</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@push('scripts')
<script>
function fetchAuditLogs() {
    fetch('/api/admin/audit-logs')
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector('#audit-table tbody');
            tbody.innerHTML = '';
            data.forEach(log => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${log.created_at}</td>
                    <td>${log.user_name || 'System'}</td>
                    <td>${log.action}</td>
                    <td>${log.subject_type} #${log.subject_id}</td>
                    <td>${log.ip}</td>
                    <td><button class='btn btn-sm btn-outline-info' onclick='showDetails(${JSON.stringify(log.details)})' aria-label='View details'>View</button></td>
                `;
                tbody.appendChild(tr);
            });
        });
}
fetchAuditLogs();
document.getElementById('refresh-audit-btn').addEventListener('click', fetchAuditLogs);
window.showDetails = function(details) {
    alert(JSON.stringify(details, null, 2));
};
document.getElementById('export-csv-btn').addEventListener('click', function() {
    fetch('/api/admin/audit-logs?export=csv')
        .then(res => res.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'audit-logs.csv';
            document.body.appendChild(a);
            a.click();
            a.remove();
        });
});
// Dark mode toggle
const darkBtn = document.getElementById('toggle-darkmode-btn');
darkBtn.addEventListener('click', function() {
    document.body.classList.toggle('bg-dark');
    document.body.classList.toggle('text-white');
    darkBtn.setAttribute('aria-pressed', document.body.classList.contains('bg-dark'));
});
</script>
@endpush
@endsection

