<?php

namespace App\Console\Commands;

use App\Jobs\SyncIncomesJob;
use App\Jobs\SyncOrdersJob;
use App\Jobs\SyncSalesJob;
use App\Jobs\SyncStocksJob;
use App\Models\Account;
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

        $accounts = Account::with('tokens')->get();

        foreach ($accounts as $account) {
            Bus::chain([
                new SyncOrdersJob($account),
                new SyncSalesJob($account),
                new SyncStocksJob($account),
                new SyncIncomesJob($account),
            ])->dispatch();
        }

        $this->info('Sync started');
    }
}
