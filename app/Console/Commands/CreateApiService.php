<?php

namespace App\Console\Commands;

use App\Models\ApiService;
use Illuminate\Console\Command;

class CreateApiService extends Command
{
    protected $signature = 'api-service:create';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('Service name (wb, ozon...)');

        $service = ApiService::create([
            'name' => $name
        ]);

        $this->info("Created API service ID: {$service->id}");
    }
}
