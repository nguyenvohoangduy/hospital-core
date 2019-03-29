<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    protected $table = 'policy';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = false;

}
