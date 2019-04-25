<?php
namespace App\Repositories\BenhNhan;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\BenhNhan;
use App\Helper\Util;

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
    
    public function getPartial($limit = 100, $page = 1, $keyword = NULL) {
        $column = [
            'id',
            'ho_va_ten',
            'ngay_sinh',
            'gioi_tinh_id',
            'so_cmnd'
        ];
        
        $query = $this->model->where('id', '>', 0);
        
        if($keyword != NULL) {
            $query = $query->where(function($queryAdv) use ($keyword) {
                $upperCase = mb_convert_case($keyword, MB_CASE_UPPER, "UTF-8");
                $lowerCase = mb_convert_case($keyword, MB_CASE_LOWER, "UTF-8");
                $titleCase = mb_convert_case($keyword, MB_CASE_TITLE, "UTF-8");
                
                $queryAdv->where('ho_va_ten', 'like', '%'.$upperCase.'%')
                        ->orWhere('ho_va_ten', 'like', '%'.$lowerCase.'%')
                        ->orWhere('ho_va_ten', 'like', '%'.$titleCase.'%')
                        ->orWhere('ho_va_ten', 'like', '%'.$keyword.'%')
                        ->orWhereRaw("cast(id as text) like '%$keyword%'");
            });
        }      
        
        $data = $query->orderBy('id', 'desc');
        
        return Util::getPartial($data, $limit, $page, $column);
    }
}