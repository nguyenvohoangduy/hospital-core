<?php

namespace App\Http\Controllers\Api\V1\ViDienTu;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\ViDienTuService;

class ViDienTuController extends APIController
{
    public function __construct(ViDienTuService $viDienTuService)
    {
        $this->viDienTuService = $viDienTuService;
    }
    
    public function giaoDich(Request $request)
    {
        $input = $request->all();
        $this->viDienTuService->giaoDich($input);
        $this->setStatusCode(201);
        return $this->respond([]);
    }
    
    public function getPartialBenhNhan(Request $request) {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        $keyword = $request->query('keyword', '');
        
        $data = $this->viDienTuService->getPartial($limit, $page, $keyword);
        return $this->respond($data);
    }
    
    public function getListLichSuGiaoDichByBenhNhanId(Request $request) {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        $from = $request->query('from', '');
        $to = $request->query('to', '');
        $benhNhanId = $request->query('benhNhanId');
        
        $data = $this->viDienTuService->getListLichSuGiaoDichByBenhNhanId($limit, $page, $benhNhanId, $from, $to);
        return $this->respond($data);
    }
}
