<?php

namespace App\Log;

class HoiBenhErrorLog extends ErrorLog
{
    public function logMessageFromUserInput($request){
        $logParams['ly_do_vao_vien'] = $request['ly_do_vao_vien'];
        $logParams['qua_trinh_benh_ly'] = $request['qua_trinh_benh_ly'];
        $logParams['tien_su_benh_ban_than'] = $request['tien_su_benh_ban_than'];
        $logParams['tien_su_benh_gia_dinh'] = $request['tien_su_benh_gia_dinh'];
        return $logParams;
    }
}