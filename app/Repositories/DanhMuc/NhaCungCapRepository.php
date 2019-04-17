<?php
namespace App\Repositories\DanhMuc;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\NhaCungCap;
use Carbon\Carbon;
use App\Helper\Util;

class NhaCungCapRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return NhaCungCap::class;
    }    
    
    public function getListNhaCungCap($limit = 100, $page = 1, $keyWords ='')
    {
        $model = $this->model;

        if($keyWords!=''){
          $model->where('ten_nha_cung_cap', 'like', '%' . strtolower($keyWords) . '%');
        }
            
        $data = $model->orderBy('id', 'desc');
        
        return Util::getPartial($data,$limit,$page);
    }
    
    public function createNhaCungCap(array $input)
    {
        if($input['trang_thai_su_dung']==true)
            $input['trang_thai_su_dung']=1;
        else
            $input['trang_thai_su_dung']=0;
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function updateNhaCungCap($id, array $input)
    {
        if($input['trang_thai_su_dung']==true)
            $input['trang_thai_su_dung']=1;
        else
            $input['trang_thai_su_dung']=0;        
        $find = $this->model->findOrFail($id);
		$find->update($input);
    }
    
    public function deleteNhaCungCap($id)
    {
        $this->model->destroy($id);
    }
    
    public function getNhaCungCapById($id)
    {
        $data = $this->model
                ->where('id', $id)
                ->first();
        return $data;
    }     
}