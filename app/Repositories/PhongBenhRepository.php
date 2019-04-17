<?php
namespace App\Repositories;

use App\Repositories\BaseRepositoryV2;
use App\Models\PhongBenh;
use App\Helper\Util;

class PhongBenhRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return PhongBenh::class;
    }
    
    public function getList($limit = 100, $page = 1, $keyWords = '')
    {
        $column = [
            'phong_benh.id',
            'phong_benh.ten',
            'phong_benh.loai_phong',
            'phong_benh.so_luong_giuong',
            'phong_benh.con_trong',
            'khoa.ten_khoa',
            'danh_muc_dich_vu.ten as ten_loai_phong'
        ];
        
        $model = $this->model;
        $data = $model
                        ->leftJoin('khoa', 'khoa.id', '=', 'phong_benh.khoa_id')
                        ->leftJoin('danh_muc_dich_vu', 'danh_muc_dich_vu.id', '=', 'phong_benh.loai_phong')
                        ->where('phong_benh.ten', 'like', '%' . $keyWords . '%')
                        ->orderBy('id', 'desc');
        
        return Util::getPartial($data,$limit,$page,$column);
    }
    
    public function getById($id)
    {
        $result = $this->model->find($id); 
        return $result;
    }
  
    public function create(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function update($id, array $input)
    {
        $data = $this->model->findOrFail($id);
		$data->update($input);
    }
    
    public function delete($id)
    {
        $this->model->where('id', $id)->delete();
    }
    
    public function getPhongConTrongByKhoa($khoaId, $loaiPhong) {
        $column = [
            'phong_benh.id',
            'phong_benh.ten',
        ];
        
        $where = [
            ['phong_benh.khoa_id', '=', $khoaId],
            ['phong_benh.con_trong', '<>', 0],
            ['phong_benh.loai_phong', '=', $loaiPhong]
        ];
        
        $result = $this->model->where($where)->get($column);
        return $result;
    }
    
    public function getLoaiPhongByKhoaId($khoaId) {
        $column = [
            'phong_benh.loai_phong',
            'danh_muc_dich_vu.ten as ten',
        ];
        
        $data = $this->model
                ->leftJoin('danh_muc_dich_vu', 'danh_muc_dich_vu.id', '=', 'phong_benh.loai_phong')
                ->where('khoa_id', $khoaId)
                ->distinct('loai_phong')
                ->get($column);
        return $data;
    }
}