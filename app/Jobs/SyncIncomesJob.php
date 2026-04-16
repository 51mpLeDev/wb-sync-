<?php
namespace App\Jobs;

use App\Models\Account;
use App\Models\Income;
use App\Models\Order;
use App\Services\Api\WbApiService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\Loggable;


class SyncIncomesJob implements ShouldQueue
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
        $this->logInfo('Incomes sync started');

        $page = 1;
        $total = 0;

        do {
            $this->logInfo("Fetching incomes page {$page}");

            $lastDate = Order::where('account_id', $this->account->id)
                ->max('date');

            $dateFrom = $lastDate
                ? Carbon::parse($lastDate)->format('Y-m-d')
                : now()->subDays(7)->format('Y-m-d');

            $params = [
                'dateFrom' => $dateFrom,
                'dateTo'   => now()->format('Y-m-d'),
                'page'     => $page,
                'limit'    => 500,
            ];

            $token = $this->account->getToken('wb', 'api_key');

            try {
                $response = $api->get('/api/incomes', $params, $token);
            } catch (\Exception $e) {

                $this->logError('Incomes API error', [
                    'account_id' => $this->account->id,
                    'message' => $e->getMessage(),
                    'params' => $params,
                ]);

                return;
            }

            $data = $response['data'] ?? [];

            foreach ($data as $item) {

                if (!isset($item['income_id'])) {
                    $this->logWarning('Income skipped (no income_id)', $item);
                    continue;
                }

                try {
                    Income::updateOrCreate(
                        [
                            'account_id' => $this->account->id,
                            'external_id' => $item['income_id']
                        ],
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
                    $this->logError('Income save failed', [
                        'error' => $e->getMessage(),
                        'data' => $item,
                    ]);
                }
            }

            $page++;

        } while (count($data) === 500);

        $this->logInfo("Incomes sync finished. Total: {$total}");
    }
}
