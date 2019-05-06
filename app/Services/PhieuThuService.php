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
    const PHIEU_THU = 'phieu-thu';
    const DA_THANH_TOAN = 1;
    
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
  
    public function create(array $input)
    {
        $result = DB::transaction(function() use ($input) {
            try{
                $soPhieuThuItem = $this->soPhieuThuRepository->getById($input['so_phieu_thu_id']);
                $input['ma_so'] = $soPhieuThuItem->so_phieu_dang_su_dung;
                $input['ngay_tao'] = Carbon::now();
                $id = $this->phieuThuRepository->create($input);
                
                // Update so phieu thu
                $dataSoPhieuThu['so_phieu_dang_su_dung'] = $input['ma_so'] + 1;
                $dataSoPhieuThu['so_phieu_su_dung'] = $soPhieuThuItem->so_phieu_su_dung + 1;
                $this->soPhieuThuRepository->update($input['so_phieu_thu_id'], $dataSoPhieuThu);
                
                // Update Y Lenh
                $dataYLenh['phieu_thu_id'] = $id;
                $this->yLenhRepository->updatePhieuThuIdByHsbaId($input['hsba_id'], $dataYLenh);
                
                //Update trạng thái hsba
                $params['trang_thai_thanh_toan'] = self::DA_THANH_TOAN;
                $this->hsbaDonViRepository->update($input['hsba_don_vi_id'], $params);
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