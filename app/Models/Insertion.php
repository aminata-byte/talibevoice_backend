<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insertion extends Model
{
    use HasFactory;

    protected $fillable = [
        'talibe_id',
        'partenaire_id',
        'formation_id',
        'type',
        'poste',
        'description',
        'lieu',
        'type_contrat',
        'date_insertion',
        'date_cloture',
        'statut',
    ];

    protected $casts = [
        'date_insertion' => 'date',
        'date_cloture' => 'date',
    ];

    // Relations
    public function talibe()
    {
        return $this->belongsTo(Talibe::class);
    }

    public function partenaire()
    {
        return $this->belongsTo(Partenaire::class);
    }

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }
}
