<?php

namespace App\Services;

use App\Models\Khoa;
use App\Http\Resources\KhoaResource;
use App\Repositories\KhoaRepository;
use Illuminate\Http\Request;
use Validator;

class KhoaService {
    public function __construct(KhoaRepository $khoaRepository)
    {
        $this->khoaRepository = $khoaRepository;
    }

    public function getListKhoa($loaiKhoa, $benhVienId)
    {
        $data = $this->khoaRepository->getListKhoa($loaiKhoa, $benhVienId);
        return $data;
    }
    
    public function listKhoaByBenhVienId($benhVienId)
    {
        $data = $this->khoaRepository->listKhoaByBenhVienId($benhVienId);
        return $data;   
    }    
    
    public function getTreeListKhoaPhong($limit, $page, $benhVienId)
    {
        $data = $this->khoaRepository->getTreeListKhoaPhong($limit, $page, $benhVienId);
        return $data;
    } 
    
    public function getByLoaiKhoaBenhVienId($loaiKhoa,$benhVienId)
    {
        $data = $this->khoaRepository->getByLoaiKhoaBenhVienId($loaiKhoa,$benhVienId);
        return $data;   
    }     
}