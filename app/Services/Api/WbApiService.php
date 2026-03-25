<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;

class WbApiService
{
    protected string $host;
    protected string $apiKey;

    public function __construct()
    {
        $this->host = config('services.wb.host');
        $this->apiKey = config('services.wb.key');
    }

    public function get(string $endpoint, array $params = [])
    {
        $params['key'] = $this->apiKey;

        return Http::get($this->host . $endpoint, $params)->json();
    }
}
