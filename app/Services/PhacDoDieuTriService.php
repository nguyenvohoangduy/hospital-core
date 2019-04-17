<?php
namespace App\Services;

use App\Http\Resources\PddtResource;
use App\Repositories\PhacDoDieuTriRepository;
use App\Repositories\DanhMuc\DanhMucDichVuRepository;
use App\Repositories\HoatChatRepository;
use App\Services\DanhMucThuocVatTuService;
use App\Services\ElasticSearchService;

class PhacDoDieuTriService
{
    const INDEX_DMTVT = 'dmtvt';
    const INDEX_SL_KHA_DUNG_TVT = 'sl_kha_dung_tvt';
    const LOAI_CAN_LAM_SANG = 1;
    
    public function __construct
    (
        PhacDoDieuTriRepository $pddtRepository, 
        DanhMucDichVuRepository $dmdvRepository,
        HoatChatRepository $hoatChatRepository,
        DanhMucThuocVatTuService $danhMucThuocVatTuService,
        ElasticSearchService $elasticSearchService
    )
    {
        $this->pddtRepository = $pddtRepository;
        $this->dmdvRepository = $dmdvRepository;
        $this->hoatChatRepository = $hoatChatRepository;
        $this->danhMucThuocVatTuService = $danhMucThuocVatTuService;
        $this->elasticSearchService = $elasticSearchService;
    }
    
    public function createPhacDoDieuTri(array $input)
    {
        $this->pddtRepository->createPhacDoDieuTri($input);
    }
    
    public function getPddtByIcd10Id($icd10Id)
    {
        $result = $this->pddtRepository->getPddtByIcd10Id($icd10Id);
        $data = [];
        
        if($result['listIdCls']) {
            $data['yLenh'] = $this->dmdvRepository->getYLenhByListId($result['listIdCls']);
            $data['list'] = $result['list'];
        }
        
        if($result['listIdTvt']) {
            $data['thuocVatTu'] = $this->elasticSearchService->searchThuocVatTuByListId(self::INDEX_DMTVT, $result['listIdTvt']);
            $data['list'] = $result['list'];
        }
        
        return $data;
    }
    
    public function getPddtById($pddtId)
    {
        $result = $this->pddtRepository->getPddtById($pddtId);
        if($result['listId']) {
            if($result['obj']->loai_nhom == self::LOAI_CAN_LAM_SANG) {
                $data['yLenh'] = $this->dmdvRepository->getYLenhByListId($result['listId']);
                $data['hoatChat'] = [];
            } else {
                $data['yLenh'] = [];
                $data['hoatChat'] = $this->hoatChatRepository->getByListId($result['listId']);
            }
            
            $data['obj'] = $result['obj'];
            return $data;
        } else {
            return [];
        }
    }
    
    public function updatePhacDoDieuTri($pddtId, array $input)
    {
        $this->pddtRepository->updatePhacDoDieuTri($pddtId, $input);
    }
    
    public function getPddtByIcd10Code($icd10Code)
    {
        $result = $this->pddtRepository->getPddtByIcd10Code($icd10Code);
        $data = [];
        
        if($result) {
            if($result['listIdCls']) {
                $data['yLenh'] = $this->dmdvRepository->getYLenhByListId($result['listIdCls']);
            } else {
                $data['yLenh'] = [];
            }
                
            if($result['listIdHc']) {
                $data['hoatChat'] = $this->hoatChatRepository->getByListId($result['listIdHc']);
            } else {
                $data['hoatChat'] = [];
            }
                
            $data['list'] = $result['list'];
        }
        
        return $data;
    }
    
    public function saveYLenhGiaiTrinh(array $input)
    {
        $this->pddtRepository->saveYLenhGiaiTrinh($input);
    }
    
    public function confirmGiaiTrinh(array $input)
    {
        $this->pddtRepository->confirmGiaiTrinh($input);
    }
}