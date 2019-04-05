<?php

namespace App\Log;

class KhamBenhErrorLog extends ErrorLog
{
    public function logMessageFromUserInput($request){
        return $request;
    }
}