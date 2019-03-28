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

    public function getListNoiGioiThieu($limit, $page, $ten, $loai)
    {
        $data = $this->noiGioiThieuRepository->getListNoiGioiThieu($limit, $page, $ten, $loai);
        return $data;
    }
    
    public function createNoiGioiThieu(array $input)
    {
        $id = $this->noiGioiThieuRepository->createNoiGioiThieu($input);
        return $id;
    }
    
    public function updateNoiGioiThieu($id, array $input)
    {
        $result = $this->noiGioiThieuRepository->updateNoiGioiThieu($id, $input);
        return $result;
    }
    
    public function deleteNoiGioiThieu($id)
    {
        $result = $this->noiGioiThieuRepository->deleteNoiGioiThieu($id);
        return $result;
    }
}