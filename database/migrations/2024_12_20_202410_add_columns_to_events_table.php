<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->time('event_start_time');
            $table->time('event_end_time');
            $table->date('event_created_at');
            $table->boolean('event_attended');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['event_start_time', 'event_end_time', 'event_created_at', 'event_attended']);
        });
    }
};
