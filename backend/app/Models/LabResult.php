<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'lab_order_id',
        'equipment_id',
        'test_code',
        'test_name',
        'result_value',
        'result_units',
        'reference_range',
        'result_flag', // 'normal', 'high', 'low', 'critical'
        'result_status', // 'preliminary', 'final', 'corrected'
        'performed_at',
        'verified_at',
        'verified_by',
        'source_type', // 'equipment', 'ocr', 'manual'
        'raw_data', // JSON field for original equipment data
        'ocr_image_path', // Path to OCR image if used
        'ocr_confidence', // OCR confidence score
        'quality_control_passed',
        'notes'
    ];

    protected $casts = [
        'performed_at' => 'datetime',
        'verified_at' => 'datetime',
        'raw_data' => 'array',
        'ocr_confidence' => 'float',
        'quality_control_passed' => 'boolean'
    ];

    public function labOrder(): BelongsTo
    {
        return $this->belongsTo(LabOrder::class);
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(LabEquipment::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function isWithinNormalRange(): ?bool
    {
        if (!$this->reference_range || !is_numeric($this->result_value)) {
            return null;
        }

        // Parse reference range (e.g., "10-50", "<10", ">50")
        $range = $this->reference_range;
        $value = (float) $this->result_value;

        if (preg_match('/^(\d+\.?\d*)-(\d+\.?\d*)$/', $range, $matches)) {
            return $value >= (float)$matches[1] && $value <= (float)$matches[2];
        }

        if (preg_match('/^<(\d+\.?\d*)$/', $range, $matches)) {
            return $value < (float)$matches[1];
        }

        if (preg_match('/^>(\d+\.?\d*)$/', $range, $matches)) {
            return $value > (float)$matches[1];
        }

        return null;
    }

    public function getResultFlagAttribute(): string
    {
        if ($this->attributes['result_flag']) {
            return $this->attributes['result_flag'];
        }

        $isNormal = $this->isWithinNormalRange();
        if ($isNormal === null) {
            return 'unknown';
        }

        return $isNormal ? 'normal' : 'abnormal';
    }
}
