<?php

namespace App\Log;
use App\Repositories\Sqs\ErrorLog\ErrorLogRepository;

abstract class ErrorLog
{
    private $folder;
    private $bucketS3;
    
    public function __construct(ErrorLogRepository $sqsRepo) {
        $this->sqsRepo = $sqsRepo;
    }
    
    public function getFolder()
    {
        return $this->folder;
    }

    public function setFolder($folder)
    {
        $this->folder = $folder;
    }
    
    public function getBucketS3()
    {
        return $this->bucketS3;
    }

    public function setBucketS3($bucketS3)
    {
        $this->bucketS3 = $bucketS3;
    }
    
    public function logMessageFromException ($ex) {
        $logParams['file'] = $ex->getFile();
        $logParams['line'] = $ex->getLine();
        $logParams['message'] = $ex->getMessage();
        return $logParams;
    }
    
    abstract public function logMessageFromUserInput ($ex);
    
    public function toLogQueue($userInput, $ex, $messageAttributes) {
        $logParams['error'] = $this->logMessageFromException($ex);
        $logParams['user-input'] = $this->logMessageFromUserInput($userInput);
        $this->pushLogQueue($messageAttributes, $logParams);
    }
    
    private function pushLogQueue(array $attributes, array $message) {
        // file_name
        $messageAttributes = [
            'bucket'    => ['DataType' => "String",
                                'StringValue' => $this->bucketS3
                            ],
            'folder'    => ['DataType' => "String",
                                'StringValue' => $this->folder
                            ],
            'app_env'    => ['DataType' => "String",
                                'StringValue' => \Config::get('app.env')
                            ]
        ];
        //merge $attributes with $messageAttributes
        $messageAttributes = array_merge($messageAttributes, $attributes);
        try {
            // Push
            $this->sqsRepo->push(
               $messageAttributes, $message
            );
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
    }
}