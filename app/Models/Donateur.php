<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donateur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'est_anonyme',
        'total_dons',
    ];

    protected $casts = [
        'est_anonyme' => 'boolean',
        'total_dons' => 'double',
    ];

    // Relations
    public function dons()
    {
        return $this->hasMany(Don::class);
    }
}
