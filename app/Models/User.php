<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPassword;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLE_ADMIN = 1;
    const ROLE_USER = 0;

    protected $appends = ['is_admin', 'is_parent'];


    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'type',
        'role',
        'is_admin'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function children(): HasMany
    {
        return $this->hasMany(Child::class);
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }


    public function getIsAdminAttribute(): bool
    {
        return (int)$this->role === self::ROLE_ADMIN;
    }

    public function getIsParentAttribute(): bool
    {
        return (int)$this->role === self::ROLE_USER;
    }

    public function redirectAfterLogin()
    {
        if ($this->is_admin) {
            return route('admin.main');
        }

        if ($this->is_parent) {
            return route('parent.index');
        }

        return '/';
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }
}

