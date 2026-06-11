<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'telephone',
        'matricule',
        'zone_affectation',
        'statut',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relations
    public function talibes()
    {
        return $this->hasMany(Talibe::class, 'agent_id');
    }

    public function missions()
    {
        return $this->hasMany(Mission::class, 'agent_id');
    }

    public function rapports()
    {
        return $this->hasMany(Rapport::class, 'agent_id');
    }

    public function besoins()
    {
        return $this->hasMany(Besoin::class, 'agent_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'admin_id');
    }

    // Helpers rôles
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isAgent()
    {
        return $this->role === 'agent';
    }
}
