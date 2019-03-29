<?php

namespace App\Services;

use App\Repositories\DanhMuc\NoiGioiThieuRepository;
use Illuminate\Http\Request;
use Validator;

class NoiGioiThieuService {
    public function __construct(NoiGioiThieuRepository $noiGioiThieuRepository)
    {
        $this->noiGioiThieuRepository = $noiGioiThieuRepository;
    }
    
    public function getAll()
    {
        $data = $this->noiGioiThieuRepository->getAll();
        return $data;
    }

    public function getPartial($limit, $page, $ten)
    {
        $data = $this->noiGioiThieuRepository->getPartial($limit, $page, $ten);
        return $data;
    }
    
    public function create(array $input)
    {
        $id = $this->noiGioiThieuRepository->create($input);
        return $id;
    }
    
    public function update($id, array $input)
    {
        $result = $this->noiGioiThieuRepository->update($id, $input);
        return $result;
    }
    
    public function delete($id)
    {
        $result = $this->noiGioiThieuRepository->delete($id);
        return $result;
    }
}