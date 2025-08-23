<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Invoice;
use App\Models\DoctorEarning;
use App\Models\DoctorAncillaryEarning;
use App\Models\Expense;
use App\Models\Salary;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'revenue_share',
        'is_active_doctor',
        'role',
        'lab_revenue_percentage',
        'radiology_revenue_percentage',
        'pharmacy_revenue_percentage',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $encryptable = [
        'name'
    ];

    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        if (in_array($key, $this->encryptable) && $value !== null) {
            try {
                // Check if value is already decrypted or corrupted
                if (strlen($value) > 200 || str_contains($value, 'eyJ')) {
                    // Value seems to be encrypted, try to decrypt
                    $decrypted = decrypt($value);
                    // Check if decryption result is still encrypted (double encryption)
                    if (str_contains($decrypted, 'eyJ')) {
                        $decrypted = decrypt($decrypted);
                    }
                    return $decrypted;
                } else {
                    // Value seems normal, return as is
                    return $value;
                }
            } catch (\Exception $e) {
                // If decryption fails, check if it's a reasonable name length
                if (strlen($value) < 100 && !str_contains($value, 'eyJ')) {
                    return $value; // Probably already decrypted
                }
                // For corrupted data, return a fallback
                return 'User ' . ($this->id ?? 'Unknown');
            }
        }
        return $value;
    }
    
    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptable)) {
            // Only encrypt if the value is not already encrypted
            if (!str_contains($value, 'eyJ') && strlen($value) < 100) {
                $value = encrypt($value);
            }
        }
        return parent::setAttribute($key, $value);
    }

    protected static function booted()
    {
        static::created(function ($user) {
            \App\Models\AuditLog::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'action' => 'created',
                'subject_type' => self::class,
                'subject_id' => $user->id,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'details' => $user->toArray(),
            ]);
        });
        static::updated(function ($user) {
            \App\Models\AuditLog::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'action' => 'updated',
                'subject_type' => self::class,
                'subject_id' => $user->id,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'details' => $user->getChanges(),
            ]);
        });
        static::deleted(function ($user) {
            \App\Models\AuditLog::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'action' => 'deleted',
                'subject_type' => self::class,
                'subject_id' => $user->id,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'details' => $user->toArray(),
            ]);
        });
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'doctor_id');
    }

    public function doctorEarnings()
    {
        return $this->hasMany(DoctorEarning::class, 'doctor_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'created_by');
    }

    public function salaries()
    {
        return $this->hasMany(Salary::class, 'staff_id');
    }

    public function doctorAncillaryEarnings()
    {
        return $this->hasMany(DoctorAncillaryEarning::class, 'doctor_id');
    }
}
