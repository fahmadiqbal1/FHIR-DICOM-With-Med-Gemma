<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .invoice-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0 0 10px 0;
            font-size: 2.5rem;
        }
        .header p {
            margin: 0;
            opacity: 0.9;
        }
        .content {
            padding: 40px;
        }
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 40px;
        }
        .detail-section h3 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }
        .detail-section p {
            margin: 5px 0;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .invoice-table th {
            background: #667eea;
            color: white;
            padding: 15px;
            text-align: left;
        }
        .invoice-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        .invoice-table tr:last-child td {
            border-bottom: none;
        }
        .total-section {
            text-align: right;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #667eea;
        }
        .total-amount {
            font-size: 1.5rem;
            font-weight: bold;
            color: #667eea;
        }
        .footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            color: #6c757d;
            font-size: 0.9rem;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-paid {
            background: #d4edda;
            color: #155724;
        }
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        @media (max-width: 600px) {
            .invoice-details {
                flex-direction: column;
                gap: 20px;
            }
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <h1>🏥 Aviva Healthcare</h1>
            <p>Professional Healthcare Services</p>
        </div>
        
        <div class="content">
            <div style="text-align: center; margin-bottom: 30px;">
                <h2 style="color: #667eea; margin: 0;">Invoice {{ $invoice->invoice_number }}</h2>
                <span class="status-badge status-{{ $invoice->status }}">{{ ucfirst($invoice->status) }}</span>
            </div>
            
            <div class="invoice-details">
                <div class="detail-section">
                    <h3>Bill To:</h3>
                    <p><strong>{{ $patient->first_name }} {{ $patient->last_name }}</strong></p>
                    <p>MRN: {{ $patient->mrn }}</p>
                    @if($patient->email)
                    <p>Email: {{ $patient->email }}</p>
                    @endif
                    @if($patient->phone)
                    <p>Phone: {{ $patient->phone }}</p>
                    @endif
                    @if($patient->address)
                    <p>Address: {{ $patient->address }}</p>
                    @endif
                </div>
                
                <div class="detail-section">
                    <h3>Invoice Details:</h3>
                    <p><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</p>
                    <p><strong>Date:</strong> {{ $invoice->created_at->format('M d, Y') }}</p>
                    <p><strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}</p>
                    <p><strong>Doctor:</strong> {{ $doctor->name }}</p>
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
            <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
                <h4 style="color: #667eea; margin-bottom: 10px;">Additional Notes:</h4>
                <p>{{ $invoice->description }}</p>
            </div>
            @endif
        </div>
        
        <div class="footer">
            <p><strong>Aviva Healthcare</strong></p>
            <p>Email: info@avivahealthcare.org | Billing: invoices@avivahealthcare.org</p>
            <p>Thank you for choosing Aviva Healthcare for your medical needs.</p>
            <p style="margin-top: 15px; font-size: 0.8rem;">
                This is an automated email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>
