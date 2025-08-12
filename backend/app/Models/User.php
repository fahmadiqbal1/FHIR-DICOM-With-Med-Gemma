<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

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
        'name', 'email', 'password'
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
}
