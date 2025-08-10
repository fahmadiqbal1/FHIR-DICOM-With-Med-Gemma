<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id','medication_id','prescribed_by','dosage','frequency','route','quantity','refills_allowed','refills_used','status','notes'
    ];

    protected $encryptable = [
        'dosage', 'frequency', 'route', 'quantity', 'status', 'notes'
    ];

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptable)) {
            $value = encrypt($value);
        }
        return parent::setAttribute($key, $value);
    }

    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        if (in_array($key, $this->encryptable) && $value !== null) {
            try {
                return decrypt($value);
            } catch (\Exception $e) {
                return $value;
            }
        }
        return $value;
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function medication(): BelongsTo
    {
        return $this->belongsTo(Medication::class);
    }

    public function prescriber(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prescribed_by');
    }

    protected static function booted()
    {
        static::created(function ($prescription) {
            \App\Models\AuditLog::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'action' => 'created',
                'subject_type' => self::class,
                'subject_id' => $prescription->id,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'details' => $prescription->toArray(),
            ]);
        });
        static::updated(function ($prescription) {
            \App\Models\AuditLog::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'action' => 'updated',
                'subject_type' => self::class,
                'subject_id' => $prescription->id,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'details' => $prescription->getChanges(),
            ]);
        });
        static::deleted(function ($prescription) {
            \App\Models\AuditLog::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'action' => 'deleted',
                'subject_type' => self::class,
                'subject_id' => $prescription->id,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'details' => $prescription->toArray(),
            ]);
        });
    }
}
