<?php

namespace App\Repositories\Redis\DanhMuc;

use App\Models\Redis\DmTongHopRedisModel as DmTongHopRedisModel;
use App\Repositories\Redis\BaseRedisRepository;
use Carbon\Carbon;
use Redis;


class DanhMucTongHopRepository extends BaseRedisRepository
{

    public function _init(){
        $prefix = 'danh_muc_tong_hop';
        parent::init(DmTongHopRedisModel::class, DmTongHopRedisModel::HASH_TYPE, $prefix);
    }
    
    
    // // get list
    // public function getList($limit, $page, $keyword, $status) {
    //     $suffix = '*'; 
    //     return $this->find($suffix);
    // }
    
    // //get id
    // public function getDataDanhMucTongHopById($khoa,$id)
    // {
    //     $suffix = $khoa . ':' .$id; 

    //     return $this->find($suffix); 
    // }
  
    // public function deleteDanhMucTongHop($khoa,$id)
    // {
    //     $redis = Redis::connection();
    //     $suffix = 'danh_muc_tong_hop:'. $khoa.':'.$id;
    //     $redis->del($suffix);
    // }
}