<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DBSChemaCompareService;


class DBSChemaCompareStaging extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db_schema_compare_staging {--dest_db_host=hospitalstaging-dbins.c3mlizysfydl.ap-southeast-1.rds.amazonaws.com} {--dest_db_name=hospital_dev} {--dest_db_user=robustaeng} {--dest_db_pass=robustaeng}';

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
     
         $table2 = $this->dbSChemaCompareService->getTableColumnsStaging();
         print_r($table2);
  
    }
}
