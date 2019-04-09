<?php
namespace App\Services;
use App\Repositories\Auth\AuthPolicyRepository;
use Illuminate\Http\Request;
use Validator;
class AuthPolicyService {
    public function __construct(
        AuthPolicyRepository $authPolicyRepository)
    {
        $this->authPolicyRepository = $authPolicyRepository;
    }
    
    public function getPartial($limit, $page, $keywords, $serviceId)
    {
        $data = $this->authPolicyRepository->getPartial($limit, $page, $keywords, $serviceId);
        return $data;
    }
    
    public function create(array $input)
    {
        $id = $this->authPolicyRepository->create($input);
        return $id;
    } 
    
    public function update($id, array $input)
    {
        $this->authPolicyRepository->update($id, $input);
    }
    
    public function delete($id)
    {
        $this->authPolicyRepository->delete($id);
    }
    
    public function getById($id)
    {
        $data = $this->authPolicyRepository->getById($id);
        return $data;
    }
    
    public function checkKey($key)
    {
        $data = $this->authPolicyRepository->checkKey($key);
        return $data;
    } 
    
    public function getByServiceId($serviceId)
    {
        $data = $this->authPolicyRepository->getByServiceId($serviceId);
        return $data;
    }    
}