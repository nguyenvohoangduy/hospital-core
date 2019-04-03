<?php
namespace App\Http\Controllers\Api\V1\DieuTri;

use Illuminate\Http\Request;
use App\Services\DieuTriService;
use App\Services\YLenhService;
use App\Http\Controllers\Api\V1\APIController;

class DieuTriController extends APIController {
    public function __construct(DieuTriService $dieuTriService, YLenhService $yLenhService)
    {
        $this->dieuTriService = $dieuTriService;
        $this->yLenhService = $yLenhService;
    }
    
    public function create(Request $request)
    {
        $input = $request->all();
        $this->dieuTriService->create($input);
        return $this->respond([]);
    }
    
    public function getById($id)
    {
        $data = $this->dieuTriService->getById($id);
        return $this->respond($data);
    } 
    
    public function getAllByHsbaId($hsbaId,$phongId)
    {
        $data = $this->dieuTriService->getAllByHsbaId($hsbaId,$phongId);
        return $this->respond($data);
    }  
    
    public function tongHopYLenh(Request $request)
    {
        $input = $request->all();
        $data = $this->yLenhService->tongHopYLenh($input);
        return $data;
    }
}