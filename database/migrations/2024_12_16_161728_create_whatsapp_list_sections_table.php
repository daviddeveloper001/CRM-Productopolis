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
        Schema::create('whatsapp_list_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('whatsapp_list_id');
            $table->timestamps();

            $table->foreign('whatsapp_list_id')->references('id')->on('whatsapp_lists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_list_sections');
    }
};
