<?php
namespace App\Repositories;

use App\Repositories\BaseRepositoryV2;
use App\Models\HoatChat;

class HoatChatRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return HoatChat::class;
    }    

    public function getById($id)
    {
        $data = $this->model->where('id', $id)->first();
        return $data;
    }  
    
    public function getAll()
    {
        $result = $this->model->orderBy('ten')->get();
        return $result;
    }
    
    public function getByListId(array $listId)
    {
        $result = $this->model->whereIn('id', $listId)->get();
        return $result;
    }
}