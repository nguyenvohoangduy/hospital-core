<?php
namespace App\Http\Controllers\Api\V1\KhoaPhong;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;

//use Services
use App\Services\PhongService;

//use Requests
use App\Http\Requests\PhongFormRequest;

class PhongController extends APIController
{
    public function __construct
    (
        PhongService $phongService
    )
    {
        $this->phongService = $phongService;
    }
    
    public function getPartial(Request $request)
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        $keyWords = $request->query('keyWords', '');
        $khoaId = $request->query('khoa_id', 20);
        
        $data = $this->phongService->getPartial($limit,$page,$keyWords,$khoaId);
        return $this->respond($data);
    }
    
    public function create(PhongFormRequest $request)
    {
        $input = $request->all();
        
        $id = $this->phongService->create($input);
        if($id) {
            $this->setStatusCode(201);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    
    public function update($id, PhongFormRequest $request)
    {
        try {
            $isNumericId = is_numeric($id);
            $input = $request->all();
            
            if($isNumericId) {
                $this->phongService->update($id, $input);
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
            $this->phongService->delete($id);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);        
    }
    
    public function searchByKeywords($keyWords)
    {
        $data = $this->phongService->searchByKeywords($keyWords);
        return $this->respond($data);
    }
    
    public function getAllByKhoaId($khoaId)
    {
        $data = $this->phongService->getAllByKhoaId($khoaId);
        return $this->respond($data);
    }
   
    public function getPhongById($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $data = $this->phongService->getPhongById($id);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
}