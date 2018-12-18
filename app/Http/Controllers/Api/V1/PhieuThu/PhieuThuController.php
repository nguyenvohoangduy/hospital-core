<?php

namespace App\Http\Controllers\Api\V1\PhieuThu;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\SoPhieuThuService;
use App\Services\PhieuThuService;
use App\Http\Requests\SoPhieuThuFormRequest;

class PhieuThuController extends APIController {
    public function __construct(SoPhieuThuService $soPhieuThuService, PhieuThuService $phieuThuService)
    {
        $this->soPhieuThuService = $soPhieuThuService;
        $this->phieuThuService = $phieuThuService;
    }
    
    public function getListSoPhieuThu(Request $request) {
        $maSo = $request->query('ma_so', '');
        $loaiSo = $request->query('loai_so', '');
        
        $data = $this->soPhieuThuService->getListSoPhieuThu($maSo, $loaiSo);
        return $this->respond($data);
    }
    
    public function createSoPhieuThu(SoPhieuThuFormRequest $request) {
        $input = $request->all();
        
        $id = $this->soPhieuThuService->createSoPhieuThu($input);
        if($id) {
            $this->setStatusCode(201);
            return $this->respond($id);
        } else {
            $this->setStatusCode(400);
            return $this->respond([]);
        }
    }
    
    public function updateSoPhieuThu($id, SoPhieuThuFormRequest $request)
    {
        try {
            $isNumericId = is_numeric($id);
            $input = $request->all();
            
            if($isNumericId) {
                $this->soPhieuThuService->updateSoPhieuThu($id, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    
    public function deleteSoPhieuThu($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $this->soPhieuThuService->deleteSoPhieuThu($id);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);        
    }
    
    public function getListPhieuThu(Request $request) {
        $data = $this->phieuThuService->getListPhieuThu();
        return $this->respond($data);
    }
    
    public function getListPhieuThuBySoPhieuThuId($soPhieuThuId) {
        $data = $this->phieuThuService->getListPhieuThuBySoPhieuThuId($soPhieuThuId);
        return $this->respond($data);
    }
    
    // public function createPhieuThu(PhieuThuFormRequest $request) {
    //     $input = $request->all();
        
    //     $id = $this->phieuThuService->createPhieuThu($input);
    //     if($id) {
    //         $this->setStatusCode(201);
    //         return $this->respond($id);
    //     } else {
    //         $this->setStatusCode(400);
    //         return $this->respond([]);
    //     }
    // }
}