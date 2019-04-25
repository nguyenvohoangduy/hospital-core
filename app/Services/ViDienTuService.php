<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Validator;
use DB;
use Carbon\Carbon;

use App\Models\Phong;
use App\Repositories\ViDienTu\LichSuGiaoDichRepository;
use App\Repositories\BenhNhan\BenhNhanRepository;
use App\Repositories\BenhVienRepository;
use App\Log\ViDienTuErrorLog;

class ViDienTuService {
    public function __construct(
        LichSuGiaoDichRepository $lichSuGiaoDichRepository, 
        BenhNhanRepository $benhNhanRepository,
        BenhVienRepository $benhVienRepository,
        ViDienTuErrorLog $errorLog
    )
    {
        $this->lichSuGiaoDichRepository = $lichSuGiaoDichRepository;
        $this->benhNhanRepository = $benhNhanRepository;
        $this->benhVienRepository = $benhVienRepository;
        $this->errorLog = $errorLog;
    }

    public function giaoDich($request) {
        $result = DB::transaction(function () use ($request) {
            try {
                //1. Update tien vao bang benh_nhan
                //2. Tao record moi o lich su giao dich
                $this->updateBenhNhan($request);
                $this->createLichSuGiaoDich($request);
            } catch(\Throwable  $ex) {
                //store log
                $this->exceptionToLog($request, $ex);
                throw $ex;
            } catch (\Exception $ex) {
                //store log
                $this->exceptionToLog($request, $ex);
                throw $ex;
            } 
        });
        return $result;
    }
    
    private function updateBenhNhan($request) {
        $dataBenhNhan = $this->benhNhanRepository->getById($request['benh_nhan_id']);
        $newEncrypter = new \Illuminate\Encryption\Encrypter(env('APP_SECRET'), \Config::get('app.cipher'));
        //$dataBenhNhan['vi_dien_tu'] = $newEncrypter->encrypt(self::VI_DIEN_TU_MAC_DINH);
        $dataBenhNhan['vi_dien_tu'] = $dataBenhNhan['vi_dien_tu'] ? $newEncrypter->decrypt($dataBenhNhan['vi_dien_tu']) : 0;
        $viDienTu = intval($dataBenhNhan['vi_dien_tu']) + $request['so_tien'];
        
        $data['vi_dien_tu'] = $newEncrypter->encrypt($viDienTu);
        $this->benhNhanRepository->update($request['benh_nhan_id'], $data);
    }
    
    private function createLichSuGiaoDich($request) {
        $data['benh_nhan_id'] = $request['benh_nhan_id'];
        $data['noi_dung'] = $request['noi_dung'];
        $data['so_tien'] = Crypt::encryptString($request['so_tien']);
        $data['ngay_giao_dich'] = Carbon::now()->toDateTimeString();
        $this->lichSuGiaoDichRepository->create($data);
    }
    
    private function exceptionToLog($params, $ex) {
        $bucketS3 = $this->getBucketS3ByBenhVienId($params['benh_vien_id']);
        $this->errorLog->setBucketS3($bucketS3);
        $this->errorLog->setFolder('vi-dien-tu');
        $messageAttributes = [
            'key'    => ['DataType' => "String",
                'StringValue' => $params['benh_nhan_id']
            ],
        ];
        $this->errorLog->toLogQueue($params, $ex, $messageAttributes);
    }
    
    private function getBucketS3ByBenhVienId($benhVienId) {
        $data = $this->benhVienRepository->getBenhVienThietLap($benhVienId);
        return $data['bucket'];
    }
}