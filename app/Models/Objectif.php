<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objectif extends Model
{
  use HasFactory;

  protected $fillable = [
    'agent_id',
    'type',
    'valeur_cible',
    'date_debut',
    'date_fin',
  ];

  protected $casts = [
    'date_debut' => 'date',
    'date_fin'   => 'date',
  ];

  public function agent()
  {
    return $this->belongsTo(User::class, 'agent_id');
  }
}
