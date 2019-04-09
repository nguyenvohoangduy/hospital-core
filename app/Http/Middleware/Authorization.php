<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use Closure;
// Service
use App\Services\AuthService;

class Authorization
{
    
    public function __construct(AuthService $authService)
      {
        $this->authService = $authService;
      }
      
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $route = Route::getCurrentRoute();
        $authService=$this->authService;
        //print_r($matchPolicyId);die;
        $matchPolicy = $this->authService->matchPolicyByUri($route);
        //print_r($matchPolicy);die;
        if (empty($matchPolicy)) {
            return $next($request);
        }
        if (!Auth::user() || !$request->header('X-RED-HID')  ) {
            return response('Unauthorized..', 401);
        } 
        else {
            $group = [];
            $benhVienId = $request->header('X-RED-HID');
            $khoaId = $request->header('X-RED-GID',null);
            $maNhomPhong = $request->header('X-RED-DCODE',null);
            $matchPolicyId = $matchPolicy['id'];
            $authService->setBenhVienId($benhVienId)
                        ->setKhoaId($khoaId)
                        ->setMaNhomPhong($maNhomPhong);
            $isAuthorized= $authService->authorize(Auth::user()->ids, $route, $matchPolicyId);
            
            /*
            $routeCollection = Route::getRoutes();
            $routesWithName  =[];
            foreach ($routeCollection as $route) {
                //$routesWithName[]= $route->getName();  
                if($route->getName() != 'v1.' && $route->getName()!= NULL) {
                    //echo $route->getName();
                    $routesWithName[]= $route->getName();  
                } 
            }   
            
            var_dump($routesWithName);
            //var_dump(get_class(Auth::user()));
            $user = User::find(Auth::user()->id);
            */
            if ($isAuthorized){
                return $next($request);
            }
        }
        return response('Unauthorized.', 401);
        
    }
    
    
    
}
