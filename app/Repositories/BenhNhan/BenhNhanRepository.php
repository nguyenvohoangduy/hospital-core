<?php
namespace App\Repositories\BenhNhan;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\BenhNhan;


class BenhNhanRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return BenhNhan::class;
    }
    
    public function createDataBenhNhan(array $input)
    {
         $id = $this->model->create($input)->id;
         return $id;
    }
    
    public function checkMaSoBenhNhan($benh_nhan_id)
    {
        $column = [
            'id as benh_nhan_id', 
        ];
        $result = $this->model->where('benh_nhan.id', $benh_nhan_id)
                            ->get($column)
                            ->first(); 
        return $result;
    }
    
    public function getById($id) {
        $result = $this->model->find($id); 
        return $result; 
    }
    
    public function update($id, array $input)
    {
        $result = $this->find($id);
        if ($result) {
            $result->update($input);
        }
    }
    
    public function checkBenhNhanTonTai($idBenhNhan, $tenBenhNhan, $ngaySinh, $gioiTinh)
    {
        $result = $this->model->where('benh_nhan.id', $idBenhNhan)
                            ->whereRaw('LOWER(trim(benh_nhan.ho_va_ten)) = ?', mb_strtolower(trim($tenBenhNhan)))
                            ->where('benh_nhan.ngay_sinh', $ngaySinh)
                            ->where('benh_nhan.gioi_tinh_id', $gioiTinh)
                            ->get(['benh_nhan.id'])
                            ->first();
        return $result;
    }
    
}