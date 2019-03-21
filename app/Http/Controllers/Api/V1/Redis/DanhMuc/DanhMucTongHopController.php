<?php
namespace App\Http\Controllers\Api\V1\Redis\DanhMuc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\V1\APIController;
use App\Services\DanhMucTongHopRedisService;


// 3rd party library
use Carbon\Carbon;

class DanhMucTongHopController extends APIController
{
    public function __construct(DanhMucTongHopRedisService $danhmucTongHopRedisService   )
    {
        $this->danhmucTongHopRedisService = $danhmucTongHopRedisService;
    }
    
    public function getListDanhMucTongHop(Request $request)
    {
        // main params
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        
        // optional params        
        $keyword = $request->query('keyword', '');
        $status = $request->query('status', 0);

        $listdmth = $this->danhmucTongHopRedisService->getList( $limit, $page, $keyword, $status);
        return $this->respond($listdmth);
    }
    
    public function getDmthById($Khoa,$Id){
        
        $isNumericId = is_numeric($Id);
        
        if($isNumericId) {
            $data = $this->danhmucTongHopRedisService->getDmthById($Khoa,$Id);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    
    public function createDanhMucTongHop(Request $request) {
        
        $input = $request->all();
        
        $id = $this->danhmucTongHopRedisService->createDanhMucTongHop($input);
        if($id) {
            $this->setStatusCode(201);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    
    public function updateDanhMucTongHop($Id, Request $request)
    {
        try {
            $isNumericId = is_numeric($Id);
            $input = $request->all();
            
            if($isNumericId) {
                $this->danhmucTongHopRedisService->updateDanhMucTongHop($input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }
   
}