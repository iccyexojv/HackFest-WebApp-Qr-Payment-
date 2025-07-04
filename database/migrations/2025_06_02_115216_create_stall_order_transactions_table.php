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
        Schema::create('stall_order_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stall_id');
            $table->foreignId('stall_order_id');
            $table->foreignId('stall_owner_id');
            $table->string('wallet_type');
            $table->decimal('amount', total:12, places:2);
            $table->string('status');
            $table->morphs('payer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stall_order_transactions');
    }
};
