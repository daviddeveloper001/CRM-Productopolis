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
        Schema::table('customer_segments', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['segment_id']);
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('segment_id')->references('id')->on('segments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_segments', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['segment_id']);
            $table->foreign('customer_id')->constrained();
            $table->foreign('segment_id')->constrained();
        });
    }
};
