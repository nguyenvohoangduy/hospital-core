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
    
    function create(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    function getById($id)
    {
        $data = $this->model->findOrFail($id);
        return $data;
    }    
    
    function getAllByDieuTriId($dieuTriId)
    {
        $column=[
            'phieu_cham_soc.*',
            'auth_users.fullname as ten_nguoi_tao'
            ];
        $data = $this->model
            ->leftJoin('auth_users','auth_users.id','=','phieu_cham_soc.auth_users_id')
            ->where('phieu_cham_soc.dieu_tri_id',$dieuTriId)
            ->orderBy('phieu_cham_soc.id','ASC')
            ->get($column);
        return $data;
    }     
}