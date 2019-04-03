<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DBSChemaCompareService;


class DBSChemaCompare extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'db_schema_compare {db_host1=env(DB_HOST)} {db_name1=env(DB_DATABASE)} {db_user1=env(DB_USERNAME)} {db_pass1=env(DB_PASSWORD)} {db_host2=env(DB_HOST_STAGING)} {db_name2=env(DB_DATABASE_STAGING)} {db_user2=env(DB_USERNAME_STAGING)} {db_pass2=env(DB_PASSWORD_STAGING)}';
    // {--db_host2=*} {--db_name2=*} {--db_user2=*} {--db_pass2=*}
    
    protected $signature = 'db_schema_compare';
  
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compare schema between two DBs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DBSChemaCompareService $dbSChemaCompareService)
    {
        parent::__construct();
        $this->dbSChemaCompareService = $dbSChemaCompareService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
     
         $table = $this->dbSChemaCompareService->getAllTablesDevelop();
          print_r($table);
          
        
    }
}
