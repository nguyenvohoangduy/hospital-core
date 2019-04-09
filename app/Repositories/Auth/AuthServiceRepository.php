<?php
namespace App\Repositories\Auth;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Auth\AuthService;

class AuthServiceRepository extends BaseRepositoryV2
{

    public function getModel()
    {
        return AuthService::class;
    }    
    
    public function getAll()
    {
        $data = $this->model->all();
        return $data;    
        
    }
}
