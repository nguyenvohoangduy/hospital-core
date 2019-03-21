<?php
namespace App\Services;

// Framework Libraries
use Illuminate\Http\Request;
use Validator;
use Storage;
use Exception;
use App\Helper\AwsS3;

// Repositories
use App\Repositories\Hsba\HsbaDonViRepository;
use App\Repositories\BenhVienRepository;

class HsbaDonViService 
{
    
    public function __construct(
        HsbaDonViRepository $hsbaDonViRepository,
        BenhVienRepository $benhVienRepository
    )
    {
        $this->hsbaDonViRepository = $hsbaDonViRepository;
        $this->benhVienRepository = $benhVienRepository;
    }
    
    public function getBenhVienThietLap($id) {
        $data = $this->benhVienRepository->getBenhVienThietLap($id);
        return $data;
    }
    
    public function getListPhongNoiTru($benhVienId, $khoaId, $phongId, $limit, $page, $options) {
        $repo = $this->hsbaDonViRepository;
        $repo = $repo   ->setKhoaPhongParams($benhVienId, $khoaId, $phongId)
                        ->setKeyWordParams($options['keyword']??null)
                        ->setKhoangThoiGianVaoVienParams($options['thoi_gian_vao_vien_from']??null, $options['thoi_gian_vao_vien_to']??null)
                        ->setPaginationParams($limit, $page);
        $data = $repo->getListPhongNoiTru();                
        return $data;
    }
    
    public function getListKhoaKhamBenh($benhVienId, $phongId, $limit, $page, $options) {
        $dataBenhVienThietLap = $this->getBenhVienThietLap($benhVienId);
        $khoaId = $dataBenhVienThietLap['khoaKhamBenh'];
        $phongDonTiepId = $dataBenhVienThietLap['phongDonTiepID'];
        if($phongDonTiepId == $phongId)
            $phongId = null;
        return $this->getListV2($benhVienId, $khoaId, $phongId, $limit, $page, $options);
    }
    
    private function getListV2($benhVienId, $khoaId, $phongId, $limit, $page, $options) {
        $repo = $this->hsbaDonViRepository;
        $dataBenhVienThietLap = $this->getBenhVienThietLap($benhVienId);
        $khoaId = $dataBenhVienThietLap['khoaHienTai'];
        $phongId = $phongId;
        
        $repo = $repo   ->setKhoaPhongParams($benhVienId, $khoaId, $phongId)
                        ->setKeyWordParams($options['keyword']??null)
                        ->setKhoangThoiGianVaoVienParams($options['thoi_gian_vao_vien_from']??null, $options['thoi_gian_vao_vien_to']??null)
                        ->setKhoangThoiGianRaVienParams($options['thoi_gian_ra_vien_from']??null, $options['thoi_gian_ra_vien_to']??null)
                        ->setLoaiBenhAnParams($options['loai_benh_an']??null)
                        //->setStatusHsbaKpParams($options['status_hsba_khoa_phong']??-1)
                        ->setPaginationParams($limit, $page);
        $data = $repo->getListV2();                
        return $data;
    }
    
    public function getByHsbaId($hsbaId, $phongId, $benhVienId)
    {
        $dataBenhVienThietLap = $this->getBenhVienThietLap($benhVienId);
        $data = $this->hsbaDonViRepository->getByHsbaId($hsbaId, $phongId, $dataBenhVienThietLap);
        return $data;
    }
    
    public function update($hsbaDonViId, array $params)
    {
        $this->hsbaDonViRepository->update($hsbaDonViId, $params);
    }    
    
    public function getListPhongHanhChinh($benhVienId, $khoaId, $phongId, $limit, $page, $options) {
        $repo = $this->hsbaDonViRepository;
        $repo = $repo   ->setKhoaPhongParams($benhVienId, $khoaId, $phongId)
                        ->setKeyWordParams($options['keyword']??null)
                        ->setKhoangThoiGianRaVienParams($options['thoi_gian_ra_vien_from']??null, $options['thoi_gian_ra_vien_to']??null)
                        ->setPaginationParams($limit, $page);
        $data = $repo->getListPhongHanhChinh();                
        return $data;
    }
    
    public function getPhongChoByHsbaId($hsbaId, $phongId)
    {
        $data = $this->hsbaDonViRepository->getPhongChoByHsbaId($hsbaId, $phongId);
        return $data;
    }
}