<?php
namespace App\Repositories\MauHoiBenh;
use App\Repositories\BaseRepositoryV2;
use App\Models\MauHoiBenh;
use Carbon\Carbon;

class MauHoiBenhRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return MauHoiBenh::class;
    }
    
    public function create($input) {
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function getMauHoiBenhByChucNangAndUserId($chucNang, $userId) {
        $where = [
            ['mau_hoi_benh.chuc_nang', '=', $chucNang],
            ['mau_hoi_benh.user_id', '=', $userId]
        ];
        
        $data = $this->model->where($where)->get();
        return $data;
    }
    
    public function getById($id, $chucNang) {
        $where = [
            ['mau_hoi_benh.id', '=', $id],
            ['mau_hoi_benh.chuc_nang', '=', $chucNang]
        ];
        
        $data = $this->model->where($where)->first();
        return $data;
    }
}