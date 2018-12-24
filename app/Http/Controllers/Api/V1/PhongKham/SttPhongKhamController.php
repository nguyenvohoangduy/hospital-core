<?php
namespace App\Http\Controllers\Api\V1\PhongKham;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\SttPhongKhamService;
use App\Services\TrangThaiService;
use App\Services\AuthService;
use App\Models\SttPhongKham;
use Carbon\Carbon;

class SttPhongKhamController extends APIController
{
    const LOAI_STT = ['A', 'B', 'C'];
    
    public function __construct(SttPhongKhamService $sttPhongKhamService, AuthService $authService, TrangThaiService $trangThaiService)
    {
        $this->sttPhongKhamService = $sttPhongKhamService;
        $this->authService = $authService;
        $this->trangThaiService = $trangThaiService;
    }
    
    private function checkExistParam($phongId, $benhVienId)
    {
        if($phongId === null || $benhVienId === null)
            return false;
        else
            return true;
    }
    
    public function goiSttPhongKham(Request $request)
    {
        $input = $request->all();
        $isValidLoaiStt = in_array($input['loaiStt'], self::LOAI_STT);
        $isExistParam = $this->checkExistParam($input['phongId'], $input['benhVienId']);
        $authUsersId = $input['authUsersId'];
        
        //sai tham so $loaiStt
        if(!$isValidLoaiStt){
            $this->setStatusCode(400);
            return $this->respond([]);
        }
        
        //khong co tham so $phongId, $benhVienId
        if(!$isExistParam) {
            $this->setStatusCode(400);
            return $this->respond([]);
        }
        
        //khong ton tai $authUsersId
        $bool = $this->authService->getUserById($authUsersId);
        if(!$bool){
            $this->setStatusCode(400);
            return $this->respond([]);
        }
        
        $data = $this->sttPhongKhamService->goiSttPhongKham($input);
        
        //ko co du lieu stt
        if($data === null)
            $this->setStatusCode(404);
        
        return $this->respond($data);
    }
    
    public function loadSttPhongKham(Request $request)
    {
        $input = $request->all();
        $isExistParam = $this->checkExistParam($input['phongId'], $input['benhVienId']);
        
        if($isExistParam) {
            $data = $this->sttPhongKhamService->loadSttPhongKham($input);
        } else {
            $data = null;
            $this->setStatusCode(400);
        }
        
        return $this->respond($data);
    }
    
    public function finishSttPhongKham($sttId)
    {
        if(is_numeric($sttId)) {
            $this->sttPhongKhamService->finishSttPhongKham($sttId);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    
    /*
    public function batDauKham($sttId) {
        $data = null;
        if(is_numeric($sttId)) {
            $this->sttPhongKhamService->batDauKham($sttId);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond($data);
    }
    */
    
    public function batDauKham($sttId, $newStatus) {
        $tableModel = app()->make(SttPhongKham::class);
        $extraUpdate = [
            'thoi_gian_goi' => Carbon::now()->toDateTimeString()
            ];
        $attributes = [
            'statusColumn' => 'trang_thai',
            'newStatus' => $newStatus,
            'idColumn' => 'id',
            'idValue' => $sttId,
            'extraUpdate' => $extraUpdate
        ];
        //$this->trangThaiService->changeToState($tableModel, $attributes['statusColumn'], $attributes['newStatus'], $attributes['idColumn'], $attributes['idValues'], $attributes['extraUpdate']);
        $this->trangThaiService->changeToState($tableModel, $attributes);
    }
}