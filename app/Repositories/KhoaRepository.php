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
        }
          
        $totalRecord = $model->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
          
            $data = $model->orderBy('id', 'desc')
                        ->offset($offset)
                        ->limit($limit)
                        ->get();
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
    
    public function searchPhongByKeywords($keyWords)
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
    
}