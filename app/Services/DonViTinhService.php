<?php
namespace App\Services;

use App\Repositories\DonViTinhRepository;

class DonViTinhService
{
    public function __construct(DonViTinhRepository $donViTinhRepository)
    {
        $this->donViTinhRepository = $donViTinhRepository;
    }
    
    public function getPartial($limit, $page, $keyword)
    {
        $result = $this->donViTinhRepository->getPartial($limit, $page, $keyword);
        return $result;
    }
    
    public function getDonViCoBan()
    {
        $result = $this->donViTinhRepository->getDonViCoBan();
        return $result;
    }
    
    public function create(array $input)
    {
        $result = $this->donViTinhRepository->create($input);
        return $result;
    }
    
    public function update($id, array $input)
    {
        $result = $this->donViTinhRepository->update($id, $input);
        return $result;
    }
    
    public function getById($id)
    {
        $result = $this->donViTinhRepository->getById($id);
        return $result;
    }
}