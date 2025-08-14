<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'status',
        'description',
        'email_sent_to',
        'email_sent_at',
        'due_date'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'email_sent_at' => 'datetime',
        'due_date' => 'datetime'
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

    public function getFormattedAmountAttribute(): string
    {
        return '$' . number_format($this->amount, 2);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'paid' => '<span class="badge badge-success">Paid</span>',
            'pending' => '<span class="badge badge-warning">Pending</span>',
            'cancelled' => '<span class="badge badge-danger">Cancelled</span>',
            default => '<span class="badge badge-secondary">Unknown</span>'
        };
    }
}
