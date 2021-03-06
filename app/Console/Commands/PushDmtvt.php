<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\ElasticSearch\DmtvtKho;

class PushDmtvt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pushDmtvt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push danh muc thuoc vat tu len Elasticsearch';
    
    private $hsbaKhoaPhongRedisRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DmtvtKho $dmtvtKho)
    {
        parent::__construct();
        $this->dmtvtKho = $dmtvtKho;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->dmtvtKho->pushDmtvt();
    }
}
