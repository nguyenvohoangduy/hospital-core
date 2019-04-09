<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use Closure;
// Service 
use App\Service\Auth\AuthService;

class Authorization
{
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
        $matchPolicyIds = $authService->matchPolicyByUri($route);
        if (empty($matchPolicyIds)) {
            return $next($request);
        }
        if (!Auth::user() || !$request->header('X-RED-HID') || !is_int($request->header('X-RED-HID')) ) {
            return response('Unauthorized.', 401);
        } 
        else {
            $group = [];
            $benhVienId = $request->header('X-RED-HID');
            $benhVienId = $request->header('X-RED-HID');
            $authService = new AuthService();
            $authService->authorize($benhVienId, Auth::user(), $route, $matchPolicyIds);
            
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
            return $next($request);
        }
        return response('Unauthorized.', 401);
        
    }
    
    
    
}
