<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ImagingStudy extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid', 'patient_id', 'accession_number', 'study_instance_uid', 'description', 
        'modality', 'started_at', 'status', 'study_date', 'urgency', 'file_paths', 'created_by'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'study_date' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->study_instance_uid)) {
                $model->study_instance_uid = '1.2.826.0.1.3680043.8.498.' . time() . '.' . rand(1000, 9999);
            }
        });
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function created_by_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
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
