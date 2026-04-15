<?php
namespace App\Jobs;

use App\Models\Account;
use App\Models\Order;
use App\Services\Api\WbApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private Account $account;

    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * Execute the job.
     */
    public function handle(WbApiService $api): void
    {
        Log::info('Orders sync started');

        $page = 1;
        $total = 0;

        do {
            Log::info("Fetching orders page {$page}");

            try {
                $response = $api->get('/api/orders', [
                    'dateFrom' => now()->subDays(7)->format('Y-m-d'),
                    'dateTo'   => now()->format('Y-m-d'),
                    'page'     => $page,
                    'limit'    => 500,
                ]);
            } catch (\Exception $e) {
                Log::error('API error: ' . $e->getMessage());
                return;
            }

            $data = $response['data'] ?? [];

            Log::info('Received: ' . count($data));

            foreach ($data as $item) {

                if (!isset($item['g_number'])) {
                    Log::warning('Skipped order without g_number', $item);
                    continue;
                }

                try {
                    Order::updateOrCreate(
                        [
                            'account_id' => $this->account->id,
                            'external_id' => $item['g_number']
                        ],
                        [
                            'number' => $item['g_number'],
                            'date' => $item['date'] ?? null,
                            'last_change_date' => $item['last_change_date'] ?? null,

                            'price' => $item['total_price'] ?? null,
                            'discount_percent' => $item['discount_percent'] ?? null,

                            'warehouse_name' => $item['warehouse_name'] ?? null,
                            'oblast' => $item['oblast'] ?? null,

                            'income_id' => $item['income_id'] ?? null,
                            'nm_id' => $item['nm_id'] ?? null,

                            'subject' => $item['subject'] ?? null,
                            'category' => $item['category'] ?? null,
                            'brand' => $item['brand'] ?? null,

                            'is_cancel' => $item['is_cancel'] ?? false,
                            'cancel_dt' => $item['cancel_dt'] ?? null,
                        ]
                    );

                    $total++;

                } catch (\Exception $e) {
                    Log::error('Save failed', [
                        'error' => $e->getMessage(),
                        'data' => $item
                    ]);
                }
            }

            $page++;

        } while (count($data) === 500);

        Log::info("Orders sync finished. Total: {$total}");
    }
}
