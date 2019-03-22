<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MauHoiBenh extends Model
{
    //
    protected $table='mau_hoi_benh';
    
    protected $primaryKey='id';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];
}
