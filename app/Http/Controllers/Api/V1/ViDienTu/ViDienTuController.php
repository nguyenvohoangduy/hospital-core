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
}
