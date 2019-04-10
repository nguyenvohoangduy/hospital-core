<?php
namespace App\Repositories\Kho;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\ChiTietPhieuKho;

class ChiTietPhieuKhoRepository extends BaseRepositoryV2
{
    const THUOC_VAT_TU_DUOC_DUYET = 1;
    
    public function getModel()
    {
        return ChiTietPhieuKho::class;
    }  
    
    public function createChiTietPhieuKho(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function saveChiTietPhieuKho(array $input)
    {
        $this->model->insert($input);
    }
    
    public function getByPhieuKhoId($phieuKhoId)
    {
        $data = $this->model->where('phieu_kho_id',$phieuKhoId)->get();
        return $data;
    }
    
    public function getByPhieuYLenhId($phieuYLenhId)
    {
        $column = [
            'danh_muc_thuoc_vat_tu_id',
            'the_kho_id',
            'phieu_kho_id'
        ];
        $data = $this->model->where('phieu_y_lenh_id',$phieuYLenhId)->get($column);
        return $data;
    }
    
    public function updateAndGetChiTietPhieuKho($phieuKhoId,array $input)
    {
        $this->model->where('phieu_kho_id',$phieuKhoId)->update($input);
        $data = $this->model
            ->where([
                ['phieu_kho_id','=',$phieuKhoId],
                ['trang_thai','=',self::THUOC_VAT_TU_DUOC_DUYET]
                ])
            ->get();
        return $data;
    }
    
    public function getThongTinChiTietByPhieuKhoId($phieuKhoId)
    {
        $column=[
            'danh_muc_thuoc_vat_tu.ma',
            'danh_muc_thuoc_vat_tu.ten',
            'danh_muc_thuoc_vat_tu.nong_do',
            'chi_tiet_phieu_kho.so_luong_yeu_cau',
            'chi_tiet_phieu_kho.id',
            'chi_tiet_phieu_kho.danh_muc_thuoc_vat_tu_id',
            'chi_tiet_phieu_kho.don_vi_co_ban'
        ];
        $data = $this->model
            ->where('chi_tiet_phieu_kho.phieu_kho_id',$phieuKhoId)
            ->leftJoin('danh_muc_thuoc_vat_tu','danh_muc_thuoc_vat_tu.id','=','chi_tiet_phieu_kho.danh_muc_thuoc_vat_tu_id')
            //->leftJoin('don_vi_tinh','don_vi_tinh.id','=','danh_muc_thuoc_vat_tu_id.don_vi_tinh_id')
            ->get($column);
        return $data;
    } 
    
    public function countItemTheKho($phieuYLenhId)
    {
        $where = [
            ['phieu_y_lenh_id', '=', $phieuYLenhId],
            ['trang_thai', '=', self::THUOC_VAT_TU_DUOC_DUYET]
        ];
        $data = $this->model->where($where)->count();
        return $data;
    }
}