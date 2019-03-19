<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DanhMucThuocVatTuService;

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
    public function __construct(DanhMucThuocVatTuService $danhMucThuocVatTuService)
    {
        parent::__construct();
        $this->danhMucThuocVatTuService = $danhMucThuocVatTuService;
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
            $this->danhMucThuocVatTuService->pushTvtByKhoToElasticSearch($khoId);
        else
            $this->error('Missing params: khoId');
    }
}
