<?php

namespace App\Models\Auth;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AuthUsers extends Authenticatable
{
    // use Notifiable;
    
    protected $table = 'auth_users';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = false;    

}
