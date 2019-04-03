<?php

namespace App\Services;

use Illuminate\Http\Request;
use Validator;
use DB;
use Schema;

class DBSChemaCompareService {
    
    public function getAllTablesDevelop() {
        //getall table name
        $tables1 = DB::connection('pgsql')->getDoctrineSchemaManager()->listTableNames(); //94 table
        $tables2 = DB::connection('pgsql_staging')->getDoctrineSchemaManager()->listTableNames(); //72 table
  
        ////So sánh các value của mảng, và trả về các sự khác nhau
        $tablesDiff = array_diff($tables1, $tables2);
        // return $tables;
        
        //So sánh các value trong mảng và trả về các so khớp
        $allTable = [];
        $table_intersect = array_intersect($tables1, $tables2);
        foreach($table_intersect as $table){
            
            $columns = DB::connection('pgsql_staging')->getSchemaBuilder()->getColumnListing($table);
            $columns2 = DB::connection('pgsql')->getSchemaBuilder()->getColumnListing($table);
            
            
            $arrayDiff1= array_diff($columns, $columns2);
            $arrayDiff2= array_diff($columns2, $columns);
            if($arrayDiff1 || $arrayDiff2){
               $allTable[]=[
                    $table => [
                       'DB1' => $arrayDiff2,
                       'DB2' => $arrayDiff1
                       ]
                   ];     
            }
        }
        $allTable[]= [
            'Missing Tables' => [
                   $tablesDiff
            ]
       ];
        return $allTable;


    }
   
}