<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $duplicates = DB::table('tokens')
            ->select('account_id', 'api_service_id', 'token_type_id')
            ->groupBy('account_id', 'api_service_id', 'token_type_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $dup) {
            $ids = DB::table('tokens')
                ->where([
                    'account_id' => $dup->account_id,
                    'api_service_id' => $dup->api_service_id,
                    'token_type_id' => $dup->token_type_id,
                ])
                ->pluck('id')
                ->toArray();

            array_shift($ids);

            DB::table('tokens')->whereIn('id', $ids)->delete();
        }

        Schema::table('tokens', function (Blueprint $table) {
            $table->unique(
                ['account_id', 'api_service_id', 'token_type_id'],
                'tokens_unique_account_service_type'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tokens', function (Blueprint $table) {

            $table->dropForeign(['account_id']);
            $table->dropForeign(['api_service_id']);
            $table->dropForeign(['token_type_id']);

            $table->dropUnique('tokens_unique_account_service_type');

            $table->foreign('account_id')->references('id')->on('accounts')->cascadeOnDelete();
            $table->foreign('api_service_id')->references('id')->on('api_services')->cascadeOnDelete();
            $table->foreign('token_type_id')->references('id')->on('token_types')->cascadeOnDelete();
        });
    }
};
