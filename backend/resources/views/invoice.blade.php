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
    }
    
    .invoice-brand {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }
    
    .invoice-logo {
        height: 80px;
        width: auto;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    .brand-info h1 {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        color: white;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .brand-info p {
        font-size: 1rem;
        margin: 0;
        opacity: 0.9;
        font-weight: 400;
        letter-spacing: 0.5px;
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
        background: white;
        color: #333;
    }
    .invoice-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
        color: #333;
    }
    .detail-section h3 {
        color: #667eea;
        margin-bottom: 1rem;
        font-size: 1.2rem;
    }
    .detail-section p {
        color: #333;
        margin-bottom: 0.5rem;
    }
    .invoice-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 2rem 0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(102,126,234,0.15);
    }
    .invoice-table th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.2rem 1rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }
    .invoice-table td {
        padding: 1.2rem 1rem;
        border-bottom: 1px solid #e9ecef;
        background: rgba(255,255,255,0.95);
        color: #495057;
    }
    .invoice-table tr:last-child td {
        border-bottom: none;
    }
    .invoice-table tr:nth-child(even) td {
        background: rgba(248,249,250,0.8);
    }
    }
    .total-section {
        text-align: right;
        margin-top: 2rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, rgba(102,126,234,0.05) 0%, rgba(118,75,162,0.05) 100%);
        border-radius: 12px;
        border: 1px solid rgba(102,126,234,0.2);
        color: #495057;
    }
    .total-amount {
        font-size: 1.8rem;
        font-weight: 700;
        color: #667eea;
        text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        margin-top: 0.5rem;
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
        background: rgba(255, 255, 255, 0.95);
        color: #333;
        padding: 1rem;
        border-radius: 5px;
        margin-top: 1rem;
        border: 1px solid #ddd;
    }
    .email-form h4 {
        color: #333 !important;
        margin-bottom: 1rem;
    }
    .form-group {
        margin-bottom: 1rem;
    }
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: bold;
        color: #333 !important;
    }
    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
        background: #fff;
        color: #333;
    }
    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
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
        <div class="invoice-brand">
            <img src="{{ asset('images/viva-healthcare-logo.png') }}" alt="Aviva Healthcare" class="invoice-logo">
            <div class="brand-info">
                <h1>Aviva Healthcare</h1>
                <p>Advanced Medical Solutions & Digital Healthcare Platform</p>
            </div>
        </div>
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
        
        <div class="invoice-footer" style="margin-top: 2rem; padding: 2rem; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 12px; text-align: center; color: #495057; border-top: 3px solid #667eea;">
            <div class="footer-brand" style="display: flex; align-items: center; justify-content: center; gap: 1rem; margin-bottom: 1.5rem;">
                <img src="{{ asset('images/viva-healthcare-logo.png') }}" 
                     style="height: 50px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" 
                     alt="Aviva Healthcare Logo">
                <div style="text-align: left;">
                    <h4 style="color: #667eea; margin: 0; font-size: 1.4rem; font-weight: 700;">Aviva Healthcare</h4>
                    <p style="margin: 0; color: #6c757d; font-size: 0.9rem;">Professional Medical Services</p>
                </div>
            </div>
            <div style="border-top: 1px solid #dee2e6; padding-top: 1rem;">
                <p style="margin: 0.5rem 0; font-weight: 500;"><i class="fas fa-envelope" style="color: #667eea; margin-right: 8px;"></i>Email: info@vivahealthcare.com | Billing: billing@vivahealthcare.com</p>
                <p style="margin: 0; font-style: italic; color: #6c757d;">Thank you for choosing Aviva Healthcare for your medical needs.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
// Check if libraries are loaded
document.addEventListener('DOMContentLoaded', function() {
    if (typeof html2canvas === 'undefined') {
        console.warn('html2canvas library not loaded, PDF generation may fail');
    }
    if (typeof window.jspdf === 'undefined') {
        console.warn('jsPDF library not loaded, PDF generation may fail');
    }
});

function toggleEmailForm() {
    const form = document.getElementById('emailForm');
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
        document.getElementById('emailTo').focus();
    } else {
        form.style.display = 'none';
    }
}

async function sendEmail(event) {
    event.preventDefault();
    
    const emailTo = document.getElementById('emailTo').value;
    const emailMessage = document.getElementById('emailMessage').value;
    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    if (!emailTo || !emailTo.includes('@')) {
        alert('Please enter a valid email address.');
        document.getElementById('emailTo').focus();
        return;
    }
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
    submitBtn.disabled = true;
    
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
            alert('✅ Invoice emailed successfully to ' + emailTo + '!');
            toggleEmailForm();
            // Clear the form
            document.getElementById('emailMessage').value = '';
        } else {
            throw new Error(result.message || 'Server returned error ' + response.status);
        }
    } catch (error) {
        console.error('Email sending error:', error);
        alert('❌ Error sending email: ' + error.message);
    } finally {
        // Restore button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
}

async function downloadPDF() {
    try {
        // Show loading message
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generating PDF...';
        btn.disabled = true;
        
        // Wait for loading message to show
        await new Promise(resolve => setTimeout(resolve, 200));
        
        // Check for required libraries
        if (typeof html2canvas === 'undefined' || typeof window.jspdf === 'undefined') {
            throw new Error('PDF generation libraries are not available');
        }
        
        // Create a clean copy of the invoice for PDF generation
        const originalInvoice = document.querySelector('.invoice-container');
        if (!originalInvoice) {
            throw new Error('Invoice container not found');
        }
        
        const invoiceClone = originalInvoice.cloneNode(true);
        
        // Remove non-printable elements from the clone
        const elementsToRemove = invoiceClone.querySelectorAll('.invoice-actions, .no-print, .email-form, .btn, button');
        elementsToRemove.forEach(el => el.remove());
        
        // Optimize clone for PDF generation
        invoiceClone.style.position = 'absolute';
        invoiceClone.style.left = '-9999px';
        invoiceClone.style.top = '0';
        invoiceClone.style.width = '800px';
        invoiceClone.style.minHeight = '1000px';
        invoiceClone.style.backgroundColor = '#ffffff';
        invoiceClone.style.fontFamily = 'Arial, sans-serif';
        
        // Temporarily add clone to DOM for rendering
        document.body.appendChild(invoiceClone);
        
        try {
            // Generate canvas with optimized settings for PDF compatibility
            const canvas = await html2canvas(invoiceClone, {
                scale: 1.5, // Reduced scale for better compatibility
                useCORS: true,
                allowTaint: false,
                backgroundColor: '#ffffff',
                width: 800,
                height: invoiceClone.scrollHeight,
                logging: false,
                imageTimeout: 15000,
                removeContainer: false
            });
            
            // Remove the temporary clone
            document.body.removeChild(invoiceClone);
            
            // Validate canvas
            if (!canvas || canvas.width === 0 || canvas.height === 0) {
                throw new Error('Failed to generate valid canvas from invoice');
            }
            
            // Create PDF with proper settings for Adobe compatibility
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF({
                orientation: 'portrait',
                unit: 'mm',
                format: 'a4',
                putOnlyUsedFonts: true,
                floatPrecision: 16
            });
            
            // Set PDF metadata for better compatibility
            pdf.setProperties({
                title: 'Invoice {{ $invoice->invoice_number ?? "" }}',
                subject: 'Healthcare Invoice - Aviva Healthcare',
                author: 'Aviva Healthcare Platform',
                creator: 'Aviva Healthcare System',
                producer: 'jsPDF'
            });
            
            // Calculate dimensions for A4
            const pageWidth = 210; // A4 width in mm
            const pageHeight = 297; // A4 height in mm
            const margin = 10; // 10mm margin
            const contentWidth = pageWidth - (margin * 2);
            const contentHeight = pageHeight - (margin * 2);
            
            // Calculate image dimensions maintaining aspect ratio
            const imgAspectRatio = canvas.width / canvas.height;
            let imgWidth = contentWidth;
            let imgHeight = contentWidth / imgAspectRatio;
            
            // If image is too tall for one page, scale it down
            if (imgHeight > contentHeight) {
                imgHeight = contentHeight;
                imgWidth = contentHeight * imgAspectRatio;
            }
            
            // Convert canvas to high-quality image data
            const imgData = canvas.toDataURL('image/jpeg', 0.95);
            
            // Add image to PDF centered on the page
            const xOffset = (pageWidth - imgWidth) / 2;
            const yOffset = margin;
            
            pdf.addImage(imgData, 'JPEG', xOffset, yOffset, imgWidth, imgHeight, undefined, 'FAST');
            
            // If content is too tall, create additional pages
            if (imgHeight > contentHeight) {
                let remainingHeight = canvas.height;
                let currentY = 0;
                let pageCount = 1;
                
                while (remainingHeight > 0 && pageCount < 10) { // Max 10 pages safety check
                    const segmentHeight = Math.min(remainingHeight, canvas.height * (contentHeight / imgHeight));
                    
                    if (pageCount > 1) {
                        pdf.addPage();
                        const segmentCanvas = document.createElement('canvas');
                        segmentCanvas.width = canvas.width;
                        segmentCanvas.height = segmentHeight;
                        const ctx = segmentCanvas.getContext('2d');
                        
                        ctx.drawImage(canvas, 0, currentY, canvas.width, segmentHeight, 0, 0, canvas.width, segmentHeight);
                        
                        const segmentImgData = segmentCanvas.toDataURL('image/jpeg', 0.95);
                        pdf.addImage(segmentImgData, 'JPEG', xOffset, yOffset, imgWidth, imgHeight * (segmentHeight / canvas.height), undefined, 'FAST');
                    }
                    
                    currentY += segmentHeight;
                    remainingHeight -= segmentHeight;
                    pageCount++;
                }
            }
            
            // Generate filename with timestamp for uniqueness
            const timestamp = new Date().toISOString().slice(0, 19).replace(/:/g, '-');
            const filename = `aviva-invoice-{{ $invoice->invoice_number ?? "unknown" }}-${timestamp}.pdf`;
            
            // Save PDF with proper MIME type
            const pdfBlob = pdf.output('blob');
            const link = document.createElement('a');
            link.href = URL.createObjectURL(pdfBlob);
            link.download = filename;
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(link.href);
            
            console.log('PDF generated successfully:', filename);
            
        } catch (canvasError) {
            // Remove clone if it still exists
            if (document.body.contains(invoiceClone)) {
                document.body.removeChild(invoiceClone);
            }
            throw canvasError;
        }
        
        // Restore button
        btn.innerHTML = originalText;
        btn.disabled = false;
        
        // Show success message
        setTimeout(() => {
            alert('✅ PDF generated successfully! Check your downloads folder.');
        }, 500);
        
    } catch (error) {
        console.error('PDF generation error:', error);
        
        // Restore button on error
        if (event && event.target) {
            event.target.innerHTML = '📄 Download PDF';
            event.target.disabled = false;
        }
        
        // Show detailed error message with fallback options
        const errorMessage = `❌ PDF Generation Failed
        
Error: ${error.message}

🔧 Troubleshooting Options:
1. Try using Chrome or Firefox browser
2. Disable ad blockers temporarily
3. Use browser's "Print to PDF" feature (Ctrl+P → Save as PDF)
4. Contact support if the issue persists

Would you like to try browser print instead?`;
        
        if (confirm(errorMessage)) {
            window.print();
        }
        window.print();
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
