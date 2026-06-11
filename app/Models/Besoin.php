<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Besoin extends Model
{
    use HasFactory;

    protected $fillable = [
        'daara_id',
        'agent_id',
        'type',
        'description',
        'priorite',
        'statut',
        'date_signalement',
    ];

    protected $casts = [
        'date_signalement' => 'date',
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
}
