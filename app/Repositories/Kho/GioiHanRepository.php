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
    
    public function updateSoLuongTonKho(array $input)
    {
        $where = [
            ['danh_muc_thuoc_vat_tu_id', '=', $input['danh_muc_thuoc_vat_tu_id']],
            ['kho_id', '=', $input['kho_id']]
        ];
        
        $this->model->where($where)->update(['sl_ton_kho' => $input['sl_ton_kho']]);
    }
    
    public function getByThuocVatTuId($tvtId, $khoId)
    {
        $where = [
            ['danh_muc_thuoc_vat_tu_id', '=', $tvtId],
            ['kho_id', '=', $khoId]
        ];
        
        $data = $this->model->where($where)->first();
        
        if($data)
            return $data;
        else
            return null;
    }
}