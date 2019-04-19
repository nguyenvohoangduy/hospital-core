<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\ElasticSearch\DmtvtKho;

class pushTvtTheoKho extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pushTvtTheoKho {khoId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push thuoc vat tu tung kho len Elasticsearch';
    
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
        $khoId = $this->argument('khoId');
        if($khoId)
            $this->dmtvtKho->createIndex($khoId);
        else
            $this->error('Missing params: khoId');
    }
}
