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
        Schema::dropIfExists('segment_registers');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('segment_registers', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('segment_id')->constrained('segments');
            $table->foreignId('customer_id')->constrained('customers');
            $table->timestamps();
        });
    }
};
