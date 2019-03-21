<?php
namespace App\Http\Controllers\Api\V1\Hsba;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\V1\APIController;
use App\Services\HsbaDonViService;


// 3rd party library
use Carbon\Carbon;

class HsbaDonViController extends APIController
{
    public function __construct(HsbaDonViService $service)
    {
        $this->service = $service;
    }
    
    public function index(Request $request)
    {
        //$data = $this->service->getDataPatient($request);
        
        //return $data;
    }
    
    public function getListKhoaKhamBenh($benhVienId, Request $request)
    {
        // main params
        $limit = $request->query('limit', 20);
        $page = $request->query('page', 1);
        $phongId = $request->query('phongId', null);

        // optional params        
        $thoiGianVaoVienFrom = $request->query('thoi_gian_vao_vien_from',null);
        $thoiGianVaoVienTo = $request->query('thoi_gian_vao_vien_to',null);
        $thoiGianRaVienFrom = $request->query('thoi_gian_ra_vien_from',null);
        $thoiGianRaVienTo = $request->query('thoi_gian_ra_vien_to',null);
        $keyword = $request->query('keyword', '');
        $status = $request->query('status', 0);
        $loaiBenhAn = $request->query('loaiBenhAn', null);
        
        $options = [
            'keyword'                   => $keyword,
            'status_hsba_khoa_phong'    => $status,
            'loai_benh_an'              => $loaiBenhAn,
            'thoi_gian_vao_vien_from'   => $thoiGianVaoVienFrom,
            'thoi_gian_vao_vien_to'     => $thoiGianVaoVienTo,
            'thoi_gian_ra_vien_from'    => $thoiGianRaVienFrom,
            'thoi_gian_ra_vien_to'      => $thoiGianRaVienTo,
        ];
        
        if($benhVienId === null){
            $this->setStatusCode(400);
            return $this->respond([]);
        }
        
        try 
        {
            $listBenhNhan = $this->service->getListKhoaKhamBenh($benhVienId,$phongId, $limit, $page, $options);
            $this->setStatusCode(200);
            return $this->respond($listBenhNhan);
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getFile().":".$ex->getLine()."::".$ex->getMessage());
        }
        
        return $this->respond($listBenhNhan);
    }
}
