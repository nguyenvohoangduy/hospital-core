<?php

namespace App\Services;

use App\Repositories\Kho\TheKhoRepository;
use Illuminate\Http\Request;
use DB;
use Validator;

class TheKhoService {
    public function __construct(
        TheKhoRepository $theKhoRepository)
    {
        $this->theKhoRepository = $theKhoRepository;
    }
    
    public function getTonKhaDungById($id,$khoId)
    {
        $data = $this->theKhoRepository->getTonKhaDungById($id,$khoId);
        return $data;
    }
    
    public function getListThuocVatTu($limit, $page, $keyWords, $khoId)
    {
        $data = $this->theKhoRepository->getListThuocVatTu($limit, $page, $keyWords, $khoId);
        
        // $result = [];
        // foreach($data['data'] as $itemData) {
        //     if(empty($result)){
        //         $result[]=$itemData;
        //     }
        //     else {
        //         $obj = null;
        //         foreach($result as $itemResult){
        //             if($itemResult->danh_muc_thuoc_vat_tu_id == $itemData->danh_muc_thuoc_vat_tu_id){
        //                 $itemResult->sl_ton_kho+=$itemData->sl_ton_kho;
        //                 $itemResult->sl_kha_dung+=$itemData->sl_kha_dung;
        //                 $itemResult->sl_ton_kho_chan+=$itemData->sl_ton_kho_chan;
        //                 $itemResult->sl_ton_kho_le_1+=$itemData->sl_ton_kho_le_1;
        //                 $itemResult->sl_ton_kho_le_2+=$itemData->sl_ton_kho_le_2;
        //             }
        //             else{
        //                 $obj=$itemData;
        //             }
        //         }
        //         if($obj){
        //             $result[]=$obj;
        //         }
        //     }
        // }
        // $data['data']=$result;
        return $data;
    }     
    
}