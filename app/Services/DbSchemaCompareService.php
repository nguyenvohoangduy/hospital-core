<?php

namespace App\Services;

use Illuminate\Http\Request;
use Validator;
use DB;
use Schema;

class DbSchemaCompareService {
    
    public function getAllTablesDevelop($database1,$database2) {
        // config database1
        config(['database.connections.pgsql_db1.host' => $database1['host']]);
        config(['database.connections.pgsql_db1.database' => $database1['database']]);
        config(['database.connections.pgsql_db1.username' => $database1['username']]);
        config(['database.connections.pgsql_db1.password' => $database1['password']]);
        // config database2
        config(['database.connections.pgsql_db2.host' => $database2['host']]);
        config(['database.connections.pgsql_db2.database' => $database2['database']]);
        config(['database.connections.pgsql_db2.username' => $database2['username']]);
        config(['database.connections.pgsql_db2.password' => $database2['password']]);

        //getall table name
        $tables1 = DB::connection('pgsql_db1')->getDoctrineSchemaManager()->listTableNames(); //94 table
        $tables2 = DB::connection('pgsql_db2')->getDoctrineSchemaManager()->listTableNames(); //72 table
  
        ////So sánh các value của mảng, và trả về các sự khác nhau
        $tablesDiff = array_diff($tables1, $tables2);
        // return $tables;
        
        //So sánh các value trong mảng và trả về các so khớp
        $allTable = [];
        $table_intersect = array_intersect($tables1, $tables2);
        foreach($table_intersect as $table){
            $columns = DB::connection('pgsql_db2')->getSchemaBuilder()->getColumnListing($table);
            $columns2 = DB::connection('pgsql_db1')->getSchemaBuilder()->getColumnListing($table);
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