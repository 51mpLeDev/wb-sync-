<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

class CreateToken extends Command
{
    protected $signature = 'token:create';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $accountId = $this->ask('Account ID');
        $serviceId = $this->ask('API Service ID');
        $typeId = $this->ask('Token type ID');
        $value = $this->ask('Token value');

        \App\Models\Token::create([
            'account_id' => $accountId,
            'api_service_id' => $serviceId,
            'token_type_id' => $typeId,
            'value' => $value,
        ]);

        $this->info('Token created');
    }
}
