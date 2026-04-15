<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $tables = ['orders', 'sales', 'stocks', 'incomes'];
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $name) {

            Schema::table($name, function (Blueprint $table) {

                $table->foreignId('account_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('accounts')
                    ->cascadeOnDelete();

            });

            Schema::table($name, function (Blueprint $table) use ($name) {

                $table->dropUnique($name . '_external_id_unique');

                $table->unique(['account_id', 'external_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables as $name) {

            Schema::table($name, function (Blueprint $table) {

                // 1. FK
                try {
                    $table->dropForeign(['account_id']);
                } catch (\Exception $e) {}

                // 2. колонка
                if (Schema::hasColumn($table->getTable(), 'account_id')) {
                    $table->dropColumn('account_id');
                }

                // 3. вернуть unique
                try {
                    $table->unique('external_id');
                } catch (\Exception $e) {}
            });
        }
    }
};
