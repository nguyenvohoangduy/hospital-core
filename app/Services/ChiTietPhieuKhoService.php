<?php

namespace App\Services;

use App\Models\ChiTietPhieuKho;
use App\Repositories\Kho\ChiTietPhieuKhoRepository;
use Illuminate\Http\Request;
use Validator;

class ChiTietPhieuKhoService {
    public function __construct(ChiTietPhieuKhoRepository $chiTietPhieuKhoRepository)
    {
        $this->chiTietPhieuKhoRepository = $chiTietPhieuKhoRepository;
    }

    public function getByPhieuYLenhId($phieuYLenhId)
    {
        $data = $this->chiTietPhieuKhoRepository->getByPhieuYLenhId($phieuYLenhId);
        return $data;
    }
    
    public function countItemTheKho($phieuYLenhId)
    {
        $result = $this->chiTietPhieuKhoRepository->countItemTheKho($phieuYLenhId);
        return $result;
    }
    
}