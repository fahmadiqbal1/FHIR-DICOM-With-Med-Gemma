<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorAncillaryEarning extends Model
{
    protected $fillable = [
        'doctor_id',
        'service_type',
        'service_id', 
        'service_amount',
        'doctor_percentage',
        'doctor_earning',
        'patient_name',
        'service_date',
        'metadata'
    ];

    protected $casts = [
        'service_date' => 'date',
        'metadata' => 'array',
        'service_amount' => 'decimal:2',
        'doctor_percentage' => 'decimal:2', 
        'doctor_earning' => 'decimal:2',
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
