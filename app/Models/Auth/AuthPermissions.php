<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $authorized_uri
 */
class AuthPermissions extends Model
{
    /**
     * @var array
     */
    protected $table = 'auth_permissions';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = false;

}
