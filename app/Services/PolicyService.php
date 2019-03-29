<?php
namespace App\Services;
use App\Repositories\Policy\PolicyRepository;
use Illuminate\Http\Request;
use Validator;
class PolicyService {
    public function __construct(
        PolicyRepository $policyRepository)
    {
        $this->policyRepository = $policyRepository;
    }
    
    public function getPartial($limit, $page, $keywords, $serviceId)
    {
        $data = $this->policyRepository->getPartial($limit, $page, $keywords, $serviceId);
        return $data;
    }
    
    public function create(array $input)
    {
        $id = $this->policyRepository->create($input);
        return $id;
    } 
    
    public function update($id, array $input)
    {
        $this->policyRepository->update($id, $input);
    }
    
    public function delete($id)
    {
        $this->policyRepository->delete($id);
    }
    
    public function getById($id)
    {
        $data = $this->policyRepository->getById($id);
        return $data;
    }
    
    public function checkKey($key)
    {
        $data = $this->policyRepository->checkKey($key);
        return $data;
    }    
}