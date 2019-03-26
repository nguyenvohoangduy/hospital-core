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
    
    public function getAllByBenhVienId($benhVienid)
    {
        $data = $this->khoaService->getAllByBenhVienId($benhVienid);
        return $this->respond($data);
    }
    
    public function getAllByLoaiKhoa($khoa)
    {
        $data = $this->khoaService->getAllByLoaiKhoa($khoa);
        return $this->respond($data);
    }
    
}