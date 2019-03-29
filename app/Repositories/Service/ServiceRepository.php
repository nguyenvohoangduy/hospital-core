<?php
namespace App\Repositories\Service;

use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Service;

class ServiceRepository extends BaseRepositoryV2
{

    public function getModel()
    {
        return Service::class;
    }    
    
    public function getAll()
    {
        $data = $this->model->all();
        return $data;    
        
    }
}
