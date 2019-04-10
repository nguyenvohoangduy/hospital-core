<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use Closure;
// Service
use App\Services\AuthService;

class Authorization
{
    const HTTP_HEADER_BENH_VIEN_ID = 'X-RED-HID';
    const HTTP_HEADER_KHOA_ID = 'X-RED-GID';
    const HTTP_HEADER_MA_NHOM_PHONG = 'X-RED-DCODE';
    
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
        if (!Auth::user() || !$request->header(self::HTTP_HEADER_BENH_VIEN_ID)  ) {
            return response('Unauthorized..', 401);
        } 
        else {
            $group = [];
            $benhVienId = $request->header(self::HTTP_HEADER_BENH_VIEN_ID);
            $khoaId = $request->header(self::HTTP_HEADER_KHOA_ID, null);
            $maNhomPhong = $request->header(self::HTTP_HEADER_MA_NHOM_PHONG, null);
            $matchPolicyId = $matchPolicy['id'];
            $authService->setBenhVienId($benhVienId)
                        ->setKhoaId($khoaId)
                        ->setMaNhomPhong($maNhomPhong);
            $isAuthorized= $authService->authorize(Auth::user()->ids, $route, $matchPolicyId);
            
            if ($isAuthorized){
                return $next($request);
            }
        }
        return response('Unauthorized.', 401);
        
    }
    
    
    
}
