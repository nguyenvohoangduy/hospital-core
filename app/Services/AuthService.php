<?php

namespace App\Services;

use App\Http\Resources\BenhVienResource;
use App\Repositories\Auth\AuthUsersRepository;
use App\Repositories\Auth\AuthUsersGroupsRepository;
use App\Repositories\Auth\AuthGroupsHasRolesRepository;
use App\Repositories\Auth\AuthServiceRepository;
use App\Http\Resources\AuthResource;
use Illuminate\Http\Request;
use Validator;

class AuthService {
    public function __construct(
        AuthUsersRepository $authUsersRepository, 
        AuthUsersGroupsRepository $authUsersGroupsRepository,
        AuthGroupsHasRolesRepository $authGroupsHasRolesRepository,
        AuthServiceRepository $authServiceRepository)
    {
        $this->authUsersRepository = $authUsersRepository;
        $this->authUsersGroupsRepository = $authUsersGroupsRepository;
        $this->authGroupsHasRolesRepository = $authGroupsHasRolesRepository;
        $this->authServiceRepository = $authServiceRepository;
    }

    public function getUserRolesByEmail($email)
    {
        $id = $this->authUsersRepository->getIdByEmail($email);
        $idGroup = $this->authUsersGroupsRepository->getIdGroupById($id->id);
        $roles = $this->authGroupsHasRolesRepository->getRolesByIdGroup($idGroup);
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
}