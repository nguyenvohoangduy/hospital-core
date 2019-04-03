<?php
namespace App\Http\Controllers\Api\V1\HoatChat;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\HoatChatService;

//use Requests
use App\Http\Requests\HoatChatFormRequest;

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
    
    public function getPartial(Request $request)
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        $keyWords = $request->query('keyWords', '');
        
        $data = $this->hoatChatService->getPartial($limit,$page,$keyWords);
        return $this->respond($data);
    }
    
    public function create(HoatChatFormRequest $request)
    {
        $input = $request->all();
        
        $id = $this->hoatChatService->create($input);
        if($id) {
            $this->setStatusCode(201);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    
    public function update($id, HoatChatFormRequest $request)
    {
        try {
            $isNumericId = is_numeric($id);
            $input = $request->all();
            
            if($isNumericId) {
                $this->hoatChatService->update($id, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    
    public function getHoatChatById($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $data = $this->hoatChatService->getHoatChatById($id);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
}