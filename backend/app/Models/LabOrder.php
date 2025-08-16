<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'lab_test_id',
        'ordered_by',
        'priority',
        'status',
        'ordered_at',
        'collected_at',
        'resulted_at',
        'result_value',
        'result_flag',
        'result_notes',
        'invoice_id',
        'price'
    ];

    protected $casts = [
        'ordered_at' => 'datetime',
        'collected_at' => 'datetime',
        'resulted_at' => 'datetime',
        'price' => 'decimal:2'
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(LabTest::class, 'lab_test_id');
    }

    public function orderingProvider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ordered_by');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    // Helper to get test name from relationship or fallback
    public function getTestNameAttribute()
    {
        return $this->test?->name ?? 'Unknown Test';
    }

    // Helper to get test code from relationship or fallback
    public function getTestCodeAttribute()
    {
        return $this->test?->code ?? 'N/A';
    }
}
