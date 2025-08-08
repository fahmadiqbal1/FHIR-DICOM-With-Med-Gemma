<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImagingStudy extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid','patient_id','accession_number','study_instance_uid','description','modality','started_at','status'
    ];

    protected $casts = [
        'started_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(DicomImage::class);
    }

    public function aiResults(): HasMany
    {
        return $this->hasMany(AiResult::class);
    }
}
