<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid', 'mrn', 'first_name', 'last_name', 'dob', 'sex', 'phone', 'email', 'address'
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    protected $encryptable = [
        'mrn', 'first_name', 'last_name', 'dob', 'sex', 'phone', 'email', 'address'
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

    public function imagingStudies(): HasMany
    {
        return $this->hasMany(ImagingStudy::class);
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    public function labOrders(): HasMany
    {
        return $this->hasMany(LabOrder::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function clinicalNotes(): HasMany
    {
        return $this->hasMany(ClinicalNote::class);
    }

    protected static function booted()
    {
        static::created(function ($patient) {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'created',
                'subject_type' => self::class,
                'subject_id' => $patient->id,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'details' => $patient->toArray(),
            ]);
        });
        static::updated(function ($patient) {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'updated',
                'subject_type' => self::class,
                'subject_id' => $patient->id,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'details' => $patient->getChanges(),
            ]);
        });
        static::deleted(function ($patient) {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'deleted',
                'subject_type' => self::class,
                'subject_id' => $patient->id,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'details' => $patient->toArray(),
            ]);
        });
    }
}
