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
        Schema::table('sales', function (Blueprint $table) {
            $table->date('date_first_order')->nullable(); 
            $table->date('date_last_order')->nullable(); 
            $table->string('previous_last_item_purchased', 60)->nullable();
            $table->integer('days_since_last_purchase')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['date_first_order', 'date_last_order', 'previous_last_item_purchased', 'days_since_last_purchase']);
        });
    }
};
