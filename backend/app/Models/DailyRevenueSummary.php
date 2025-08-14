<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyRevenueSummary extends Model
{
    use HasFactory;

    protected $table = 'daily_revenue_summary';

    protected $fillable = [
        'summary_date',
        'total_consultations',
        'total_lab_fees',
        'total_imaging_fees',
        'total_other_fees',
        'total_doctor_shares',
        'total_admin_shares',
        'gross_revenue',
        'net_admin_revenue',
        'total_patients',
        'total_invoices'
    ];

    protected $casts = [
        'summary_date' => 'date',
        'total_consultations' => 'decimal:2',
        'total_lab_fees' => 'decimal:2',
        'total_imaging_fees' => 'decimal:2',
        'total_other_fees' => 'decimal:2',
        'total_doctor_shares' => 'decimal:2',
        'total_admin_shares' => 'decimal:2',
        'gross_revenue' => 'decimal:2',
        'net_admin_revenue' => 'decimal:2'
    ];
}
