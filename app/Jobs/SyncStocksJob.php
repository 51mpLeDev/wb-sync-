<?php
namespace App\Jobs;

use App\Models\Order;
use App\Models\Sale;
use App\Models\Stock;
use App\Services\Api\WbApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncStocksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */

    public function handle(WbApiService $api): void
    {
        Log::info('Stocks sync started');

        try {
            $response = $api->get('/api/stocks', [
                'dateFrom' => now()->format('Y-m-d'),
            ]);
        } catch (\Exception $e) {
            Log::error('Stocks API error: ' . $e->getMessage());
            return;
        }

        $data = $response['data'] ?? [];

        Log::info('Stocks received: ' . count($data));

        $total = 0;

        foreach ($data as $item) {

            if (!isset($item['nm_id']) || !isset($item['warehouse_name'])) {
                Log::warning('Stock skipped (no key)', $item);
                continue;
            }

            // 🔥 уникальный ключ
            $externalId = $item['warehouse_name'] . '_' . $item['nm_id'];

            try {
                Stock::updateOrCreate(
                    ['external_id' => $externalId],
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
                Log::error('Stock save failed', [
                    'error' => $e->getMessage(),
                    'data' => $item,
                ]);
            }
        }

        Log::info("Stocks sync finished. Total: {$total}");
    }
}
