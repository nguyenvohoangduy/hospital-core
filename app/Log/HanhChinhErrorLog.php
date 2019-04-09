<?php

namespace App\Log;

class HanhChinhErrorLog extends ErrorLog
{
    public function logMessageFromUserInput($request){
        return $request;
    }
}