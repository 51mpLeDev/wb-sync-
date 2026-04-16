<?php

namespace App\Console\Commands;

use App\Models\Company;
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

        $company = Company::create([
            'name' => $name
        ]);

        $this->info("Created company ID: {$company->id}");
    }
}
