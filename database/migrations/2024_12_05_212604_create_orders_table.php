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
            $table->date('first_order_date');
            $table->date('last_order_date');
            $table->date('last_order_date_delivered');
            $table->foreignId('seller_id')->constrained();
            $table->foreignId('shop_id')->constrained();
            $table->foreignId('payment_method_id')->constrained();
            $table->decimal('total_order', 10, 2);
            $table->decimal('total_entries', 10, 2);
            $table->decimal('total_returns', 10, 2);
            $table->decimal('total_sales', 10, 2);
            $table->decimal('total_revenues', 10, 2);
            $table->decimal('return_value', 10, 2);
            $table->integer('days_since_last_purchase');
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
