<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

class CreateAccount extends Command
{
    protected $signature = 'account:create';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $companyId = $this->ask('Company ID');
        $name = $this->ask('Account name');

        $account = \App\Models\Account::create([
            'company_id' => $companyId,
            'name' => $name
        ]);

        $this->info("Created account ID: {$account->id}");
    }
}
