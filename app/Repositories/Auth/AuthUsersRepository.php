<?php
namespace App\Repositories\Auth;
use DB;
use App\Repositories\BaseRepositoryV2;
use App\Models\Auth\AuthUsers;
use Carbon\Carbon;
use App\Helper\Util;

class AuthUsersRepository extends BaseRepositoryV2
{
    const ACTIVE = 1;
    
    public function getModel()
    {
        return AuthUsers::class;
    }    

     public function getIdByEmail($email)
    {
        $data = $this->model->where('email',$email)->first();
        if($data)
            return $data;
        else 
            return null;
    }
    
    public function getUserNameByEmail($email)
    {
        $data = $this->model->where('email',$email)->first();
        if($data)
            return $data;
        else
            return null;
    }
    
    public function getUserById($authUsersId)
    {
        $data = $this->model->where('id', $authUsersId)->first();
        if($data)
            return true;
        else
            return false;
    }
    
    public function getInforAuthUserById($authUsersId)
    {
        $data = $this->model->where('id', $authUsersId)->first();
        return $data;
    }    
    
    public function getListNguoiDung($limit = 100, $page = 1, $keyWords ='')
    {
        $offset = ($page - 1) * $limit;
        
        $column = [
            'id',
            'fullname',
            'email',
            'khoa',
            'chuc_vu',
            'created_at',
            'updated_at',
            'userstatus'
        ];
        
        $query = $this->model;
        if($keyWords!=""){
            $query = $query->where('fullname', 'like', '%' . strtolower($keyWords) . '%')
                 ->orWhere('email', 'like', '%' . strtolower($keyWords) . '%');
        }
            
        $totalRecord = $query->count();
        if($totalRecord) {
            $totalPage = ($totalRecord % $limit == 0) ? $totalRecord / $limit : ceil($totalRecord / $limit);
            
            $data = $query->orderBy('id', 'desc')
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
    
    public function getAuthUsersById($id)
    {
        $result = $this->model->where('id', $id)->first(); 
        return $result;
    }
    
    public function createAuthUsers(array $input)
    {
        $unique=$this->model->where('email',$input['email'])->first();
        if(!$unique)
        {
            if($input['userstatus']==true)
                $input['userstatus']=1;
            else
                $input['userstatus']=0;
            $input['password']=bcrypt($input['password']);    
            $input['created_at']=Carbon::now()->toDateTimeString();
            $input['updated_at']=Carbon::now()->toDateTimeString();
            $id = $this->model->create($input)->id;
            return $id;
        }
    }
    public function deleteAuthUsers($id)
    {
        $this->model->destroy($id);
    }
    
    public function checkEmailbyEmail($email)
    {
        $data = $this->model->where('email', $email)->first();
        return $data;
    }
    
    public function updateAuthUsers($id, array $input)
    {
        if($input['userstatus']==true)
            $input['userstatus']=1;
        else
            $input['userstatus']=0; 
        $update = $this->model->findOrFail($id);
		$update->update($input);
    }
    
     public function resetPasswordByUserId($input)
    {
        $password = bcrypt($input['password']);
        $this->model->where('id',$input['id'])->update(['password' => $password]);
    }  
    
    public function updateLastVisit($email)
    {
        $loginDate = date('m/d/Y h:i:s a', time());
        $this->model->where('email',$email)->update(['login_at' => $loginDate]);
    }
    
    public function getAuthUserThuNgan() {
        $limit = 100;
        $page = 1;
        $offset = ($page - 1) * $limit;
        
        $column = [
            'id',
            'fullname',
        ];
        
        $khoaThuNgan = ['Khoa Tài Chính Kế Toán', 'Khoa Khám Bệnh'];
        
        $query = $this->model;
        
        $query = $query->whereIn('khoa', $khoaThuNgan);
        $data = $query->orderBy('id', 'desc');
        
        return Util::getPartial($data,$limit,$page);
    }
    
    public function checkActive($email)
    {
        $where = [
            ['email','=',$email],
            ['userstatus','=',self::ACTIVE]
            ];
       $find = $this->model->where($where)->first();
       if($find) return true;
       else return false;
    }     
}