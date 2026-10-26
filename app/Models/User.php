<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
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

    // Relation avec Role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Vérifier si l'utilisateur a un rôle spécifique
    public function hasRole($roleNom)
    {
        return $this->role && $this->role->nom === $roleNom;
    }

    public function notifications()
{
    return $this->hasMany(Notification::class)->latest();
}

public function unreadNotifications()
{
    return $this->hasMany(Notification::class)->where('is_read', false);
}
}