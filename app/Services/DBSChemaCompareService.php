<?php

namespace App\Services;

use Illuminate\Http\Request;
use Validator;
use DB;
use Schema;

class DBSChemaCompareService {
    
    public function getAllTablesDevelop() {
        //getall table name
        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
        return $tables;
    }
    public function getAllTablesStaging() {
       
        //getall table name
        $tables2 = DB::connection()->getDoctrineSchemaManager()->listTableNames();
        return $tables2;
    }
    
    public function getAllColumsDevelop($keyWords){
          // $columns = Schema::getColumnListing('phong');
           $columns = DB::getSchemaBuilder()->getColumnListing($keyWords);
           return $columns;
    }
    
    public function getAllColumsStaging(){
          // $columns = Schema::getColumnListing('phong');
           $columns = DB::getSchemaBuilder()->getColumnListing('khoa');
           return $columns;
    }
   
}