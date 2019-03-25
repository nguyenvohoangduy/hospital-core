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
        return KhoaResource::collection(
           $this->khoaRepository->getListKhoa($loaiKhoa, $benhVienId)
        );
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
    
    public function searchKhoaByKeywords($keyWords)
    {
        $data = $this->khoaRepository->searchKhoaByKeywords($keyWords);
        return $data;
    }  
    
    public function getAllKhoaByBenhVienId($benhVienId)
    {
        $data = $this->khoaRepository->getAllKhoaByBenhVienId($benhVienId);
        return $data;
    }  
    
}