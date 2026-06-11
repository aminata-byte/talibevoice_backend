<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Talibe extends Model
{
    use HasFactory;

    protected $fillable = [
        'daara_id',
        'agent_id',
        'nom',
        'prenom',
        'date_naissance',
        'lieu_naissance',
        'statut',
        'a_etat_civil',
        'niveau_etude',
        'est_majeur',
        'statut_insertion',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'a_etat_civil' => 'boolean',
        'est_majeur' => 'boolean',
    ];

    // Relations
    public function daara()
    {
        return $this->belongsTo(Daara::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function insertions()
    {
        return $this->hasMany(Insertion::class);
    }

    // Accesseur age
    public function getAgeAttribute()
    {
        return $this->date_naissance
            ? $this->date_naissance->age
            : null;
    }
}
