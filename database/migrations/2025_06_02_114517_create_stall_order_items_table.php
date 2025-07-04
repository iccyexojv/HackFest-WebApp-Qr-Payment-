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
        Schema::create('stall_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stall_id');
            $table->foreignId('stall_order_id');
            $table->foreignId('stall_item_id');
            $table->decimal('fest_point_price', total:12, places:2);
            $table->decimal('game_point_price', total:12, places:2);
            $table->decimal('quantity', total:12, places:2);
            $table->decimal('fest_point_total_amount', total:12, places:2);
            $table->decimal('game_point_total_amount', total:12, places:2);
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stall_order_items');
    }
};
