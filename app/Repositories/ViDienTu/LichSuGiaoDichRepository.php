<?php
namespace App\Repositories\ViDienTu;

use App\Repositories\BaseRepositoryV2;
use App\Models\LichSuGiaoDich;

class LichSuGiaoDichRepository extends BaseRepositoryV2
{
    public function getModel()
    {
        return LichSuGiaoDich::class;
    }
      
    public function create(array $input)
    {
        $id = $this->model->create($input)->id;
        return $id;
    }
}