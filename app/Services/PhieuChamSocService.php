<?php
namespace App\Services;

use App\Repositories\PhieuChamSoc\PhieuChamSocRepository;

class PhieuChamSocService
{
    public function __construct(PhieuChamSocRepository $phieuChamSocRepository)
    {
        $this->phieuChamSocRepository = $phieuChamSocRepository;
    }
    
    public function create($input)
    {
        $this->phieuChamSocRepository->create($input);
    }
    
    public function getById($id)
    {
        $data = $this->phieuChamSocRepository->getById($id);
        return $data;
    }
    
    public function getAllByDieuTriId($dieuTriId)
    {
        $data = $this->phieuChamSocRepository->getAllByDieuTriId($dieuTriId);
        return $data;
    }     
    
}