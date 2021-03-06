<?php
namespace App\Http\Controllers\Api\V1\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\AuthPermissionsService;
use App\Services\KhoaService;
use App\Services\PhongService;
use App\Http\Requests\CreatePermissionsFormRequest;
use App\Http\Requests\UpdatePermissionsFormRequest;

class PermissionController extends APIController
{
    public function __construct(AuthPermissionsService $authPermissionsService,KhoaService $khoaService,PhongService $phongService)
    {
        $this->authPermissionsService = $authPermissionsService;
        $this->khoaService = $khoaService;
        $this->phongService = $phongService;
    }
    
    public function index(Request $request)
    {
        $this->setStatusCode(200);
        return $this->respond([]);
    }    
    
    public function getPartial(Request $request)
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        $keywords = $request->query('keywords', '');
        $serviceId = $request->query('serviceId', '');
        $data = $this->authPermissionsService->getPartial($limit, $page, $keywords, $serviceId);
        return $this->respond($data);
    }
    
    public function create(CreatePermissionsFormRequest $request)
    {
        if ($request->isMethod('get')) {
            $this->setStatusCode(200);
            return $this->respond([]);
        }        
        
        $input = $request->all();
        
        $id = $this->authPermissionsService->create($input);
        if($id) {
            $this->setStatusCode(201);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    
    public function update($id,UpdatePermissionsFormRequest $request)
    {
        try {
            if ($request->isMethod('get')) {
                $this->setStatusCode(200);
                return $this->respond([]);
            }            
            
            $isNumericId = is_numeric($id);
            $input = $request->all();
            
            if($isNumericId) {
                $this->authPermissionsService->update($id, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }    
    
    public function getById($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $data = $this->authPermissionsService->getById($id);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function getKhoaByLoaiKhoaBenhVienId($loaiKhoa,$benhVienId) {
        $data = $this->khoaService->getByLoaiKhoaBenhVienId($loaiKhoa,$benhVienId);
        return $this->respond($data);
    }
    
    public function getMaNhomPhongByKhoaId($khoaId) {
        $data = $this->phongService->getMaNhomPhongByKhoaId($khoaId);
        return $this->respond($data);
    }
    
    public function checkData(Request $request) {
        if ($request->isMethod('get')) {
            $this->setStatusCode(200);
            return $this->respond([]);
        }        
        
        $input = $request->all();
        $status = $this->authPermissionsService->checkData($input);
        return $this->respond($status);
    }
    
    public function getKhoByUrl($url) {
        $data = $this->authPermissionsService->getKhoByUrl($url);
        return $this->respond($data);
    }    
}