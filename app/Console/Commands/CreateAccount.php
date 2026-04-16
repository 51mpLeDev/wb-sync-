<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\Company;
use Illuminate\Console\Command;

class CreateAccount extends Command
{
    protected $signature = 'account:create';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $companies = Company::pluck('name', 'id')->toArray();

        $companyName = $this->choice('Select company', $companies);

        $companyId = array_search($companyName, $companies);

        $name = $this->ask('Account name');

        $account = Account::create([
            'company_id' => $companyId,
            'name' => $name
        ]);

        $this->info("Account created: {$account->id}");
    }
}
