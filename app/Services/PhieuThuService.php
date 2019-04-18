<?php

namespace App\Services;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Repositories\PhieuThu\PhieuThuRepository;
use App\Repositories\PhieuThu\SoPhieuThuRepository;
use App\Repositories\YLenh\YLenhRepository;
use App\Repositories\Hsba\HsbaDonViRepository;
use DB;

class PhieuThuService {
    const DA_NOP_TIEN = 3;
    const PHIEU_THU = 'phieu-thu';
    
    public function __construct(
        SoPhieuThuRepository $soPhieuThuRepository, 
        PhieuThuRepository $phieuThuRepository,
        YLenhRepository $yLenhRepository,
        HsbaDonViRepository $hsbaKhoaPhongRepository
    )
    {
        $this->phieuThuRepository = $phieuThuRepository;
        $this->soPhieuThuRepository = $soPhieuThuRepository;
        $this->yLenhRepository = $yLenhRepository;
        $this->hsbaDonViRepository = $hsbaKhoaPhongRepository;
    }
  
    public function createPhieuThu(array $input)
    {
        $result = DB::transaction(function() use ($input) {
            try{
                $soPhieuThuItem = $this->soPhieuThuRepository->getSoPhieuThuByAuthUserIdAndTrangThai($input['auth_users_id']);
                //cần check khi chưa có sổ phiếu thu thì sao ???
                $input['so_phieu_thu_id'] = $soPhieuThuItem->id;
                $input['ma_so'] = $soPhieuThuItem->so_phieu_su_dung + 1;
                $input['ngay_tao'] = Carbon::now();
                $id = $this->phieuThuRepository->createDataPhieuThu($input);
                
                // Update so phieu thu
                $dataSoPhieuThu['so_phieu_su_dung'] = $input['ma_so'];
                $this->soPhieuThuRepository->updateSoPhieuThu($soPhieuThuItem->id, $dataSoPhieuThu);
                
                // Update Y Lenh
                $dataYLenh['phieu_thu_id'] = $id;
                $this->yLenhRepository->updatePhieuThuIdByHsbaId($input['hsba_id'], $dataYLenh);
                
                //Update trạng thái hsba
                $params['trang_thai'] = self::DA_NOP_TIEN;
                $this->hsbaDonViRepository->update($input['hsba_khoa_phong_id'], $params);
                return $id;
            }catch(\Throwable  $ex) {
                throw $ex;
            } catch (\Exception $ex) {
                throw $ex;
            }
        });
        return $result;
    }
    
    public function getListPhieuThu() {
        $data = $this->phieuThuRepository->phieuThuRepository();
        return $data;
    }
    
    public function getListPhieuThuBySoPhieuThuId($id) {
        $data = $this->phieuThuRepository->getListPhieuThuBySoPhieuThuId($id);
        return $data;
    }
    
    public function getListPhieuThuByHsbaId($hsbaId) {
        $data = $this->phieuThuRepository->getListPhieuThuByHsbaId($hsbaId);
        return $data;
    }
}