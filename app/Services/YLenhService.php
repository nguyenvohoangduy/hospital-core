<?php

namespace App\Services;

use App\Models\YLenh;
use App\Repositories\YLenh\YLenhRepository;
use App\Repositories\PhieuYLenh\PhieuYLenhRepository;
use App\Repositories\DanhMuc\DanhMucKQYLRepository;
use App\Repositories\YLenh\KetQuaYLenhRepository;
use App\Repositories\Auth\AuthUsersRepository;
use App\Repositories\DanhMuc\DanhMucDichVuRepository;
use App\Repositories\VienPhi\VienPhiRepository;
use App\Repositories\Kho\PhieuKhoRepository;
use App\Services\DieuTriService;
use App\Services\PhieuKhoService;
use App\Repositories\BenhVienRepository;
use Illuminate\Http\Request;
use Validator;
use DB;
use Carbon\Carbon;
use App\Log\ChiDinhYLenhErrorLog;

class YLenhService {
    const PHIEU_DIEU_TRI = 3;
    const THUOC = 5;
    const LOAI_PHIEU_XUAT = 1;
    const LOAI_PHIEU_NHAP = 0;

    const Y_LENH = 'y-lenh';
    const THUOC_VAT_TU = 'thuoc-vat-tu';
    
    private $bucketS3;
    
    public function __construct
    (
        YLenhRepository $yLenhRepository, 
        PhieuYLenhRepository $phieuYLenhRepository,
        DanhMucKQYLRepository $danhMucKQYLRepository,
        KetQuaYLenhRepository $ketQuaYLenhRepository,
        AuthUsersRepository $authUsersRepository,
        DanhMucDichVuRepository $danhMucDichVuRepository,
        VienPhiRepository $vienPhiRepository,
        PhieuKhoRepository $phieuKhoRepository,
        BenhVienRepository $benhVienRepository,
        DieuTriService $dieuTriService,
        PhieuKhoService $phieuKhoService,
        ChiDinhYLenhErrorLog $errorLog
    )
    {
        $this->yLenhRepository = $yLenhRepository;
        $this->phieuYLenhRepository = $phieuYLenhRepository;
        $this->danhMucKQYLRepository=$danhMucKQYLRepository;
        $this->ketQuaYLenhRepository=$ketQuaYLenhRepository;
        $this->authUsersRepository=$authUsersRepository;
        $this->danhMucDichVuRepository=$danhMucDichVuRepository;
        $this->vienPhiRepository = $vienPhiRepository;
        $this->phieuKhoRepository = $phieuKhoRepository;
        $this->benhVienRepository = $benhVienRepository;
        $this->dieuTriService = $dieuTriService;
        $this->phieuKhoService = $phieuKhoService;
        $this->errorLog = $errorLog;
    }

    public function saveYLenh(array $input)
    {
        $array = [];
        
        $result = DB::transaction(function() use ($input, $array) {
            try {
                $this->bucketS3 = $this->getBucketByBenhVienId($input['benh_vien_id']);
                //insert table phieu_y_lenh
                $phieuYLenhId = $this->createPhieuYLenh($input);
                //insert table y_lenh
                if($input['data']) {
                    foreach($input['data'] as $value) {
                        $array[] = [
                            'vien_phi_id'           => $input['vien_phi_id'],
                            'phieu_y_lenh_id'       => $phieuYLenhId,
                            'doi_tuong_benh_nhan'   => $input['doi_tuong_benh_nhan'],
                            'khoa_id'               => $input['khoa_id'],
                            'phong_id'              => $input['phong_id'],
                            'ma'                    => $value['ma'],
                            'ten'                   => $value['ten'],
                            'ten_bhyt'              => $value['ten_bhyt'] ?? null,
                            'ten_nuoc_ngoai'        => $value['ten_nuoc_ngoai'] ?? null,
                            'trang_thai'            => 0,
                            'gia'                   => $value['gia'],
                            'gia_bhyt'              => $value['gia_bhyt'],
                            'gia_nuoc_ngoai'        => $value['gia_nuoc_ngoai'],
                            'so_luong'              => $value['so_luong'],
                            'loai_y_lenh'           => $value['loai_nhom'],
                            'thoi_gian_chi_dinh'    => Carbon::now()->toDateTimeString(),
                            'bhyt_tra'              => $value['bhyt_tra'],
                            'vien_phi'              => $value['vien_phi'],
                            'muc_huong'             => $input['muc_huong'],
                            'loai_thanh_toan_cu'    => $input['loai_vien_phi'],
                            'loai_thanh_toan_moi'   => $input['loai_vien_phi'],
                            'ms_bhyt'               => $input['ms_bhyt'],
                            'danh_muc_id'           => $value['id']
                        ];
                    }
                }
                $this->yLenhRepository->saveYLenh($array);
                return true;
            } catch(\Throwable  $ex) {
                $this->exceptionToLog($input, $ex, self::Y_LENH);
                throw $ex;
            } catch (\Exception $ex) {
                $this->exceptionToLog($input, $ex, self::Y_LENH);
                throw $ex;
            }
        });
        
        return $result;
    }
    
    public function saveThuocVatTu(array $input)
    {
        $result = DB::transaction(function() use ($input) {
            try {
                //insert table phieu_y_lenh
                $this->bucketS3 = $this->getBucketByBenhVienId($input['benh_vien_id']);
                $phieuYLenhId = $this->createPhieuYLenh($input);
                //insert table y_lenh
                if($input['data']) {
                    foreach($input['data'] as $value) {
                        $huongDanSuDung = [];
                        if($value['loai_nhom'] == self::THUOC) {
                            $huongDanSuDung['duong_dung'] = $value['duong_dung'];
                            $huongDanSuDung['sang'] = $value['sang']['tong_cong'];
                            $huongDanSuDung['trua'] = $value['trua']['tong_cong'];
                            $huongDanSuDung['chieu'] = $value['chieu']['tong_cong'];
                            $huongDanSuDung['toi'] = $value['toi']['tong_cong'];
                            $huongDanSuDung['don_vi_tinh'] = $value['don_vi_tinh'];
                            $huongDanSuDung['ghi_chu'] = $value['ghi_chu'];
                        }
                        
                        $array[] = [
                            'vien_phi_id'           => $input['vien_phi_id'],
                            'phieu_y_lenh_id'       => $phieuYLenhId,
                            'doi_tuong_benh_nhan'   => $input['doi_tuong_benh_nhan'],
                            'khoa_id'               => $input['khoa_id'],
                            'phong_id'              => $input['phong_id'],
                            'ma'                    => $value['ma'],
                            'ten'                   => $value['ten'],
                            'ten_bhyt'              => $value['ten_bhyt'] ?? null,
                            'ten_nuoc_ngoai'        => $value['ten_nuoc_ngoai'] ?? null,
                            'trang_thai'            => 0,
                            'gia'                   => $value['gia'],
                            'gia_bhyt'              => $value['gia_bhyt'],
                            'gia_nuoc_ngoai'        => $value['gia_nuoc_ngoai'],
                            'so_luong'              => $value['so_luong'],
                            'loai_y_lenh'           => $value['loai_nhom'],
                            'thoi_gian_chi_dinh'    => Carbon::now()->toDateTimeString(),
                            'bhyt_tra'              => $value['bhyt_tra'],
                            'vien_phi'              => $value['vien_phi'],
                            'muc_huong'             => $input['muc_huong'],
                            'loai_thanh_toan_cu'    => $input['loai_vien_phi'],
                            'loai_thanh_toan_moi'   => $input['loai_vien_phi'],
                            'ms_bhyt'               => $input['ms_bhyt'],
                            'huong_dan_su_dung'     => count($huongDanSuDung) > 0 ? json_encode($huongDanSuDung) : null,
                            'don_vi_tinh'           => $value['don_vi_tinh'],
                            'kho_id'                => $value['kho_id'],
                            'danh_muc_id'           => $value['id']
                        ];
                    }
                }
                $this->yLenhRepository->saveYLenh($array);
                
                //create phieu yeu cau xuat
                $phieuYeuCauParams = [];
                $phieuYeuCauParams['phong_id'] = $input['phong_id'];
                $phieuYeuCauParams['kho_id_xu_ly'] = $input['kho_id_xu_ly'];
                $phieuYeuCauParams['ten_kho_xu_ly'] = $input['ten_kho_xu_ly'];
                $phieuYeuCauParams['loai_phieu'] = self::LOAI_PHIEU_XUAT;
                $phieuYeuCauParams['ghi_chu'] = 'Chỉ định thuốc cho bệnh nhân ' . $input['ten_benh_nhan'] . ' (MS: ' . $input['benh_nhan_id'] . ')';
                $phieuYeuCauParams['nguoi_lap_phieu_id'] = $input['auth_users_id'];
                $phieuYeuCauParams['ngay_lap_phieu'] = Carbon::now()->toDateTimeString();
                $phieuYeuCauParams['data_dich_vu'] = $input['data'];
                $phieuYeuCauParams['phieu_y_lenh_id'] = $phieuYLenhId;
                $this->phieuKhoService->createPhieuYeuCau($phieuYeuCauParams);
                
                return true;
            } catch(\Throwable  $ex) {
                $this->exceptionToLog($input, $ex, self::THUOC_VAT_TU);
                throw $ex;
            } catch (\Exception $ex) {
                $this->exceptionToLog($input, $ex, self::THUOC_VAT_TU);
                throw $ex;
            }
        });
        
        return $result;
    }
    
    public function traThuoc(array $input, $theKho)
    {
        $result = DB::transaction(function() use ($input, $theKho) {
            try {
                //update status of phieu_y_lenh
                $this->phieuYLenhRepository->updateStatus($input['phieuYLenhId']);
                
                //update status of y_lenh
                $this->yLenhRepository->updateStatus($input['phieuYLenhId']);
                
                $phieuKhoId = 0;
                if($input['data']) {
                    foreach($input['data'] as $index=>$value) {
                        $theKhoId = 0;
                        if($value['so_luong_tra'] > 0) {
                           foreach($theKho as $item) {
                               if($item['danh_muc_thuoc_vat_tu_id'] == $value['danh_muc_id']) {
                                   $theKhoId = $item['the_kho_id'];
                                   $phieuKhoId = $item['phieu_kho_id'];
                                   break;
                               }
                           }
                        } 
                        
                        $input['data'][$index]['the_kho_id'] = $theKhoId;
                    }
                }
                
                //get phieu kho by phieu_kho_id
                $phieuKho = $this->phieuKhoRepository->getPhieuKhoById($phieuKhoId);
                
                //create phieu yeu cau tra
                $phieuYeuCauParams = [];
                $phieuYeuCauParams['phong_id'] = $phieuKho['phong_id'];
                $phieuYeuCauParams['kho_id_xu_ly'] = $phieuKho['kho_id_xu_ly'];
                $phieuYeuCauParams['ten_kho_xu_ly'] = $phieuKho['ten_kho_xu_ly'];
                $phieuYeuCauParams['loai_phieu'] = self::LOAI_PHIEU_NHAP;
                $phieuYeuCauParams['ghi_chu'] = 'Ngưng y lệnh, trả thuốc';
                $phieuYeuCauParams['nguoi_lap_phieu_id'] = $input['auth_users_id'];
                $phieuYeuCauParams['ngay_lap_phieu'] = Carbon::now()->toDateTimeString();
                $phieuYeuCauParams['data_dich_vu'] = $input['data'];
                $this->phieuKhoService->createPhieuYeuCauTra($phieuYeuCauParams);
                
                return true;
            } catch (\Exception $ex) {
                throw $ex;
            }
        });
        
        return $result;
    }
    
    public function getLichSuYLenh(array $input)
    {
        $result = $this->yLenhRepository->getLichSuYLenh($input);
        return $result;
    }
    
    public function getLichSuThuocVatTu(array $input)
    {
        $result = $this->yLenhRepository->getLichSuThuocVatTu($input);
        return $result;
    }
    
    public function getYLenhByHsbaId($hsbaId) {
        $result = $this->yLenhRepository->getYLenhByHsbaId($hsbaId);
        return $result;
    }

    public function getDetailPhieuYLenh($phieuYLenhId,$type)
    {
        $result = $this->yLenhRepository->getDetailPhieuYLenh($phieuYLenhId,$type);
        foreach($result as $item){
            $ketQuaYLenh = $this->ketQuaYLenhRepository->getKetQuaYLenhByCode($item->ma);
            foreach($ketQuaYLenh as $itemKQYL){
                $danhMucKetQua = $this->danhMucKQYLRepository->getDanhMucKetQuaByCode($itemKQYL->ma_ket_qua_y_lenh);
                $item['children']=$danhMucKetQua;                
            }
        }
        return $result;
    } 
    
    public function countItemYLenh($hsbaId)
    {
        $result = $this->yLenhRepository->countItemYLenh($hsbaId);
        return $result;
    }
    
    public function countItemThuocVatTu($hsbaId)
    {
        $result = $this->yLenhRepository->countItemThuocVatTu($hsbaId);
        return $result;
    }
    
    public function getListYLenhByVienPhiId($vienPhiId,$keyWords)
    {
        $loaiYLenh = array(1,2,3,4);
        $result = $this->yLenhRepository->getListYLenhByVienPhiId($vienPhiId,$keyWords);
        
        foreach($result as $item){
            $authUser = $this->authUsersRepository->getInforAuthUserById($item->auth_users_id);
            $item['auth_users_name']=$authUser?$authUser->fullname:null;
            if(in_array($item->loai_y_lenh,$loaiYLenh)){
                $dataDmdv=$this->danhMucDichVuRepository->getDichVuByCode($item->ma);
                $item['don_vi_tinh']=$dataDmdv?$dataDmdv->don_vi_tinh:null;
                $item['ma_nhom_dich_vu']=$dataDmdv?$dataDmdv->ten_nhom:null;
            }
        }
        
        return $result;
    }
    
    public function updateYLenhById($yLenhId,array $input)
    {
        $this->yLenhRepository->updateYLenhById($yLenhId, $input);
    }
    
    public function getAllCanLamSang($hsbaId)
    {
        $data = $this->vienPhiRepository->getAllCanLamSang($hsbaId);
        return $data;
    }  
    
    private function createPhieuYLenh(array $input)
    {
        if(isset($input['dataYLenh']))
            $input = array_except($input, ['dataYLenh', 'username', 'icd10code']);
        if(isset($input['kho_id_xu_ly']))
            $input = array_except($input, ['kho_id_xu_ly', 'ten_kho_xu_ly']);
        $phieuYLenhParams = $input;
        $phieuYLenhParams = array_except($phieuYLenhParams, ['ten_benh_nhan', 'hsba_don_vi_id', 'data', 'doi_tuong_benh_nhan', 'muc_huong', 'loai_vien_phi', 'ms_bhyt', 'benh_vien_id', 'danh_muc_id']);
        $phieuYLenhParams['loai_phieu_y_lenh'] = self::PHIEU_DIEU_TRI;
        $phieuYLenhParams['trang_thai'] = 0;
        $phieuYLenhParams['thoi_gian_chi_dinh'] = Carbon::now()->toDateTimeString();
        $phieuYLenhId = $this->phieuYLenhRepository->getPhieuYLenhId($phieuYLenhParams);
        return $phieuYLenhId;
    }
    
    public function getByDieuTriId($dieuTriId)
    {
        $phieuYLenh = $this->phieuYLenhRepository->getAllByDieuTriId($dieuTriId);
        
        $phieuYLenhId=[];
        foreach($phieuYLenh as $item){
            $phieuYLenhId[]=$item['id'];
        }
        $yLenh = $this->yLenhRepository->getByPhieuYLenhId($phieuYLenhId);
        return $yLenh;
    }    
    
    private function getBucketByBenhVienId($id) {
        $data = $this->benhVienRepository->getBenhVienThietLap($id);
        return $data['bucket'];
    }
    
    private function exceptionToLog($params, $ex, $folder) {
        $this->errorLog->setBucketS3($this->bucketS3);
        $this->errorLog->setFolder($folder);
        $messageAttributes = [
            'key'    => ['DataType' => "String",
                'StringValue' => $params['ten_benh_nhan']
            ],
        ];
        $this->errorLog->toLogQueue($params, $ex, $messageAttributes);
    }
}