<?php
namespace App\Repositories\ViDienTu;

use App\Repositories\BaseRepositoryV2;
use App\Models\LichSuGiaoDich;
use App\Helper\Util;
use Carbon\Carbon;

class LichSuGiaoDichRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return LichSuGiaoDich::class;
    }
      
    public function create(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function getListByBenhNhanId($limit = 100, $page = 1, $benhNhanId, $from, $to) {
        $query = $this->model->where('benh_nhan_id', '=', $benhNhanId);
        
        if($from == $to){
            $query = $query->whereDate('ngay_giao_dich', '=', $from);
        } else {
            $query = $query->whereBetween('ngay_giao_dich', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()]);
        }
        
        $data = $query->orderBy('id', 'desc');
        
        return Util::getPartial($data, $limit, $page);
    }
}