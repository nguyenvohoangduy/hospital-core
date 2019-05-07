<?php

namespace App\Services;

use App\Repositories\Kho\GioiHanRepository;
use Illuminate\Http\Request;
use DB;
use Validator;

class GioiHanService {
    public function __construct(
        GioiHanRepository $gioiHanRepository)
    {
        $this->gioiHanRepository = $gioiHanRepository;
    }
    
    public function getListThuocVatTuSapHet($limit, $page, $keyWords, $khoId)
    {
        $data = $this->gioiHanRepository->getListThuocVatTuSapHet($limit, $page, $keyWords, $khoId);
        
        return $data;
    } 
}