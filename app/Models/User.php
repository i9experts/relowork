<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'organization_id',
        'is_admin',
        'permissions',
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
            'is_admin' => 'boolean',
            'permissions' => 'array',
        ];
    }

    public function hasPermission(string $key): bool
    {
        if ($this->is_admin) {
            return true;
        }

        $perms = $this->permissions ?? [];
        return is_array($perms) && ($perms[$key] ?? false);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function volunteers()
    {
        return $this->hasMany(Volunteer::class);
    }

    public function recordedTransactions()
    {
        return $this->hasMany(FinancialManagement::class, 'recorded_by');
    }

    public function approvedTransactions()
    {
        return $this->hasMany(FinancialManagement::class, 'approved_by');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'created_by');
    }

    public function isSystemAdmin(): bool
    {
        try {
            return (bool) $this->roles()->where('is_system', true)->exists();
        } catch (\Throwable $e) {
            return false;
        }
    }
}
