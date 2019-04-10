<?php
namespace App\Repositories;

use DB;
use App\Models\Phong;
use App\Repositories\BaseRepositoryV2;

class PhongRepository extends BaseRepositoryV2
{
    const BENH_AN_KHAM_BENH = 24;
    const TRANG_THAI_HOAT_DONG = 1;
    const PHONG_HANH_CHINH = 1;
    const PHONG_NOI_TRU = 3;
    
    const KHOA_KHAM_BENH_DON_TIEP = 'KKB_ĐT';
    
    public function getModel()
    {
        return Phong::class;
    }
    
    public function getListPhong($loaiPhong,$khoaId)
    {
        $phong = $this->model->where([
                                'loai_phong'=>$loaiPhong,
                                'khoa_id'=>$khoaId,
                                //'loai_benh_an'=>self::BENH_AN_KHAM_BENH,
                                'trang_thai'=>self::TRANG_THAI_HOAT_DONG
                            ])
                            ->orderBy('ten_phong')
                            ->get();
        return $phong;    
    }
    
    public function getNhomPhong($loaiPhong,$khoaId)
    {
        $phong = $this->model->where([
                                'loai_phong'=>$loaiPhong,
                                'khoa_id'=>$khoaId,
                                //'loai_benh_an'=>self::BENH_AN_KHAM_BENH,
                                'trang_thai'=>self::TRANG_THAI_HOAT_DONG
                            ])
                            ->orderBy('ten_nhom')
                            ->distinct()
                            ->get(['ten_nhom','ma_nhom']);
        return $phong;    
    }
    
    public function getDataById($id)
    {
        $phong = $this->model->where(['id'=>$id])
                            ->get()
                            ->first();
        return $phong;
    }
    
    public function getPhongHanhChinhByKhoaID($khoaId)
    {
        $phong = $this->model->where([
                                ['khoa_id', '=', $khoaId],
                                ['loai_phong', '=', self::PHONG_HANH_CHINH],
                                //['loai_benh_an', '!=', self::BENH_AN_KHAM_BENH]
                            ])
                            ->get()
                            ->first();
        return $phong;
    }
    
    public function getPhongNoiTruByKhoaId($khoaId) {
        $phong = $this->model->where([
                                ['khoa_id', '=', $khoaId],
                                ['loai_phong', '=', self::PHONG_NOI_TRU]
                            ])
                            ->get()
                            ->first();
        return $phong;
    }
    
    public function getListKhoaPhongByBenhVienId($benhVienId) {
        $column = [
            'phong.id',
            'phong.khoa_id',
            'phong.ten_phong',
            'phong.ma_nhom',
            'khoa.ten_khoa',
            'phong.loai_phong',
            'khoa.benh_vien_id'
        ];
        
        $data = $this->model
                        ->join('khoa', function($join) use ($benhVienId) {
                            $join->on('khoa.id', '=', 'phong.khoa_id')
                                ->where('khoa.benh_vien_id', '=', $benhVienId);
                        })
                        ->orderBy('khoa.ten_khoa')
                        ->get($column);
        return $data;
    }
    
    public function getMaNhomPhongByKhoaId($khoaId) {
        $data = $this->model->where('khoa_id',$khoaId)->distinct()->orderBy('ma_nhom')->get(['ma_nhom']);
        return $data;
    }    
    
    public function getKhoaPhongDonTiepByBenhVienId($benhVienId) {
        $column = [
            'phong.id',
            'phong.khoa_id',
            'phong.ten_phong',
            'phong.ma_nhom',
            'khoa.ten_khoa',
            'phong.loai_phong',
            'khoa.benh_vien_id'
        ];
        
        $data = $this->model
                        ->join('khoa', function($join) use ($benhVienId) {
                            $join->on('khoa.id', '=', 'phong.khoa_id')
                                ->where('khoa.benh_vien_id', '=', $benhVienId);
                        })
                        ->where('phong.ma_nhom', '=', self::KHOA_KHAM_BENH_DON_TIEP)
                        ->orderBy('khoa.ten_khoa')
                        ->get($column);
        return $data;
    }
}