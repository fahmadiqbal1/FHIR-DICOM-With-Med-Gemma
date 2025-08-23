<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagingTestType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'modality',
        'description',
        'body_part',
        'preparation_instructions',
        'estimated_duration',
        'is_active'
    ];

    protected $casts = [
        'preparation_instructions' => 'array',
        'is_active' => 'boolean',
        'estimated_duration' => 'decimal:1'
    ];

    // Scope for active tests
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Common imaging modalities
    public static function getModalities()
    {
        return [
            'X-ray' => 'X-ray',
            'CT' => 'CT Scan',
            'MRI' => 'MRI',
            'Ultrasound' => 'Ultrasound',
            'Mammography' => 'Mammography',
            'Nuclear Medicine' => 'Nuclear Medicine',
            'PET' => 'PET Scan',
            'Fluoroscopy' => 'Fluoroscopy'
        ];
    }
}
