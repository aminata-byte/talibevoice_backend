<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    use HasFactory;

    protected $fillable = [
        'partenaire_id',
        'titre',
        'domaine',
        'description',
        'date_debut',
        'date_fin',
        'capacite',
        'lieu',
        'prerequis',
        'statut',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'capacite' => 'integer',
    ];

    // Relations
    public function partenaire()
    {
        return $this->belongsTo(Partenaire::class);
    }

    public function insertions()
    {
        return $this->hasMany(Insertion::class);
    }
}
