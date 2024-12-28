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
        Schema::table('campaign_customers', function (Blueprint $table) {
            $table->enum('fulfillment_status', ['pending', 'fulfilled'])->default('pending');
            $table->integer('fulfilled_via_block_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaign_customers', function (Blueprint $table) {
            $table->dropColumn('fulfillment_status');
            $table->dropColumn('fulfilled_via_block_id');
        });
    }
};
