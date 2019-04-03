<?php
namespace App\Repositories\DieuTri;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\DieuTri;
use App\Http\Resources\HsbaResource;

class DieuTriRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return DieuTri::class;
    }
    
    public function createDataDieuTri(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function getDieuTriByHsbaDv($hsbaDonViId, $khoa_id, $phong_id)
    {
        $where = [
                ['dieu_tri.hsba_don_vi_id', '=', $hsbaDonViId],
                ['dieu_tri.khoa_id', '=', $khoa_id],
                ['dieu_tri.phong_id', '=', $phong_id]
            ];
        $result = $this->model->where($where)->first(); 
        return $result;
    }
    
    public function updateDieuTri($dieu_tri_id, $input)
    {
        $dieuTri = $this->model->findOrFail($dieu_tri_id);
		$dieuTri->update($input);
    }
    
    public function getPhieuDieuTri(array $input)
    {
        $where = [
            ['hsba_don_vi_id', '=', $input['hsba_don_vi_id']],    
            ['hsba_id', '=', $input['hsba_id']],
            ['benh_nhan_id', '=', $input['benh_nhan_id']],
            ['khoa_id', '=', $input['khoa_id']],
            ['phong_id', '=', $input['phong_id']],
        ];
        
        $result = $this->model->where($where)->orderBy('id')->get()->first();
        
        if($result)
            return $result;
        else
            return null;
    }
    
    public function getInforDieuTriById($id)
    {
        $result = $this->model->where('id',$id)->orderBy('id')->get()->first();
        if($result)
            return $result;
        else
            return null;
    }
    
    function getById($id)
    {
        $data = $this->model->findOrFail($id);
        return $data;
    }
    
    function getAllByHsbaId($hsbaId,$phongId)
    {
        $column=[
            'dieu_tri.*',
            'khoa.ten_khoa',
            'phong.ten_phong',
            'auth_users.fullname as ten_nguoi_tao'
            ];
        $where = [
            ['dieu_tri.hsba_id','=',$hsbaId],
            ['dieu_tri.phong_id','=',$phongId]
            ];
        $data = $this->model
            ->leftJoin('khoa','khoa.id','=','dieu_tri.khoa_id')
            ->leftJoin('phong','phong.id','=','dieu_tri.phong_id')
            ->leftJoin('auth_users','auth_users.id','=','dieu_tri.auth_users_id')
            ->where($where)
            ->orderBy('dieu_tri.id','DESC')
            ->get($column);
        return $data;
    }
    
}