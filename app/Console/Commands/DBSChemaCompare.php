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
        $database1 = [
            'host'      => $this->argument('db_host1'),
            'driver'    => 'pgsql',
            'port'      => '5432',
            'database'  => $this->argument('db_name1'),
            'username'  => $this->argument('db_user1'),
            'password'  => $this->argument('db_pass1'),
            'charset'   => 'utf8',
            'prefix'    => '',
            'schema'    => 'public',
            'sslmode'   => 'prefer',
        ];
        $database2 = [
            'host'      => $this->argument('db_host2'),
            'driver'    => 'pgsql',
            'port'      => '5432',
            'database'  => $this->argument('db_name2'),
            'username'  => $this->argument('db_user2'),
            'password'  => $this->argument('db_pass2'),
            'charset'   => 'utf8',
            'prefix'    => '',
            'schema'    => 'public',
            'sslmode'   => 'prefer',
        ];
        if($database1 && $database2){
            $table = $this->dbSChemaCompareService->getAllTablesDevelop($database1,$database2);
            print_r($table);
        }
    }
}
