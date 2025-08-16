<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LabEquipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'model',
        'manufacturer',
        'serial_number',
        'ip_address',
        'port',
        'connection_type', // 'serial', 'tcp', 'file_transfer', 'hl7'
        'protocol', // 'astm', 'hl7', 'lis', 'custom'
        'is_active',
        'last_connected_at',
        'configuration', // JSON field for equipment-specific settings
        'supported_tests', // JSON array of test codes this equipment can perform
        'auto_fetch_enabled',
        'backup_method' // 'ocr', 'manual', 'none'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'auto_fetch_enabled' => 'boolean',
        'last_connected_at' => 'datetime',
        'configuration' => 'array',
        'supported_tests' => 'array'
    ];

    public function results(): HasMany
    {
        return $this->hasMany(LabResult::class, 'equipment_id');
    }

    public function canPerformTest($testCode): bool
    {
        return in_array($testCode, $this->supported_tests ?? []);
    }

    public function isOnline(): bool
    {
        // Check if equipment was connected within last 5 minutes
        return $this->last_connected_at && 
               $this->last_connected_at->diffInMinutes(now()) <= 5;
    }
}
