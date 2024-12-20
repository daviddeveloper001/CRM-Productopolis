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
        Schema::table('events', function (Blueprint $table) {
            $table->date('event_end_date')->nullable()->change();
            $table->text('event_description')->nullable()->change();   
            $table->boolean('event_attended')->nullable()->change();   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->date('event_end_date')->change();
            $table->text('event_description')->change();
            $table->boolean('event_attended')->change();
        });
    }
};
