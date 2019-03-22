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
    public function __construct(DanhMucTongHopRepository $danhMucTongHopRepository ,DanhMucTongHopRedisRepository $dmTongHopRedisRepository  )
    {
        $this->danhMucTongHopRepository = $danhMucTongHopRepository;
     
          
        $this->dmTongHopRedisRepository = $dmTongHopRedisRepository;
        $this->dmTongHopRedisRepository->_init();
    }

   //push data redis hash
    public function pushToRedis()
    {
        $data = $this->danhMucTongHopRepository->geyAll();
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
    
    
    public function getListNgheNghiep()
    {
        return DanhMucTongHopResource::collection(
           $this->danhMucTongHopRepository->getListNgheNghiep()
        );
    }

    public function getListDanToc()
    {
        return DanhMucTongHopResource::collection(
           $this->danhMucTongHopRepository->getListDanToc()
        );
    }
    
    public function getListQuocTich()
    {
        return DanhMucTongHopResource::collection(
           $this->danhMucTongHopRepository->getListQuocTich()
        );
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
    
    public function getListDanhMucTongHop($limit, $page, $dienGiai, $khoa)
    {
        $data = $this->danhMucTongHopRepository->getListDanhMucTongHop($limit, $page, $dienGiai, $khoa);
        return $data;
    }
    
    public function getDmthById($dmthId)
    {
        $data = $this->danhMucTongHopRepository->getDataDanhMucTongHopById($dmthId);
        
        return $data;
    }
    
    public function getDanhMucTongHopTheoKhoa($khoa, $limit, $page) {
        $data = $this->danhMucTongHopRepository->getDanhMucTongHopTheoKhoa($khoa, $limit, $page);
        
        return $data;
    }
    
    public function createDanhMucTongHop(array $input)
    {
        $id = $this->danhMucTongHopRepository->createDanhMucTongHop($input);
       
        $isNumericId = is_numeric($id);
        if($isNumericId){
            //insert redis
            $suffix = $input['khoa'].':'.$id;
            $this->dmTongHopRedisRepository->hmset($suffix,$input);  
        }
              
        return $id;
    }
    
    public function updateDanhMucTongHop($dmthId, array $input)
    {
        $this->danhMucTongHopRepository->updateDanhMucTongHop($dmthId, $input);
        
        // Update : Redis không có update, nếu key tồn tại nó sẽ ghi đè
        $isNumericId = is_numeric($dmthId);
        if($isNumericId){
            //update redis
            $suffix = $input['khoa'].':'.$dmthId;
            $this->dmTongHopRedisRepository->hmset($suffix,$input);  
        }
    }
    
    public function deleteDanhMucTongHop($dmthId)
    {
       
        //delete database
        $this->danhMucTongHopRepository->deleteDanhMucTongHop($dmthId);
        
         // //delete redis
        $data = $this->danhMucTongHopRepository->getById($dmthId);
  
        $redis = Redis::connection();
        $suffix = 'danh_muc_tong_hop:'. $data['khoa'].':'.$dmthId;
        $redis->del($suffix);
        
        
      
    }
    
    public function getAllKhoa()
    {
        $data = $this->danhMucTongHopRepository->getAllKhoa();
        return $data;
    }

}