<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Daara extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'adresse',
        'capacite_accueil',
        'nombre_talibes',
        'nom_responsable',
        'telephone_responsable',
        'statut',
        'region',
        'commune',
        'latitude',
        'longitude',
    ];

    // Relations
    public function talibes()
    {
        return $this->hasMany(Talibe::class);
    }

    public function besoins()
    {
        return $this->hasMany(Besoin::class);
    }

    public function localisation()
    {
        return $this->hasOne(Localisation::class);
    }

    public function missions()
    {
        return $this->hasMany(Mission::class);
    }

    public function rapports()
    {
        return $this->hasMany(Rapport::class);
    }

    public function redistributions()
    {
        return $this->hasMany(Redistribution::class);
    }
}
