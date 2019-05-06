<?php
namespace App\Repositories\Kho;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\TheKho;
use App\Helper\Util;

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
        $arr = [];
        $result = [];
        
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
                    $result['the_kho_id'] = $item['id'];
                } else {
                    $newKhaDung += $item['sl_kha_dung'];
                }
            }
            
            $result['sl_kha_dung'] = $newKhaDung;
        }
        
        return $result;
    }
    
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
    
    public function updateSoLuongKhaDung($id, $soLuongKhaDung)
    {
        $this->model->where('id',$id)
                    ->update([
                        'sl_kha_dung'   =>  $soLuongKhaDung
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
 
    public function getById($id)   
    {
        $data = $this->model->findOrFail($id);
        return $data;
    }
    
    public function getListThuocVatTu($limit = 100, $page = 1, $keyWords=null, $khoId=null)
    {
        $model = $this->model;
        
        if($khoId){
            $model = $model->where('kho_id',$khoId);
        }
       
        $offset = ($page - 1) * $limit;
        
        $totalRecord = $model->count();
        
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            
            $data = $model->select(
                            'danh_muc_thuoc_vat_tu_id',
                            DB::raw("SUM(sl_dau_ky) AS sl_dau_ky"),
                            DB::raw("SUM(sl_kha_dung) AS sl_kha_dung"),
                            DB::raw("SUM(sl_ton_kho) AS sl_ton_kho"),
                            DB::raw("SUM(sl_ton_kho_chan) AS sl_ton_kho_chan"),
                            DB::raw("SUM(sl_ton_kho_le_1) AS sl_ton_kho_le_1"),
                            DB::raw("SUM(sl_ton_kho_le_2) AS sl_ton_kho_le_2"),
                            'don_vi_co_ban',
                            DB::raw("SUM(sl_nhap_chan) AS sl_nhap_chan"),
                            DB::raw("SUM(sl_nhap_le) AS sl_nhap_le"),
                            'don_vi_nhap',
                            'ten',
                            'ma'
                            )
                    ->leftJoin('danh_muc_thuoc_vat_tu','danh_muc_thuoc_vat_tu.id','=','the_kho.danh_muc_thuoc_vat_tu_id')
                    ->groupBy('the_kho.danh_muc_thuoc_vat_tu_id','the_kho.don_vi_co_ban','the_kho.don_vi_nhap','danh_muc_thuoc_vat_tu.ten','danh_muc_thuoc_vat_tu.ma');

            if($keyWords){
                $data = $data->whereRaw('LOWER(ten) LIKE ? ',['%'.strtolower($keyWords).'%']);
            }
            
            $data = $data->offset($offset)->limit($limit)->get();
            
        } else {
            $totalPage = 0;
            $data = [];
            $page = 0;
            $totalRecord = 0;
        }
            
        $result = [
            'data'          => $data,
            'page'          => $page,
            'totalPage'     => $totalPage,
            'totalRecord'   => $totalRecord?$data->count():0
        ];
        
        return $result;        
    }    
    
    public function getListThuocVatTuHetHan($limit = 100, $page = 1, $keyWords=null, $khoId=null)
    {
        $model = $this->model->whereRaw('sl_ton_kho > 0');
         
        if($khoId){
            $model = $model->where('kho_id',$khoId);
        }
   
        $data = $model->select(
                        'danh_muc_thuoc_vat_tu_id',
                        DB::raw("SUM(sl_dau_ky) AS sl_dau_ky"),
                        DB::raw("SUM(sl_kha_dung) AS sl_kha_dung"),
                        DB::raw("SUM(sl_ton_kho) AS sl_ton_kho"),
                        'don_vi_co_ban',
                        'ten',
                        'ma',
                        'the_kho.han_su_dung',
                        'ten_kho'
                        )
                ->leftJoin('danh_muc_thuoc_vat_tu','danh_muc_thuoc_vat_tu.id','=','the_kho.danh_muc_thuoc_vat_tu_id')
                ->leftJoin('kho','kho.id','=','the_kho.kho_id')
                ->whereRaw("han_su_dung < (now()+(canh_bao_het_han||' day')::interval)")
                ->groupBy('the_kho.danh_muc_thuoc_vat_tu_id','the_kho.don_vi_co_ban','the_kho.don_vi_nhap'
                    ,'danh_muc_thuoc_vat_tu.ten','danh_muc_thuoc_vat_tu.ma','the_kho.han_su_dung','kho.ten_kho');

        if($keyWords){
            $data = $data->whereRaw('LOWER(ten) LIKE ? ',['%'.strtolower($keyWords).'%']);
        }
        
        return Util::getPartial($data,$limit,$page);
    }   
    
}