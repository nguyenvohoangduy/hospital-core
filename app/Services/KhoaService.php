<?php

namespace App\Services;

use App\Models\Khoa;
use App\Http\Resources\KhoaResource;
use App\Repositories\KhoaRepository;

use App\Repositories\DanhMuc\DanhMucTongHopRepository;

use Illuminate\Http\Request;
use Validator;

class KhoaService {
    public function __construct(KhoaRepository $khoaRepository,DanhMucTongHopRepository $danhMucTongHopRepository)
    {
        $this->khoaRepository = $khoaRepository;
        $this->danhMucTongHopRepository = $danhMucTongHopRepository;
    }
    public function getAll()
    {
        $data = $this->khoaRepository->getAll();
        return $data;
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
    
    public function getPartial($limit, $page, $keyWords, $benhVienId)
    {
        $data = $this->khoaRepository->getPartial($limit, $page, $keyWords, $benhVienId);

        return $data;
    }
    
    public function create(array $input)
    {
        $id = $this->khoaRepository->create($input);
        return $id;
    } 
    
    public function update($id, array $input)
    {
        $this->khoaRepository->update($id, $input);
    }
    
    public function delete($id)
    {
        $this->khoaRepository->delete($id);
    }
    
    public function searchByKeywords($keyWords)
    {
        $data = $this->khoaRepository->searchByKeywords($keyWords);
        return $data;
    }  
    
    public function getAllByBenhVienId($benhVienId)
    {
        $data = $this->khoaRepository->getAllByBenhVienId($benhVienId);
        return $data;
    }  
    
    public function getAllByLoaiKhoa($loaiKhoa)
    {
        $data = $this->danhMucTongHopRepository->getAllByKhoa($loaiKhoa);
        return $data;
    }  
    
    public function getById($id)
    {
        $data = $this->khoaRepository->getById($id);
        return $data;
    }
}