<?php
namespace App\Repositories\DanhMuc;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\NoiGioiThieu;
use App\Helper\Util;

class NoiGioiThieuRepository extends BaseRepositoryV2
{
    const LOAI_NOI_GIOI_THIEU = 1;
    const TRANG_THAI_SU_DUNG = 1;
    const TRANG_THAI_XOA = 0;
    
    public function getModel()
    {
        return NoiGioiThieu::class;
    }  
    
    public function getAll() {
        $data = $this->model
                ->where('loai', '=', (self::LOAI_NOI_GIOI_THIEU))
                ->where('trang_thai', '=', (self::TRANG_THAI_SU_DUNG))
                ->orderBy('id', 'desc')
                ->get();
                
        $totalRecord = $data->count();
        
        $result = [
            'data'          => $data,
            'totalRecord'   => $totalRecord
        ];
        
        return $result;
    }

    public function getPartial($limit = 100, $page = 1, $ten = '') {
        
        $query = $this->model->where('trang_thai', '=', self::TRANG_THAI_SU_DUNG);
        if($ten)
            $query = $query
                ->where('ten', 'like', '%' . $ten . '%')
                ->orderBy('id', 'desc');
        
        return Util::getPartial($query,$limit,$page);
    }
    
    public function create(array $input)
    {
        $arrayAdd = array_merge($input, array('trang_thai' => self::TRANG_THAI_SU_DUNG));
        $noiGioiThieu = $this->model->where([
                                'ten'=>$input["ten"],
                                'loai'=>$input["loai"],
                                'trang_thai'=>self::TRANG_THAI_SU_DUNG
                            ])
                            ->limit(1)
                            ->get(); 
        $array = json_decode($noiGioiThieu, true);
        $id = $array?$array[0]["id"]:0;
        //print_r($id);die();
        if($id == 0)
            $id = $this->model->create($arrayAdd)->id;
        return $id;
    }
    
    public function update($id, array $input)
    {
        $dmngt = $this->model->findOrFail($id);
		$dmngt->update($input);
    }
    
    public function delete($id)
    {
        $trang_thai = array("trang_thai" => self::TRANG_THAI_XOA);
		$dmngt = $this->model->findOrFail($id);
		$dmngt->update($trang_thai);
    }
}