<?php

namespace App\Services;

use Illuminate\Http\Request;
use Validator;
use DB;

class DBSChemaCompareService {
    
    public function getTableColumns() {
       
        //get col table
        //  return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
        //getall table name
        
        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
        return $tables;
        
    }
    public function getTableColumnsStaging() {
       
        //get col table
        //  return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
        //getall table name
        
        $tables2 = DB::connection()->getDoctrineSchemaManager()->listTableNames();
        return $tables2;
        
    }
}