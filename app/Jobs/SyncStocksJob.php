<?php
namespace App\Jobs;

use App\Models\Account;
use App\Models\Stock;
use App\Services\Api\WbApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\Loggable;

class SyncStocksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Loggable;

    /**
     * Create a new job instance.
     */
    public Account $account;

    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * Execute the job.
     */

    public function handle(WbApiService $api): void
    {
        $this->logInfo('Stocks sync started');

        $token = $this->account->getToken('wb', 'api_key');

        try {
            $params = [
                'dateFrom' => now()->format('Y-m-d'),
            ];

            $response = $api->get('/api/stocks', $params, $token);
        } catch (\Exception $e) {

            $this->logError('Stocks API error', [
                'account_id' => $this->account->id,
                'message' => $e->getMessage(),
                'params' => $params,
            ]);

            return;
        }

        $data = $response['data'] ?? [];

        $this->logInfo('Stocks received: ' . count($data));

        $total = 0;

        foreach ($data as $item) {

            if (!isset($item['nm_id']) || !isset($item['warehouse_name'])) {
                $this->logWarning('Stock skipped (no key)', $item);
                continue;
            }

            // 🔥 уникальный ключ
            $externalId = $item['warehouse_name'] . '_' . $item['nm_id'];

            try {
                Stock::updateOrCreate(
                    [
                        'account_id' => $this->account->id,
                        'external_id' => $externalId
                    ],
                    [
                        'date' => $item['date'] ?? null,
                        'warehouse_name' => $item['warehouse_name'] ?? null,

                        'nm_id' => $item['nm_id'] ?? null,

                        'quantity' => $item['quantity'] ?? 0,

                        'in_way_to_client' => $item['in_way_to_client'] ?? null,
                        'in_way_from_client' => $item['in_way_from_client'] ?? null,

                        'barcode' => $item['barcode'] ?? null,
                    ]
                );

                $total++;

            } catch (\Exception $e) {
                $this->logError('Stock save failed', [
                    'error' => $e->getMessage(),
                    'data' => $item,
                ]);
            }
        }

        $this->logInfo("Stocks sync finished. Total: {$total}");
    }
}
