<?php
namespace App\Repositories\Kho;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Kho;
use Carbon\Carbon;
use App\Helper\Util;

class KhoRepository extends BaseRepositoryV2
{
    const NHAP_TU_NHA_CUNG_CAP = 1;
    const KHO_DANG_SU_DUNG = 1;
    
    public function getModel()
    {
        return Kho::class;
    }
    
    public function getById($limit = 100, $page = 1, $keyWords ='', $benhVienId)
    {    
    }
    
    public function getListKho($limit = 100, $page = 1, $keyWords ='', $benhVienId)
    {
        $model = $this->model->where([
            ['benh_vien_id','=',$benhVienId],
            //['nhap_tu_ncc', '=', self::NHAP_TU_NHA_CUNG_CAP]
        ]);
      
        if($keyWords!=""){
            $model->whereRaw('LOWER(ten_kho) LIKE ? ',['%'.strtolower($keyWords).'%'])
                ->orWhereRaw('LOWER(ky_hieu) LIKE ? ',['%'.strtolower($keyWords).'%']);
        }
          
        $data = $model->orderBy('id', 'desc');
        
        return Util::getPartial($data,$limit,$page);
    }
    
    public function createKho(array $input)
    {
        $input['trang_thai']=$input['trang_thai']==true?1:0;
        $input['duoc_ban']=$input['duoc_ban']==true?1:0;
        $input['nhap_tu_ncc']=$input['nhap_tu_ncc']==true?1:0;
        $input['tu_truc']=$input['tu_truc']==true?1:0;
          
        $stt = $this->model->orderBy('stt','DESC')->first();
      
        $input['stt']=$stt?$stt['stt']+1:1;
          
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function updateKho($id, array $input)
    {
        $input['trang_thai']=$input['trang_thai']==true?1:0;
        $input['duoc_ban']=$input['duoc_ban']==true?1:0;
        $input['nhap_tu_ncc']=$input['nhap_tu_ncc']==true?1:0;
        $input['tu_truc']=$input['tu_truc']==true?1:0;
        $input['phong_duoc_nhin_thay']=!empty($input['phong_duoc_nhin_thay'])?json_encode($input['phong_duoc_nhin_thay']):null;
        $find = $this->model->findOrFail($id);
	    $find->update($input);
    }
    
    public function deleteKho($id)
    {
        $this->model->destroy($id);
    }
    
    public function getKhoById($id)
    {
        $data = $this->model
                    ->where('id', $id)
                    ->first();
        return $data;
    }
    
    public function getAllKhoByBenhVienId($benhVienId)
    {
        $data = $this->model
                    ->where('benh_vien_id', $benhVienId)
                    ->get();
        return $data;
    } 
    
    public function getKhoByListId(array $listId)
    {
        $data = $this->model->whereIn('id', $listId)->get();
        return $data;
    }
    
    public function getNhapTuNccByBenhVienId($benhVienId)
    {
        $where=  [
            ['nhap_tu_ncc', '=', self::NHAP_TU_NHA_CUNG_CAP],
            ['benh_vien_id', '=', $benhVienId]
            ];
        $data = $this->model->where($where)->get();
        return $data;
    } 
    
    public function getListKhoLap($loaiKho,$benhVienId)
    {
        $where = [
            ['loai_kho','=',$loaiKho],
            ['nhap_tu_ncc','<>',1],
            ['benh_vien_id','=',$benhVienId],
            ['trang_thai','=',self::KHO_DANG_SU_DUNG],
            ];
        $data = $this->model->where($where)->get();
        return $data;
    }    
}