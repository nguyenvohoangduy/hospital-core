<?php
namespace App\Services;
use App\Repositories\Auth\AuthPermissionsRepository;
use App\Repositories\Auth\AuthUsersGroupsRepository;
use Illuminate\Http\Request;
use Validator;
class AuthPermissionsService {
    public function __construct(
        AuthPermissionsRepository $authPermissionsRepository,
        AuthUsersGroupsRepository $authUsersGroupsRepository
    )
    {
        $this->authPermissionsRepository = $authPermissionsRepository;
        $this->authUsersGroupsRepository = $authUsersGroupsRepository;
    }
    
    public function getPartial($limit, $page, $keywords, $serviceId)
    {
        $data = $this->authPermissionsRepository->getPartial($limit, $page, $keywords, $serviceId);
        return $data;
    }
    
    public function create(array $input)
    {
        $id = $this->authPermissionsRepository->create($input);
        return $id;
    } 
    
    public function update($id, array $input)
    {
        $this->authPermissionsRepository->update($id, $input);
    }
    
    public function getById($id)
    {
        $data = $this->authPermissionsRepository->getById($id);
        return $data;
    }
    
    public function getAllPermission()
    {
        $data = $this->authPermissionsRepository->getAllPermission();
        return $data;
    }
    
    public function getAllPermissionByBenhVienId(int $benhVienId)
    {
        $data = $this->authPermissionsRepository->getAllPermissionByBenhVienId($benhVienId);
        return $data;
    }
    
    public function checkData($input)
    {
        $status = $this->authPermissionsRepository->checkData($input);
        return $status;
    }    
    
    public function getAllPermissionAndServiceByUserId($userId) {
        $listGroupId = $this->authUsersGroupsRepository->getListGroupByUserId($userId);
        $listGroup = [];
        foreach($listGroupId as $item) {
            $listGroup[] = $item['group_id'];
        }
        $data = $this->authPermissionsRepository->getAllPermissionAndServiceByUserId($listGroup);
        return $data;
    }
}