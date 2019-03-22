<?php
namespace App\Http\Controllers\Api\V1\PhieuChamSoc;

use Illuminate\Http\Request;
use App\Services\PhieuChamSocService;
use App\Http\Controllers\Api\V1\APIController;

class PhieuChamSocController extends APIController {
    public function __construct(
        PhieuChamSocService $phieuChamSocService
    )
    {
        $this->phieuChamSocService = $phieuChamSocService;
    }

    public function createPhieuChamSoc(Request $request)
    {
        $input = $request->all();
        $this->phieuChamSocService->create($input);
        return $this->respond([]);
    }
    
    public function getPhieuChamSocById($id)
    {
        $data = $this->phieuChamSocService->getById($id);
        return $this->respond($data);
    } 
    
    public function getListPhieuChamSocByDieuTriId($dieuTriId)
    {
        $data = $this->phieuChamSocService->getAllByDieuTriId($dieuTriId);
        return $this->respond($data);
    }
}