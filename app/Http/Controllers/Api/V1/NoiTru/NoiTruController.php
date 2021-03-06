<?php
namespace App\Http\Controllers\Api\V1\NoiTru;

use Illuminate\Http\Request;
use App\Services\HsbaDonViService;
use App\Services\PhieuChamSocService;
use App\Services\VienPhiService;
use App\Services\YLenhService;
use App\Services\ChiTietPhieuKhoService;
use App\Services\DieuTriService;
use App\Http\Controllers\Api\V1\APIController;
use Carbon\Carbon;

class NoiTruController extends APIController {
    public function __construct(
        HsbaDonViService $hsbaDonViService,
        VienPhiService $vienPhiService,
        YLenhService $yLenhService,
        ChiTietPhieuKhoService $chiTietPhieuKhoService,
        DieuTriService $dieuTriService
    )
    {
        $this->dieuTriService = $dieuTriService;
        $this->hsbaDonViService = $hsbaDonViService;
        $this->vienPhiService = $vienPhiService;
        $this->yLenhService = $yLenhService;
        $this->chiTietPhieuKhoService = $chiTietPhieuKhoService;
    }
    
    public function index(Request $request)
    {
        $this->setStatusCode(200);
        return $this->respond([]);
    }    
    
    public function getListPhongNoiTru($benhVienId, Request $request)
    {
        // main params
        $limit = $request->query('limit', 20);
        $page = $request->query('page', 1);
        $phongId = $request->query('phongId', null);
        $khoaId = $request->query('khoaId', null);
        
        // optional params        
        $thoiGianRaVienFrom = $request->query('thoi_gian_vao_vien_from',null);
        $thoiGianRaVienTo = $request->query('thoi_gian_vao_vien_to',null);
        $keyword = $request->query('keyword', '');
        $status = $request->query('status', 0);
        
        $options = [
            'keyword'                   => $keyword,
            'thoi_gian_vao_vien_from'    => $thoiGianRaVienFrom,
            'thoi_gian_vao_vien_to'      => $thoiGianRaVienTo,
        ];
        
        if($benhVienId === null){
            $this->setStatusCode(400);
            return $this->respond([]);
        }
        
        try 
        {
            $listBenhNhan = $this->hsbaDonViService->getListPhongNoiTru($benhVienId, $khoaId, $phongId, $limit, $page, $options);
            $this->setStatusCode(200);
            return $this->respond($listBenhNhan);
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getFile().":".$ex->getLine()."::".$ex->getMessage());
        }
        
        return $this->respond($listBenhNhan);
    }
    
    public function getByHsbaId($hsbaId, $phongId, $benhVienId)
    {
        if(is_numeric($hsbaId)) {
            $data = $this->hsbaDonViService->getByHsbaId($hsbaId, $phongId, $benhVienId);
            if($data['ms_bhyt']) {
                $input['ms_bhyt'] = $data['ms_bhyt'];
                $input['vien_phi_id'] = $data['vien_phi_id'];
                $input['loai_vien_phi'] = $data['loai_vien_phi'];
                //cần check thời hạn thẻ BHYT
                $data['muc_huong'] = $this->vienPhiService->getMucHuong($input);
            } else {
                $data['muc_huong'] = 0;
            }
            return $this->respond($data);
        } else {
            $this->setStatusCode(400);
            return $this->respond([]);
        }
    }
    
    public function traThuoc(Request $request)
    {
        $input = $request->all();
        if(is_numeric($input['phieuYLenhId'])) {
            $data = $this->chiTietPhieuKhoService->getByPhieuYLenhId($input['phieuYLenhId']);
            $bool = $this->yLenhService->traThuoc($input, $data);
            return $this->respond($bool);
        } else {
            $this->setStatusCode(400);
            return $this->respond(false);
        }
    }     
    
    public function xuTriNoiTru(Request $request)
    {
        try 
        {
            $input = $request->all();
            $data = $this->dieuTriService->xuTriNoiTru($input);
            
            return $this->respond($data);
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getMessage());
        } catch (\Throwable  $ex) {
            return $this->respondInternalError($ex->getMessage());
        }
    }
}