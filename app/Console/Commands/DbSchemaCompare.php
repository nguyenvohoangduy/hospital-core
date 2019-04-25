<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DbSchemaCompareService;


class DbSchemaCompare extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db_schema_compare {db_host1} {db_name1} {db_user1} {db_pass1} {db_host2} {db_name2} {db_user2} {db_pass2}';

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
    public function __construct(DbSchemaCompareService $dbSchemaCompareService)
    {
        parent::__construct();
        $this->dbSchemaCompareService = $dbSchemaCompareService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $database1 = [
            'host'      => $this->argument('db_host1'),
            'database'  => $this->argument('db_name1'),
            'username'  => $this->argument('db_user1'),
            'password'  => $this->argument('db_pass1'),
        ];
        $database2 = [
            'host'      => $this->argument('db_host2'),
            'database'  => $this->argument('db_name2'),
            'username'  => $this->argument('db_user2'),
            'password'  => $this->argument('db_pass2'),
        ];
        if($database1 && $database2){
            $table = $this->dbSchemaCompareService->getAllTablesDevelop($database1,$database2);
            print_r($table);
        }
    }
}
