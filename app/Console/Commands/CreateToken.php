<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\ApiService;
use App\Models\Token;
use App\Models\TokenType;
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

        $account = Account::find($accountId);
        if (!$account) {
            $this->error('Account not found');
            return;
        }

        $services = ApiService::pluck('name', 'id')->toArray();
        $serviceName = $this->choice('API Service', $services);
        $serviceId = array_search($serviceName, $services);

        $types = TokenType::pluck('name', 'id')->toArray();
        $typeName = $this->choice('Token type', $types);
        $typeId = array_search($typeName, $types);

        $value = $this->secret('Token value');

        if (empty($value)) {
            $this->error('Token value cannot be empty');
            return;
        }

        $exists = Token::where([
            'account_id' => $accountId,
            'api_service_id' => $serviceId,
            'token_type_id' => $typeId,
        ])->exists();

        if ($exists) {
            $this->warn('Token already exists for this account/service/type');

            if ($this->confirm('Do you want to update it?')) {
                Token::where([
                    'account_id' => $accountId,
                    'api_service_id' => $serviceId,
                    'token_type_id' => $typeId,
                ])->update([
                    'value' => $value
                ]);

                $this->info('Token updated');
            }
            return;
        }

        Token::create([
            'account_id' => $accountId,
            'api_service_id' => $serviceId,
            'token_type_id' => $typeId,
            'value' => $value,
        ]);

        $this->info('Token created');
    }
}
