<?php
namespace App\Services;

use App\Http\Resources\PddtResource;
use App\Repositories\HoatChatRepository;

class HoatChatService
{
    public function __construct(HoatChatRepository $hoatChatRepository)
    {
        $this->hoatChatRepository = $hoatChatRepository;
    }
    
    public function getAll()
    {
        $result = $this->hoatChatRepository->getAll();
        return $result;
    }
    
    
}