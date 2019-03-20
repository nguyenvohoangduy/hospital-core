<?php
namespace App\Http\Controllers\Api\V1\NoiTru;

use Illuminate\Http\Request;
use App\Services\HsbaDonViService;
use App\Services\PhieuChamSocService;
use App\Http\Controllers\Api\V1\APIController;
use Carbon\Carbon;

class NoiTruController extends APIController {
    public function __construct(
        HsbaDonViService $hsbaDonViService,
        PhieuChamSocService $phieuChamSocService
    )
    {
        $this->hsbaDonViService = $hsbaDonViService;
        $this->phieuChamSocService = $phieuChamSocService;
    }
    
    public function getListPhongNoiTru($benhVienId, Request $request)
    {
        // main params
        $limit = $request->query('limit', 20);
        $page = $request->query('page', 1);
        $phongId = $request->query('phongId', null);
        $khoaId = $request->query('khoaId', null);
        
        // optional params        
        $thoiGianRaVienFrom = $request->query('thoi_gian_vao_vien_from',null);
        $thoiGianRaVienTo = $request->query('thoi_gian_vao_vien_to',null);
        $keyword = $request->query('keyword', '');
        $status = $request->query('status', 0);
        
        $options = [
            'keyword'                   => $keyword,
            'thoi_gian_vao_vien_from'    => $thoiGianRaVienFrom,
            'thoi_gian_vao_vien_to'      => $thoiGianRaVienTo,
        ];
        
        if($benhVienId === null){
            $this->setStatusCode(400);
            return $this->respond([]);
        }
        
        try 
        {
            $listBenhNhan = $this->hsbaDonViService->getListPhongNoiTru($benhVienId, $khoaId, $phongId, $limit, $page, $options);
            $this->setStatusCode(200);
            return $this->respond($listBenhNhan);
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getFile().":".$ex->getLine()."::".$ex->getMessage());
        }
        
        return $this->respond($listBenhNhan);
    }
    
    public function getByHsbaId($hsbaId, $phongId)
    {
        if(is_numeric($hsbaId)) {
            $data = $this->hsbaDonViService->getByHsbaId($hsbaId, $phongId);
            return $this->respond($data);
        } else {
            $this->setStatusCode(400);
            return $this->respond([]);
        }
    }
    
    public function createPhieuChamSoc(Request $request)
    {
        $input = $request->all();
        $this->phieuChamSocService->createPhieuChamSoc($input);
        return $this->respond([]);
    }
    
    public function getPhieuChamSocById($id)
    {
        $data = $this->phieuChamSocService->getPhieuChamSocById($id);
        return $this->respond($data);
    } 
    
    public function getListPhieuChamSocByHsbaId($hsbaId)
    {
        $data = $this->phieuChamSocService->getListPhieuChamSocByHsbaId($hsbaId);
        return $this->respond($data);
    }     
}