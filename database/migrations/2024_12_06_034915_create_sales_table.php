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
            $table->date('order_date');
            $table->date('last_order_date_delivered');
            $table->decimal('total_sales', 10, 2);
            $table->decimal('total_revenues', 10, 2);
            $table->integer('orders_number');
            $table->integer('number_entries');
            $table->integer('returns_number');
            $table->decimal('return_value', 10, 2);
            $table->integer('last_days_purchase_days');
            $table->string('last_item_purchased', 60);
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('shop_id')->constrained();
            $table->foreignId('seller_id')->constrained();
            $table->foreignId('segmentation_id')->constrained();
            $table->foreignId('return_alert_id')->constrained();
            $table->foreignId('payment_method_id')->constrained();
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
