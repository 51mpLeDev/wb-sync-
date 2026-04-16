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
        DB::statement("
            DELETE t1 FROM tokens t1
            INNER JOIN tokens t2
            WHERE
                t1.id > t2.id
                AND t1.account_id = t2.account_id
                AND t1.api_service_id = t2.api_service_id
                AND t1.token_type_id = t2.token_type_id
        ");

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
