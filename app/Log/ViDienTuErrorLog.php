<?php

namespace App\Log;

class ViDienTuErrorLog extends ErrorLog
{
    public function logMessageFromUserInput($request){
        return $request;
    }
}