<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Don extends Model
{
    use HasFactory;

    protected $fillable = [
        'donateur_id',
        'type',
        'montant',
        'mode_paiement',
        'items_materiel',
        'statut',
        'date_don',
    ];

    protected $casts = [
        'date_don' => 'date',
        'montant' => 'double',
        'items_materiel' => 'array',
    ];

    // Relations
    public function donateur()
    {
        return $this->belongsTo(Donateur::class);
    }

    public function redistributions()
    {
        return $this->hasMany(Redistribution::class);
    }
}
