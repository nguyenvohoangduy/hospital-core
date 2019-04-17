<?php

namespace App\Services;

use App\Repositories\Kho\PhieuKhoRepository;
use App\Repositories\Kho\TheKhoRepository;
use App\Repositories\Kho\ChiTietPhieuKhoRepository;
use App\Repositories\Kho\GioiHanRepository;
use App\Repositories\Kho\KhoRepository;
use App\Repositories\DanhMuc\DanhMucThuocVatTuRepository;
use App\Services\DanhMucThuocVatTuService;
use Cviebrock\LaravelElasticsearch\Facade as Elasticsearch;
use Illuminate\Http\Request;
use DB;
use Validator;
use Carbon\Carbon;
use App\Helper\Util;

class PhieuKhoService {
    
    const THUOC_VAT_TU_DUOC_DUYET = 1;
    const THUOC_VAT_TU_KHONG_DUYET = 0;
    const SU_DUNG = 1;
    const KHONG_SU_DUNG = 0;
    
    const DA_NHAP_STATUS=32;
    const DA_DUYET_YEU_CAU_STATUS=2;
    const YEU_CAU_NHAP_STATUS=42;
    const YEU_CAU_TRA_STATUS=43;
    
    const LOAI_PHIEU_NHAP = 0;
    const LOAI_PHIEU_XUAT = 1;
    
    const HE_SO_QUY_DOI = 1;
    
    public function __construct
    (
        PhieuKhoRepository $phieuKhoRepository,
        TheKhoRepository $theKhoRepository,
        ChiTietPhieuKhoRepository $chiTietPhieuKhoRepository,
        KhoRepository $khoRepository,
        GioiHanRepository $gioiHanRepository,
        DanhMucThuocVatTuRepository $danhMucThuocVatTuRepository,
        DanhMucThuocVatTuService $danhMucThuocVatTuService
    )
    {
        $this->phieuKhoRepository = $phieuKhoRepository;
        $this->theKhoRepository = $theKhoRepository;
        $this->chiTietPhieuKhoRepository = $chiTietPhieuKhoRepository;
        $this->khoRepository = $khoRepository;
        $this->gioiHanRepository = $gioiHanRepository;
        $this->danhMucThuocVatTuRepository = $danhMucThuocVatTuRepository;
        $this->danhMucThuocVatTuService = $danhMucThuocVatTuService;
    }
    
    public function createPhieuKho(array $input)
    {
        DB::transaction(function () use ($input) {
            $maPhieu = $this->phieuKhoRepository->getMaPhieu();
            $dataKho = $this->khoRepository->getKhoById($input['kho_id']);            
            
            $phieuKhoParams = [];
            $phieuKhoParams['kho_id']=$input['kho_id'];
            $phieuKhoParams['kho_id_xu_ly']=$input['kho_id'];
            $phieuKhoParams['ten_kho_xu_ly']=$dataKho['ten_kho'];
            $phieuKhoParams['nhan_vien_yeu_cau']=$input['nguoi_lap_phieu_id'];
            $phieuKhoParams['thoi_gian_yeu_cau']=$input['ngay_lap_phieu'];
            $phieuKhoParams['thoi_gian_duyet']=$input['ngay_lap_phieu'];
            $phieuKhoParams['nhan_vien_duyet']=$input['nguoi_lap_phieu_id'];
            $phieuKhoParams['so_chung_tu']=$input['so_chung_tu'];
            $phieuKhoParams['ncc_id']=$input['nha_cung_cap_id'];
            //$phieuKhoParams['nguoi_giao']=$input['nguoi_giao'];
            $phieuKhoParams['dia_chi_giao'] = $input['dia_chi_giao'] ?? null;
            $phieuKhoParams['ghi_chu'] = $input['ghi_chu'] ?? null;
            $phieuKhoParams['trang_thai']=self::DA_NHAP_STATUS;
            $phieuKhoParams['loai_phieu']=self::LOAI_PHIEU_NHAP;
            $phieuKhoParams['ma_phieu']=$maPhieu;
            $phieuKhoId = $this->phieuKhoRepository->createPhieuKho($phieuKhoParams);
            
            $listId = [];
            foreach($input['data_dich_vu'] as $item) {
                $listId[]=$item['id'];
                
                $theKhoParams = [];
                $theKhoParams['kho_id']=$input['kho_id'];
                $theKhoParams['danh_muc_thuoc_vat_tu_id']=$item['id'];
                $theKhoParams['sl_dau_ky'] = $item['so_luong'];
                $theKhoParams['sl_kha_dung'] = $item['he_so_quy_doi'] ? $item['he_so_quy_doi'] * $item['so_luong'] : $item['so_luong'];
                $theKhoParams['sl_nhap_chan'] = $item['so_luong'];
                $theKhoParams['don_vi_nhap'] = $item['don_vi_tinh'];
                $theKhoParams['sl_ton_kho'] = $theKhoParams['sl_kha_dung'];
                $theKhoParams['sl_ton_kho_chan'] = $theKhoParams['sl_kha_dung'];
                $theKhoParams['don_vi_co_ban'] = $item['don_vi_co_ban'];
                $theKhoParams['he_so_quy_doi'] = $item['he_so_quy_doi'] ?? self::HE_SO_QUY_DOI;
                $theKhoParams['gia_nhap']=$item['don_gia_nhap'];  
                //$theKhoParams['vat_nhap']=$item['vat%'];
                $theKhoParams['trang_thai']=self::SU_DUNG;   
                $theKhoId = $this->theKhoRepository->createTheKho($theKhoParams);
                
                $chiTietPhieuKhoParams = [];
                $chiTietPhieuKhoParams['phieu_kho_id']=$phieuKhoId;
                $chiTietPhieuKhoParams['danh_muc_thuoc_vat_tu_id']=$item['id'];
                $chiTietPhieuKhoParams['the_kho_id']=$theKhoId;
                $chiTietPhieuKhoParams['so_luong_nhap']=$item['so_luong'];
                //$chiTietPhieuKhoParams['vat_gia_nhap']=$item['vat%'];
                $chiTietPhieuKhoParams['gia_nhap']=$item['don_gia_nhap'];
                $chiTietPhieuKhoParams['trang_thai'] = self::SU_DUNG;  
                $chiTietPhieuKhoParams['don_vi_nhap'] = $item['don_vi_tinh'];
                $chiTietPhieuKhoParams['he_so_quy_doi'] = $item['he_so_quy_doi'] ?? self::HE_SO_QUY_DOI;
                $chiTietPhieuKhoParams['don_vi_co_ban'] = $item['don_vi_co_ban'];
                $this->chiTietPhieuKhoRepository->createChiTietPhieuKho($chiTietPhieuKhoParams);
            }
        });
    }
    
    public function createPhieuYeuCau(array $input)
    {
        DB::transaction(function () use ($input) {
            $maPhieu = $this->phieuKhoRepository->getMaPhieu();
            
            $phieuKhoParams = [];
            $phieuKhoParams['phong_id']=$input['phong_id'] ?? null;
            $phieuKhoParams['kho_id']=$input['kho_id'] ?? null;
            $phieuKhoParams['kho_id_xu_ly']=$input['kho_id_xu_ly'];
            $phieuKhoParams['ten_kho_xu_ly']=$input['ten_kho_xu_ly'];
            $phieuKhoParams['loai_phieu']=$input['loai_phieu'];
            $phieuKhoParams['trang_thai']=self::DA_DUYET_YEU_CAU_STATUS;
            $phieuKhoParams['dien_giai']=$input['ghi_chu'];
            $phieuKhoParams['nhan_vien_yeu_cau']=$input['nguoi_lap_phieu_id'];
            $phieuKhoParams['thoi_gian_yeu_cau']=$input['ngay_lap_phieu'];
            $phieuKhoParams['ma_phieu']=$maPhieu;
            
            $phieuKhoId = $this->phieuKhoRepository->createPhieuKho($phieuKhoParams);
            
            foreach($input['data_dich_vu'] as $item) {
                $theKhoParams = [];
                $theKhoParams['kho_id']=$input['kho_id_xu_ly'];
                $theKhoParams['danh_muc_thuoc_vat_tu_id']=$item['id'];
                $theKhoParams['so_luong']=$item['so_luong'];
 
                $result = $this->theKhoRepository->updateTheKho($theKhoParams);
                
                //update so_luong_kha_dung in table gioi_han
                $theKhoParams['sl_kha_dung'] = $result['sl_kha_dung'];
                $this->gioiHanRepository->updateSoLuongKhaDung($theKhoParams);
                
                //update so_luong_kha_dung in elasticsearch
                $this->elasticSearchService->updateSoLuongKhaDungById($theKhoParams);
                
                if($result) {
                    $chiTietPhieuKhoParams = [];
                    $chiTietPhieuKhoParams['phieu_kho_id']=$phieuKhoId;
                    $chiTietPhieuKhoParams['danh_muc_thuoc_vat_tu_id']=$item['id'];
                    $chiTietPhieuKhoParams['the_kho_id'] = $result['the_kho_id']; 
                    $chiTietPhieuKhoParams['so_luong_yeu_cau']=$item['so_luong'];
                    $chiTietPhieuKhoParams['trang_thai'] = self::THUOC_VAT_TU_KHONG_DUYET; 
                    $chiTietPhieuKhoParams['phieu_y_lenh_id']=$input['phieu_y_lenh_id'] ?? null;
                    $chiTietPhieuKhoParams['don_vi_co_ban']=$item['don_vi_tinh'] ?? null;
                    $this->chiTietPhieuKhoRepository->createChiTietPhieuKho($chiTietPhieuKhoParams); 
                }
            }
        });
    }
    
    public function createPhieuYeuCauTra(array $input)
    {
        DB::transaction(function () use ($input) {
            $maPhieu = $this->phieuKhoRepository->getMaPhieu();
            
            $phieuKhoParams = [];
            $phieuKhoParams['phong_id']=$input['phong_id'] ?? null;
            $phieuKhoParams['kho_id']=$input['kho_id'] ?? null;
            $phieuKhoParams['kho_id_xu_ly']=$input['kho_id_xu_ly'];
            $phieuKhoParams['ten_kho_xu_ly']=$input['ten_kho_xu_ly'];
            $phieuKhoParams['loai_phieu']=$input['loai_phieu'];
            $phieuKhoParams['trang_thai']=self::YEU_CAU_TRA_STATUS;
            $phieuKhoParams['dien_giai']=$input['ghi_chu'];
            $phieuKhoParams['nhan_vien_yeu_cau']=$input['nguoi_lap_phieu_id'];
            $phieuKhoParams['thoi_gian_yeu_cau']=$input['ngay_lap_phieu'];
            $phieuKhoParams['ma_phieu']=$maPhieu;
            
            $phieuKhoId = $this->phieuKhoRepository->createPhieuKho($phieuKhoParams);
            $chiTietPhieuKhoParams = [];
            
            foreach($input['data_dich_vu'] as $item) {
                $theKhoParams = [];
                $theKhoParams['kho_id']=$input['kho_id_xu_ly'];
                $theKhoParams['danh_muc_thuoc_vat_tu_id']=$item['danh_muc_id'];
                $theKhoParams['so_luong']=$item['so_luong_tra'];
 
                if($item['so_luong_tra'] > 0) {
                    $chiTietPhieuKhoParams[] = [
                        'phieu_kho_id'              => $phieuKhoId,
                        'danh_muc_thuoc_vat_tu_id'  => $item['danh_muc_id'],
                        'the_kho_id'                => $item['the_kho_id'],
                        'so_luong_yeu_cau'          => $item['so_luong_tra'],
                        'trang_thai'                => self::THUOC_VAT_TU_KHONG_DUYET,
                        'don_vi_co_ban'             => $item['don_vi_tinh'],
                        'sl_tra_nguyen'             => $item['arrQty'][0],
                        'sl_tra_le_1'               => $item['arrQty'][1],
                        'sl_tra_le_2'               => $item['arrQty'][2],
                    ];
                }
            }
            
            if(count($chiTietPhieuKhoParams) > 0)
                $this->chiTietPhieuKhoRepository->saveChiTietPhieuKho($chiTietPhieuKhoParams);
        });
    }
    
    public function getListPhieuKhoByKhoIdXuLy($startDay,$endDay,$khoIdXuLy)
    {
        $data = $this->phieuKhoRepository->getListPhieuKhoByKhoIdXuLy($startDay,$endDay,$khoIdXuLy);
        return $data;
    }
    
    public function createPhieuXuat($phieuKhoId,$nhanVienDuyetId)
    {
        DB::transaction(function () use ($phieuKhoId,$nhanVienDuyetId) {
            $data = $this->phieuKhoRepository->updateAndGetPhieuKho($phieuKhoId,$nhanVienDuyetId);
            $dataKho = $this->khoRepository->getKhoById($data['kho_id_xu_ly']);
            $maPhieu = $this->phieuKhoRepository->getMaPhieu();            
            
            $phieuKhoParams = [];
            $phieuKhoParams['kho_id']=$data['kho_id_xu_ly'];
            $phieuKhoParams['kho_id_xu_ly']=$data['kho_id'];
            $phieuKhoParams['ten_kho_xu_ly']=$dataKho['ten_kho'];
            $phieuKhoParams['loai_phieu']=self::LOAI_PHIEU_XUAT;
            $phieuKhoParams['trang_thai']=self::YEU_CAU_NHAP_STATUS;
            $phieuKhoParams['nhan_vien_yeu_cau']=$nhanVienDuyetId;
            $phieuKhoParams['thoi_gian_yeu_cau']=Carbon::now();
            $phieuKhoParams['phieu_kho_yeu_cau_id']=$phieuKhoId;
            $phieuKhoParams['ma_phieu']=$maPhieu;
            
            $this->phieuKhoRepository->createPhieuKho($phieuKhoParams);
            
            $chiTietPhieuKhoParams['trang_thai']=self::THUOC_VAT_TU_DUOC_DUYET;
            
            $chiTietPhieuKhoData = $this->chiTietPhieuKhoRepository->updateAndGetChiTietPhieuKho($phieuKhoId,$chiTietPhieuKhoParams);
            
            $arrDmtvt=[];
            foreach($chiTietPhieuKhoData as $item) {
                $arrDmtvt[]=$item['danh_muc_thuoc_vat_tu_id'];
            
            }
            $dataTheKho = $this->theKhoRepository->getTheKho($data['kho_id_xu_ly'],$arrDmtvt);
            $arrIdTheKho = [];
            $arrTheKho = [];
            
            foreach($chiTietPhieuKhoData as $item) {
                $id = $item['danh_muc_thuoc_vat_tu_id'];
                $soLuongYeuCau = $item['so_luong_yeu_cau'];
                
                foreach($dataTheKho as $itemDataTheKho) {
                    if($itemDataTheKho['danh_muc_thuoc_vat_tu_id']==$id && $soLuongYeuCau > 0 && $itemDataTheKho['sl_ton_kho'] > 0){
                        $chenhLech = $itemDataTheKho['sl_ton_kho'] - $soLuongYeuCau;
                        if($chenhLech < 0) {
                            $soLuongTonKho = 0;
                            $soLuongTonKhoChan = 0;
                            $soLuongTonKhoLe1 = 0;
                            $soLuongTonKhoLe2 = 0;
                            $soLuongNhapChan = 0;
                            $soLuongNhapLe = 0;
                            $soLuongYeuCau = $chenhLech;
                        } else {
                            $soLuongTonKho = $chenhLech;
                            $soLuongYeuCau = 0;
                            
                            if($chenhLech == 0) {
                                $soLuongTonKhoChan = 0;
                                $soLuongTonKhoLe1 = 0;
                                $soLuongTonKhoLe2 = 0;
                                $soLuongNhapChan = 0;
                                $soLuongNhapLe = 0;
                            } else {
                                $arrSoLuong = explode('.', $chenhLech);
                                $soLuongTonKhoChan = $arrSoLuong[0];
                                $soLuongTonKhoLe1 = $itemDataTheKho['sl_ton_kho_le_1'];
                                $soLuongTonKhoLe2 = $itemDataTheKho['sl_ton_kho_le_2'];
                                $soLuongNhapChan = floor($chenhLech / $itemDataTheKho['he_so_quy_doi']);
                                $soLuongNhapLe = $chenhLech - ($soLuongNhapChan * $itemDataTheKho['he_so_quy_doi']);
                                
                                if(isset($arrSoLuong[1])) {
                                    switch($arrSoLuong[1]) {
                                        case 5:
                                            $soLuongTonKhoLe1 = 1;
                                            $soLuongTonKhoLe2 = 0;
                                            break;
                                        case 25:
                                            $soLuongTonKhoLe1 = 0;
                                            $soLuongTonKhoLe2 = 1;
                                            break;
                                        case 75:
                                            $soLuongTonKhoLe1 = 1;
                                            $soLuongTonKhoLe2 = 1;
                                            break;
                                    }
                                } else {
                                    $soLuongTonKhoLe1 = 0;
                                    $soLuongTonKhoLe2 = 0;
                                }
                            }
                        }
                        
                        $arrTheKho[] = [
                            'id'                => $itemDataTheKho['id'],
                            'sl_ton_kho'        => $soLuongTonKho,
                            'sl_ton_kho_chan'   => $soLuongTonKhoChan,
                            'sl_ton_kho_le_1'   => $soLuongTonKhoLe1,
                            'sl_ton_kho_le_2'   => $soLuongTonKhoLe2,
                            'sl_nhap_chan'      => $soLuongNhapChan,
                            'sl_nhap_le'        => $soLuongNhapLe
                        ];
                    }
                }
            }

            foreach($arrTheKho as $item) {
                $this->theKhoRepository->updateSoLuongTon($item);
            }
        });
    }
    
    public function createPhieuNhap($phieuKhoId,$nhanVienDuyetId)
    {
        DB::transaction(function () use ($phieuKhoId,$nhanVienDuyetId) {
            $data = $this->phieuKhoRepository->getPhieuKhoById($phieuKhoId);
            $dataKho = $this->khoRepository->getKhoById($data['kho_id_xu_ly']);
            $maPhieu = $this->phieuKhoRepository->getMaPhieu();            
            
            $phieuKhoParams = [];
            $phieuKhoParams['kho_id']=$data['kho_id_xu_ly'];
            $phieuKhoParams['kho_id_xu_ly']=$data['kho_id_xu_ly'];
            $phieuKhoParams['ten_kho_xu_ly']=$dataKho['ten_kho'];
            $phieuKhoParams['loai_phieu']=self::LOAI_PHIEU_NHAP;
            $phieuKhoParams['trang_thai']=self::DA_NHAP_STATUS;
            $phieuKhoParams['nhan_vien_duyet']=$nhanVienDuyetId;
            $phieuKhoParams['thoi_gian_yeu_cau']=Carbon::now();
            $phieuKhoParams['thoi_gian_duyet']=Carbon::now();
            if($data['trang_thai'] == self::YEU_CAU_NHAP_STATUS)
                $phieuKhoParams['phieu_kho_yeu_cau_id'] = $data['phieu_kho_yeu_cau_id'];
            if($data['trang_thai'] == self::YEU_CAU_TRA_STATUS)
                $phieuKhoParams['phieu_kho_yeu_cau_id'] = $phieuKhoId;
            $phieuKhoParams['ma_phieu']=$maPhieu;
            
            $this->phieuKhoRepository->updateTrangThaiPhieuKho($phieuKhoId,self::DA_NHAP_STATUS);
            $this->phieuKhoRepository->createPhieuKho($phieuKhoParams);
            
            if($data['trang_thai'] == self::YEU_CAU_NHAP_STATUS) {
                $chiTietPhieuKhoData = $this->chiTietPhieuKhoRepository->getByPhieuKhoId($data['phieu_kho_yeu_cau_id']);
                
                foreach($chiTietPhieuKhoData as $item) {
                    if($item['trang_thai']==self::THUOC_VAT_TU_DUOC_DUYET) {
                        $theKhoParams = [];
                        $theKhoParams['kho_id'] = $data['kho_id_xu_ly'];
                        $theKhoParams['danh_muc_thuoc_vat_tu_id'] = $item['danh_muc_thuoc_vat_tu_id'];
                        $theKhoParams['sl_dau_ky'] = $item['so_luong_yeu_cau'];
                        $theKhoParams['sl_kha_dung'] = $item['so_luong_yeu_cau'];
                        $theKhoParams['sl_ton_kho_chan'] = floor($item['so_luong_yeu_cau']);
                        
                        $this->theKhoRepository->createTheKho($theKhoParams);
                    }
                }
            } else if($data['trang_thai'] == self::YEU_CAU_TRA_STATUS) {
                $chiTietPhieuKhoData = $this->chiTietPhieuKhoRepository->getByPhieuKhoId($phieuKhoId);
                
                foreach($chiTietPhieuKhoData as $item) {
                    $theKho = $this->theKhoRepository->getById($item['the_kho_id']);
                    
                    $soLuongKhaDung = $theKho['sl_kha_dung'] + $item['so_luong_yeu_cau'];
                    $soLuongTonKho = $theKho['sl_ton_kho'] + $item['so_luong_yeu_cau'];
                    $soLuongTonKhoChan = $theKho['sl_ton_kho_chan'] + $item['sl_tra_nguyen'];
                    $soLuongTonKhoLe1 = $theKho['sl_ton_kho_le_1'] + $item['sl_tra_le_1'];
                    $soLuongTonKhoLe2 = $theKho['sl_ton_kho_le_2'] + $item['sl_tra_le_2'];
                    $soLuongNhapChan = floor($soLuongTonKhoChan / $theKho['he_so_quy_doi']);
                    $soLuongNhapLe = $soLuongTonKho - ($soLuongNhapChan * $theKho['he_so_quy_doi']);
                    
                    $arrTheKho = [
                        'id'                => $item['the_kho_id'],
                        'sl_ton_kho'        => $soLuongTonKho,
                        'sl_ton_kho_chan'   => $soLuongTonKhoChan,
                        'sl_ton_kho_le_1'   => $soLuongTonKhoLe1,
                        'sl_ton_kho_le_2'   => $soLuongTonKhoLe2,
                        'sl_nhap_chan'      => $soLuongNhapChan,
                        'sl_nhap_le'        => $soLuongNhapLe
                    ];
                    
                    $this->theKhoRepository->updateSoLuongTon($arrTheKho);
                    $this->theKhoRepository->updateSoLuongKhaDung($item['the_kho_id'], $soLuongKhaDung);
                    
                    $slKhaDung = $this->theKhoRepository->getTonKhaDungById($item['danh_muc_thuoc_vat_tu_id'], $theKho['kho_id']);
                    $theKhoParams = [];
                    $theKhoParams['kho_id'] = $theKho['kho_id'];
                    $theKhoParams['danh_muc_thuoc_vat_tu_id'] = $theKho['danh_muc_thuoc_vat_tu_id'];
                    $theKhoParams['sl_kha_dung'] = $slKhaDung['so_luong_kha_dung'];
                    //update so_luong_kha_dung in table gioi_han
                    $this->gioiHanRepository->updateSoLuongKhaDung($theKhoParams);
                    
                    //update so_luong_kha_dung in elasticsearch
                    $this->elasticSearchService->updateSoLuongKhaDungById($theKhoParams);
                }
            }
        });
    }
    
    public function getChiTietPhieuXuatNhap($phieuKhoId)
    {
        $phieuKhoData = $this->phieuKhoRepository->getThongTinPhieuKhoById($phieuKhoId);
        $chiTietPhieuKhoData = $this->chiTietPhieuKhoRepository->getThongTinChiTietByPhieuKhoId($phieuKhoId);
        
        $data=[
            'phieu_kho_data'            =>$phieuKhoData,
            'chi_tiet_phieu_kho_data'   =>$chiTietPhieuKhoData
            ];
        return $data;
    }
    
}