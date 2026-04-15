<?php
namespace App\Jobs;

use App\Models\Account;
use App\Models\Order;
use App\Models\Sale;
use App\Services\Api\WbApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncSalesJob implements ShouldQueue
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
        Log::info('Sales sync started');

        $page = 1;
        $total = 0;

        do {
            Log::info("Fetching sales page {$page}");

            $response = $api->get('/api/sales', [
                'dateFrom' => now()->subDays(7)->format('Y-m-d'),
                'dateTo'   => now()->format('Y-m-d'),
                'page'     => $page,
                'limit'    => 500,
            ]);

            $data = $response['data'] ?? [];

            foreach ($data as $item) {

                if (!isset($item['sale_id'])) {
                    Log::warning('Sale skipped (no sale_id)', $item);
                    continue;
                }

                try {
                    Sale::updateOrCreate(
                        [
                            'account_id' => $this->account->id,
                            'external_id' => $item['sale_id']
                        ],
                        [
                            'g_number' => $item['g_number'] ?? null,
                            'date' => $item['date'] ?? null,
                            'last_change_date' => $item['last_change_date'] ?? null,

                            'price' => $item['total_price'] ?? null,
                            'discount_percent' => $item['discount_percent'] ?? null,

                            'warehouse_name' => $item['warehouse_name'] ?? null,
                            'region_name' => $item['region_name'] ?? null,

                            'income_id' => $item['income_id'] ?? null,
                            'nm_id' => $item['nm_id'] ?? null,

                            'subject' => $item['subject'] ?? null,
                            'category' => $item['category'] ?? null,
                            'brand' => $item['brand'] ?? null,

                            'is_supply' => $item['is_supply'] ?? false,
                            'is_realization' => $item['is_realization'] ?? false,

                            'for_pay' => $item['for_pay'] ?? null,
                            'finished_price' => $item['finished_price'] ?? null,
                            'price_with_disc' => $item['price_with_disc'] ?? null,
                        ]
                    );

                    $total++;

                } catch (\Exception $e) {
                    Log::error('Sale save failed', [
                        'error' => $e->getMessage(),
                        'data' => $item,
                    ]);
                }
            }

            $page++;

        } while (count($data) === 500);

        Log::info("Sales sync finished. Total: {$total}");
    }
}
