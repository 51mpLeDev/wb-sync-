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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('external_id')->unique();

            $table->string('number')->nullable();
            $table->dateTime('date')->nullable();
            $table->date('last_change_date')->nullable();

            $table->decimal('price', 10, 2)->nullable();
            $table->integer('discount_percent')->nullable();

            $table->string('warehouse_name')->nullable();
            $table->string('oblast')->nullable();

            $table->string('income_id')->nullable();
            $table->string('nm_id')->nullable();

            $table->string('subject')->nullable();
            $table->string('category')->nullable();
            $table->string('brand')->nullable();

            $table->boolean('is_cancel')->default(false);
            $table->dateTime('cancel_dt')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
