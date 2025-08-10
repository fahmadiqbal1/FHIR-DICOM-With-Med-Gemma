@extends('layouts.app')
@section('title', 'Help & Onboarding')
@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2>Help & Onboarding</h2>
        <p class="lead">Welcome to MedGemma Healthcare! Here’s how to get started and make the most of the platform.</p>
        <ul class="list-group mb-4">
            <li class="list-group-item"><strong>Dashboard:</strong> View key stats and quick links to all features.</li>
            <li class="list-group-item"><strong>Patients:</strong> Add, edit, and manage patient records securely.</li>
            <li class="list-group-item"><strong>MedGemma AI:</strong> Run AI analysis on imaging and labs, and get second opinions.</li>
            <li class="list-group-item"><strong>Reports:</strong> Review all AI, lab, and imaging results in one place.</li>
        </ul>
        <h5>Tips for Best Experience</h5>
        <ul>
            <li>Use the navigation bar to access all features.</li>
            <li>Click on any patient or report for more details.</li>
            <li>Use the help button or this page for guidance at any time.</li>
            <li>All actions are logged for security and compliance.</li>
            <li><span class="badge bg-info">Info</span> notifications will appear for helpful tips.</li>
            <li><span class="badge bg-success">Success</span> and <span class="badge bg-danger">Error</span> notifications will confirm your actions or alert you to issues.</li>
        </ul>
        <div class="alert alert-info mt-4" role="alert">
            <strong>Tip:</strong> You’ll see notifications at the top of each page for important actions and feedback.
        </div>
        <h5 class="mt-4">Need More Help?</h5>
        <p>Email <a href="mailto:support@medgemma.com">support@medgemma.com</a> or contact your administrator.</p>
        <h5>Accessibility & Best Practices</h5>
        <ul>
            <li>All forms and modals are keyboard accessible.</li>
            <li>ARIA labels and roles are used where appropriate for screen readers.</li>
            <li>All interactive elements (buttons, links) are accessible by keyboard.</li>
            <li>Responsive design for all devices.</li>
            <li>Consistent branding and color palette for a professional look.</li>
        </ul>
        <div class="alert alert-success mt-4" role="alert">
            <strong>Success:</strong> Your actions (like saving a patient) will show a green notification at the top.
        </div>
        <div class="alert alert-danger mt-2" role="alert">
            <strong>Error:</strong> If something goes wrong, you'll see a red notification with details.
        </div>
        <div class="alert alert-info mt-2" role="alert">
            <strong>Info:</strong> Helpful tips and information will appear in blue.
        </div>
    </div>
</div>
@endsection
