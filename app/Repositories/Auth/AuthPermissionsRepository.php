<?php
namespace App\Repositories\Auth;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Auth\AuthPermissions;

class AuthPermissionsRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return AuthPermissions::class;
    }

    public function getPartial($limit = 100, $page = 1, $keywords='', $serviceId='')
    {
        $offset = ($page - 1) * $limit;

        $model = $this->model;
        
        // if($keywords!=''){
        //     $model = $model->whereRaw('LOWER(auth_policy.name) LIKE ? ',['%'.strtolower($keywords).'%']);
        // }
        
        $column = [
            'auth_permissions.*',
            'benh_vien.ten as ten_benh_vien',
            'khoa.ten_khoa',
            ];
          
        $totalRecord = $model->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
          
            $data = $model
                    ->leftJoin('benh_vien','benh_vien.id','=','auth_permissions.benh_vien_id')
                    ->leftJoin('khoa','khoa.id','=','auth_permissions.khoa')
                    ->orderBy('auth_permissions.id', 'desc')
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
    
    public function getById($id)
    {
        $data = $this->model->findOrFail($id);
        return $data;
    }
    
    public function getAllPermission()
    {
        $data = $this->model->all();
        return $data;
    }     
}