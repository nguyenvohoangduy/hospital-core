<?php
namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class AuthPolicy extends Model
{
    protected $table = 'auth_policy';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = false;

}
