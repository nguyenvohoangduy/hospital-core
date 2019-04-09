<?php
namespace App\Http\Controllers\Api\V1\DanhMuc;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\DanhMucDichVuService;
use App\Services\DanhMucTongHopService;
use App\Services\DanhMucTrangThaiService;
use App\Services\DanhMucThuocVatTuService;
use App\Services\NoiGioiThieuService;
use App\Services\NhomDanhMucService;
use App\Http\Requests\DanhMucDichVuFormRequest;
use App\Http\Requests\DanhMucTongHopFormRequest;
use App\Http\Requests\DanhMucTrangThaiFormRequest;
use App\Http\Requests\NoiGioiThieuFormRequest;
use App\Http\Requests\DanhMucThuocVatTuFormRequest;

class DanhMucController extends APIController
{
    public function __construct
    (
        DanhMucDichVuService $dmdvService,
        DanhMucTongHopService $dmthService, 
        DanhMucTrangThaiService $dmttService, 
        DanhMucThuocVatTuService $dmtvtService,
        NhomDanhMucService $nhomDanhMucService,
        NoiGioiThieuService $noiGioiThieuService
    )
    {
        $this->dmdvService = $dmdvService;
        $this->dmthService = $dmthService;
        $this->dmttService = $dmttService;
        $this->dmtvtService = $dmtvtService;
        $this->nhomDanhMucService = $nhomDanhMucService;
        $this->noiGioiThieuService = $noiGioiThieuService;
    }
    
    public function getListDanhMucDichVu(Request $request)
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        $loaiNhom = $request->query('loai_nhom', 0);
        
        $data = $this->dmdvService->getListDanhMucDichVu($limit, $page, $loaiNhom);
        return $this->respond($data);
    }
    
    public function getDmdvById($dmdvId)
    {
        $isNumericId = is_numeric($dmdvId);
        
        if($isNumericId) {
            $data = $this->dmdvService->getDmdvById($dmdvId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function createDanhMucDichVu(DanhMucDichVuFormRequest $request)
    {
        $input = $request->all();
        
        $id = $this->dmdvService->createDanhMucDichVu($input);
        if($id) {
            $this->setStatusCode(201);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    
    public function updateDanhMucDichVu($dmdvId, DanhMucDichVuFormRequest $request)
    {
        try {
            $isNumericId = is_numeric($dmdvId);
            $input = $request->all();
            
            if($isNumericId) {
                $this->dmdvService->updateDanhMucDichVu($dmdvId, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    
    public function deleteDanhMucDichVu($dmdvId)
    {
        $isNumericId = is_numeric($dmdvId);
        
        if($isNumericId) {
            $this->dmdvService->deleteDanhMucDichVu($dmdvId);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);        
    }
    
    public function getYLenhByLoaiNhom($loaiNhom)
    {
        $isNumeric = is_numeric($loaiNhom);
        
        if($isNumeric) {
            $data = $this->dmdvService->getYLenhByLoaiNhom($loaiNhom);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function getDanhMucDichVuPhongOc() {
        $data = $this->dmdvService->getDanhMucDichVuPhongOc();
        return $this->respond($data);
    }
    
    public function getPartialDanhMucTongHop(Request $request)
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        $dienGiai = $request->query('dien_giai', '');
        $khoa = $request->query('khoa', '');
        
        $data = $this->dmthService->getPartial($limit, $page, $dienGiai, $khoa);
        return $this->respond($data);
    }
    
    public function getDmthById($dmthId)
    {
        $isNumericId = is_numeric($dmthId);
        
        if($isNumericId) {
            $data = $this->dmthService->find($dmthId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function getDanhMucTongHopTheoKhoa(Request $request, $khoa) {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        
        if($khoa === null){
            $this->setStatusCode(400);
            return $this->respond([]);
        }
        $data = $this->dmthService->getPartial($limit, $page, null, $khoa);
        
        if(empty($data)) {
            $this->setStatusCode(400);
            $data = [];
        }
        return $this->respond($data);
    }
    
    public function createDanhMucTongHop(DanhMucTongHopFormRequest $request) {
        $input = $request->all();
        
        $id = $this->dmthService->create($input);
        if($id) {
            $this->setStatusCode(201);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    
    public function updateDanhMucTongHop($dmthId, DanhMucTongHopFormRequest $request)
    {
        try {
            $isNumericId = is_numeric($dmthId);
            $input = $request->all();
            
            if($isNumericId) {
                $this->dmthService->update($dmthId, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    
    public function deleteDanhMucTongHop($dmthId)
    {
        $isNumericId = is_numeric($dmthId);
        
        if($isNumericId) {
            $this->dmthService->delete($dmthId);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);        
    }
    
    public function getAllColumnKhoaDanhMucTongHop()
    {
        $data = $this->dmthService->getAllColumnKhoa();
        return $this->respond($data);  
    }
    
    public function getPartialDanhMucTrangThai(Request $request)
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        $dienGiai = $request->query('dien_giai', '');
        $khoa = $request->query('khoa', '');
        
        $data = $this->dmttService->getPartial($limit, $page, $dienGiai, $khoa);
        return $this->respond($data);
    }
    
    public function getDanhMucTrangThaiTheoKhoa(Request $request, $khoa) {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        
        if($khoa === null){
            $this->setStatusCode(400);
            return $this->respond([]);
        }
        $data = $this->dmttService->getPartial($limit, $page, null, $khoa);
        
        if(empty($data)) {
            $this->setStatusCode(400);
            $data = [];
        }
        return $this->respond($data);
    }
    
    public function getDmttById($dmdvId)
    {
        $isNumericId = is_numeric($dmdvId);
        
        if($isNumericId) {
            $data = $this->dmttService->find($dmdvId);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function createDanhMucTrangThai(DanhMucTrangThaiFormRequest $request) {
        $input = $request->all();
        
        $id = $this->dmttService->create($input);
        if($id) {
            $this->setStatusCode(201);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    
    public function updateDanhMucTrangThai($dmttId, DanhMucTrangThaiFormRequest $request)
    {
        try {
            $isNumericId = is_numeric($dmttId);
            $input = $request->all();
            
            if($isNumericId) {
                $this->dmttService->update($dmttId, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    
    public function deleteDanhMucTrangThai($dmttId)
    {
        $isNumericId = is_numeric($dmttId);
        
        if($isNumericId) {
            $this->dmttService->delete($dmttId);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);        
    }
    
    public function getAllColumnKhoaDanhMucTrangThai()
    {
        $data = $this->dmttService->getAllColumnKhoa();
        return $this->respond($data);  
    }
    
    public function getThuocVatTuByLoaiNhom($loaiNhom)
    {
        $isNumeric = is_numeric($loaiNhom);
        
        if($isNumeric) {
            $data = $this->dmtvtService->getThuocVatTuByLoaiNhom($loaiNhom);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function getThuocVatTuByCode($maNhom, $loaiNhom)
    {
        if($maNhom) {
            $data = $this->dmtvtService->getThuocVatTuByCode($maNhom, $loaiNhom);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function getListNhomDanhMuc()
    {
        $data = $this->nhomDanhMucService->getListNhomDanhMuc();
        return $this->respond($data);
    }
    
    public function getNhomDmById($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $data = $this->nhomDanhMucService->getNhomDmById($id);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function createNhomDanhMuc(Request $request) {
        $input = $request->all();
        
        $id = $this->nhomDanhMucService->createNhomDanhMuc($input);
        if($id) {
            $this->setStatusCode(201);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    
    public function updateNhomDanhMuc($id, Request $request)
    {
        try {
            $isNumericId = is_numeric($id);
            $input = $request->all();
            
            if($isNumericId) {
                $this->nhomDanhMucService->updateNhomDanhMuc($id, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    
    public function getAllNoiGioiThieu()
    {
        $data = $this->noiGioiThieuService->getAll();
        return $this->respond($data);
    }
    
    public function getPartialNoiGioiThieu(Request $request)
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        $ten = $request->query('ten', '');
        
        $data = $this->noiGioiThieuService->getPartial($limit, $page, $ten);
        return $this->respond($data);
    }
    
    public function createNoiGioiThieu(NoiGioiThieuFormRequest $request) {
        $input = $request->all();
        
        $id = $this->noiGioiThieuService->create($input);
        if($id) {
            $this->setStatusCode(201);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    
    public function updateNoiGioiThieu($id, NoiGioiThieuFormRequest $request)
    {
        try {
            $isNumericId = is_numeric($id);
            $input = $request->all();
            if($isNumericId) {
                $this->noiGioiThieuService->update($id, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    
    public function deleteNoiGioiThieu($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $this->noiGioiThieuService->delete($id);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);        
    }
    // quanlydanhmucthuocvattu
    public function getPartialDMTVatTu(Request $request)
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        $keyWords = $request->query('keyWords', '');
        
        $data = $this->dmtvtService->getPartialDMTVatTu($limit,$page,$keyWords);
        return $this->respond($data);
    }
    
    public function createDMTVatTu(DanhMucThuocVatTuFormRequest $request)
    {
        $input = $request->all();
        $id = $this->dmtvtService->createDMTVatTu($input);
        if($id) {
            $this->setStatusCode(201);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    
    public function updateDMTVatTu($id, DanhMucThuocVatTuFormRequest $request)
    {
        try {
            $isNumericId = is_numeric($id);
            $input = $request->all();
            
            if($isNumericId) {
                $this->dmtvtService->updateDMTVatTu($id, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    
    public function deleteDMTVatTu($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $this->dmtvtService->deleteDMTVatTu($id);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);        
    }
    public function getDMTVatTuById($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $data = $this->dmtvtService->getDMTVatTuById($id);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    public function getAllNhomDanhMuc()
    {
        $data = $this->dmtvtService->getAllNhomDanhMuc();
        return $this->respond($data);
    }
    
    public function getAllDonViTinh()
    {
        $data = $this->dmtvtService->getAllDonViTinh();
        return $this->respond($data);
    }
    
    public function getAllHoatChat()
    {
        $data = $this->dmtvtService->getAllHoatChat();
        return $this->respond($data);
    }
    
    public function getAllNuocSanXuat($khoa)
    {
        $data = $this->dmtvtService->getAllNuocSanXuat($khoa);
        return $this->respond($data);
    }
}