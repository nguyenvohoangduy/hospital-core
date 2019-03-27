<?php
namespace App\Repositories;

use DB;
use App\Models\Khoa;
use App\Repositories\BaseRepositoryV2;

class KhoaRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return Khoa::class;
    }
    
    public function getListKhoa($loaiKhoa, $benhVienId)
    {
        $data = $this->model->where([
                    'loai_khoa'     =>  $loaiKhoa,
                    'benh_vien_id'  =>  $benhVienId
                ])
                ->orderBy('ten_khoa')
                ->get();
        return $data;    
    }
    
    public function listKhoaByBenhVienId($benhVienId)
    {
        $data = $this->model->where([
                    'benh_vien_id'  =>  $benhVienId
                ])
                ->orderBy('ten_khoa')
                ->get();
        return $data;    
    }
    
    public function getTreeListKhoaPhong($limit = 100, $page = 1, $benhVienId)
    {
        $offset = ($page - 1) * $limit;
        
        $query = $this->model->where('benh_vien_id',$benhVienId);
        
        $dataSet = [];    
        
        $totalRecord = $query->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            
            $data = $query->orderBy('ten_khoa', 'asc')
                        ->offset($offset)
                        ->limit($limit)
                        ->get();
            if($data){
                foreach($data as $item){
                    $dataPhong = DB::table('phong')
                            ->select(DB::raw("CONCAT('0',phong.khoa_id,phong.id) AS key"),
                                'phong.id as phong_id',
                                'phong.khoa_id',
                                'phong.ten_phong as ten_khoa_phong',
                                'phong.ma_nhom as ma_phong',
                                'khoa.ma_khoa as ma_khoa',
                                'khoa.ten_khoa as ten_khoa'
                            )
                            ->leftJoin('khoa', 'khoa.id', '=', 'phong.khoa_id')
                            ->orderBy('phong.ten_phong','asc')
                            ->where('phong.khoa_id',$item->id)
                            ->get();
                    $dataSet[]=[
                        'key'               =>  $item->id,
                        'id'                =>  $item->id,
                        'ma_khoa'           =>  $item->ma_khoa,
                        'ten_khoa_phong'    =>  $item->ten_khoa,
                        'children'          =>  $dataPhong
                    ];
                }
            }
            
        } else {
            $totalPage = 0;
            $data = [];
            $page = 0;
            $totalRecord = 0;
        }
            
        $result = [
            'data'          => $dataSet,
            'page'          => $page,
            'totalPage'     => $totalPage,
            'totalRecord'   => $totalRecord
        ];
        
        return $result;
    }    
    
    public function getPartial($limit = 100, $page = 1, $keyWords ='', $benhVienId)
    {
        $offset = ($page - 1) * $limit;

        $model = $this->model->where('benh_vien_id','=',$benhVienId);
      
        if($keyWords!=""){
            $model->whereRaw('LOWER(ten_khoa) LIKE ? ',['%'.strtolower($keyWords).'%']);

            // $upperCase = mb_convert_case($keyWords, MB_CASE_UPPER, "UTF-8");
            // $lowerCase = mb_convert_case($keyWords, MB_CASE_LOWER, "UTF-8");
            // $titleCase = mb_convert_case($keyWords, MB_CASE_TITLE, "UTF-8");
            // $ucfirst = ucfirst($keyWords);
            
            // $model->orWhere('ten_khoa', 'like', '%'.$upperCase.'%')
            //         ->orWhere('ten_khoa', 'like', '%'.$lowerCase.'%')
            //         ->orWhere('ten_khoa', 'like', '%'.$titleCase.'%')
            //         ->orWhere('ten_khoa', 'like', '%'.$keyWords.'%')
            //         ->orWhere('ten_khoa', 'like', '%'.$ucfirst.'%');
                    // ->orWhere('icd10code', 'like', '%'.$upperCase.'%')
                    // ->orWhere('icd10code', 'like', '%'.$lowerCase.'%')
                    // ->orWhere('icd10code', 'like', '%'.$titleCase.'%')
                    // ->orWhere('icd10code', 'like', '%'.$keyWords.'%');
        }
        
        $column = [
            'khoa.*',
            'danh_muc_tong_hop.dien_giai as ten_loai_khoa',
            'benh_vien.ten as ten_benh_vien'
            ];
          
        $totalRecord = $model->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
          
            $data = $model
                        ->leftJoin('danh_muc_tong_hop', function($join) {
                                $join->on(DB::raw('cast(danh_muc_tong_hop.gia_tri as integer)'), '=', 'khoa.loai_khoa')
                                    ->where('danh_muc_tong_hop.khoa', '=', 'loai_khoa');
                        })
                        ->leftJoin('benh_vien','benh_vien.id','=','khoa.benh_vien_id')
                        ->orderBy('khoa.id', 'desc')
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
                        ->whereRaw('LOWER(ten_khoa) LIKE ? ',['%'.strtolower($keyWords).'%'])
                        ->get();
        return $data;
    } 
    
    public function getAllByBenhVienId($benhVienId)
    {
        $data = $this->model
                    ->where('benh_vien_id', $benhVienId)
                    ->get();
        return $data;
    } 
    
    public function getKhoaById($id)
    {
        $data = $this->model
                    ->where('id', $id)
                    ->first();
        return $data;
    }
    
}