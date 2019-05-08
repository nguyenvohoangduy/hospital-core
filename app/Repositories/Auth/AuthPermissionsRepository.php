<?php
namespace App\Repositories\Auth;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Auth\AuthPermissions;

class AuthPermissionsRepository extends BaseRepositoryV2
{
    const SERVICE_PHONG_KHAM = 2;
    const SERVICE_NOI_TRU = 3;
    const SERVICE_THUOC_VAT_TU = 4;
    
    const KEY_PHONG_KHAM_INDEX = 'phong-kham.index';
    const KEY_NOI_TRU_INDEX = 'noi-tru.index';
    const KEY_THUOC_VAT_TU_INDEX = 'thuoc-vat-tu.index';
    
    public function getModel()
    {
        return AuthPermissions::class;
    }
    
    
    public function findPermission( $benhVienId, $khoaId, $maNhomPhong, $listGroupId, $uri, $policyId) {
        $where = [
            ['auth_permissions.policy_id', '=', $policyId],
            ['auth_permissions.benh_vien_id', '=', $benhVienId]
        ];
        
        $model = $this->model->where($where);
        
        if (!is_null($khoaId)) {
            $model->where('khoa', $khoaId);
        }
        
        if (!is_null($maNhomPhong)) {
            $model->where('ma_nhom_phong', 'like', '%' . $maNhomPhong . '%');
        }
        
        $permission = $model->join('auth_groups_has_permissions as t1', function($join) use ($listGroupId) {
                $join->on('t1.permission_id', '=', 'auth_permissions.id')
                    ->whereIn('t1.group_id', $listGroupId);
        })->get()->toArray();

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
        if(array_key_exists('kho',$input)) {
            $where[]=['kho','=',$input['kho']];
        }        
        $data = $this->model->where($where)->first();
        
        if($data)
            return true;
        else
            return false;
    }     
    
    public function getAllPermissionByUserId($listGroupId,$benhVienId) {
        $column=[
            'auth_permissions.*',
            'auth_service.*',
            'auth_policy.url',
            'auth_policy.name as policy_name'
            ];
        $data = $this->model
            ->where('auth_permissions.benh_vien_id',$benhVienId)
            ->join('auth_groups_has_permissions as t1', function($join) use ($listGroupId) {
                $join->on('t1.permission_id', '=', 'auth_permissions.id')
                    ->whereIn('t1.group_id', $listGroupId);
            })
            ->leftJoin('auth_service', 'auth_service.id', '=', 'auth_permissions.service_id')
            ->leftJoin('auth_policy', 'auth_policy.id', '=', 'auth_permissions.policy_id')
            ->get($column);
        return $data;
    }
    
    public function getKhoByUrl($url)
    {
        $data = $this->model
            ->where('key','LIKE','%'.$url.'%')
            ->whereNotNull('kho')
            ->get();
        return $data;
    }    
}