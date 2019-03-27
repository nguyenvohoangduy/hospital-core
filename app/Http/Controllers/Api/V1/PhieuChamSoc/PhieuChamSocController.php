<?php
namespace App\Http\Controllers\Api\V1\PhieuChamSoc;

use Illuminate\Http\Request;
use App\Services\PhieuChamSocService;
use App\Services\YLenhService;
use App\Http\Controllers\Api\V1\APIController;

class PhieuChamSocController extends APIController {
    public function __construct(
        PhieuChamSocService $phieuChamSocService,
        YLenhService $yLenhService
    )
    {
        $this->phieuChamSocService = $phieuChamSocService;
        $this->yLenhService = $yLenhService;
    }

    public function create(Request $request)
    {
        $input = $request->all();
        $this->phieuChamSocService->create($input);
        return $this->respond([]);
    }
    
    public function getById($id)
    {
        $data = $this->phieuChamSocService->getById($id);
        return $this->respond($data);
    } 
    
    public function getAllByDieuTriId($dieuTriId)
    {
        $data = $this->phieuChamSocService->getAllByDieuTriId($dieuTriId);
        return $this->respond($data);
    }
    
    public function getYLenhByDieuTriId($dieuTriId)
    {
        $data = $this->yLenhService->getByDieuTriId($dieuTriId);
        return $this->respond($data);
    }     
}