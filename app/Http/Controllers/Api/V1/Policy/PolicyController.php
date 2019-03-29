<?php
namespace App\Http\Controllers\Api\V1\Policy;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\PolicyService;
use App\Services\Service;
use App\Http\Requests\CreatePolicyFormRequest;
use App\Http\Requests\UpdatePolicyFormRequest;
use Illuminate\Support\Facades\Route;

class PolicyController extends APIController
{
    public function __construct(PolicyService $policyService,Service $service)
    {
        $this->policyService = $policyService;
        $this->service = $service;
    }
    
    public function getPartial(Request $request)
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        $keywords = $request->query('keywords', '');
        $serviceId = $request->query('serviceId', '');
        $data = $this->policyService->getPartial($limit, $page, $keywords, $serviceId);
        return $this->respond($data);
    }
    
    public function create(CreatePolicyFormRequest $request)
    {
        $input = $request->all();
        
        $id = $this->policyService->create($input);
        if($id) {
            $this->setStatusCode(201);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    
    public function update($id,UpdatePolicyFormRequest $request)
    {
        try {
            $isNumericId = is_numeric($id);
            $input = $request->all();
            
            if($isNumericId) {
                $this->policyService->update($id, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }    
    
    public function delete($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $this->policyService->delete($id);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);        
    }
    
    public function getById($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $data = $this->policyService->getById($id);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function getAllService()
    {
        $data = $this->service->getAll();   
        return $this->respond($data);
    } 
    
    public function getRoute($serviceName)
    {
        $routeCollection = Route::getRoutes();
        $routesWithName  =[];
        foreach ($routeCollection as $route) {
            //$routesWithName[]= $route->getName();  
            if($route->getName() != 'v1.' && $route->getName()!= NULL) {
                //echo $route->getName()
                $arrRoute = explode('.',$route->getName());
                if($arrRoute[1]==$serviceName) {
                    $routesWithName[]= [
                        'route_name'  => $route->getName(),
                        'method'     => $route->methods[0]
                    ];
                }
            } 
        }
        return $routesWithName;
    }
    
    public function getAllRoute()
    {
        $routeCollection = Route::getRoutes();
        $routesWithName  =[];
        foreach ($routeCollection as $route) {
            if($route->getName() != 'v1.' && $route->getName()!= NULL) {
                $arrRoute = explode('.',$route->getName());
                $routesWithName[]= [
                    'service_name'  => $arrRoute[1],
                    'route_name'    => $route->getName(),
                    'method'        => $route->methods[0]
                ];
            } 
        }
        return $routesWithName;
    }    
    
    public function checkKey($key)
    {
        $data = $this->policyService->checkKey($key);   
        return $this->respond($data);
    }     
}