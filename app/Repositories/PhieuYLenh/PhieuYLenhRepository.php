<?php
namespace App\Repositories\PhieuYLenh;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\PhieuYLenh;

class PhieuYLenhRepository extends BaseRepositoryV2
{
    const NGUNG_Y_LENH = 1;
    
    public function getModel()
    {
        return PhieuYLenh::class;
    }
    
    public function getPhieuYLenhId(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function getListPhieuYLenh($hsbaId)
    {
        $result = $this->model
                ->where('hsba_id',$hsbaId)
                ->orderBy('id', 'desc')
                ->get();
        if($result)
            return $result;
        else
            return null;
    }
    
    public function getAllByDieuTriId($dieuTriId)
    {
        $data = $this->model->where('dieu_tri_id',$dieuTriId)->get();
        return $data;
    } 
    
    public function updateStatus($phieuYLenhId)
    {
        $this->model->where('id', $phieuYLenhId)->update(['trang_thai' => self::NGUNG_Y_LENH]);
    }
}