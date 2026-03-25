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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();

            $table->string('external_id')->unique();

            $table->date('date')->nullable();
            $table->date('last_change_date')->nullable();
            $table->date('date_close')->nullable();

            $table->integer('quantity')->nullable();

            $table->decimal('price', 10, 2)->nullable();

            $table->string('warehouse_name')->nullable();

            $table->string('nm_id')->nullable();
            $table->string('barcode')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
