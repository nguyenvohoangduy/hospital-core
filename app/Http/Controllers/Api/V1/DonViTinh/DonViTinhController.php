<?php
namespace App\Http\Controllers\Api\V1\DonViTinh;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\DonViTinhService;

class DonViTinhController extends APIController
{
    public function __construct(DonViTinhService $donViTinhService)
    {
        $this->donViTinhService = $donViTinhService;
    }
    
    public function getAll()
    {
        $data = $this->donViTinhService->getAll();
        return $this->respond($data);
    }
    
    public function getPartial(Request $request) 
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        $keyword = $request->query('keyword', '');
        
        $data = $this->donViTinhService->getPartial($limit, $page, $keyword);
        return $this->respond($data);
    }
    
    public function getDonViCoBan()
    {
        $data = $this->donViTinhService->getDonViCoBan();
        return $this->respond($data);
    }
    
    public function create(Request $request) 
    {
        try {
            $input = $request->all();
            $this->donViTinhService->create($input);
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    
    public function update($id, Request $request) 
    {
        try {
            $input = $request->all();
            $this->donViTinhService->update($id, $input);
        } catch (\Exception $ex) {
            $this->setStatusCode(400);
            return $ex;
        }
    }
    
    public function getById($id) 
    {
        $data = $this->donViTinhService->getById($id);
        return $this->respond($data);
    }
}