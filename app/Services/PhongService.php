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
        return PhongResource::collection(
           $this->phongRepository->getNhomPhong($loaiPhong,$khoaId)
        );
    }
}