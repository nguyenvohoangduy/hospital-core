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
    
    public function getByHsbaId($hsbaId, $phongId)
    {
        $data = $this->hsbaDonViRepository->getByHsbaId($hsbaId, $phongId);
        return $data;
    }
}