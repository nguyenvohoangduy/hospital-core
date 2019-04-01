<?php
namespace App\Repositories\DanhMuc;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\NoiGioiThieu;

class NoiGioiThieuRepository extends BaseRepositoryV2
{
    const LOAI_NOI_GIOI_THIEU = 1;
    
    public function getModel()
    {
        return NoiGioiThieu::class;
    }  
    
    public function getAll() {
        $data = $this->model
                ->where('loai', '=', (self::LOAI_NOI_GIOI_THIEU))
                ->orderBy('id', 'desc')
                ->get();
                
        $totalRecord = $data->count();
        
        $result = [
            'data'          => $data,
            'totalRecord'   => $totalRecord
        ];
        
        return $result;
    }

    public function getPartial($limit = 100, $page = 1, $ten = '') {
        $offset = ($page - 1) * $limit;
        
        $query = $this->model;
        if($ten)
            $query = $query
                ->where('ten', 'like', '%' . $ten . '%');
                
        $totalRecord = $query->count();
        
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            
            $data = $query->orderBy('id', 'desc')
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
        $noiGioiThieu = $this->model->where([
                                'ten'=>$input["ten"],
                                'loai'=>$input["loai"]
                            ])
                            ->limit(1)
                            ->get(); 
        $array = json_decode($noiGioiThieu, true);
        $id = $array?$array[0]["id"]:0;
        //print_r($id);die();
        if($id == 0)
            $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function update($id, array $input)
    {
        $dmngt = $this->model->findOrFail($id);
		$dmngt->update($input);
    }
    
    public function delete($id)
    {
		$this->model->destroy($id);
    }
}