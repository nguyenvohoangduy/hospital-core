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
use Cviebrock\LaravelElasticsearch\Facade as Elasticsearch;

class DanhMucThuocVatTuService
{
    const MOT_NUA = 0.5;
    const MOT_PHAN_TU = 0.25;
    const THUOC_DANG_VIEN = 'Viên';
    
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
    
    // public function getListDanhMucThuocVatTu($limit, $page)
    // {
    //     $data = $this->repository->getListDanhMucThuocVatTu($limit, $page);
        
    //     return $data;
    // }
    
    // public function getDmdvById($dmdvId)
    // {
    //     $data = $this->repository->getDataDanhMucThuocVatTuById($dmdvId);
        
    //     return $data;
    // }

    // public function createDanhMucThuocVatTu(array $input)
    // {
    //     $id = $this->repository->createDanhMucThuocVatTu($input);
        
    //     return $id;
    // }
    
    // public function updateDanhMucThuocVatTu($dmdvId, array $input)
    // {
    //     $this->repository->updateDanhMucThuocVatTu($dmdvId, $input);
    // }
    
    // public function deleteDanhMucThuocVatTu($dmdvId)
    // {
    //     $this->repository->deleteDanhMucThuocVatTu($dmdvId);
    // }
    
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
    
    public function pushToElasticSearch() {
        $data = $this->repository->getThuocVatTu();
        
        $params = ['body' => []];
        
        for ($i = 1; $i <= count($data); $i++) {
            $params['body'][] = [
                'index' => [
                    '_index' => 'dmtvt',
                    '_type' => 'doc',
                    '_id' => $i
                ]
            ];
        
            $params['body'][] = [
                'id'                    => $data[$i-1]->id,
                'nhom_danh_muc_id'      => $data[$i-1]->nhom_danh_muc_id,
                'ten'                   => $data[$i-1]->ten,
                'ten_khong_dau'         => Util::convertViToEn(strtolower($data[$i-1]->ten)),
                'ten_bhyt'              => $data[$i-1]->ten_bhyt,
                'ten_nuoc_ngoai'        => $data[$i-1]->ten_nuoc_ngoai,
                'ma'                    => $data[$i-1]->ma,
                'ma_bhyt'               => $data[$i-1]->ma_bhyt,
                'don_vi_tinh_id'        => $data[$i-1]->don_vi_tinh_id,
                'don_vi_tinh'           => $data[$i-1]->don_vi_tinh,
                'nhan_vien_tao'         => $data[$i-1]->nhan_vien_tao,
                'nhan_vien_cap_nhat'    => $data[$i-1]->nhan_vien_cap_nhat,
                'thoi_gian_tao'         => $data[$i-1]->thoi_gian_tao,
                'thoi_gian_cap_nhat'    => $data[$i-1]->thoi_gian_cap_nhat,
                'hoat_chat_id'          => $data[$i-1]->hoat_chat_id,
                'hoat_chat'             => $data[$i-1]->hoat_chat,
                'biet_duoc_id'          => $data[$i-1]->biet_duoc_id,
                'nong_do'               => $data[$i-1]->nong_do,
                'duong_dung'            => $data[$i-1]->duong_dung,
                'dong_goi'              => $data[$i-1]->dong_goi,
                'hang_san_xuat'         => $data[$i-1]->hang_san_xuat,
                'nuoc_san_xuat'         => $data[$i-1]->nuoc_san_xuat,
                'trang_thai'            => $data[$i-1]->trang_thai,
                'loai_nhom'             => $data[$i-1]->loai_nhom,
                'gia'                   => $data[$i-1]->gia,
                'gia_bhyt'              => $data[$i-1]->gia_bhyt,
                'gia_nuoc_ngoai'        => $data[$i-1]->gia_nuoc_ngoai,
            ];
        
            // Every 1000 documents stop and send the bulk request
            if ($i % 1000 == 0) {
                $responses = Elasticsearch::bulk($params);
        
                // erase the old bulk request
                $params = ['body' => []];
        
                // unset the bulk response when you are done to save memory
                unset($responses);
            }
        }
        
        // Send the last batch if it exists
        if (!empty($params['body'])) {
            $responses = Elasticsearch::bulk($params);
        }        
    }
    
    public function searchThuocVatTuByKeywords($keyWords)
    {
        $params = [
            'index' => 'dmtvt',
            'type' => 'doc',
            'body' => [
                'from' => 0,
                'size' => 1000,
                'query' => [
                    'wildcard' => [
                        'ten' => '*'.$keyWords.'*'
                    ]
                ]
            ]
        ];
        $response = Elasticsearch::search($params);   
        
        $result=[];
        foreach($response['hits']['hits'] as $item) {
            $result[] = $item['_source'];
        };
        
        return $result;
    }      
    
    public function searchThuocVatTuByListId($index, array $listId)
    {
        $params = [
            'index' => $index,
            'type' => 'doc',
            'body' => [
                'from' => 0,
                'size' => 1000,
                'query' => [
                    'terms' => [
                        '_id' => $listId
                    ]
                ]
            ]
        ];
        $response = Elasticsearch::search($params);   
        
        $result=[];
        foreach($response['hits']['hits'] as $item) {
            $result[] = $item['_source'];
        };
        
        return $result;        
    } 
    
    public function searchThuocVatTuByTenVaHoatChat($keyword)
    {
        $params = [
            'index' => 'dmtvt',
            'type' => 'doc',
            'body' => [
                'from' => 0,
                'size' => 1000,
                'query' => [
                    'bool' => [
                        'should' => [
                            'wildcard' => [
                                'ten' => '*'.$keyword.'*',
                            ], 
                            'wildcard' => [
                                'hoat_chat' => '*'.$keyword.'*'
                            ]
                        ]    
                    ]
                ]
            ]
        ];
        $response = Elasticsearch::search($params);   
        
        $result=[];
        foreach($response['hits']['hits'] as $item) {
            $result[] = $item['_source'];
        };
        
        return $result;        
    }  
    
    public function pushTvtByKhoToElasticSearch($khoId)
    {
        $data = $this->repository->getThuocVatTuByKhoId($khoId);
        
        $params = ['body' => []];
        
        for ($i = 1; $i <= count($data); $i++) {
            $params['body'][] = [
                'index' => [
                    '_index' => 'dmtvt_kho_' . $khoId,
                    '_type' => 'doc',
                    '_id' => $data[$i-1]->id
                ]
            ];
            
            $nuaVien = '';
            $motPhanTu = '';
            
            if($data[$i-1]->don_vi_quy_doi == self::THUOC_DANG_VIEN || $data[$i-1]->don_vi_tinh == self::THUOC_DANG_VIEN) {
                $nuaVien = self::MOT_NUA;
                $motPhanTu = self::MOT_PHAN_TU;
            }
        
            $params['body'][] = [
                'id'                    => $data[$i-1]->id,
                'nhom_danh_muc_id'      => $data[$i-1]->nhom_danh_muc_id,
                'ten'                   => $data[$i-1]->ten,
                'ten_khong_dau'         => Util::convertViToEn(strtolower($data[$i-1]->ten)),
                'ten_bhyt'              => $data[$i-1]->ten_bhyt,
                'ten_nuoc_ngoai'        => $data[$i-1]->ten_nuoc_ngoai,
                'ma'                    => $data[$i-1]->ma,
                'ma_bhyt'               => $data[$i-1]->ma_bhyt,
                'don_vi_tinh_id'        => $data[$i-1]->don_vi_tinh_id,
                'don_vi_tinh'           => $data[$i-1]->don_vi_tinh,
                'don_vi_quy_doi'        => $data[$i-1]->don_vi_quy_doi,
                'sl_kha_dung'           => $data[$i-1]->sl_kha_dung,
                'nhan_vien_tao'         => $data[$i-1]->nhan_vien_tao,
                'nhan_vien_cap_nhat'    => $data[$i-1]->nhan_vien_cap_nhat,
                'thoi_gian_tao'         => $data[$i-1]->thoi_gian_tao,
                'thoi_gian_cap_nhat'    => $data[$i-1]->thoi_gian_cap_nhat,
                'hoat_chat_id'          => $data[$i-1]->hoat_chat_id,
                'hoat_chat'             => $data[$i-1]->hoat_chat,
                'biet_duoc_id'          => $data[$i-1]->biet_duoc_id,
                'nong_do'               => $data[$i-1]->nong_do,
                'duong_dung'            => $data[$i-1]->duong_dung,
                'dong_goi'              => $data[$i-1]->dong_goi,
                'hang_san_xuat'         => $data[$i-1]->hang_san_xuat,
                'nuoc_san_xuat'         => $data[$i-1]->nuoc_san_xuat,
                'trang_thai'            => $data[$i-1]->trang_thai,
                'kho_id'                => $data[$i-1]->kho_id,
                'loai_nhom'             => $data[$i-1]->loai_nhom,
                'gia'                   => $data[$i-1]->gia,
                'gia_bhyt'              => $data[$i-1]->gia_bhyt,
                'gia_nuoc_ngoai'        => $data[$i-1]->gia_nuoc_ngoai,
                'he_so_le_1'            => $nuaVien,
                'he_so_le_2'            => $motPhanTu
            ];
        
            // Every 1000 documents stop and send the bulk request
            if ($i % 1000 == 0) {
                $responses = Elasticsearch::bulk($params);
        
                // erase the old bulk request
                $params = ['body' => []];
        
                // unset the bulk response when you are done to save memory
                unset($responses);
            }
        }
        
        // Send the last batch if it exists
        if (!empty($params['body'])) {
            $responses = Elasticsearch::bulk($params);
        } 
    }
    
    public function searchThuocVatTuByKhoId($khoId, $keyword)
    {
        $params = [
            'index' => 'dmtvt_kho_' . $khoId,
            'type' => 'doc',
            'body' => [
                'from' => 0,
                'size' => 10000,
                'query' => [
                    'bool' => [
                        'should' => [
                            'wildcard' => [
                                'ten' => '*'.$keyword.'*',
                            ], 
                            'wildcard' => [
                                'hoat_chat' => '*'.$keyword.'*'
                            ]
                        ]    
                    ]
                ]
            ]
        ];
        $response = Elasticsearch::search($params);   
        
        $result=[];
        foreach($response['hits']['hits'] as $item) {
            $result[] = $item['_source'];
        };
        
        return $result;        
    } 
    
    public function updateSoLuongKhaDungById(array $input)
    {
        $params = [
            'index' => 'dmtvt_kho_' . $input['kho_id'],
            'type' => 'doc',
            'id' => $input['danh_muc_thuoc_vat_tu_id'],
            'body' => [
                'doc' => [
                    'sl_kha_dung' => $input['sl_kha_dung']
                ]
            ]
        ];
        $response = Elasticsearch::update($params); 
    }
    // quanlydanhmucthuocvattu
    public function getPartialDMTVatTu($limit, $page, $keyWords)
    {
        $data = $this->repository->getPartialDMTVatTu($limit, $page, $keyWords);
        return $data;
    }
    
    public function createDMTVatTu(array $input)
    {
        $id = $this->repository->createDMTVatTu($input);
        return $id;
    } 
    
    public function updateDMTVatTu($id, array $input)
    {
        $this->repository->updateDMTVatTu($id, $input);
    }
    
    public function deleteDMTVatTu($id)
    {
        $this->repository->deleteDMTVatTu($id);
    }
    public function getDMTVatTuById($id)
    {
        $data = $this->repository->getDMTVatTuById($id);
        return $data;
    }
}