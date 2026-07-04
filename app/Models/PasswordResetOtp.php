<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetOtp extends Model
{
    protected $table = 'password_resets_otp';

    protected $fillable = [
        'email',
        'otp',
        'utilise',
        'expire_at',
    ];

    protected $casts = [
        'expire_at' => 'datetime',
        'utilise' => 'boolean',
    ];
}
