<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhieuChamSoc extends Model
{
    protected $table = 'phieu_cham_soc';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = false;

}
