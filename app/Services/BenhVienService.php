<?php

namespace App\Services;

use App\Http\Resources\BenhVienResource;
use App\Repositories\BenhVienRepository;
use Illuminate\Http\Request;

class BenhVienService{
    public function __construct(BenhVienRepository $repository)
    {
        $this->benhVienRepository = $repository;        
    }
    
    public function listBenhVien()
    {
        // return BenhVienResource::collection(
        //   $this->benhVienRepository->listBenhVien()
        // );
        $data = $this->benhVienRepository->listBenhVien();
        return $data;
    }
    
    public function getPartial($limit, $page, $name)
    {
        $data = $this->benhVienRepository->getPartial($limit, $page, $name);
        return $data;
    }
    
    public function find($id)
    {
        return $this->benhVienRepository->find($id);
    }
    
    public function create(array $input)
    {
        $id = $this->benhVienRepository->create($input);
        return $id;
    }
    
    public function update($id, array $input)
    {
        $result = $this->benhVienRepository->update($id, $input);
        return $result;
    }
    
    public function delete($id)
    {
        $this->benhVienRepository->delete($id);
    }
}