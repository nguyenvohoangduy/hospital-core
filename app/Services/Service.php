<?php
namespace App\Services;
use App\Repositories\Service\ServiceRepository;
use Illuminate\Http\Request;
use Validator;
class Service {
    public function __construct(
        ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }
    
    public function getAll()
    {
        $data = $this->serviceRepository->getAll();
        return $data;
    }
}