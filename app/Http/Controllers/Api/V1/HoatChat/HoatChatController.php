<?php
namespace App\Http\Controllers\Api\V1\HoatChat;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\HoatChatService;

class HoatChatController extends APIController
{
    public function __construct(HoatChatService $hoatChatService)
    {
        $this->hoatChatService = $hoatChatService;
    }
    
    public function getAll() 
    {
        $data = $this->hoatChatService->getAll();
        return $this->respond($data);
    }
    
    
}