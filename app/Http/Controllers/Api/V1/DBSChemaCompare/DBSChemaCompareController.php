<?php
namespace App\Http\Controllers\Api\V1\DBSChemaCompare;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\APIController;
use App\Services\DBSChemaCompareService;


class DBSChemaCompareController extends APIController
{
    public function __construct
    (
        DBSChemaCompareService $dbSchemaCompareService
    )
    {
        $this->dbSchemaCompareService = $dbSchemaCompareService;
    }
    
    public function getAllTablesDevelop()
    {
       $data = $this->dbSchemaCompareService->getAllTablesDevelop();
       return $this->respond($data);
    }
    public function getAllTablesStaging()
    {
       $data = $this->dbSchemaCompareService->getAllTablesStaging();
       return $this->respond($data);
    }
    
    public function getAllColumsDevelop($keyWords)
    {
       $data = $this->dbSchemaCompareService->getAllColumsDevelop($keyWords);
       return $this->respond($data);
    }
    
    public function getAllColumsStaging($keyWords)
    {
       $data = $this->dbSchemaCompareService->getAllColumsStaging($keyWords);
       return $this->respond($data);
    }
    
}