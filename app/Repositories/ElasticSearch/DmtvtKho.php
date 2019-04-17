<?php
namespace App\Repositories\ElasticSearch;

use App\Repositories\DanhMuc\DanhMucThuocVatTuRepository;
use Cviebrock\LaravelElasticsearch\Facade as Elasticsearch;

class DmtvtKho
{
    const MOT_NUA = 0.5;
    const MOT_PHAN_TU = 0.25;
    const THUOC_DANG_VIEN = 'Viên';
    
    public function __construct(DanhMucThuocVatTuRepository $dmtvtRepository)
    {
        $this->dmtvtRepository = $dmtvtRepository;
    }
    
    public function createIndex($khoId)
    {
        $params = [
            'index' => 'dmtvt_kho_' . $khoId,
            'body' => [
                'settings' => [
                    'analysis' => [
                        'analyzer' => [
                            'folding' => [
                                'tokenizer' => 'standard',
                                'filter' =>  [ 'lowercase', 'asciifolding' ]
                            ]
                        ],
                        'normalizer' => [
                            'lowerasciinormalizer' => [
                                'type' => 'custom',
                                'filter' =>  [ 'lowercase', 'asciifolding' ]
                            ]
                        ]
                    ]
                ],
                'mappings' => [
                    'doc' => [
                        'properties' => [
                            'ten' => [
                                'type' => 'keyword',
                                "normalizer" => "lowerasciinormalizer" 
                            ],
                            'ten_khong_dau' => [
                                'type' => 'keyword'
                            ]
                        ]
                    ]
                ]
            ]
        ];
        
        Elasticsearch::indices()->create($params); 
        $this->pushTvtByKhoId($khoId);
    }
    
    private function pushTvtByKhoId($khoId)
    {
        $data = $this->dmtvtRepository->getThuocVatTuByKhoId($khoId);
        
        $params = ['body' => []];
        
        for ($i = 1; $i <= count($data); $i++) {
            $params['body'][] = [
                'index' => [
                    '_index' => 'dmtvt_kho_' . $khoId,
                    '_type' => 'doc',
                    '_id' => $data[$i-1]->id
                ]
            ];
            
            $motNua = '';
            $motPhanTu = '';
            
            if($data[$i-1]->don_vi_quy_doi == self::THUOC_DANG_VIEN || $data[$i-1]->don_vi_tinh == self::THUOC_DANG_VIEN) {
                $motNua = self::MOT_NUA;
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
                'he_so_le_1'            => $motNua,
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
    
    public function pushDmtvt() 
    {
        $data = $this->dmtvtRepository->getThuocVatTu();
        
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
    
    public function searchThuocVatTuByKeywords($keywords)
    {
        $params = [
            'index' => 'dmtvt',
            'type' => 'doc',
            'body' => [
                'from' => 0,
                'size' => 1000,
                'query' => [
                    'wildcard' => [
                        'ten' => '*'.$keywords.'*'
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
    
    public function isExistIndex($khoId)
    {
        $params = ['index' => 'dmtvt_kho_' . $khoId];
        $bool = Elasticsearch::indices()->exists($params) ? false : true;
        return $bool;
    }
}