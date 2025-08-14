@extends('layouts.main')
@section('title', 'Invoice ' . $invoice->invoice_number)

@section('styles')
<style>
    .invoice-container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    .invoice-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        text-align: center;
    }
    .invoice-actions {
        background: #f8f9fa;
        padding: 1rem 2rem;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .invoice-content {
        padding: 2rem;
    }
    .invoice-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }
    .detail-section h3 {
        color: #667eea;
        margin-bottom: 1rem;
        font-size: 1.2rem;
    }
    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        margin: 2rem 0;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .invoice-table th {
        background: #667eea;
        color: white;
        padding: 1rem;
        text-align: left;
    }
    .invoice-table td {
        padding: 1rem;
        border-bottom: 1px solid #eee;
    }
    .total-section {
        text-align: right;
        margin-top: 2rem;
        padding-top: 1rem;
        border-top: 2px solid #667eea;
    }
    .total-amount {
        font-size: 1.5rem;
        font-weight: bold;
        color: #667eea;
    }
    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
        text-transform: uppercase;
    }
    .status-pending { background: #fff3cd; color: #856404; }
    .status-paid { background: #d4edda; color: #155724; }
    .status-cancelled { background: #f8d7da; color: #721c24; }
    
    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-primary {
        background: #667eea;
        color: white;
    }
    .btn-primary:hover {
        background: #5a6fd8;
        color: white;
    }
    .btn-secondary {
        background: #6c757d;
        color: white;
    }
    .btn-secondary:hover {
        background: #545b62;
        color: white;
    }
    .btn-success {
        background: #28a745;
        color: white;
    }
    .btn-success:hover {
        background: #218838;
        color: white;
    }
    
    .email-form {
        display: none;
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 5px;
        margin-top: 1rem;
    }
    .form-group {
        margin-bottom: 1rem;
    }
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: bold;
    }
    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
    }
    
    @media (max-width: 768px) {
        .invoice-details {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        .invoice-actions {
            flex-direction: column;
            align-items: stretch;
        }
        .invoice-content {
            padding: 1rem;
        }
    }
    
    @media print {
        .invoice-actions, .btn, .no-print {
            display: none !important;
        }
        .invoice-container {
            box-shadow: none;
        }
    }
</style>
@endsection

@section('content')
<div class="invoice-container">
    <div class="invoice-header">
        <h1>🏥 Aviva Healthcare</h1>
        <p>Professional Healthcare Services</p>
    </div>
    
    <div class="invoice-actions no-print">
        <div>
            <a href="/patients" class="btn btn-secondary">← Back to Patients</a>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <button onclick="window.print()" class="btn btn-primary">🖨️ Print Invoice</button>
            <button onclick="downloadPDF()" class="btn btn-secondary">📄 Download PDF</button>
            <button onclick="toggleEmailForm()" class="btn btn-success">✉️ Email Invoice</button>
        </div>
    </div>
    
    <div class="email-form" id="emailForm">
        <h4>Email Invoice</h4>
        <form onsubmit="sendEmail(event)">
            <div class="form-group">
                <label for="emailTo">Email Address:</label>
                <input type="email" id="emailTo" class="form-control" required 
                       value="{{ $invoice->patient->email ?? '' }}" 
                       placeholder="Enter recipient email address">
            </div>
            <div class="form-group">
                <label for="emailMessage">Additional Message (Optional):</label>
                <textarea id="emailMessage" class="form-control" rows="3" 
                          placeholder="Add a personal message to include with the invoice..."></textarea>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-success">Send Email</button>
                <button type="button" onclick="toggleEmailForm()" class="btn btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
    
    <div class="invoice-content">
        <div style="text-align: center; margin-bottom: 2rem;">
            <h2 style="color: #667eea; margin: 0;">Invoice {{ $invoice->invoice_number }}</h2>
            <span class="status-badge status-{{ $invoice->status }}">{{ ucfirst($invoice->status) }}</span>
        </div>
        
        <div class="invoice-details">
            <div class="detail-section">
                <h3>Bill To:</h3>
                <p><strong>{{ $invoice->patient->first_name }} {{ $invoice->patient->last_name }}</strong></p>
                <p>MRN: {{ $invoice->patient->mrn }}</p>
                @if($invoice->patient->email)
                <p>Email: {{ $invoice->patient->email }}</p>
                @endif
                @if($invoice->patient->phone)
                <p>Phone: {{ $invoice->patient->phone }}</p>
                @endif
                @if($invoice->patient->address)
                <p>Address: {{ $invoice->patient->address }}</p>
                @endif
            </div>
            
            <div class="detail-section">
                <h3>Invoice Details:</h3>
                <p><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>Date:</strong> {{ $invoice->created_at->format('M d, Y') }}</p>
                <p><strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}</p>
                <p><strong>Doctor:</strong> {{ $invoice->doctor->name }}</p>
                @if($invoice->email_sent_at)
                <p><strong>Email Sent:</strong> {{ $invoice->email_sent_at->format('M d, Y H:i') }}</p>
                @endif
            </div>
        </div>
        
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Service Description</th>
                    <th style="text-align: center;">Quantity</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ $invoice->service_type }}</strong>
                        @if($invoice->description)
                        <br><small style="color: #6c757d;">{{ $invoice->description }}</small>
                        @endif
                    </td>
                    <td style="text-align: center;">1</td>
                    <td style="text-align: right;">PKR {{ number_format($invoice->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>
        
        <div class="total-section">
            <p><strong>Subtotal: PKR {{ number_format($invoice->amount, 2) }}</strong></p>
            <p><strong>Tax: PKR 0.00</strong></p>
            <p class="total-amount">Total: PKR {{ number_format($invoice->amount, 2) }}</p>
        </div>
        
        @if($invoice->description)
        <div style="margin-top: 2rem; padding: 1.5rem; background: #f8f9fa; border-radius: 8px;">
            <h4 style="color: #667eea; margin-bottom: 1rem;">Additional Notes:</h4>
            <p>{{ $invoice->description }}</p>
        </div>
        @endif
        
        <div style="margin-top: 2rem; padding: 1.5rem; background: #f8f9fa; border-radius: 8px; text-align: center; color: #6c757d;">
            <p><strong>Aviva Healthcare</strong></p>
            <p>Email: info@avivahealthcare.org | Billing: invoices@avivahealthcare.org</p>
            <p>Thank you for choosing Aviva Healthcare for your medical needs.</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
function toggleEmailForm() {
    const form = document.getElementById('emailForm');
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}

async function sendEmail(event) {
    event.preventDefault();
    
    const emailTo = document.getElementById('emailTo').value;
    const emailMessage = document.getElementById('emailMessage').value;
    
    try {
        const response = await fetch('/api/invoices/{{ $invoice->id }}/email', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                email: emailTo,
                message: emailMessage
            })
        });
        
        const result = await response.json();
        
        if (response.ok) {
            alert('Invoice emailed successfully!');
            toggleEmailForm();
        } else {
            alert('Error sending email: ' + (result.message || 'Unknown error'));
        }
    } catch (error) {
        alert('Error sending email: ' + error.message);
    }
}

async function downloadPDF() {
    try {
        // Show loading message
        const originalContent = document.querySelector('.invoice-content').innerHTML;
        
        // Create a clone of the invoice for PDF generation
        const invoiceClone = document.querySelector('.invoice-container').cloneNode(true);
        
        // Remove actions and no-print elements from clone
        const actionsToRemove = invoiceClone.querySelectorAll('.invoice-actions, .no-print');
        actionsToRemove.forEach(el => el.remove());
        
        // Generate PDF using html2canvas and jsPDF
        const canvas = await html2canvas(invoiceClone, {
            scale: 2,
            useCORS: true,
            allowTaint: true
        });
        
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('p', 'mm', 'a4');
        
        const imgWidth = 210; // A4 width in mm
        const pageHeight = 295; // A4 height in mm
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        let heightLeft = imgHeight;
        
        let position = 0;
        
        pdf.addImage(canvas.toDataURL('image/png'), 'PNG', 0, position, imgWidth, imgHeight);
        heightLeft -= pageHeight;
        
        while (heightLeft >= 0) {
            position = heightLeft - imgHeight;
            pdf.addPage();
            pdf.addImage(canvas.toDataURL('image/png'), 'PNG', 0, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;
        }
        
        pdf.save('invoice-{{ $invoice->invoice_number }}.pdf');
    } catch (error) {
        alert('Error generating PDF: ' + error.message);
    }
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});
</script>
@endsection
