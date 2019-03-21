<?php
namespace App\Http\Controllers\Api\V1\DieuTri;

use Illuminate\Http\Request;
use App\Services\DieuTriService;
use App\Http\Controllers\Api\V1\APIController;

class DieuTriController extends APIController {
    public function __construct(
        DieuTriService $dieuTriService
    )
    {
        $this->dieuTriService = $dieuTriService;
    }
    
    public function createPhieuDieuTri(Request $request)
    {
        $input = $request->all();
        $this->dieuTriService->createPhieuDieuTri($input);
        return $this->respond([]);
    }
    
    public function getDetailById($id)
    {
        $data = $this->dieuTriService->getDetailById($id);
        return $this->respond($data);
    } 
    
    public function getListByHsbaId($hsbaId)
    {
        $data = $this->dieuTriService->getListByHsbaId($hsbaId);
        return $this->respond($data);
    }     
}