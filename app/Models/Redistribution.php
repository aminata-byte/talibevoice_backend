<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redistribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'don_id',
        'daara_id',
        'montant',
        'date_redistribution',
        'motif',
        'statut',
    ];

    protected $casts = [
        'date_redistribution' => 'date',
        'montant' => 'double',
    ];

    // Relations
    public function don()
    {
        return $this->belongsTo(Don::class);
    }

    public function daara()
    {
        return $this->belongsTo(Daara::class);
    }
}
