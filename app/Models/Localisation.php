<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'daara_id',
        'latitude',
        'longitude',
        'zone',
        'region',
        'commune',
    ];

    // Relations
    public function daara()
    {
        return $this->belongsTo(Daara::class);
    }
}
