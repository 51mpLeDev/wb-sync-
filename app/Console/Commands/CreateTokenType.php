<?php

namespace App\Console\Commands;

use App\Models\TokenType;
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

        $type = TokenType::create([
            'name' => $name
        ]);

        $this->info("Created type ID: {$type->id}");
    }
}
