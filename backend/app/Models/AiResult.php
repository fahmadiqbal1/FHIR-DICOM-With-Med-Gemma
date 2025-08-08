<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'imaging_study_id','model','request_id','status','confidence_score','result'
    ];

    protected $casts = [
        'result' => 'array',
        'confidence_score' => 'float',
    ];

    public function imagingStudy(): BelongsTo
    {
        return $this->belongsTo(ImagingStudy::class);
    }
}
