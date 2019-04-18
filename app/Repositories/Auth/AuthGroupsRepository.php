<?php
namespace App\Repositories\Auth;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Auth\AuthGroups;
use Carbon\Carbon;
use App\Helper\Util;

class AuthGroupsRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return AuthGroups::class;
    }    
    
    public function getListAuthGroups($limit = 100, $page = 1, $keyWords ='',$benhVienId)
    {
        $column = [
            'id',
            'name',
            'description',
            'benh_vien_id'
        ];
        $model = $this->model->where('benh_vien_id',$benhVienId);
        if($keyWords!=""){
            $query = $model->where([['name', 'like', '%' . $keyWords . '%']])
                 ->orWhere([['description', 'like', '%' . $keyWords . '%']]);
        }

        $data = $model->orderBy('id', 'asc');
        
        return Util::getPartial($data,$limit,$page,$column);
    }
    
    public function getByListId($limit = 100,$page =1,$id)
    {
        $id = json_encode($id);
       
        $offset = ($page - 1) * $limit;
        
        $column = [
            'id',
            'name',
            'description'
        ];
        
        $query = $this->model;
            
        $totalRecord = $query->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            
            $data = $query->whereIn('id',$id)
                        ->orderBy('id', 'desc')
                        ->offset($offset)
                        ->limit($limit)
                        ->get($column);
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
    
    public function createAuthGroups(array $input)
    {
        $input['description']=$input['ghi_chu'];    
        $id = $this->model->create($input)->id;
        return $id;
    }

    public function getAuthGroupsById($id)
    {
        $column = [
            'auth_groups.*',
            'auth_groups_has_permissions.permission_id',
            ];
        $result = $this->model
            ->leftJoin('auth_groups_has_permissions','auth_groups_has_permissions.group_id','=','auth_groups.id')
            ->where('auth_groups.id', $id)
            ->get($column);
            //->first(); 
        return $result;
    }
    
    public function updateAuthGroups($id, array $input)
    {
        // $arr = [];
        // if($input['phongId']){
        //     foreach($input['phongId'] as $item){
        //         if(isset($item['phong_id'])){
        //             $arr[]=$item['phong_id'];
        //         }
        //     }
        // }
        // $input['meta_data']=json_encode($arr);
        $update = $this->model->findOrFail($id);
		$update->update($input);
    }
    
    public function getKhoaPhongByGroupsId($id,$benhVienId)
    {
        $metaData = $this->model
            ->where('id', $id)
            ->get()
            ->first()
            ->meta_data;
        if($metaData){
            $result = DB::table('phong')
                ->select(DB::raw("CONCAT('0',khoa_id,id) AS key"),'id as phong_id','khoa_id','ten_phong as ten_khoa_phong')
                ->whereIn('id',json_decode($metaData))
                ->get();
            return $result;
        }
    }    
    
}