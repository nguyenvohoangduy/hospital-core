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
    public function __construct(PhongRepository $phongRepository,DanhMucTongHopRepository $danhmucTongHopRepository,KhoaRepository $khoaRepository)
    {
        $this->phongRepository = $phongRepository;
        $this->danhmucTongHopRepository = $danhmucTongHopRepository;
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
    
    public function getPhongById($id)
    {
        $data = $this->phongRepository->getPhongById($id);
        return $data;
    }
    
    public function getAllByLoaiPhong($khoa)
    {
        $data = $this->danhmucTongHopRepository->getAllByKhoa($khoa);
        return $data;
    }  
    
    public function getAllKhoa()
    {
        $data = $this->khoaRepository->getAll();
        return $data;
    }  
    
}