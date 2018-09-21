<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 */
class AuthGroups extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'description'];

}
