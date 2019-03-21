<?php
namespace App\Repositories\Kho;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\GioiHan;

class GioiHanRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return GioiHan::class;
    }
    
    public function createGioiHan($input)
    {
        $this->model->create($input);
    }
    
    public function updateSoLuongKhaDung(array $input)
    {
        $where = [
            ['danh_muc_thuoc_vat_tu_id', '=', $input['danh_muc_thuoc_vat_tu_id']],
            ['kho_id', '=', $input['kho_id']]
        ];
        
        $this->model->where($where)->update(['sl_kha_dung' => $input['sl_kha_dung']]);
    }
}