<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'patient_id',
        'doctor_id',
        'service_type',
        'amount',
        'doctor_share',
        'owner_share',
        'doctor_percentage',
        'issuer_role',
        'status',
        'description',
        'email_sent_to',
        'email_sent_at',
        'due_date',
        // Lab tech specific fields
        'total_amount',
        'amount_received',
        'payment_method',
        'payment_notes',
        'generated_by',
        'collected_by',
        'generated_at',
        'paid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'doctor_share' => 'decimal:2',
        'owner_share' => 'decimal:2',
        'doctor_percentage' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_received' => 'decimal:2',
        'email_sent_at' => 'datetime',
        'due_date' => 'datetime',
        'generated_at' => 'datetime',
        'paid_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($invoice) {
            if (!$invoice->invoice_number) {
                $invoice->invoice_number = 'INV-' . date('Y') . '-' . str_pad(
                    static::whereYear('created_at', date('Y'))->count() + 1, 
                    6, 
                    '0', 
                    STR_PAD_LEFT
                );
            }
            
            if (!$invoice->due_date) {
                $invoice->due_date = Carbon::now()->addDays(30);
            }
            
            // Auto-calculate revenue split
            if ($invoice->doctor_id && $invoice->amount) {
                $doctor = User::find($invoice->doctor_id);
                if ($doctor && $doctor->revenue_share) {
                    $invoice->doctor_percentage = $doctor->revenue_share;
                    $invoice->doctor_share = ($invoice->amount * $doctor->revenue_share) / 100;
                    $invoice->owner_share = $invoice->amount - $invoice->doctor_share;
                }
            }
        });
        
        static::updating(function ($invoice) {
            // Recalculate revenue split if amount or doctor changes
            if ($invoice->isDirty(['amount', 'doctor_id']) && $invoice->doctor_id && $invoice->amount) {
                $doctor = User::find($invoice->doctor_id);
                if ($doctor && $doctor->revenue_share) {
                    $invoice->doctor_percentage = $doctor->revenue_share;
                    $invoice->doctor_share = ($invoice->amount * $doctor->revenue_share) / 100;
                    $invoice->owner_share = $invoice->amount - $invoice->doctor_share;
                }
            }
        });
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function labOrders(): HasMany
    {
        return $this->hasMany(LabOrder::class);
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function collectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    public function getFormattedAmountAttribute(): string
    {
        $amount = $this->total_amount ?? $this->amount;
        return '$' . number_format($amount, 2);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'paid' => '<span class="badge badge-success">Paid</span>',
            'pending' => '<span class="badge badge-warning">Pending</span>',
            'cancelled' => '<span class="badge badge-danger">Cancelled</span>',
            'overdue' => '<span class="badge badge-danger">Overdue</span>',
            default => '<span class="badge badge-secondary">Unknown</span>'
        };
    }
}
