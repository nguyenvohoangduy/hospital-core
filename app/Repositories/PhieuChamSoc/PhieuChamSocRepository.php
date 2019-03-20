<?php
namespace App\Repositories\PhieuChamSoc;

use App\Repositories\BaseRepositoryV2;
use App\Models\PhieuChamSoc;

class PhieuChamSocRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return PhieuChamSoc::class;
    }
    
    function createPhieuChamSoc(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    function getPhieuChamSocById($id)
    {
        $data = $this->model->findOrFail($id);
        return $data;
    }    
    
    function getListPhieuChamSocByHsbaId($hsbaId)
    {
        $column=[
            'phieu_cham_soc.*',
            'khoa.ten_khoa',
            'phong.ten_phong',
            'auth_users.fullname as ten_nguoi_tao'
            ];
        $data = $this->model
            ->leftJoin('khoa','khoa.id','=','phieu_cham_soc.khoa_id')
            ->leftJoin('phong','phong.id','=','phieu_cham_soc.phong_id')
            ->leftJoin('auth_users','auth_users.id','=','phieu_cham_soc.nguoi_tao_id')
            ->where('phieu_cham_soc.hsba_id',$hsbaId)
            ->orderBy('phieu_cham_soc.id','DESC')
            ->get($column);
        return $data;
    }     
}