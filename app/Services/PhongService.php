<?php

namespace App\Services;

use App\Models\Phong;
use App\Http\Resources\PhongResource;
use App\Repositories\PhongRepository;
use Illuminate\Http\Request;
use Validator;

class PhongService {
    public function __construct(PhongRepository $phongRepository)
    {
        $this->phongRepository = $phongRepository;
    }

    public function getListPhong($loaiPhong,$khoaId)
    {
        $data = $this->phongRepository->getListPhong($loaiPhong,$khoaId);
        return $data;
    }
    
    public function getNhomPhong($loaiPhong,$khoaId)
    {
        $data = $this->phongRepository->getNhomPhong($loaiPhong,$khoaId);
        return $data;
    }
    
    public function getMaNhomPhongByKhoaId($khoaId)
    {
        $data = $this->phongRepository->getMaNhomPhongByKhoaId($khoaId);
        return $data;
    }    
}