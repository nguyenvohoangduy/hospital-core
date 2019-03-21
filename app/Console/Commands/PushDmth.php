<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DanhMucTongHopRedisService;

class PushDmth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PushDmth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push danh muc tổng hợp';
    
    private $danhmucTongHopRedisService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DanhMucTongHopRedisService $danhmucTongHopRedisService)
    {
        parent::__construct();
        $this->danhmucTongHopRedisService = $danhmucTongHopRedisService;
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
    
        $this->danhmucTongHopRedisService->pushToRedis();
    }
}
