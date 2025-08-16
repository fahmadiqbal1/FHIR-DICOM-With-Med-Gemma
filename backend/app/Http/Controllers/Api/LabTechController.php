<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LabOrder;
use App\Models\Invoice;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LabTechController extends Controller
{
    /**
     * Get lab technician dashboard statistics
     */
    public function getStats(Request $request): JsonResponse
    {
        $today = Carbon::today();
        
        $stats = [
            'pending_orders' => LabOrder::whereIn('status', ['ordered'])->count(),
            'samples_collected_today' => LabOrder::where('status', 'collected')
                ->whereDate('collected_at', $today)
                ->count(),
            'results_submitted_today' => LabOrder::where('status', 'resulted')
                ->whereDate('resulted_at', $today)
                ->count(),
            'total_tests_today' => LabOrder::whereDate('created_at', $today)->count(),
            
            // Financial stats
            'total_revenue' => $this->getTotalRevenue(),
            'pending_payments' => $this->getPendingPayments(),
            'today_revenue' => $this->getTodayRevenue(),
            'total_invoices' => $this->getTotalInvoices(),
        ];
        
        return response()->json($stats);
    }
    
    /**
     * Get lab orders for lab technician dashboard
     */
    public function getOrders(Request $request): JsonResponse
    {
        $query = LabOrder::with(['patient', 'test', 'orderingProvider'])
            ->orderBy('priority', 'desc') // STAT first, then urgent, then routine
            ->orderBy('ordered_at', 'asc'); // Oldest first within priority
        
        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        // Filter by priority
        if ($request->has('priority') && $request->priority !== '') {
            $query->where('priority', $request->priority);
        }
        
        // Filter by date
        if ($request->has('date') && $request->date !== '') {
            $date = Carbon::parse($request->date);
            $query->whereDate('ordered_at', $date);
        }
        
        $orders = $query->get();
        
        $formattedOrders = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'patient_name' => $order->patient->first_name . ' ' . $order->patient->last_name,
                'patient_mrn' => $order->patient->mrn,
                'patient_id' => $order->patient_id,
                'test_name' => $order->test->name ?? 'Unknown Test',
                'test_code' => $order->test->code ?? 'N/A',
                'priority' => $order->priority,
                'status' => $order->status,
                'status_display' => ucfirst($order->status),
                'ordered_at' => $order->ordered_at?->toISOString(),
                'ordered_by_name' => $order->orderingProvider->name ?? 'Unknown',
                'collected_at' => $order->collected_at?->toISOString(),
                'resulted_at' => $order->resulted_at?->toISOString(),
                'result_value' => $order->result_value,
                'result_flag' => $order->result_flag,
                'result_notes' => $order->result_notes,
                'price' => $order->price ?? 50.00, // Default price
                'invoiced' => !is_null($order->invoice_id),
            ];
        });
        
        return response()->json($formattedOrders);
    }
    
    /**
     * Record sample collection
     */
    public function collectSample(Request $request, LabOrder $order): JsonResponse
    {
        $request->validate([
            'collected_at' => 'required|date',
            'collection_notes' => 'nullable|string|max:1000'
        ]);
        
        try {
            $collectionTime = Carbon::parse($request->collected_at);
            
            $order->update([
                'status' => 'collected',
                'collected_at' => $collectionTime,
                'result_notes' => $this->appendNote($order->result_notes, 'Collection: ' . ($request->collection_notes ?? 'Sample collected'))
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Sample collection recorded successfully',
                'order' => [
                    'id' => $order->id,
                    'status' => $order->status,
                    'collected_at' => $order->collected_at?->toISOString(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error recording sample collection: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Submit test results
     */
    public function submitResults(Request $request, LabOrder $order): JsonResponse
    {
        $request->validate([
            'result_value' => 'required|string|max:255',
            'result_flag' => 'required|in:normal,high,low,critical',
            'result_notes' => 'nullable|string|max:1000',
            'resulted_at' => 'required|date'
        ]);
        
        try {
            $resultTime = Carbon::parse($request->resulted_at);
            
            $order->update([
                'status' => 'resulted',
                'result_value' => $request->result_value,
                'result_flag' => $request->result_flag,
                'resulted_at' => $resultTime,
                'result_notes' => $this->appendNote($order->result_notes, 'Results: ' . ($request->result_notes ?? 'Results entered by lab technician'))
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Test results submitted successfully',
                'order' => [
                    'id' => $order->id,
                    'status' => $order->status,
                    'result_value' => $order->result_value,
                    'result_flag' => $order->result_flag,
                    'resulted_at' => $order->resulted_at?->toISOString(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error submitting results: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get patients for invoice generation
     */
    public function getPatients(): JsonResponse
    {
        $patients = DB::table('lab_orders')
            ->join('patients', 'lab_orders.patient_id', '=', 'patients.id')
            ->where('lab_orders.status', 'resulted')
            ->whereNull('lab_orders.invoice_id')
            ->select('patients.id', 'patients.first_name', 'patients.last_name', 'patients.mrn')
            ->distinct()
            ->get()
            ->map(function ($patient) {
                return [
                    'id' => $patient->id,
                    'name' => $patient->first_name . ' ' . $patient->last_name,
                    'mrn' => $patient->mrn
                ];
            });
        
        return response()->json($patients);
    }
    
    /**
     * Get lab orders for a specific patient that can be invoiced
     */
    public function getPatientOrders(Request $request, $patientId): JsonResponse
    {
        $orders = LabOrder::with('test')
            ->where('patient_id', $patientId)
            ->where('status', 'resulted')
            ->whereNull('invoice_id')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'test_name' => $order->test->name ?? 'Unknown Test',
                    'test_code' => $order->test->code ?? 'N/A',
                    'price' => $order->price ?? 50.00
                ];
            });
        
        return response()->json($orders);
    }
    
    /**
     * Generate new invoice
     */
    public function generateInvoice(Request $request): JsonResponse
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:lab_orders,id',
            'total_amount' => 'required|numeric|min:0'
        ]);
        
        try {
            DB::beginTransaction();
            
            // Create invoice
            $invoice = Invoice::create([
                'patient_id' => $request->patient_id,
                'invoice_number' => $this->generateInvoiceNumber(),
                'total_amount' => $request->total_amount,
                'status' => 'pending',
                'service_type' => 'laboratory',
                'generated_by' => Auth::id() ?? 1, // Lab tech user
                'generated_at' => now()
            ]);
            
            // Update lab orders with invoice ID
            LabOrder::whereIn('id', $request->order_ids)
                ->update(['invoice_id' => $invoice->id]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Invoice generated successfully',
                'invoice' => $invoice
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error generating invoice: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get all invoices
     */
    public function getInvoices(Request $request): JsonResponse
    {
        $query = Invoice::with('patient')
            ->where('service_type', 'laboratory')
            ->orWhereNotNull('total_amount');
        
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        $invoices = $query->orderBy('created_at', 'desc')->get();
        
        // Add lab orders for each invoice
        $invoices->each(function ($invoice) {
            $invoice->lab_orders = LabOrder::with('test')
                ->where('invoice_id', $invoice->id)
                ->get()
                ->pluck('test.name')
                ->filter()
                ->toArray();
                
            $invoice->patient_name = $invoice->patient->first_name . ' ' . $invoice->patient->last_name;
            $invoice->patient_mrn = $invoice->patient->mrn;
            $invoice->amount = $invoice->total_amount ?? $invoice->amount;
        });
        
        return response()->json($invoices);
    }
    
    /**
     * Collect payment for invoice
     */
    public function collectPayment(Request $request): JsonResponse
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'payment_method' => 'required|in:cash,card,check,insurance',
            'amount_received' => 'required|numeric|min:0',
            'payment_notes' => 'nullable|string'
        ]);
        
        try {
            $invoice = Invoice::findOrFail($request->invoice_id);
            
            if ($invoice->status === 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice is already paid'
                ], 400);
            }
            
            $invoice->update([
                'status' => 'paid',
                'payment_method' => $request->payment_method,
                'amount_received' => $request->amount_received,
                'payment_notes' => $request->payment_notes,
                'paid_at' => now(),
                'collected_by' => Auth::id() ?? 1
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Payment collected successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error collecting payment: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Helper function to append notes
     */
    private function appendNote(?string $existingNotes, string $newNote): string
    {
        $timestamp = Carbon::now()->format('Y-m-d H:i:s');
        $formattedNote = "[{$timestamp}] {$newNote}";
        
        if (empty($existingNotes)) {
            return $formattedNote;
        }
        
        return $existingNotes . "\n" . $formattedNote;
    }
    
    /**
     * Generate unique invoice number
     */
    private function generateInvoiceNumber(): string
    {
        $today = Carbon::today();
        $count = Invoice::whereDate('created_at', $today)->count() + 1;
        return 'LAB' . $today->format('Ymd') . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Calculate total revenue
     */
    private function getTotalRevenue(): float
    {
        return Invoice::where('status', 'paid')
            ->where(function($query) {
                $query->where('service_type', 'laboratory')
                      ->orWhereNotNull('total_amount');
            })
            ->sum('amount_received') ?? 0;
    }
    
    /**
     * Calculate pending payments
     */
    private function getPendingPayments(): float
    {
        return Invoice::where('status', 'pending')
            ->where(function($query) {
                $query->where('service_type', 'laboratory')
                      ->orWhereNotNull('total_amount');
            })
            ->sum('total_amount') ?? 0;
    }
    
    /**
     * Calculate today's revenue
     */
    private function getTodayRevenue(): float
    {
        return Invoice::where('status', 'paid')
            ->where(function($query) {
                $query->where('service_type', 'laboratory')
                      ->orWhereNotNull('total_amount');
            })
            ->whereDate('paid_at', Carbon::today())
            ->sum('amount_received') ?? 0;
    }
    
    /**
     * Get total invoices count
     */
    private function getTotalInvoices(): int
    {
        return Invoice::where(function($query) {
            $query->where('service_type', 'laboratory')
                  ->orWhereNotNull('total_amount');
        })->count();
    }
}
