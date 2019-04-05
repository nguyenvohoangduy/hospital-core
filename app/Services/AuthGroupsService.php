<?php

namespace App\Services;

use App\Repositories\Auth\AuthGroupsRepository;
use App\Repositories\Auth\AuthGroupsHasRolesRepository;
use App\Repositories\Auth\AuthGroupsHasPermissionsRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class AuthGroupsService
{
    public function __construct(
        AuthGroupsRepository $authGroupsRepository,
        AuthGroupsHasRolesRepository $authGroupsHasRolesRepository,
        AuthGroupsHasPermissionsRepository $authGroupsHasPermissionsRepository
        )
    {
        $this->authGroupsRepository = $authGroupsRepository;
        $this->authGroupsHasRolesRepository = $authGroupsHasRolesRepository; 
        $this->authGroupsHasPermissionsRepository = $authGroupsHasPermissionsRepository; 
    }
    
    public function getListAuthGroups($limit, $page, $keyWords, $benhVienId)
    {
        $data = $this->authGroupsRepository->getListAuthGroups($limit, $page, $keyWords, $benhVienId);
        return $data;
    }
    
    public function getByListId($limit,$page,$id)
    {
        $data = $this->authGroupsRepository->getByListId($limit,$page,$id);
        return $data;
    }
    
    public function createAuthGroups(array $input)
    {
        DB::transaction(function () use ($input) {
            $authGroupsParams = $input;
            unset($authGroupsParams['permission_id']);
            
            $id = $this->authGroupsRepository->createAuthGroups($authGroupsParams);
            
            $groupsHasPermissionsParams['group_id'] = $id;
            foreach($input['permission_id'] as $item) {
                $groupsHasPermissionsParams['permission_id'] = $item;
                $this->authGroupsHasPermissionsRepository->create($groupsHasPermissionsParams);
            }
            return $id;
        });
    }
    
    public function getAuthGroupsById($id)
    {
        $data = $this->authGroupsRepository->getAuthGroupsById($id);
        if(count($data)>0) {
            $permissionId = [];
            foreach($data as $item) {
                $permissionId[] = $item->permission_id;
            }
            $result = $data[0];
            $result->permission_id = $permissionId;
            return $result;
        }
        else
            return $data;
    }
    
    public function updateAuthGroups($id,array $input)
    {
        DB::transaction(function () use ($id,$input) {
            $authGroupsParams = $input;
            $authGroupsParams['description']=$input['ghi_chu'];
            unset($authGroupsParams['permission_id']);

            $this->authGroupsRepository->updateAuthGroups($id, $authGroupsParams);
            
            $this->authGroupsHasPermissionsRepository->deleteByGroupId($id);
            $groupsHasPermissionsParams['group_id'] = $id;
            foreach($input['permission_id'] as $item) {
                $groupsHasPermissionsParams['permission_id'] = $item;

                $this->authGroupsHasPermissionsRepository->create($groupsHasPermissionsParams);
                //$this->authGroupsHasRolesRepository->updateAuthGroupsHasRoles($id, $input['rolesSelected']);
            }
        });        
    }
    
    public function getKhoaPhongByGroupsId($id,$benhVienId)
    {
        $data = $this->authGroupsRepository->getKhoaPhongByGroupsId($id,$benhVienId);
        return $data;
    }
}