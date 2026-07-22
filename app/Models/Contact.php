<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'nom',
        'email',
        'sujet',
        'message',
        'est_lu',
    ];

    protected $casts = [
        'est_lu' => 'boolean',
    ];
}
