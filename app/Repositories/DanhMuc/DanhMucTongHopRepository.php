<?php
namespace App\Repositories\DanhMuc;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\DanhMucTongHop;
use App\Helper\Util;

class DanhMucTongHopRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return DanhMucTongHop::class;
    }
    
    public function getAllByKhoa($khoa) {
        $data   = $this->model
                ->where('khoa', $khoa)
                ->get();
        return $data; 
    }
    
    public function getListBenhVien()
    {
        $benhVien = $this->model
                ->orderBy('id')
                ->get();
        return $benhVien;    
    }
    
    public function getTenDanhMucTongHopByKhoaGiaTri($khoa, $gia_tri)
    {
        $where = [
                ['danh_muc_tong_hop.khoa', '=', $khoa],
                ['danh_muc_tong_hop.gia_tri', '=', $gia_tri]
            ];
        $column = [
            'danh_muc_tong_hop.gia_tri',
            'danh_muc_tong_hop.dien_giai'
        ];
        $data = $this->model
                ->where($where)
                ->get($column);
        $array = json_decode($data, true);
      
        return collect($array)->first();  
    }
    
    public function getPartial($limit = 100, $page = 1, $dienGiai = '', $khoa = '') {
        $query = $this->model
                ->where('dien_giai', 'like', '%' . $dienGiai . '%');
                
        if($khoa != "") {
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
        $data = $this->model->select('khoa')->distinct()->get();
    
        return $data;
    }

    public function getById($dmthId)
    {
        $result = $this->model->findOrFail($dmthId); 
        return $result;
    }

    
    public function getAll()
    {
        $result = $this->model->orderBy('id','ASC')->get();
        return $result;
    }

}