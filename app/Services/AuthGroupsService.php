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
            
            $groupsHasPermissionsParams['permission_id'] = json_encode($input['permission_id']);
            $groupsHasPermissionsParams['group_id'] = $id;
            $this->authGroupsHasPermissionsRepository->create($groupsHasPermissionsParams);
            return $id;
        });
    }
    
    public function getAuthGroupsById($id)
    {
        $data = $this->authGroupsRepository->getAuthGroupsById($id);
        return $data;
    }
    
    public function updateAuthGroups($id,array $input)
    {
        DB::transaction(function () use ($id,$input) {
            $authGroupsParams = $input;
            $authGroupsParams['description']=$input['ghi_chu'];
            unset($authGroupsParams['permission_id']);

            $this->authGroupsRepository->updateAuthGroups($id, $authGroupsParams);
            
            $groupsHasPermissionsParams['permission_id'] = json_encode($input['permission_id']);

            $this->authGroupsHasPermissionsRepository->update($id,$groupsHasPermissionsParams);
            //$this->authGroupsHasRolesRepository->updateAuthGroupsHasRoles($id, $input['rolesSelected']);
        });        
    }
    
    public function getKhoaPhongByGroupsId($id,$benhVienId)
    {
        $data = $this->authGroupsRepository->getKhoaPhongByGroupsId($id,$benhVienId);
        return $data;
    }    
}