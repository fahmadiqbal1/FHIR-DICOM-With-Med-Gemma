<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DicomImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'imaging_study_id','series_instance_uid','sop_instance_uid','file_path','size_bytes','checksum','metadata','width','height','frames','content_type'
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function imagingStudy(): BelongsTo
    {
        return $this->belongsTo(ImagingStudy::class);
    }
}
