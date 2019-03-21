<?php
namespace App\Services;

// Framework Libraries
use Illuminate\Http\Request;
use Validator;

// Repositories
use App\Repositories\Redis\DanhMuc\DmTongHopRedisRepository as dmTongHopRedisRepository;
use App\Repositories\DanhMucTongHopRepository as dmTongHopRepository;

use App\Http\Resources\DanhMucTongHopResource;

use App\Helper\Util;
use Cviebrock\LaravelElasticsearch\Facade as Elasticsearch;

class DanhMucTongHopRedisService {
    
    public function __construct(
         dmTongHopRedisRepository $dmTongHopRedisRepository ,
         dmTongHopRepository $dmTongHopRepository
    )
    {
        $this->dmTongHopRepository = $dmTongHopRepository;
        
        $this->dmTongHopRedisRepository = $dmTongHopRedisRepository;
        $this->dmTongHopRedisRepository->_init();
       
    }
    
     
    //push data redis hash
    public function pushToRedis()
    {
        $data = $this->dmTongHopRepository->getAllDmTH();
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
    
    
    /**
    * Deprecating
    */
    public function getList($limit, $page, $keyword, $status)
    {
        $data = $this->dmTongHopRedisRepository->getList($limit, $page, $keyword, $status);
        return $data;
    }
    
    public function getDmthById($Khoa,$Id)
    {
        $data = $this->dmTongHopRedisRepository->getDataDanhMucTongHopById($Khoa,$Id);
        
        return $data;
    }
    
  
    
    public function createDanhMucTongHop(array $input)
    {
        $suffix = $input['khoa'].':'.$input['id'];
        $this->dmTongHopRedisRepository->hmset($suffix,$input);            
  
    }
    
    
}