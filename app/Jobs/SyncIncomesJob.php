<?php
namespace App\Jobs;

use App\Models\Income;
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

class SyncIncomesJob implements ShouldQueue
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
        Log::info('Incomes sync started');

        $page = 1;
        $total = 0;

        do {
            Log::info("Fetching incomes page {$page}");

            $response = $api->get('/api/incomes', [
                'dateFrom' => now()->subDays(7)->format('Y-m-d'),
                'dateTo'   => now()->format('Y-m-d'),
                'page'     => $page,
                'limit'    => 500,
            ]);

            $data = $response['data'] ?? [];

            foreach ($data as $item) {

                if (!isset($item['income_id'])) {
                    Log::warning('Income skipped (no income_id)', $item);
                    continue;
                }

                try {
                    Income::updateOrCreate(
                        ['external_id' => $item['income_id']],
                        [
                            'date' => $item['date'] ?? null,
                            'last_change_date' => $item['last_change_date'] ?? null,
                            'date_close' => $item['date_close'] ?? null,

                            'quantity' => $item['quantity'] ?? null,
                            'price' => $item['total_price'] ?? null,

                            'warehouse_name' => $item['warehouse_name'] ?? null,

                            'nm_id' => $item['nm_id'] ?? null,
                            'barcode' => $item['barcode'] ?? null,
                        ]
                    );

                    $total++;

                } catch (\Exception $e) {
                    Log::error('Income save failed', [
                        'error' => $e->getMessage(),
                        'data' => $item,
                    ]);
                }
            }

            $page++;

        } while (count($data) === 500);

        Log::info("Incomes sync finished. Total: {$total}");
    }
}
