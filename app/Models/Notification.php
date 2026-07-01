<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'mission_id',
        'message',
        'type',
        'destinataire_type',
        'destinataire_id',
        'est_lue',
        'date_envoi',
    ];

    public function mission()
    {
        return $this->belongsTo(Mission::class);
    }

    protected $casts = [
        'date_envoi' => 'date',
        'est_lue' => 'boolean',
    ];

    // Relations
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Relation polymorphique destinataire
    public function destinataire()
    {
        if ($this->destinataire_type === 'agent') {
            return $this->belongsTo(User::class, 'destinataire_id');
        }
        return $this->belongsTo(Partenaire::class, 'destinataire_id');
    }


}
