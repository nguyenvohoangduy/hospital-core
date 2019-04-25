<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichSuGiaoDich extends Model
{
    //
    protected $table='lich_su_giao_dich';
    
    protected $primaryKey='id';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];
}
