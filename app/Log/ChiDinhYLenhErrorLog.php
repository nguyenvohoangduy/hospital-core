<?php

namespace App\Log;

class ChiDinhYLenhErrorLog extends ErrorLog
{
    public function logMessageFromUserInput($request){
        return $request;
    }
}