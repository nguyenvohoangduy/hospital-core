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
    protected $signature = 'db_schema_compare {--dest_db_host=env(DB_HOST)} {--dest_db_name=env(DB_DATABASE)} {--dest_db_user=env(DB_USERNAME)} {--dest_db_pass=env(DB_PASSWORD)}';

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
