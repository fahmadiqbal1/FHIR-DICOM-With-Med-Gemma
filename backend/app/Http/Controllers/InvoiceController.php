<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\User;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class InvoiceController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'patient_id' => 'required|exists:patients,id',
                'doctor_id' => 'required|exists:users,id',
                'service_type' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0',
                'description' => 'nullable|string|max:1000',
                'due_date' => 'nullable|date',
                'status' => 'nullable|string|in:pending,paid,overdue,cancelled'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed: ' . $validator->errors()->first(),
                    'errors' => $validator->errors(),
                    'request_data' => $request->all()
                ], 422);
            }

            // Generate invoice number if not provided
            $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad((Invoice::count() + 1), 4, '0', STR_PAD_LEFT);

            $invoice = Invoice::create([
                'patient_id' => $request->patient_id,
                'doctor_id' => $request->doctor_id,
                'service_type' => $request->service_type,
                'amount' => $request->amount,
                'description' => $request->description,
                'due_date' => $request->due_date ?? now()->addDays(30),
                'status' => $request->status ?? 'pending',
                'invoice_number' => $invoiceNumber,
                'email_sent_to' => null // Don't auto-assign email
            ]);

            // Load relationships
            $invoice->load(['patient', 'doctor']);

            return response()->json([
                'message' => 'Invoice created successfully',
                'invoice' => $invoice,
                'view_url' => route('invoices.view', $invoice->id)
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Invoice creation failed: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'error' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Failed to create invoice: ' . $e->getMessage(),
                'error_details' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $invoices = Invoice::with(['patient', 'doctor'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'patient_id' => $invoice->patient_id,
                    'doctor_id' => $invoice->doctor_id,
                    'patient_name' => $invoice->patient ? ($invoice->patient->name ?? ($invoice->patient->first_name . ' ' . $invoice->patient->last_name)) : 'Unknown Patient',
                    'doctor_name' => $invoice->doctor ? $invoice->doctor->name : 'Unknown Doctor',
                    'service_type' => $invoice->service_type,
                    'amount' => $invoice->amount,
                    'status' => $invoice->status,
                    'due_date' => $invoice->due_date,
                    'created_at' => $invoice->created_at,
                    'description' => $invoice->description
                ];
            });
            
        return response()->json($invoices);
    }

    public function show(Invoice $invoice)
    {
        return $invoice->load(['patient', 'doctor']);
    }

    /**
     * Show invoice view page
     */
    public function view(Invoice $invoice)
    {
        $invoice->load(['patient', 'doctor']);
        return view('invoice', compact('invoice'));
    }

    /**
     * Send invoice via email to custom address
     */
    public function sendEmail(Request $request, Invoice $invoice)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'message' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $invoice->load(['patient', 'doctor']);
            
            // Create a custom mail class for sending to custom email
            $customMail = new class($invoice, $request->email, $request->message) extends \Illuminate\Mail\Mailable {
                use \Illuminate\Bus\Queueable, \Illuminate\Queue\SerializesModels;
                
                public $invoice;
                public $customEmail;
                public $customMessage;
                
                public function __construct($invoice, $customEmail, $customMessage = null)
                {
                    $this->invoice = $invoice;
                    $this->customEmail = $customEmail;
                    $this->customMessage = $customMessage;
                }
                
                public function build()
                {
                    $mail = $this->from('info@avivahealthcare.org')
                                 ->to($this->customEmail)
                                 ->subject('Invoice ' . $this->invoice->invoice_number . ' - Aviva Healthcare')
                                 ->view('emails.invoice')
                                 ->with([
                                     'invoice' => $this->invoice,
                                     'patient' => $this->invoice->patient,
                                     'doctor' => $this->invoice->doctor,
                                     'customMessage' => $this->customMessage
                                 ]);
                    
                    return $mail;
                }
            };

            Mail::send($customMail);

            return response()->json([
                'message' => 'Invoice sent successfully to ' . $request->email
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send custom invoice email: ' . $e->getMessage(), [
                'invoice_id' => $invoice->id,
                'email' => $request->email
            ]);

            return response()->json([
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate PDF of invoice
     */
    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load(['patient', 'doctor']);
        
        try {
            // For now, return the HTML that can be converted to PDF on frontend
            // In production, you might want to use a proper PDF library like DomPDF
            return response()->json([
                'html' => View::make('emails.invoice', [
                    'invoice' => $invoice,
                    'patient' => $invoice->patient,
                    'doctor' => $invoice->doctor,
                ])->render()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to generate PDF: ' . $e->getMessage(), ['invoice_id' => $invoice->id]);
            
            return response()->json([
                'message' => 'Failed to generate PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}
