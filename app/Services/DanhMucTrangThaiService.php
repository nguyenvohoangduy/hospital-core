<?php

namespace App\Services;

use App\Models\Department;
use App\Http\Resources\DanhMucTrangThaiResource;
use App\Http\Resources\HanhChinhResource;
use App\Repositories\DanhMuc\DanhMucTrangThaiRepository;
use Illuminate\Http\Request;
use Validator;

class DanhMucTrangThaiService {
    public function __construct(DanhMucTrangThaiRepository $danhMucTrangThaiRepository)
    {
        $this->danhMucTrangThaiRepository = $danhMucTrangThaiRepository;
    }
    
    public function getAllByKhoa($khoa) {
        return $this->danhMucTrangThaiRepository->getAllByKhoa($khoa);
    }

    public function getPartial($limit, $page, $dienGiai, $khoa)
    {
        $data = $this->danhMucTrangThaiRepository->getPartial($limit, $page, $dienGiai, $khoa);
        return $data;
    }
    
    public function find($id)
    {
        return $this->danhMucTrangThaiRepository->find($id);
    }
    
    public function create(array $input)
    {
        $id = $this->danhMucTrangThaiRepository->create($input);
        return $id;
    }
    
    public function update($id, array $input)
    {
        $result = $this->danhMucTrangThaiRepository->update($id, $input);
        return $result;
    }
    
    public function delete($id)
    {
        $this->danhMucTrangThaiRepository->delete($id);
    }
    
    public function getAllColumnKhoa()
    {
        $data = $this->danhMucTrangThaiRepository->getAllColumnKhoa();
        return $data;
    }
}