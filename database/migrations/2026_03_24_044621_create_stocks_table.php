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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();

            $table->string('external_id')->unique();

            $table->date('date')->nullable();

            $table->string('warehouse_name')->nullable();

            $table->bigInteger('nm_id')->nullable();

            $table->integer('quantity')->nullable();

            $table->integer('in_way_to_client')->nullable();
            $table->integer('in_way_from_client')->nullable();

            $table->integer('barcode')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
