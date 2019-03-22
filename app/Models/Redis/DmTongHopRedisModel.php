<?php
namespace App\Models\Redis;

use App\Models\Redis\BaseModel;

class DmTongHopRedisModel extends BaseModel
{
    public $attributes = [];
    public $fields = [
        'id','khoa', 'gia_tri', 'dien_giai', 'parent_id'
    ];
    
    public $validations = [];
    
   
    
}
