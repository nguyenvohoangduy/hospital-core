<?php
namespace App\Services;

use App\Repositories\PhieuChamSoc\PhieuChamSocRepository;

class PhieuChamSocService
{
    public function __construct(PhieuChamSocRepository $phieuChamSocRepository)
    {
        $this->phieuChamSocRepository = $phieuChamSocRepository;
    }
    
    public function createPhieuChamSoc($input)
    {
        $this->phieuChamSocRepository->createPhieuChamSoc($input);
    }
    
    public function getPhieuChamSocById($id)
    {
        $data = $this->phieuChamSocRepository->getPhieuChamSocById($id);
        return $data;
    }
    
    public function getListPhieuChamSocByHsbaId($hsbaId)
    {
        $data = $this->phieuChamSocRepository->getListPhieuChamSocByHsbaId($hsbaId);
        return $data;
    }     
    
}