<?php

namespace App\Services;

use App\Repositories\DanhMuc\DanhMucThuocVatTuRepository;
use App\Repositories\DanhMuc\NhomDanhMucRepository;
use App\Repositories\HoatChatRepository;
use App\Repositories\DonViTinhRepository;
use App\Repositories\DanhMuc\DanhMucTongHopRepository;
use App\Repositories\Auth\AuthUsersRepository;

use Illuminate\Http\Request;
use App\Helper\Util;

class DanhMucThuocVatTuService
{
    public function __construct(
        DanhMucThuocVatTuRepository $repository,
        HoatChatRepository $hoatChatRepository,
        NhomDanhMucRepository $nhomdanhmucRepository,
        DonViTinhRepository $donvitinhRepository,
        DanhMucTongHopRepository $danhmucTongHopRepository,
        AuthUsersRepository $authUsersRepository
    ){
        $this->repository = $repository;
        $this->hoatChatRepository = $hoatChatRepository;  
        $this->nhomdanhmucRepository = $nhomdanhmucRepository;
        $this->donvitinhRepository = $donvitinhRepository;
        $this->danhmucTongHopRepository = $danhmucTongHopRepository;
        $this->authUsersRepository = $authUsersRepository;
    }
    
    public function getThuocVatTuByLoaiNhom($loaiNhom)
    {
        $data = $this->repository->getThuocVatTuByLoaiNhom($loaiNhom);
        
        return $data;
    }
    
    public function getThuocVatTuByCode($maNhom, $loaiNhom)
    {
        $data = $this->repository->getThuocVatTuByCode($maNhom, $loaiNhom);
        
        return $data;
    }
    
    // public function pushToRedis()
    // {
    //     $data = $this->repository->getAllThuocVatTu();
    //     foreach($data as $item){
    //         $arrayItem=[
    //             'id'                    => (string)$item->id ?? '-',
    //             'nhom_danh_muc_id'      => (string)$item->nhom_danh_muc_id ?? '-',
    //             'ten'                   => (string)$item->ten ?? '-', 
    //             'ten_bhyt'              => $item->ten_bhyt ?? '-',
    //             'ten_nuoc_ngoai'        => (string)$item->ten_nuoc_ngoai ?? '-',
    //             'ky_hieu'               => (string)$item->ky_hieu ?? '-',
    //             'ma_bhyt'               => (string)$item->ma_bhyt ?? '-',
    //             'don_vi_tinh_id'        => (string)$item->don_vi_tinh_id ?? '-',
    //             'stt'                   => $item->stt ?? '-',
    //             'nhan_vien_tao'         => (string)$item->nhan_vien_tao ?? '-',
    //             'nhan_vien_cap_nhat'    => (string)$item->nhan_vien_cap_nhat ?? '-',
    //             'thoi_gian_tao'         => (string)$item->thoi_gian_tao ?? '-',
    //             'thoi_gian_cap_nhat'    => (string)$item->thoi_gian_cap_nhat ?? '-',
    //             'hoat_chat_id'          => (string)$item->hoat_chat_id ?? '-',
    //             'biet_duoc_id'          => (string)$item->biet_duoc_id ?? '-',
    //             'nong_do'               => (string)$item->nong_do ?? '-',
    //             'duong_dung'            => (string)$item->duong_dung ?? '-',
    //             'dong_goi'              => (string)$item->dong_goi ?? '-',
    //             'hang_san_xuat'         => (string)$item->hang_san_xuat ?? '-',
    //             'nuoc_san_xuat'         => (string)$item->nuoc_san_xuat ?? '-',
    //             'trang_thai'            => (string)$item->trang_thai ?? '-'
    //             ];            
    //         $this->danhMucThuocVatTuRedisRepository->_init();
    //         //$suffix = $item['nhom_danh_muc_id'].':'.$item['id'].":".Util::convertViToEn(str_replace(" ","_",strtolower($item['ten'])));
    //         $suffix='test';
    //         $this->danhMucThuocVatTuRedisRepository->hmset($suffix,$arrayItem);            
    //     };
    // }
    
    // public function getListByKeywords($keyWords)
    // {
    //     $this->danhMucThuocVatTuRedisRepository->_init();
    //     $data = $this->danhMucThuocVatTuRedisRepository->getListByKeywords($keyWords);
    //     return $data;
    // }
    
    public function getAllThuocVatTu()
    {
        $data = $this->repository->getAllThuocVatTu();
        return $data;
    }
    
    public function getPartialDMTVatTu($limit, $page, $keyWords)
    {
        $data = $this->repository->getPartialDMTVatTu($limit, $page, $keyWords);
        return $data;
    }
    
    public function createDMTVatTu(array $input)
    {
        $id = $this->repository->create($input);
        return $id;
    } 
    
    public function updateDMTVatTu($id, array $input)
    {
        $this->repository->update($id, $input);
    }
    
    public function deleteDMTVatTu($id)
    {
        $this->repository->delete($id);
    }
    
    public function getDMTVatTuById($id)
    {
        $data = $this->repository->getById($id);
        return $data;
    }
}