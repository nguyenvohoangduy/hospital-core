<?php
namespace App\Repositories;

use App\Repositories\BaseRepositoryV2;
use App\Models\DonViTinh;
use App\Helper\Util;

class DonViTinhRepository extends BaseRepositoryV2
{
    const DON_VI_CO_BAN = 1;
    
    public function getModel()
    {
        return DonViTinh::class;
    }  
    
    public function getAll()
    {
        $result = $this->model->orderBy('ten', 'asc')->get();
        return $result;
    }

    public function getPartial($limit = 100, $page = 1, $keyword = '')
    {
        $query = $this->model;
        
        if($keyword != '') {
            $query = $query->where(function($queryAdv) use ($keyword) {
                $upperCase = mb_convert_case($keyword, MB_CASE_UPPER, "UTF-8");
                $lowerCase = mb_convert_case($keyword, MB_CASE_LOWER, "UTF-8");
                $titleCase = mb_convert_case($keyword, MB_CASE_TITLE, "UTF-8");
                $ucfirst = ucfirst($keyword);
                
                $queryAdv->where('ten', 'like', '%'.$upperCase.'%')
                        ->orWhere('ten', 'like', '%'.$lowerCase.'%')
                        ->orWhere('ten', 'like', '%'.$titleCase.'%')
                        ->orWhere('ten', 'like', '%'.$keyword.'%')
                        ->orWhere('ten', 'like', '%'.$ucfirst.'%');
            });
        }
        $query = $query->orderBy('ten', 'asc');
        
        return Util::getPartial($query,$limit,$page);
    }
    
    public function getDonViCoBan()
    {
        $data = $this->model->where('don_vi_co_ban', self::DON_VI_CO_BAN)->orderBy('ten', 'asc')->get();
        return $data;
    } 
    
    // public function create(array $input)
    // {
    //     $result = $this->model->create($input);
    //     return $result;
    // }
    
    // public function update($id, array $input)
    // {
    //     $result = $this->model->update($input);
    //     return $result;
    // }
    
    public function getById($id)
    {
        $data = $this->model->where('id', $id)->first();
        return $data;
    } 
}