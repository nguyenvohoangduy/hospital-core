<?php
namespace App\Repositories\DanhMuc;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\NoiGioiThieu;

class NoiGioiThieuRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return NoiGioiThieu::class;
    }    

    public function getListNoiGioiThieu($limit = 100, $page = 1, $ten = '', $loai = '') {
        $offset = ($page - 1) * $limit;
        
        $query = $this->model
                ->where('ten', 'like', '%' . $ten . '%');
                
        if($loai != '')
            $query = $query
                ->where('loai', '=', $loai);
          
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
    
    public function createNoiGioiThieu(array $input)
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
    
    public function updateNoiGioiThieu($id, array $input)
    {
        $dmngt = $this->model->findOrFail($id);
		$dmngt->update($input);
    }
    
    public function deleteNoiGioiThieu($id)
    {
		$this->model->destroy($id);
    }
}