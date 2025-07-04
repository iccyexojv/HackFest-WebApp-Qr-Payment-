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
        Schema::create('stall_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stall_id');
            $table->string('name');
            $table->string('description')->nullable();
            // Images
            $table->decimal('fest_point_price', total:12, places:2);
            $table->decimal('game_point_price', total:12, places:2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stall_items');
    }
};
