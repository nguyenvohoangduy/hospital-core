<?php

namespace App\Log;

class DangKyKhamBenhErrorLog extends ErrorLog
{
    public function logMessageFromUserInput($request){
        $logParams['benh_vien_id'] = $request['benh_vien_id'];
        $logParams['khoa_id'] = $request['khoa_id'];
        $logParams['phong_id'] = $request['phong_id'];
        $logParams['ho_va_ten'] = $request['ho_va_ten'];
        return $logParams;
    }
}