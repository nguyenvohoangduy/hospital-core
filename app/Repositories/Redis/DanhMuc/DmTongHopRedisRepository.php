<?php

namespace App\Repositories\Redis\DanhMuc;

use App\Models\Redis\DmTongHopRedisModel as DmTongHopRedisModel;
use App\Repositories\Redis\BaseRedisRepository;
use Carbon\Carbon;
use Redis;


class DmTongHopRedisRepository extends BaseRedisRepository
{

    public function _init(){
        $prefix = 'danh_muc_tong_hop';
        parent::init(DmTongHopRedisModel::class, DmTongHopRedisModel::HASH_TYPE, $prefix);
    }
    
    
    // get list
    public function getList($limit, $page, $keyword, $status) {
        $suffix = '*'; 
        return $this->find($suffix);
    }
    
    //get id
    public function getDataDanhMucTongHopById($Khoa,$Id)
    {
        $suffix = $Khoa . ':' .$Id; 

        return $this->find($suffix); 
    }
    
    // // insert 
    // public function createDanhMucTongHop(array $input)
    // {
    //     // $id = $this->model->create($input)->id;
    //     // return $id;
        
    //     //HASH
    //     $redis = Redis::connection();
    //     $test = array(
    //         'name' => 'Iván',
    //         'lastname' => 'Sánchez'
    //     );
    //     $redis->hmset('me', $test);
    //     $me = $redis->hgetall('me');
    //     var_dump($me);die;
        
    // }
    
    public function deleteDanhMucTongHop($khoa,$Id)
    {
        $redis = Redis::connection();
        $suffix = 'danh_muc_tong_hop:'. $khoa.':'.$Id;
        $redis->del($suffix);
    }
}