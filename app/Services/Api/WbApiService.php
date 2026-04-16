<?php

namespace App\Services\Api;

use App\Traits\Loggable;
use Illuminate\Support\Facades\Http;

class WbApiService
{
    use Loggable;

    protected string $host;
    protected string $apiKey;

    public function __construct()
    {
        $this->host = config('services.wb.host');
        $this->apiKey = config('services.wb.key');
    }

    public function get(string $endpoint, array $params = [], $token = null, int $attempt = 1)
    {
        $request = Http::retry(5, 2000);

        if ($token) {
            $request = $this->applyToken($request, $token);
        }

        $response = $request->get($this->host . $endpoint, $params);

        if ($response->status() === 429) {

            if ($attempt > 5) {
                throw new \Exception('Too many retries (429)');
            }

            $this->logInfo("429 received, retry {$attempt}");

            sleep(2 * $attempt);

            return $this->get($endpoint, $params, $token, $attempt + 1);
        }

        if ($response->failed()) {
            throw new \Exception($response->body());
        }

        return $response->json();
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
