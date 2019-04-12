<?php
namespace App\Http\Controllers\Api\V1\BenhVien;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\BenhVienService;
use App\Http\Requests\BenhVienFormRequest;

class BenhVienController extends APIController {
    public function __construct
    (
        BenhVienService $benhVienService
    )
    {
        $this->benhVienService = $benhVienService;
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
        $name = $request->query('name', '');
        
        $data = $this->benhVienService->getPartial($limit, $page, $name);
        return $this->respond($data);
    }
    
    public function find($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $data = $this->benhVienService->find($id);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function create(BenhVienFormRequest $request)
    {
        if ($request->isMethod('get')) {
            $this->setStatusCode(200);
            return $this->respond([]);
        }
        
        $input = $request->all();
        
        $id = $this->benhVienService->create($input);
        if($id) {
            $this->setStatusCode(201);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    
    public function update($id, BenhVienFormRequest $request)
    {
        try {
            if ($request->isMethod('get')) {
                $this->setStatusCode(200);
                return $this->respond([]);
            }            
            
            $isNumericId = is_numeric($id);
            $input = $request->all();
            
            if($isNumericId) {
                $this->benhVienService->update($id, $input);
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
            $this->benhVienService->delete($id);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);        
    }
    
    public function getListKhoaPhongByBenhVienId($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $data = $this->benhVienService->getListKhoaPhongByBenhVienId($id);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);      
    }
}