<?php

namespace App\Services;

use App\Repositories\MauHoiBenh\MauHoiBenhRepository;
use Illuminate\Http\Request;
use Validator;
use DB;

class MauHoiBenhService {

    public function __construct(MauHoiBenhRepository $mauHoiBenhRepository)
    {
        $this->mauHoiBenhRepository = $mauHoiBenhRepository;
    }
    
    public function create(array $input)
    {
        $this->mauHoiBenhRepository->create($input);
    }
    
    public function getMauHoiBenhByChucNangAndUserId($chucNang, $userId)
    {
        $data = $this->mauHoiBenhRepository->getMauHoiBenhByChucNangAndUserId($chucNang, $userId);
        return $data;
    }
    
    public function getById($id, $chucNang)
    {
        $data = $this->mauHoiBenhRepository->getById($id, $chucNang);
        return $data;
    }
}