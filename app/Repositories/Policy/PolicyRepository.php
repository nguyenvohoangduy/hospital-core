<?php
namespace App\Repositories\Policy;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Policy;
use Carbon\Carbon;
class PolicyRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return Policy::class;
    }

    public function getPartial($limit = 100, $page = 1, $keywords='', $serviceId='')
    {
        $offset = ($page - 1) * $limit;

        $model = $this->model;
      
        if($serviceId!=''){
            $model = $model->where('policy.service_id',$serviceId);
        }
        
        if($keywords!=''){
            $model = $model->whereRaw('LOWER(policy.name) LIKE ? ',['%'.strtolower($keywords).'%']);
        }
        
        $column = [
            'policy.*',
            'service.name as service_name'
            ];
          
        $totalRecord = $model->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
          
            $data = $model
                    ->leftJoin('service','service.id','=','policy.service_id')
                    ->orderBy('policy.id', 'desc')
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
        $find = $this->model->findOrFail($id);
	    $find->update($input);
    }
    
    public function deleteKho($id)
    {
        $this->model->destroy($id);
    }
    
    public function getById($id)
    {
        $data = $this->model->findOrFail($id);
        return $data;
    }
    
    public function checkKey($key)
    {
        $data = $this->model->where('key',$key)->first();
        if($data)
            return true;
        else
            return false;
    }    
}