<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'designation',
        'base_salary',
        'bonus',
        'deductions',
        'net_salary',
        'paid_on',
        'status',
        'notes'
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'bonus' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'paid_on' => 'date'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($salary) {
            $salary->net_salary = $salary->base_salary + $salary->bonus - $salary->deductions;
        });
        
        static::updating(function ($salary) {
            $salary->net_salary = $salary->base_salary + $salary->bonus - $salary->deductions;
        });
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function getFormattedNetSalaryAttribute(): string
    {
        return '$' . number_format($this->net_salary, 2);
    }
}
