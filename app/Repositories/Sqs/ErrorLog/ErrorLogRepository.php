<?php
namespace App\Repositories\Sqs\ErrorLog;

use App\Repositories\Sqs\BaseSQSRepository;
use App\Models\Sqs\ErrorLog as ErrorLogMessage;

class ErrorLogRepository extends BaseSQSRepository
{
    public function __construct() {
        $this->init(ErrorLogMessage::class, 'error-log-to-s3');
    }
}