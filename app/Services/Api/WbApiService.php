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

    public function get(string $endpoint, array $params = [], $token = null)
    {
        $params['key'] = $this->apiKey;

        $request = Http::retry(5, 2000);

        if ($token) {
            $request = $this->applyToken($request, $token);
        }
        return $request->get($this->host . $endpoint, $params)->json();
    }

    private function applyToken($request, $token)
    {
        $type = $token->tokenType->name;

        return match ($type) {
            'bearer' => $request->withHeaders([
                'Authorization' => 'Bearer ' . $token->value
            ]),

            'api_key' => $request->withQueryParameters([
                'key' => $token->value
            ]),

            'basic' => $request->withBasicAuth(
                $token->value['login'],
                $token->value['password']
            ),

            default => $request,
        };
    }
}
