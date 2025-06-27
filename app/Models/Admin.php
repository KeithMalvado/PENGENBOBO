<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'organization',
        'email',
        'phone',
        'password',
        'firebase_uid'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
