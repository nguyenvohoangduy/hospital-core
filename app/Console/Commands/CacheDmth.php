<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DanhMucTongHopService;

class CacheDmth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CacheDmth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache danh muc tổng hợp';
    
    private $danhmucTongHopRedisService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DanhMucTongHopService $danhmucTongHopService)
    {
        parent::__construct();
        $this->danhmucTongHopService = $danhmucTongHopService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //$data = $this->danhMucThuocVatTuService->pushToRedis();
        // $this->danhMucThuocVatTuService->pushToElasticSearch();
    
        $this->danhmucTongHopService->pushToRedis();
    }
}
