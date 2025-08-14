<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorEarning extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'earning_date',
        'patients_attended',
        'total_consultations',
        'doctor_share',
        'admin_share'
    ];

    protected $casts = [
        'earning_date' => 'date',
        'total_consultations' => 'decimal:2',
        'doctor_share' => 'decimal:2',
        'admin_share' => 'decimal:2'
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
