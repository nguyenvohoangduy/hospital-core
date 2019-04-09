<?php

namespace App\Services;

use App\Http\Resources\BenhVienResource;
use App\Repositories\Auth\AuthUsersRepository;
use App\Repositories\Auth\AuthUsersGroupsRepository;
use App\Repositories\Auth\AuthGroupsHasRolesRepository;
use App\Repositories\Auth\AuthServiceRepository;
use App\Repositories\Auth\AuthPolicyRepository;
use App\Repositories\Auth\AuthPermissionsRepository;
use App\Repositories\Auth\AuthGroupsHasPermissionsRepository;
use App\Http\Resources\AuthResource;
use Illuminate\Http\Request;
use Validator;

class AuthService {
    
    private $benhVienId = null;
    private $khoaId = null;
    private $maNhomPhong = null;
    
    public function __construct(
        AuthUsersRepository $authUsersRepository, 
        AuthUsersGroupsRepository $authUsersGroupsRepository,
        AuthGroupsHasRolesRepository $authGroupsHasRolesRepository,
        AuthServiceRepository $authServiceRepository,
        AuthPolicyRepository $authPolicyRepository,
        AuthPermissionsRepository $authPermissionsRepository
    )
    {
        $this->authUsersRepository = $authUsersRepository;
        $this->authUsersGroupsRepository = $authUsersGroupsRepository;
        $this->authGroupsHasRolesRepository = $authGroupsHasRolesRepository;
        $this->authServiceRepository = $authServiceRepository;
        $this->authPolicyRepository = $authPolicyRepository;
        $this->authPermissionsRepository = $authPermissionsRepository;
    }
    
    public function setBenhVienId($benhVienId) {
        $this->benhVienId = $benhVienId;
        return $this;
    }
    
    public function setKhoaId($khoaId) {
        $this->khoaId = $khoaId;
        return $this;
    }
    
    public function setMaNhomPhong($maNhomPhong) {
        $this->maNhomPhong = $maNhomPhong;
        return $this;
    }

    public function getUserRolesByEmail($email)
    {
        $id = $this->authUsersRepository->getIdbyEmail($email);
        $idGroup = $this->authUsersGroupsRepository->getIdGroupbyId($id->id);
        $roles = $this->authGroupsHasRolesRepository->getRolesbyIdGroup($idGroup);
        $data = [
            'roles' => $roles,
            'idGroup' => $idGroup
        ];
        return $data;
    }
    
    public function getUserNameByEmail($email)
    {
        $userName = $this->authUsersRepository->getUserNameByEmail($email);
        return $userName;
    }
    
    public function getUserById($authUsersId)
    {
        $bool = $this->authUsersRepository->getUserById($authUsersId);
        return $bool;
    }
    
    public function getKhoaPhongId($id,$benhVienId)
    {
        $khoaPhong = $this->authUsersGroupsRepository->getKhoaPhongByUserId($id,$benhVienId);
        return $khoaPhong;
    }
    
    public function updateLastVisit($email)
    {
        $this->authUsersRepository->updateLastVisit($email);
    }
    
    public function getAll()
    {
        $data = $this->authServiceRepository->getAll();
        return $data;
    }
    
    public function authorize($userId, \Illuminate\Routing\Route $route, $policyId ):bool {
        /*
        select t1.* from auth_permissions t1 inner join auth_groups_has_permissions t2 on t1.id = t2.permission_id
          where 
            group_id in (select group_id from auth_users_groups where user_id=<user_id>)
          and
            policy_id = <policy_id>
          and 
            benh_vien_id = <benh_vien_id>
          ...    
        */
        //var_dump($policyId);die();
        $authPermission = $this->authPermissionsRepository->findPermission($this->benhVienId,$this->khoaId,$this->maNhomPhong,$userId,$route->uri(),$policyId);
        return !empty($authPermission);
    }
    
    public function matchPolicyByUri(\Illuminate\Routing\Route $route):array {
        //var_dump($route->uri());die();
        return $this->authPolicyRepository->getByUri($route->uri());
    }
    
}