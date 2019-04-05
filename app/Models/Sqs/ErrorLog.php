<?php
namespace App\Models\Sqs;

class ErrorLog extends BaseModel
{
    public $attributes = [];
    public $skipCheckFields = true;
    public $message;    
    public $body;
    
    public $validations = [];
    
}
