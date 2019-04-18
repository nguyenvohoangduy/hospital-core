<?php
namespace App\Repositories\DanhMuc;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\DanhMucTrangThai;
use App\Helper\Util;

class DanhMucTrangThaiRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return DanhMucTrangThai::class;
    }    
    
    public function getAllByKhoa($khoa) {
        $data   = $this->model
                ->where('khoa', $khoa)
                ->get();
        return $data; 
    }

    public function getPartial($limit = 100, $page = 1, $dienGiai = NULL, $khoa = NULL) {
        $query = $this->model->where('id', '>', 0);
        
        if($dienGiai != NULL) {
            $query->where('dien_giai', 'like', '%' . $dienGiai . '%');
        }
        
        if($khoa != NULL) {
            $query->where('khoa', $khoa);
        }        
        
        $data = $query->orderBy('id', 'desc');
        
        return Util::getPartial($data,$limit,$page);
    }
    
    public function create(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function update($id, array $input)
    {
        $result = $this->find($id);
        if ($result) {
            $result->update($input);
        }
    }
    
    public function delete($id)
    {
        $result = $this->find($id);
        if ($result) {
            $result->destroy($id);
        }
    }
    
    public function find($id) {
        $result = $this->model->find($id); 
        return $result; 
    }
    
    public function getAllColumnKhoa()
    {
        $result = $this->model->select('khoa')->distinct()->get();
        return $result;
    }
}