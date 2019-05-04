<?php
namespace App\Http\Controllers\Api\V1\PhieuYeuCau;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\TheKhoService;
use App\Services\PhieuKhoService;
use App\Services\KhoService;

class PhieuYeuCauController extends APIController
{
    public function __construct(theKhoService $theKhoService,PhieuKhoService $phieuKhoService,KhoService $khoService)
    {
        $this->theKhoService = $theKhoService;
        $this->phieuKhoService = $phieuKhoService;
        $this->khoService = $khoService;
    }
    
    public function index(Request $request)
    {
        $this->setStatusCode(200);
        return $this->respond([]);
    }    
    
    public function getTonKhaDungById($id,$khoId)
    {
        $data = $this->theKhoService->getTonKhaDungById($id,$khoId);
        return $this->respond($data);
    }
    
    public function createPhieuYeuCau(Request $request)
    {
        $input = $request->all();
        $this->phieuKhoService->createPhieuYeuCau($input);
        return $this->respond([]);
    } 
    
    public function getListKhoLap($loaiKho,$benhVienId)
    {
        $data = $this->khoService->getListKhoLap($loaiKho,$benhVienId);
        return $this->respond($data);
    }     
}