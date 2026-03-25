<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;

class WbApiService
{
    protected string $host = 'http://109.73.206.144:6969';
    protected string $apiKey = 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie';

    public function get(string $endpoint, array $params = [])
    {
        $params['key'] = $this->apiKey;

        return Http::get($this->host . $endpoint, $params)->json();
    }
}
