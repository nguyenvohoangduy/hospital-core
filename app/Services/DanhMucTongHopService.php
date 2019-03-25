<?php

namespace App\Services;

use App\Models\Department;
use App\Http\Resources\DanhMucTongHopResource;
use App\Http\Resources\HanhChinhResource;
use App\Repositories\DanhMuc\DanhMucTongHopRepository;
use App\Repositories\Redis\DanhMuc\DanhMucTongHopRepository as DanhMucTongHopRedisRepository;

use Illuminate\Http\Request;
use Validator;
use Redis;

class DanhMucTongHopService {
    public function __construct(DanhMucTongHopRepository $danhMucTongHopRepository, DanhMucTongHopRedisRepository $dmTongHopRedisRepository)
    {
        $this->danhMucTongHopRepository = $danhMucTongHopRepository;
        $this->dmTongHopRedisRepository = $dmTongHopRedisRepository;
        $this->dmTongHopRedisRepository->_init();
    }

   //push data redis hash
    public function pushToRedis()
    {
        $data = $this->danhMucTongHopRepository->getAll();
        foreach($data as $item){
            $arrayItem=[
                'id'                => (string)$item->id ?? '-',
                'khoa'              => (string)$item->khoa ?? '-',
                'gia_tri'           => (string)$item->gia_tri ?? '-', 
                'dien_giai'         => $item->dien_giai ?? '-',
                'parent_id'         => (string)$item->parent_id ?? '-',
            ];   
            
            // $this->dmTongHopRedisRepository->_init();
            //$suffix = $item['nhom_danh_muc_id'].':'.$item['id'].":".Util::convertViToEn(str_replace(" ","_",strtolower($item['ten'])));
           
            $suffix = $item['khoa'].':'.$item['id'];
            $this->dmTongHopRedisRepository->hmset($suffix,$arrayItem);            
        };
    }
    
    public function getAllByKhoa($khoa) {
        return $this->danhMucTongHopRepository->getAllByKhoa($khoa);
    }

    public function getListTinh()
    {
        return HanhChinhResource::collection(
           $this->danhMucTongHopRepository->getListTinh()
        );
    }
    
    public function getListHuyen($maTinh)
    {
        return HanhChinhResource::collection(
           $this->danhMucTongHopRepository->getListHuyen($maTinh)
        );
    }
    
    public function getListXa($maHuyen,$maTinh)
    {
        return HanhChinhResource::collection(
           $this->danhMucTongHopRepository->getListXa($maHuyen,$maTinh)
        );
    }
    
    public function getPartial($limit, $page, $dienGiai, $khoa)
    {
        $data = $this->danhMucTongHopRepository->getPartial($limit, $page, $dienGiai, $khoa);
        return $data;
    }
    
    public function find($id)
    {
        $data = $this->danhMucTongHopRepository->find($id);
        
        return $data;
    }
    
    public function create(array $input)
    {
        $id = $this->danhMucTongHopRepository->create($input);
       
        $isNumericId = is_numeric($id);
        if($isNumericId){
            //insert redis
            $suffix = $input['khoa'].':'.$id;
            $this->dmTongHopRedisRepository->hmset($suffix,$input);  
        }
        return $id;
    }
    
    public function update($id, array $input)
    {
        $this->danhMucTongHopRepository->update($id, $input);
        
        // Update : Redis không có update, nếu key tồn tại nó sẽ ghi đè
        $isNumericId = is_numeric($id);
        if($isNumericId){
            //update redis
            $suffix = $input['khoa'].':'.$id;
            $this->dmTongHopRedisRepository->hmset($suffix,$input);  
        }
    }
    
    public function delete($id)
    {
        $this->danhMucTongHopRepository->delete($id);
         // //delete redis
        $data = $this->danhMucTongHopRepository->getById($id);
  
        $redis = Redis::connection();
        $suffix = 'danh_muc_tong_hop:'. $data['khoa'].':'.$id;
        $redis->del($suffix);
    }
    
    public function getAllColumnKhoa()
    {
        $data = $this->danhMucTongHopRepository->getAllColumnKhoa();
        return $data;
    }

}