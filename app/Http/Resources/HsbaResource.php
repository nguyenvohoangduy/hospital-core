<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Carbon\Carbon;

class HsbaResource extends Resource
{
    public function toArray($request)
    {
        return [
            'id'                        => $this->id,
            'gioi_tinh'                 => $this->gioi_tinh,
            'nghe_nghiep_id'            => $this->nghe_nghiep_id,
            'dan_toc_id'                => $this->dan_toc_id,
            'quoc_tich_id'              => $this->quoc_tich_id,
            'loai_benh_an'              => $this->loai_benh_an,
            'ten_benh_nhan'             => $this->ten_benh_nhan,
            'benh_nhan_id'              => $this->benh_nhan_id,
            'ms_bhyt'                   => $this->ms_bhyt,
            'ngay_sinh'                 => $this->ngay_sinh,
            'nam_sinh'                  => $this->nam_sinh,
            'tuoi'                      => Carbon::parse($this->ngay_sinh)->diffInYears(Carbon::now()),
            'so_nha'                    => $this->so_nha,
            'duong_thon'                => $this->duong_thon,
            'phuong_xa_id'              => $this->phuong_xa_id,
            'quan_huyen_id'             => $this->quan_huyen_id,
            'tinh_thanh_pho_id'         => $this->tinh_thanh_pho_id,
            'noi_lam_viec'              => $this->noi_lam_viec,
            'hsba_id'                   => $this->hsba_id,
            'ten_phuong_xa'             => $this->ten_phuong_xa,
            'ten_quan_huyen'            => $this->ten_quan_huyen,
            'ten_tinh_thanh_pho'        => $this->ten_tinh_thanh_pho,
            'dien_thoai_benh_nhan'      => $this->dien_thoai_benh_nhan,
            'email_benh_nhan'           => $this->email_benh_nhan,
            'dia_chi_lien_he'           => $this->dia_chi_lien_he,
            'url_hinh_anh'              => $this->url_hinh_anh,
            'nguoi_than'                => $this->nguoi_than,
            'trang_thai_hsba'           => $this->trang_thai_hsba,
            'is_dang_ky_truoc'          => $this->is_dang_ky_truoc,
            'phong_id'                  => $this->phong_id,
            'ten_phong'                 => $this->ten_phong,
            'ma_cskcbbd'                => $this->ma_cskcbbd,
            'tu_ngay'                   => $this->tu_ngay,
            'den_ngay'                  => $this->den_ngay,
            'ma_noi_song'               => $this->ma_noi_song,
            'du5nam6thangluongcoban'    => $this->du5nam6thangluongcoban,
            'dtcbh_luyke6thang'         => $this->dtcbh_luyke6thang,
            'ma_so_thue'                => $this->ma_so_thue,
            'so_cmnd'                   => $this->so_cmnd,
            'ma_tiem_chung'             => $this->ma_tiem_chung,
            'url_hinh_anh'              => $this->url_hinh_anh,
        ];
    }
}