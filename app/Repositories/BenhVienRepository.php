<?php
namespace App\Repositories;
use DB;
use App\Models\BenhVien;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Config;
use Exception;
use App\Helper\Util;

class BenhVienRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return BenhVien::class;
    }
    
    public function listBenhVien()
    {
        $dataSet = $this->model
                ->orderBy('id')
                ->get();
        return $dataSet;    
    }
    
    public function getPartial($limit = 100, $page = 1, $name = NULL) {
        $query = $this->model->where('id', '>', 0);
        
        if($name != NULL) {
            $query->where('ten', 'like', '%' . $dienGiai . '%');
        }
        
        $query = $query->orderBy('id', 'desc');
        
        return Util::getPartial($query,$limit,$page);
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
    
    public function getBenhVienThietLap($id) {
        $data = [];
        $hospital = $this->model->find($id);
        $settingHospital = json_decode($hospital->thiet_lap);
        if(empty($hospital->thiet_lap)) {
            throw new Exception(Config::get('constants.error_get_thiet_lap_benh_vien'));
        }
        
        $khoaKhamBenh = $settingHospital->khoa->khoa_kham_benh;
        $data['bucket']     = $settingHospital->bucket;
        $data['khoaHienTai'] = intval($khoaKhamBenh->id); //khoa kham benh
        $data['khoaKhamBenh'] = intval($khoaKhamBenh->id); //khoa kham benh
        $data['phongDonTiepID'] = intval($khoaKhamBenh->phong->phong_don_tiep);
        return $data;
    }
}