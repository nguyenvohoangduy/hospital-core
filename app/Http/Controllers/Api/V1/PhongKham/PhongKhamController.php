<?php
namespace App\Http\Controllers\Api\V1\PhongKham;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\HsbaKhoaPhongService;
use App\Services\HsbaDonViService;
use App\Services\HsbaPhongKhamService;
use App\Services\SttPhongKhamService;
use App\Services\DieuTriService;
use App\Services\Icd10Service;
use App\Services\YLenhService;
use App\Services\PhacDoDieuTriService;
use App\Services\PhieuYLenhService;
use App\Services\DanhMucDichVuService;
use App\Services\DanhMucThuocVatTuService;
use App\Services\MauHoiBenhService;
use App\Services\TheKhoService;
use App\Services\ChiTietPhieuKhoService;
use App\Services\KhoService;
use Validator;
use App\Http\Requests\UploadFileFormRequest;
use App\Http\Requests\MauHoiBenhFormRequest;

class PhongKhamController extends APIController
{
    public function __construct
    (
        HsbaKhoaPhongService $hsbaKhoaPhongService, 
        HsbaDonViService $hsbaDonViService,
        HsbaPhongKhamService $hsbaPhongKhamService,
        SttPhongKhamService $sttPhongKhamService, 
        DieuTriService $dieuTriService, 
        Icd10Service $icd10Service,
        YLenhService $yLenhService,
        PhacDoDieuTriService $pddtService,
        PhieuYLenhService $phieuYLenhService,
        DanhMucDichVuService $dmdvService,
        DanhMucThuocVatTuService $dmTvtService,
        MauHoiBenhService $mauHoiBenhService,
        TheKhoService $theKhoService,
        ChiTietPhieuKhoService $chiTietPhieuKhoService,
        KhoService $khoService
    )
    {
        $this->hsbaKhoaPhongService = $hsbaKhoaPhongService;
        $this->hsbaDonViService = $hsbaDonViService;
        $this->hsbaPhongKhamService = $hsbaPhongKhamService;
        $this->sttPhongKhamService = $sttPhongKhamService;
        $this->dieuTriService = $dieuTriService;
        $this->icd10Service = $icd10Service;
        $this->yLenhService = $yLenhService;
        $this->pddtService = $pddtService;
        $this->phieuYLenhService = $phieuYLenhService;
        $this->dmdvService = $dmdvService;
        $this->dmTvtService = $dmTvtService;
        $this->mauHoiBenhService = $mauHoiBenhService;
        $this->theKhoService = $theKhoService;
        $this->chiTietPhieuKhoService = $chiTietPhieuKhoService;
        $this->khoService = $khoService;
    }
    
    public function update($hsbaDonViId, Request $request)
    {
        try {
            $isNumeric = is_numeric($hsbaDonViId);
            
            if($isNumeric) {
                $input = $request->all();
                
                $data = $this->hsbaDonViService->update($hsbaDonViId, $input);
                if($data['status'] === 'error') {
                    $this->setStatusCode($data['statusCode']);
                }
                return $this->respond($data);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getMessage());
        }
    }
    
    public function getById($hsbaKhoaPhongId)
    {
        $isNumeric = is_numeric($hsbaKhoaPhongId);
        
        if($isNumeric) {
            $data = $this->hsbaKhoaPhongService->getById($hsbaKhoaPhongId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function updateInfoDieuTri(UploadFileFormRequest $request)
    {
        try 
        {
            $input = $request->all();
            $input = $request->except('bmi');
            $this->dieuTriService->updateInfoDieuTri($input);
            $this->setStatusCode(201);
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getMessage());
        } catch (\Throwable  $ex) {
            return $this->respondInternalError($ex->getMessage());
        }
    }
  
    public function getListPhongKham($hsbaId)
    {
        $isNumeric = is_numeric($hsbaId);
        
        if($isNumeric) {
            $data = $this->sttPhongKhamService->getListPhongKham($hsbaId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function xuTriBenhNhan(Request $request)
    {
        try 
        {
            $input = $request->all();
            $data = $this->dieuTriService->xuTriBenhNhan($input);
            
            return $this->respond($data);
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getMessage());
        } catch (\Throwable  $ex) {
            return $this->respondInternalError($ex->getMessage());
        }
    }
    
    public function chuyenKhoaPhong(Request $request)
    {   
        $input = $request->all();
        $data = $this->dieuTriService->createChuyenPhong($input);
        
        if(!$data) {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function getIcd10ByCode($icd10Code)
    {
        $data = $this->icd10Service->getIcd10ByCode($icd10Code);
        
        if(!$data) {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function saveYLenh(Request $request)
    {
        try 
        {
            $input = $request->all();
            
            if($input['data']) {
                // $input['dieu_tri_id'] = $phieuDieuTri->id;
                $bool = $this->yLenhService->saveYLenh($input);
                
                if($bool) {
                    $this->setStatusCode(201);
                } else {
                    $this->setStatusCode(400);
                }
            
                return $this->respond($bool);
            } else {
                $this->setStatusCode(400);
                return $this->respond([]);
            }
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getMessage());
        } catch (\Throwable  $ex) {
            return $this->respondInternalError($ex->getMessage());
        }
    }
    
    public function saveThuocVatTu(Request $request)
    {
        try {
            $input = $request->all();
            $result = [];
            $flag = false;
            
            if($input['data']) {
                foreach($input['data'] as $item) {
                    $quantity = $this->theKhoService->getTonKhaDungById($item['id'], $item['kho_id']);
                    if($quantity['so_luong_kha_dung'] < $item['so_luong']) {
                        $flag = true;
                        $result[] = [
                            'id'            => $item['id'],
                            'ten'           => $item['ten'],
                            'so_luong'      => $quantity['so_luong_kha_dung'],
                            'don_vi_tinh'   => $item['don_vi_tinh']
                        ];
                    }
                }
                
                if($flag) {
                    return $this->respond($result);
                } else {
                    $bool = $this->yLenhService->saveThuocVatTu($input);
                    if($bool) {
                        $this->setStatusCode(201);
                    } else {
                        $this->setStatusCode(400);
                    }
                    return $this->respond([]);
                }
            } else {
                $this->setStatusCode(400);
                return $this->respond([]);
            }    
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getMessage());
        } catch (\Throwable  $ex) {
            return $this->respondInternalError($ex->getMessage());
        } 
    }
    
    public function getLichSuYLenh(Request $request)
    {
        $input = $request->all();
        if(!$input['dieu_tri_id'])
            $input['dieu_tri_id'] = $this->dieuTriService->getPhieuDieuTri($input);
            
        $data = $this->yLenhService->getLichSuYLenh($input);
        if(!$data) {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function getLichSuThuocVatTu(Request $request)
    {
        $input = $request->all();
        if(!$input['dieu_tri_id'])
            $input['dieu_tri_id'] = $this->dieuTriService->getPhieuDieuTri($input);
            
        $data = $this->yLenhService->getLichSuThuocVatTu($input);
        if(!$data) {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function batDauKham($hsbaDonViId)
    {
        if(is_numeric($hsbaDonViId)) {
            $this->hsbaDonViService->batDauKham($hsbaDonViId);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    
    public function getPddtByIcd10Code($icd10Code)
    {
        $data = $this->pddtService->getPddtByIcd10Code($icd10Code);
        
        if(!$data) {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function getListPhieuYLenh($hsbaId,$type)
    {
        $isNumeric = is_numeric($hsbaId);

        if($isNumeric) {
            $data = $this->phieuYLenhService->getListPhieuYLenh($hsbaId,$type);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }

    public function getDetailPhieuYLenh($phieuYLenhId,$type)
    {
        $isNumeric = is_numeric($phieuYLenhId);
        $typeIsNumeric = is_numeric($type);
        
        if($isNumeric && $typeIsNumeric) {
            $data = $this->yLenhService->getDetailPhieuYLenh($phieuYLenhId,$type);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }    
    
    public function updateHsbaPhongKham($hsbaDonViId, UploadFileFormRequest $request)
    {
        try {
            $isNumeric = is_numeric($hsbaDonViId);
            
            if($isNumeric) {
                $input = $request->all();
                $data = $this->hsbaPhongKhamService->update($hsbaDonViId, $input);
                if($data['status'] === 'error') {
                    $this->setStatusCode($data['statusCode']);
                }
                return $this->respond($data);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $this->respondInternalError($ex->getMessage());
        } catch (\Throwable  $ex) {
            return $this->respondInternalError($ex->getMessage());
        }
    }
    
    public function getDetailHsbaPhongKham($hsbaId, $phongId) {
        $isNumeric = is_numeric($hsbaId);
        $phongIsNumeric = is_numeric($phongId);
        
        if($isNumeric && $phongIsNumeric) {
            $data = $this->hsbaPhongKhamService->getDetailHsbaPhongKham($hsbaId, $phongId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
      
        return $this->respond($data);
    }
  
    public function countItemYLenh($hsbaId)
    {
        $isNumeric = is_numeric($hsbaId);
           
        if($isNumeric) {
            $data = $this->yLenhService->countItemYLenh($hsbaId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
            
        return $this->respond($data);
    }
    
    public function countItemThuocVatTu($hsbaId)
    {
        $isNumeric = is_numeric($hsbaId);
           
        if($isNumeric) {
            $data = $this->yLenhService->countItemThuocVatTu($hsbaId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
            
        return $this->respond($data);
    }
    
    public function countItemTheKho($phieuYLenhId)
    {
        $isNumeric = is_numeric($phieuYLenhId);
           
        if($isNumeric) {
            $data = $this->chiTietPhieuKhoService->countItemTheKho($phieuYLenhId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
            
        return $this->respond($data);
    }
    
    public function searchIcd10Code($icd10Code)
    {
        if($icd10Code) {
            $data = $this->icd10Service->searchIcd10Code($icd10Code);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function searchIcd10Text($icd10Text)
    {
        if($icd10Text) {
            $data = $this->icd10Service->searchIcd10Text($icd10Text);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function getListHsbaPhongKham($hsbaId)
    {
        $isNumeric = is_numeric($hsbaId);
        
        if($isNumeric) {
            $data = $this->hsbaPhongKhamService->getListHsbaPhongKham($hsbaId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function getAllCanLamSang($hsbaId)
    {
        $isNumeric = is_numeric($hsbaId);

        if($isNumeric) {
            $data = $this->yLenhService->getAllCanLamSang($hsbaId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    } 
    
    public function searchListIcd10ByCode($icd10Code)
    {
        if($icd10Code) {
            $data = $this->icd10Service->searchListIcd10ByCode($icd10Code);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function searchThuocVatTuByTenVaHoatChat($keyword)
    {
        if($keyword) {
            $data = $this->khoService->searchThuocVatTuByTenVaHoatChat($keyword);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    } 
    
    public function createMauHoiBenh(Request $request) {
        $input = $request->all();
        $this->mauHoiBenhService->create($input);
    }
    
    public function getMauHoiBenhByChucNangAndUserId($chucNang, $userId)
    {
        $isNumeric = is_numeric($userId);

        if($isNumeric) {
            $data = $this->mauHoiBenhService->getMauHoiBenhByChucNangAndUserId($chucNang, $userId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function getMauHoiBenhById($id, $chucNang)
    {
        $isNumeric = is_numeric($id);

        if($isNumeric) {
            $data = $this->mauHoiBenhService->getById($id, $chucNang);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }

        return $this->respond($data);
    }
    public function searchThuocVatTuByKhoId($khoId, $keyword)
    {
        if($keyword) {
            $data = $this->khoService->searchThuocVatTuByKhoId($khoId, $keyword);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function index(Request $request)
    {
        return response(200);
    }     
    
    public function getReportPdf()
    {
        $data = [];
        $url='https://s3-us-west-2.amazonaws.com/hospital-79488/storage/dang-ky-kham-benh/local/2019/04/12/chuky1.jpg';
        $b64image = "data:image/jpg;base64,".base64_encode(file_get_contents($url));
        $data['ho_ten'] = 'nguyễn văn a';
        $data['chu_ky'] = $b64image;
        return $data;
    }  
}