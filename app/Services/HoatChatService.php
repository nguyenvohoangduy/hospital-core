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
    
     public function getPartial($limit, $page, $keyWords)
    {
        $data = $this->hoatChatRepository->getPartial($limit, $page, $keyWords);
        return $data;
    }
    
    public function create(array $input)
    {
        $id = $this->hoatChatRepository->create($input);
        return $id;
    } 
    
    public function update($id, array $input)
    {
        $this->hoatChatRepository->update($id, $input);
    }

    public function getById($id)
    {
        $data = $this->hoatChatRepository->getById($id);
        return $data;
    }
    
    
}