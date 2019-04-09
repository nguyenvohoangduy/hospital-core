<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class AuthService extends Model
{
    protected $table='auth_service';
    
    protected $primaryKey='id';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];
}
