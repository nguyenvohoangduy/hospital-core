<?php
namespace App\Repositories\Auth;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Auth\AuthPermissions;

class AuthPermissionsRepository extends BaseRepositoryV2
{
    const SERVICE_PHONG_KHAM = 2;
    const KEY_PHONG_KHAM_INDEX = 'phong-kham.index';
    
    public function getModel()
    {
        return AuthPermissions::class;
    }
    
    
    public function findPermission( $benhVienId, $khoaId, $maNhomPhong, $userId, $uri, $policiId) {
        $query = $this->model->leftJoin('auth_groups_has_permissions', 'auth_permissions.id', '=', 'auth_groups_has_permissions.permission_id');
        $where = [
                ['auth_permissions.policy_id', '=', $policiId],
                ['auth_permissions.benh_vien_id', '=', $benhVienId]
            ];
        if ($khoaId === null) {
            $query->whereNull('khoa');
        } else {
            $query->where('khoa',$khoaId);
        }
        if ($maNhomPhong === null) {
            $query->whereNull('ma_nhom_phong');
        } else {
            $query->where('ma_nhom_phong', $maNhomPhong);
        }  
            
        $permission = $query->where($where)->get()->toArray();// $query->where($where)->toSql();
        
        //var_dump($permission);die;
        return $permission;
    }
    
    public function getAllByHospitalAndPolicies(int $benhVienId, array $policieIds):array{
        return $model->whereIn('policy_id',$policieIds)>where('benh_vien_id',$benhVienId)->get()->toArray();
    }
    
    public function getPartial($limit = 100, $page = 1, $keywords='', $serviceId='')
    {
        $offset = ($page - 1) * $limit;

        $model = $this->model;
        
        if($keywords!=''){
            $model = $model->whereRaw('LOWER(auth_permissions.name) LIKE ? ',['%'.strtolower($keywords).'%']);
        }
        
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
    
    public function checkData($input)
    {
        $where=[
            ['policy_id','=',$input['policy_id']],
            ['benh_vien_id','=',$input['benh_vien_id']]
            ];
        if(array_key_exists('id',$input)) {
            $where[]=['id','!=',$input['id']];
        }            
        if(array_key_exists('khoa',$input)) {
            $where[]=['khoa','=',$input['khoa']];
        }
        if(array_key_exists('ma_nhom_phong',$input)) {
            $where[]=['ma_nhom_phong','=',$input['ma_nhom_phong']];
        }        
        $data = $this->model->where($where)->first();
        
        if($data)
            return true;
        else
            return false;
    }     
    
    public function getAllPermissionAndServiceByUserId($listGroupId) {
        $column = [
            'auth_service.name as service_name',
            'auth_service.display_name',
        ];
        
        $data = $this->model
            ->join('auth_groups_has_permissions as t1', function($join) use ($listGroupId) {
                $join->on('t1.permission_id', '=', 'auth_permissions.id')
                    ->whereIn('t1.group_id', $listGroupId);
            })
            ->leftJoin('auth_service', 'auth_service.id', '=', 'auth_permissions.service_id')
            ->distinct()
            ->get($column);
            
        return $data;
    }
    
    public function getMaNhomPhongByUserId($listGroupId) {
        $column = [
            'auth_permissions.ma_nhom_phong',
        ];
        
        $data = $this->model
            ->join('auth_groups_has_permissions as t1', function($join) use ($listGroupId) {
                $join->on('t1.permission_id', '=', 'auth_permissions.id')
                    ->whereIn('t1.group_id', $listGroupId);
            })
            ->where('auth_permissions.service_id', self::SERVICE_PHONG_KHAM)
            ->where('auth_permissions.key', self::KEY_PHONG_KHAM_INDEX)
            ->get($column);
            
        return $data;
    }
}