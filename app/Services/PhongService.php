<?php

namespace App\Services;

use App\Models\Phong;
use App\Http\Resources\PhongResource;
use App\Repositories\PhongRepository;
use App\Repositories\DanhMuc\DanhMucTongHopRepository;
use App\Repositories\KhoaRepository;

use Illuminate\Http\Request;
use Validator;

class PhongService {
    public function __construct(PhongRepository $phongRepository,DanhMucTongHopRepository $danhMucTongHopRepository,KhoaRepository $khoaRepository)
    {
        $this->phongRepository = $phongRepository;
        $this->danhMucTongHopRepository = $danhMucTongHopRepository;
        $this->khoaRepository = $khoaRepository;
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
    
    public function getKhoaPhongDonTiepByBenhVienId($benhVienId)
    {
        $data = $this->phongRepository->getKhoaPhongDonTiepByBenhVienId($benhVienId);
        return $data;
    }    
  
    public function getPartial($limit, $page, $keyWords)
    {
        $data = $this->phongRepository->getPartial($limit, $page, $keyWords);

        return $data;
    }
    
    public function create(array $input)
    {
        $id = $this->phongRepository->create($input);
        return $id;
    } 
    
    public function update($id, array $input)
    {
        $this->phongRepository->update($id, $input);
    }
    
    public function delete($id)
    {
        $this->phongRepository->delete($id);
    }
    
    public function searchByKeywords($keyWords)
    {
        $data = $this->phongRepository->searchByKeywords($keyWords);
        return $data;
    }  
    
    public function getAllByKhoaId($khoaId)
    {
        $data = $this->phongRepository->getAllByKhoaId($khoaId);
        return $data;
    }  
    
    public function getById($id)
    {
        $data = $this->phongRepository->getById($id);
        return $data;
    }
    
    public function getAllByLoaiPhong($loaiPhong)
    {
        $data = $this->danhMucTongHopRepository->getAllByKhoa($loaiPhong);
        return $data;
    }  
    
}