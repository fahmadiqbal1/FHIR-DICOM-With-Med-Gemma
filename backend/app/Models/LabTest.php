<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LabTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'code','name','normal_range_low','normal_range_high','units','specimen_type'
    ];

    protected $casts = [
        'normal_range_low' => 'float',
        'normal_range_high' => 'float',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(LabOrder::class, 'lab_test_id');
    }
}
