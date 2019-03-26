<?php
namespace App\Services;

use App\Repositories\PhieuChamSoc\PhieuChamSocRepository;
use App\Repositories\YLenh\YLenhRepository;

class PhieuChamSocService
{
    public function __construct(PhieuChamSocRepository $phieuChamSocRepository,YLenhRepository $yLenhRepository)
    {
        $this->phieuChamSocRepository = $phieuChamSocRepository;
        $this->yLenhRepository = $yLenhRepository;
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
        foreach($data as $item){
            $arrayYLenhThucHien = $item['y_lenh_thuc_hien']?json_decode($item['y_lenh_thuc_hien']):[];
            $yLenh = $this->yLenhRepository->getByArrayId($arrayYLenhThucHien);
            $item['y_lenh']=$yLenh;
        }
        return $data;
    }     
    
}