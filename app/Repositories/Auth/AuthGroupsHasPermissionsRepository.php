<?php
namespace App\Repositories\Auth;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Auth\AuthGroupsHasPermissions;

class AuthGroupsHasPermissionsRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return AuthGroupsHasPermissions::class;
    }    

     public function create(array $input)
    {
        $this->model->create($input);
    }
    
     public function update($groupId,array $input)
    {
        $find = $this->model->where('group_id',$groupId)->first();
        if($find) {
		    $find->update($input);
        }
    }    
}