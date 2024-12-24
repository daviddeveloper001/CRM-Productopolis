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
        Schema::create('sales_comparatives', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->decimal('sales_before', 10, 2)->default(0);
            $table->decimal('sales_after', 10, 2)->default(0);
            $table->decimal('revenues_before', 10, 2)->default(0);
            $table->decimal('revenues_after', 10, 2)->default(0);
            $table->decimal('returns_before', 10, 2)->default(0);
            $table->decimal('returns_after', 10, 2)->default(0);
            $table->integer('orders_before')->default(0);
            $table->integer('orders_after')->default(0);
            $table->integer('delivered_before')->default(0);
            $table->integer('delivered_after')->default(0);
            $table->integer('returns_number_before')->default(0);
            $table->integer('returns_number_after')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_comparatives');
    }
};
