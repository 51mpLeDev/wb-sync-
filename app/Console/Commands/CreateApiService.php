<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
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

        $service = \App\Models\ApiService::create([
            'name' => $name
        ]);

        $this->info("Created API service ID: {$service->id}");
    }
}
