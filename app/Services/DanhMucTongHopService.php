<?php

namespace App\Services;

use App\Models\Department;
use App\Http\Resources\DanhMucTongHopResource;
use App\Http\Resources\HanhChinhResource;
use App\Repositories\DanhMuc\DanhMucTongHopRepository;
use Illuminate\Http\Request;
use Validator;

class DanhMucTongHopService {
    public function __construct(DanhMucTongHopRepository $danhMucTongHopRepository)
    {
        $this->danhMucTongHopRepository = $danhMucTongHopRepository;
    }
    
    public function getAllByKhoa($khoa) {
        return $this->danhMucTrangThaiRepository->getAllByKhoa($khoa);
    }

    public function getListTinh()
    {
        return HanhChinhResource::collection(
           $this->danhMucTongHopRepository->getListTinh()
        );
    }
    
    public function getListHuyen($maTinh)
    {
        return HanhChinhResource::collection(
           $this->danhMucTongHopRepository->getListHuyen($maTinh)
        );
    }
    
    public function getListXa($maHuyen,$maTinh)
    {
        return HanhChinhResource::collection(
           $this->danhMucTongHopRepository->getListXa($maHuyen,$maTinh)
        );
    }
    
    public function getPartial($limit, $page, $dienGiai, $khoa)
    {
        $data = $this->danhMucTongHopRepository->getPartial($limit, $page, $dienGiai, $khoa);
        return $data;
    }
    
    public function find($id)
    {
        $data = $this->danhMucTongHopRepository->find($id);
        
        return $data;
    }
    
    public function create(array $input)
    {
        $id = $this->danhMucTongHopRepository->create($input);
        return $id;
    }
    
    public function update($id, array $input)
    {
        $this->danhMucTongHopRepository->update($id, $input);
    }
    
    public function delete($id)
    {
        $this->danhMucTongHopRepository->delete($id);
    }
    
    public function getAllColumnKhoa()
    {
        $data = $this->danhMucTongHopRepository->getAllColumnKhoa();
        return $data;
    }

}