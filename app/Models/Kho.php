<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kho extends Model
{
    protected $table = 'kho';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = false;

}
