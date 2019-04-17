<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ElasticSearchService;

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
    public function __construct(ElasticSearchService $elasticSearchService)
    {
        parent::__construct();
        $this->elasticSearchService = $elasticSearchService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->elasticSearchService->pushDmtvt();
    }
}
