<?php
namespace App\Services;
use App\Repositories\Auth\AuthPermissionsRepository;
use App\Repositories\Auth\AuthUsersGroupsRepository;
use App\Repositories\Kho\KhoRepository;
use Illuminate\Http\Request;
use Validator;
class AuthPermissionsService {
    public function __construct(
        AuthPermissionsRepository $authPermissionsRepository,
        AuthUsersGroupsRepository $authUsersGroupsRepository,
        KhoRepository $khoRepository
    )
    {
        $this->authPermissionsRepository = $authPermissionsRepository;
        $this->authUsersGroupsRepository = $authUsersGroupsRepository;
        $this->khoRepository = $khoRepository;
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
    
    public function getAllPermissionByUserId($userId,$benhVienId) {
        $listGroupId = $this->authUsersGroupsRepository->getListGroupByUserId($userId);
        $listGroup = [];
        foreach($listGroupId as $item) {
            $listGroup[] = $item['group_id'];
        }
        $data = $this->authPermissionsRepository->getAllPermissionByUserId($listGroup,$benhVienId);
        return $data;
    }
    
    public function getKhoByUrl($url)
        {
            $data = $this->authPermissionsRepository->getKhoByUrl($url);
            $arrKhoId = [];
            if(!empty($data)){
                foreach($data as $item){
                    $arr = json_decode($item->kho);
                    foreach($arr as $itemArr){
                        $arrKhoId[]=$itemArr;
                    }
                }
            }
            $result = [];
            $arrUnique = array_values(array_unique($arrKhoId));
            foreach($arrUnique as $item){
                $dataKho = $this->khoRepository->getKhoById($item);
                if($dataKho){
                    $result[]=$dataKho;
                }
            }
            return $result;
        }    
}