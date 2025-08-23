<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LabTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name', 
        'category',
        'normal_range',
        'normal_range_low',
        'normal_range_high',
        'unit',
        'units',
        'specimen_type',
        'price',
        'is_active'
    ];

    protected $casts = [
        'normal_range_low' => 'float',
        'normal_range_high' => 'float',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(LabOrder::class, 'lab_test_id');
    }
}
