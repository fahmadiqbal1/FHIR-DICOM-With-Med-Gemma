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

    public function orders(): HasMany
    {
        return $this->hasMany(LabOrder::class);
    }
}
