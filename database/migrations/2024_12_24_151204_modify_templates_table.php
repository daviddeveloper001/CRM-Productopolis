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
        Schema::table('templates', function (Blueprint $table) {
            $table->unsignedBigInteger('whatsapp_list_id')->nullable()->change();
            $table->string('event_type');
            $table->string('campaign_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->unsignedBigInteger('whatsapp_list_id')->nullable(false)->change();
            $table->dropColumn('event_type');
            $table->dropColumn('campaign_type');
        });
    }
};
