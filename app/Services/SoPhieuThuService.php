<?php

namespace App\Services;

use App\Repositories\PhieuThu\SoPhieuThuRepository;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;

class SoPhieuThuService {
  
    public function __construct(SoPhieuThuRepository $soPhieuThuRepository)
    {
        $this->soPhieuThuRepository = $soPhieuThuRepository;
    }
  
    public function create(array $input)
    {
        $input['so_phieu_den'] = $input['so_phieu_tu'] + $input['tong_so_phieu'] - 1;
        $input['ngay_tao'] = Carbon::now();
        $input['so_phieu_su_dung'] = 0;
        $input['so_phieu_dang_su_dung'] = $input['so_phieu_tu'];
        $id = $this->soPhieuThuRepository->create($input);
        return $id;
    }
    
    public function getList($maSo, $trangThai) {
        $data = $this->soPhieuThuRepository->getList($maSo, $trangThai);
        return $data;
    }
    
    public function getById($id)
    {
        $data = $this->soPhieuThuRepository->getById($id);
        
        return $data;
    }
    
    public function update($id, array $input)
    {
        $this->soPhieuThuRepository->update($id, $input);
    }
    
    public function delete($id)
    {
        $this->soPhieuThuRepository->delete($id);
    }
    
    public function getByAuthUserIdAndTrangThai($userId)
    {
        $data = $this->soPhieuThuRepository->getByAuthUserIdAndTrangThai($userId);
        
        return $data;
    }
}