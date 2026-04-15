<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

class CreateCompany extends Command
{
    protected $signature = 'company:create';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('Company name');

        $company = \App\Models\Company::create([
            'name' => $name
        ]);

        $this->info("Created company ID: {$company->id}");
    }
}
