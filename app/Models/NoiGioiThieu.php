<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoiGioiThieu extends Model
{
    //
    protected $table='noi_gioi_thieu';
    
    protected $primaryKey='id';
    
    public $timestamps = false;
    
    protected $guarded = ['id'];
}
