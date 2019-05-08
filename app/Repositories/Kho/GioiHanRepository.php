<?php
namespace App\Repositories\Kho;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\GioiHan;
use App\Helper\Util;

class GioiHanRepository extends BaseRepositoryV2
{
    const SAP_HET = '1';
    const DA_HET = '2';
    const TAT_CA = '0';
    
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
    
    public function getListThuocVatTuSapHet($limit = 100, $page = 1, $keyWords=null, $khoId=null, $loaiTonKho=self::SAP_HET)
    {
        $model = $this->model->whereRaw('sl_ton_kho <= co_so');
         
        if($khoId){
            $model = $model->where('kho_id',$khoId);
        }
        
        if($loaiTonKho==self::SAP_HET){
            $model = $model->whereRaw("sl_ton_kho <= co_so and sl_ton_kho > 0");
        }
        else if($loaiTonKho==self::DA_HET){
            $model = $model->whereRaw("sl_ton_kho <= 0");
        }
        else{
            $model = $model->whereRaw("sl_ton_kho <= coalesce(co_so, 0)");
        }
   
        $data = $model->select(
                        'gioi_han.danh_muc_thuoc_vat_tu_id',
                        'gioi_han.sl_kha_dung',
                        'gioi_han.sl_ton_kho',
                        DB::raw('don_vi_tinh.ten as don_vi_co_ban'),
                        'danh_muc_thuoc_vat_tu.ten',
                        'danh_muc_thuoc_vat_tu.ma',
                        'ten_kho'
                        )
                ->leftJoin('danh_muc_thuoc_vat_tu','danh_muc_thuoc_vat_tu.id','=','gioi_han.danh_muc_thuoc_vat_tu_id')
                ->leftJoin('kho','kho.id','=','gioi_han.kho_id')
                ->leftJoin('don_vi_tinh','don_vi_tinh.id','=','danh_muc_thuoc_vat_tu.don_vi_tinh_id');

        if($keyWords){
            $data = $data->whereRaw('LOWER(ten) LIKE ? ',['%'.strtolower($keyWords).'%']);
        }
        
        return Util::getPartial($data,$limit,$page);
    }  
}