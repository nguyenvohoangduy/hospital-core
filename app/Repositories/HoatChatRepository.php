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
    
    public function getPartial($limit = 100, $page = 1, $keyWords ='')
    {
        $offset = ($page - 1) * $limit;
        
        $model = $this->model;
        
        if($keyWords!=""){
            // $model->whereRaw('LOWER(ten) LIKE ? ',['%'.strtolower($keyWords).'%']);
            
            $model = $model->where(function($queryAdv) use ($keyWords) {
                $upperCase = mb_convert_case($keyWords, MB_CASE_UPPER, "UTF-8");
                $lowerCase = mb_convert_case($keyWords, MB_CASE_LOWER, "UTF-8");
                $titleCase = mb_convert_case($keyWords, MB_CASE_TITLE, "UTF-8");
                $ucfirst = ucfirst($keyWords);
                
                $queryAdv->where('ten', 'like', '%'.$upperCase.'%')
                        ->orWhere('ten', 'like', '%'.$lowerCase.'%')
                        ->orWhere('ten', 'like', '%'.$titleCase.'%')
                        ->orWhere('ten', 'like', '%'.$keyWords.'%')
                        ->orWhere('ten', 'like', '%'.$ucfirst.'%');
                    
            });
        }
        
        $totalRecord = $model->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
          
            $data = $model
                        ->orderBy('ten', 'ASC')
                        ->offset($offset)
                        ->limit($limit)
                        ->get();
        } else {
            $totalPage = 0;
            $data = [];
            $page = 0;
            $totalRecord = 0;
        }
          
        $result = [
            'data'          => $data,
            'page'          => $page,
            'totalPage'     => $totalPage,
            'totalRecord'   => $totalRecord
        ];
      
        return $result;
    }
    
    public function create(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
    
    public function update($id, array $input)
    {
        $result = $this->find($id);
        if ($result) {
            $result->update($input);
        }
    }


}