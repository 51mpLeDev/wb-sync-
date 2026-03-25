<?php

namespace App\Console\Commands;

use App\Jobs\SyncIncomesJob;
use App\Jobs\SyncOrdersJob;
use App\Jobs\SyncSalesJob;
use App\Jobs\SyncStocksJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class SyncWbData extends Command
{
    protected $signature = 'wb:sync';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Dispatching jobs...');

        Bus::chain([
            new SyncOrdersJob(),
            new SyncSalesJob(),
            new SyncStocksJob(),
            new SyncIncomesJob(),
        ])->catch(function ($e) {
            Log::error('Sync chain failed: ' . $e->getMessage());
        })->dispatch();

        $this->info('Sync started');
    }
}
