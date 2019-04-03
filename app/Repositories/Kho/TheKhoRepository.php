<?php
namespace App\Repositories\Kho;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\TheKho;

class TheKhoRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return TheKho::class;
    }  
    
    public function createTheKho(array $input)
    {
        $find = $this->model
                     ->where('danh_muc_thuoc_vat_tu_id',$input['danh_muc_thuoc_vat_tu_id'])
                     ->where('kho_id',$input['kho_id'])
                     ->orderBy('ma_con','DESC')
                     ->first();
        if($find) {
            $explode = explode('.',$find['ma_con']);
            $input['ma_con']=$input['danh_muc_thuoc_vat_tu_id'].'.'.(intval($explode[1])+1);
        }
        else {
            $input['ma_con']=$input['danh_muc_thuoc_vat_tu_id'].'.1';
        }
        
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function getTonKhaDungById($id,$khoId)
    {
        $data = $this->model
            ->where('danh_muc_thuoc_vat_tu_id',$id)
            ->where('kho_id',$khoId)
            // ->select(DB::raw('(sl_ton_kho_chan+sl_ton_kho_le_1+sl_ton_kho_le_2) AS sl_ton_kho'),'sl_kha_dung')
            ->get();
        $slKhaDung = 0;
        $slTonKho = 0;
        $result=[];
        foreach($data as $item) {
            $slKhaDung+=$item->sl_kha_dung;
            $slTonKho+=$item->sl_ton_kho;
        }
        $result[]=[
            'so_luong_ton' => $slTonKho,
            'so_luong_kha_dung' => $slKhaDung
            ];
        return $result[0];
    }
    
    public function updateTheKho(array $input)
    {
        $where = [
            ['danh_muc_thuoc_vat_tu_id','=',$input['danh_muc_thuoc_vat_tu_id']],
            ['kho_id','=',$input['kho_id']],
            ['sl_kha_dung', '>', 0]
        ];
        
        $data = $this->model
                     ->where($where)
                     ->orderBy('ma_con','ASC')
                     ->get();
                     
        $soLuongYeuCau = $input['so_luong'];
        $newKhaDung = 0;
        
        if($data) {
            foreach($data as $item) {
                if($soLuongYeuCau > 0 && $item['sl_kha_dung'] > 0) {
                    $chenhLech = $item['sl_kha_dung'] - $soLuongYeuCau;
                    if($chenhLech < 0) {
                        $soLuongKhaDung = 0;
                        $soLuongYeuCau = $chenhLech * (-1);
                    } else {
                        $soLuongKhaDung = $chenhLech;
                        $soLuongYeuCau = 0;
                    }
                    
                    $this->model->where('id', $item['id'])->update(['sl_kha_dung' => $soLuongKhaDung]);
                    $newKhaDung += $soLuongKhaDung;
                } else {
                    $newKhaDung += $item['sl_kha_dung'];
                }
            }
        }
        
        return $newKhaDung;
    }
    
    // public function updateTheKhoV2(array $theKho, array $chiTietPhieuKho)
    // {
    //     $where = [
    //         ['danh_muc_thuoc_vat_tu_id', '=', $theKho['danh_muc_thuoc_vat_tu_id']],
    //         ['kho_id', '=', $theKho['kho_id']],
    //         ['sl_kha_dung', '>', 0]
    //     ];
        
    //     $data = $this->model
    //                  ->where($where)
    //                  ->orderBy('ma_con','ASC')
    //                  ->get();
                     
    //     $soLuongYeuCau = $theKho['so_luong'];
    //     $newKhaDung = 0;
        
    //     if($data) {
    //         foreach($data as $item) {
    //             if($soLuongYeuCau > 0 && $item['sl_kha_dung'] > 0) {
    //                 $chenhLech = $item['sl_kha_dung'] - $soLuongYeuCau;
    //                 if($chenhLech < 0) {
    //                     $soLuongKhaDung = 0;
    //                     $soLuongYeuCau = $chenhLech * (-1);
    //                     $chiTietPhieuKho['so_luong_yeu_cau'] = $item['sl_kha_dung'];
    //                 } else {
    //                     $soLuongKhaDung = $chenhLech;
    //                     $soLuongYeuCau = 0;
    //                     $chiTietPhieuKho['so_luong_yeu_cau'] = $soLuongYeuCau;
    //                 }
                    
    //                 $this->model->where('id', $item['id'])->update(['sl_kha_dung' => $soLuongKhaDung]);
    //                 $newKhaDung += $soLuongKhaDung;
                    
    //                 $chiTietPhieuKho['the_kho_id'] = $item['id'];
    //                 $this->chiTietPhieuKhoRepository->createChiTietPhieuKho($chiTietPhieuKho); 
    //             } else {
    //                 $newKhaDung += $item['sl_kha_dung'];
    //             }
    //         }
    //     }
        
    //     return $newKhaDung;
    // }
    
    public function getTheKho($khoId,$arrDmtvt)
    {
        $where = [
            ['kho_id','=',$khoId]
            ];
            
        $find = $this->model
                    ->where($where)
                    ->whereIn('danh_muc_thuoc_vat_tu_id',$arrDmtvt)
                    ->orderBy('ma_con','ASC')
                    ->get();  
        return $find;
    } 
    
    public function updateSoLuongTon($input)
    {
        $this->model->where('id',$input['id'])
                    ->update([
                        'sl_ton_kho_chan'   =>  $input['sl_ton_kho_chan'],
                        'sl_ton_kho_le_1'   =>  $input['sl_ton_kho_le_1'],
                        'sl_ton_kho_le_2'   =>  $input['sl_ton_kho_le_2'],
                        'sl_ton_kho'        =>  $input['sl_ton_kho'],
                        'sl_nhap_chan'      =>  $input['sl_nhap_chan'],
                        'sl_nhap_le'        =>  $input['sl_nhap_le'],
                    ]);
    }
    
    public function getTheKhoById(array $input)
    {
        $where = [
            ['danh_muc_thuoc_vat_tu_id','=',$input['danh_muc_thuoc_vat_tu_id']],
            ['kho_id','=',$input['kho_id']],
            ['sl_ton_kho', '>', 0]
        ];
        
        $data = $this->model
                     ->where($where)
                     ->orderBy('ma_con','ASC')
                     ->get();
                     
        return $data;
    }
}