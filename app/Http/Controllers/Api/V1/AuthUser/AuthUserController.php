<?php
namespace App\Http\Controllers\Api\V1\AuthUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\AuthUsersService;
use App\Http\Requests\AuthUserFormRequest;
use App\Http\Requests\UpdateAuthUsersFormRequest;

class AuthUserController extends APIController
{
    public function __construct(AuthUsersService $authUsersService)
    {
        $this->authUsersService = $authUsersService;
    }
    
    public function index(Request $request)
    {
        $this->setStatusCode(200);
        return $this->respond([]);
    }

    public function getListNguoiDung(Request $request)
    {
        $limit = $request->query('limit', 100);
        $page = $request->query('page', 1);
        $keyWords = $request->query('keyWords', '');
        
        $data = $this->authUsersService->getListNguoiDung($limit, $page, $keyWords);
        return $this->respond($data);
    }
    
    public function getAuthUsersById($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $data = $this->authUsersService->getAuthUsersById($id);
        } else {
            $this->setStatusCode(400);
            $data = [];
        }
        
        return $this->respond($data);
    }
    
    public function createAuthUsers(AuthUserFormRequest $request)
    {
        if ($request->isMethod('get')) {
            $this->setStatusCode(200);
            return $this->respond([]);
        }
            
        $input = $request->all();
        
        $id = $this->authUsersService->createAuthUsers($input);
        if($id) {
            $this->setStatusCode(201);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);
    }
    
    public function deleteAuthUsers($id)
    {
        $isNumericId = is_numeric($id);
        
        if($isNumericId) {
            $this->authUsersService->deleteAuthUsers($id);
            $this->setStatusCode(204);
        } else {
            $this->setStatusCode(400);
        }
        
        return $this->respond([]);        
    }

    public function checkEmailbyEmail($email)
    {
        $data = $this->authUsersService->checkEmailbyEmail($email);
        return $this->respond($data);
    }
    
    public function updateAuthUsers($id,UpdateAuthUsersFormRequest $request)
    {
        try {
            if ($request->isMethod('get')) {
                $this->setStatusCode(200);
                return $this->respond([]);
            }            
            
            $isNumericId = is_numeric($id);
            $input = $request->all();
            
            if($isNumericId) {
                $this->authUsersService->updateAuthUsers($id, $input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    
    public function resetPasswordByUserId(Request $request)
    {
        try {
            $input = $request->all();
            var_dump($input);
            $isNumericId = is_numeric($input['id']);
            
            if($isNumericId) {
                $this->authUsersService->resetPasswordByUserId($input);
            } else {
                $this->setStatusCode(400);
            }
        } catch (\Exception $ex) {
            return $ex;
        }
    }    
    
    public function getAuthUserThuNgan(Request $request) {
        $data = $this->authUsersService->getAuthUserThuNgan();
        return $this->respond($data);
    }
}