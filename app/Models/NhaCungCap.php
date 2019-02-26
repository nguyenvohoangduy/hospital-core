<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhaCungCap extends Model
{
    protected $table = 'nha_cung_cap';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    public $timestamps = false;

}
