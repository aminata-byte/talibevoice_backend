<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partenaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'domaine',
        'nom_contact',
        'email',
        'telephone',
        'site_web',
        'code_partenaire',
        'message_motivation',
        'statut',
    ];

    // Relations
    public function formations()
    {
        return $this->hasMany(Formation::class);
    }

    public function insertions()
    {
        return $this->hasMany(Insertion::class);
    }
}
