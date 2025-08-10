<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicalNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id','provider_id','soap_subjective','soap_objective','soap_assessment','soap_plan','icd10_code','cpt_code'
    ];

    protected $encryptable = [
        'soap_subjective', 'soap_objective', 'soap_assessment', 'soap_plan', 'icd10_code', 'cpt_code'
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

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

    protected static function booted()
    {
        static::created(function ($note) {
            \App\Models\AuditLog::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'action' => 'created',
                'subject_type' => self::class,
                'subject_id' => $note->id,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'details' => $note->toArray(),
            ]);
        });
        static::updated(function ($note) {
            \App\Models\AuditLog::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'action' => 'updated',
                'subject_type' => self::class,
                'subject_id' => $note->id,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'details' => $note->getChanges(),
            ]);
        });
        static::deleted(function ($note) {
            \App\Models\AuditLog::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'action' => 'deleted',
                'subject_type' => self::class,
                'subject_id' => $note->id,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'details' => $note->toArray(),
            ]);
        });
    }
}
