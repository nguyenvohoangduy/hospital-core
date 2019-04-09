<?php
namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class AuthGroupsHasPermissions extends Model
{
    protected $table = 'auth_groups_has_permissions';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = false;

}
