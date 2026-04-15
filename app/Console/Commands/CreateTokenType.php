<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

class CreateTokenType extends Command
{
    protected $signature = 'token-type:create';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('Type (bearer, api_key...)');

        $type = \App\Models\TokenType::create([
            'name' => $name
        ]);

        $this->info("Created type ID: {$type->id}");
    }
}
