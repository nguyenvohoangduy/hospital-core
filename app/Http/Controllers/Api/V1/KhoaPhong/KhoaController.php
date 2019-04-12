<?php
namespace App\Http\Controllers\Api\V1\KhoaPhong;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;

//use Services
use App\Services\KhoaService;

//use Requests
use App\Http\Requests\KhoaFormRequest;

class KhoaController extends APIController
{
    public function __construct
    (
        KhoaService $khoaService
    )
    {
        $this->khoaService = $khoaService;
    }
    public function getAll()
    {
        $data = $this->khoaService->getAll();
        return $this->respond($data);
    }
    
    public function getPartial(Request $request)
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        $keyWords = $request->query('keyWords', '');
        $benhVienId = $request->query('benhVienId', '');
        
        $data = $this->khoaService->getPartial($limit,$page,$keyWords,$benhVienId);
        return $this->respond($data);
    }
    
    public function create(KhoaFormRequest $request)
    {
        $input = $request->all();
        
        $id = $this->khoaService->create($input);
        if($id) {
            $this->setStatusCode(201);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    
    public function update($id, KhoaFormRequest $request)
    {
        try {
            $isNumericId = is_numeric($id);
            $input = $request->all();
            
            if($isNumericId) {
                $this->khoaService->update($id, $input);
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
            $this->khoaService->delete($id);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);        
    }
    
    public function searchByKeywords($keyWords)
    {
        $data = $this->khoaService->searchByKeywords($keyWords);
        return $this->respond($data);
    }
    
    public function getAllByBenhVienId($benhVienId)
    {
        $data = $this->khoaService->getAllByBenhVienId($benhVienId);
        return $this->respond($data);
    }
    
    public function getAllByLoaiKhoa($loaiKhoa)
    {
        $data = $this->khoaService->getAllByLoaiKhoa($loaiKhoa);
        return $this->respond($data);
    }
    
    public function getById($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $data = $this->khoaService->getById($id);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
}