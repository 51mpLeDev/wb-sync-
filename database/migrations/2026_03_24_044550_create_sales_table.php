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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            $table->string('external_id')->unique();

            $table->string('g_number')->nullable();

            $table->date('date')->nullable();
            $table->date('last_change_date')->nullable();

            $table->decimal('price', 10, 2)->nullable();
            $table->integer('discount_percent')->nullable();

            $table->string('warehouse_name')->nullable();
            $table->string('region_name')->nullable();

            $table->string('income_id')->nullable();
            $table->string('nm_id')->nullable();

            $table->string('subject')->nullable();
            $table->string('category')->nullable();
            $table->string('brand')->nullable();

            $table->boolean('is_supply')->default(false);
            $table->boolean('is_realization')->default(false);

            $table->decimal('for_pay', 10, 2)->nullable();
            $table->decimal('finished_price', 10, 2)->nullable();
            $table->decimal('price_with_disc', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
