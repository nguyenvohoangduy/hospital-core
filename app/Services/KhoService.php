<?php
namespace App\Services;
use App\Repositories\Kho\KhoRepository;
use App\Repositories\BenhVienRepository;
use App\Repositories\ElasticSearch\DmtvtKho;
use Illuminate\Http\Request;
use Validator;
class KhoService {
    public function __construct
    (
        KhoRepository $khoRepository,
        BenhVienRepository $benhVienRepository,
        DmtvtKho $dmtvtKho
    )
    {
        $this->khoRepository = $khoRepository;
        $this->benhVienRepository = $benhVienRepository;
        $this->dmtvtKho = $dmtvtKho;
    }
    public function getListKho($limit, $page, $keyWords, $benhVienId)
    {
        $data = $this->khoRepository->getListKho($limit, $page, $keyWords, $benhVienId);
        if(!empty($data['data'])){
            foreach($data['data'] as $item){
                $benhVien = $this->benhVienRepository->find($item->benh_vien_id);
                $item['ten_benh_vien']=$benhVien['ten'];
            }
        }
        return $data;
    }
    
    public function createKho(array $input)
    {
        $id = $this->khoRepository->createKho($input);
        $this->dmtvtKho->createIndex($id);
        return $id;
    } 
    
    public function updateKho($id, array $input)
    {
        $this->khoRepository->updateKho($id, $input);
        $notExistIndex = $this->dmtvtKho->isExistIndex($id);
        if($notExistIndex) {
            $this->dmtvtKho->createIndex($id);
        }
    }
    
    public function deleteKho($id)
    {
        $this->khoRepository->deleteKho($id);
    }
    
    public function getKhoById($id)
    {
        $data = $this->khoRepository->getKhoById($id);
        return $data;
    }
    
    public function getAllKhoByBenhVienId($benhVienId)
    {
        $data = $this->khoRepository->getAllKhoByBenhVienId($benhVienId);
        return $data;
    }  
    
    public function getKhoByListId(array $listId)
    {
        $data = $this->khoRepository->getKhoByListId($listId);
        return $data;
    }
    
    public function getNhapTuNccByBenhVienId($benhVienId)
    {
        $data = $this->khoRepository->getNhapTuNccByBenhVienId($benhVienId);
        return $data;
    }  
    
    public function searchThuocVatTuByKeywords($keywords)
    {
        $data = $this->dmtvtKho->searchThuocVatTuByKeywords($keywords);
        return $data;
    }
    
    public function searchThuocVatTuByListId($index, array $listId)
    {
        $data = $this->dmtvtKho->searchThuocVatTuByListId($index, $listId);
        return $data;
    }
    
    public function searchThuocVatTuByTenVaHoatChat($keyword)
    {
        $data = $this->dmtvtKho->searchThuocVatTuByTenVaHoatChat($keyword);
        return $data;
    }
    
    public function searchThuocVatTuByKhoId($khoId, $keyword)
    {
        $data = $this->dmtvtKho->searchThuocVatTuByKhoId($khoId, $keyword);
        return $data;
    }
    
    public function getListKhoLap($loaiKho,$benhVienId)
    {
        $data = $this->khoRepository->getListKhoLap($loaiKho,$benhVienId);
        return $data;
    }    
}