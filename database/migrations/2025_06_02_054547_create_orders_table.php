<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stall_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('stall_owner_id')->constrained()->onDelete('cascade');
            $table->morphs('orderable'); // creates orderable_type and orderable_id
            $table->integer('fest_point')->default(0);
            $table->integer('game_point')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

