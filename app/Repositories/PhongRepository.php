<?php
namespace App\Repositories;

use DB;
use App\Models\Phong;
use App\Repositories\BaseRepositoryV2;

class PhongRepository extends BaseRepositoryV2
{
    const BENH_AN_KHAM_BENH = 24;
    const TRANG_THAI_HOAT_DONG = 1;
    const PHONG_HANH_CHINH = 1;
    const PHONG_NOI_TRU = 3;
    
    public function getModel()
    {
        return Phong::class;
    }
    
    public function getListPhong($loaiPhong,$khoaId)
    {
        $phong = $this->model->where([
                                'loai_phong'=>$loaiPhong,
                                'khoa_id'=>$khoaId,
                                //'loai_benh_an'=>self::BENH_AN_KHAM_BENH,
                                'trang_thai'=>self::TRANG_THAI_HOAT_DONG
                            ])
                            ->orderBy('ten_phong')
                            ->get();
        return $phong;    
    }
    
    public function getNhomPhong($loaiPhong,$khoaId)
    {
        $phong = $this->model->where([
                                'loai_phong'=>$loaiPhong,
                                'khoa_id'=>$khoaId,
                                //'loai_benh_an'=>self::BENH_AN_KHAM_BENH,
                                'trang_thai'=>self::TRANG_THAI_HOAT_DONG
                            ])
                            ->orderBy('ten_nhom')
                            ->distinct()
                            ->get(['ten_nhom','ma_nhom']);
        return $phong;    
    }
    
    public function getDataById($id)
    {
        $phong = $this->model->where(['id'=>$id])
                            ->get()
                            ->first();
        return $phong;
    }
    
    public function getPhongHanhChinhByKhoaID($khoaId)
    {
        $phong = $this->model->where([
                                ['khoa_id', '=', $khoaId],
                                ['loai_phong', '=', self::PHONG_HANH_CHINH],
                                //['loai_benh_an', '!=', self::BENH_AN_KHAM_BENH]
                            ])
                            ->get()
                            ->first();
        return $phong;
    }
    
    public function getPhongNoiTruByKhoaId($khoaId) {
        $phong = $this->model->where([
                                ['khoa_id', '=', $khoaId],
                                ['loai_phong', '=', self::PHONG_NOI_TRU]
                            ])
                            ->get()
                            ->first();
        return $phong;
    }
    
    public function getPartial($limit = 100, $page = 1, $keyWords ='')
    {
        $offset = ($page - 1) * $limit;

        // $model = $this->model->where('khoa_id','=',$khoaId);
        $model = $this->model;
        
        if($keyWords!=""){
            // $model->whereRaw('LOWER(ten_phong) LIKE ? ',['%'.strtolower($keyWords).'%']);
            
            $model = $model->where(function($queryAdv) use ($keyWords) {
                $upperCase = mb_convert_case($keyWords, MB_CASE_UPPER, "UTF-8");
                $lowerCase = mb_convert_case($keyWords, MB_CASE_LOWER, "UTF-8");
                $titleCase = mb_convert_case($keyWords, MB_CASE_TITLE, "UTF-8");
                $ucfirst = ucfirst($keyWords);
                
                $queryAdv->where('ten_phong', 'like', '%'.$upperCase.'%')
                        ->orWhere('ten_phong', 'like', '%'.$lowerCase.'%')
                        ->orWhere('ten_phong', 'like', '%'.$titleCase.'%')
                        ->orWhere('ten_phong', 'like', '%'.$keyWords.'%')
                        ->orWhere('ten_phong', 'like', '%'.$ucfirst.'%');
                    
            });
        }
        
        $column = [
            'phong.*',
            'danh_muc_tong_hop.dien_giai as ten_loai_phong',
            'khoa.ten_khoa as ten_khoa'
        ];
            
        $totalRecord = $model->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
          
            $data = $model
                        ->leftJoin('danh_muc_tong_hop', function($join) {
                                $join->on(DB::raw('cast(danh_muc_tong_hop.gia_tri as integer)'), '=', 'phong.loai_phong')
                                    ->where('danh_muc_tong_hop.khoa', '=', 'loai_phong');
                        })
                        ->leftJoin('khoa','khoa.id','=','phong.khoa_id')
                        ->orderBy('phong.id', 'desc')
                        ->offset($offset)
                        ->limit($limit)
                        ->get($column);
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
            'totalRecord'   => $totalRecord
        ];
      
        return $result;
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
    
    public function searchByKeywords($keyWords)
    {
        $data = $this->model
                        ->whereRaw('LOWER(ten_phong) LIKE ? ',['%'.strtolower($keyWords).'%'])
                        ->get();
        return $data;
    } 
    
    public function getAllByKhoaId($khoaId)
    {
        $data = $this->model
                    ->where('khoa_id', $khoaId)
                    ->get();
        return $data;
    } 
    
    public function getPhongById($id)
    {
        $data = $this->model
                    ->where('id', $id)
                    ->first();
        return $data;
    }
    
}