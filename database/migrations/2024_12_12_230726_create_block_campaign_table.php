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
        Schema::create('block_campaign', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_id')->constrained('blocks');
            $table->foreignId('campaign_id')->constrained('campaigns');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('block_campaign');
    }
};
