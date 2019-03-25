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
        return PhongResource::collection(
           $this->phongRepository->getListPhong($loaiPhong,$khoaId)
        );
    }
    
    public function getNhomPhong($loaiPhong,$khoaId)
    {
        return PhongResource::collection(
           $this->phongRepository->getNhomPhong($loaiPhong,$khoaId)
        );
    }
    
    public function getPartial($limit, $page, $keyWords, $khoaId)
    {
        $data = $this->phongRepository->getPartial($limit, $page, $keyWords, $khoaId);

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
    
    public function searchPhongByKeywords($keyWords)
    {
        $data = $this->phongRepository->searchPhongByKeywords($keyWords);
        return $data;
    }  
    
    public function getAllPhongByKhoaId($khoaId)
    {
        $data = $this->phongRepository->getAllPhongByKhoaId($khoaId);
        return $data;
    }  
}