<?php

use App\Models\StallOwner;
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
        Schema::create('stall_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stall_id');
            $table->foreignId('stall_owner_id');
            $table->morphs('ordered_for');
            $table->decimal('fest_point_total_amount', total: 12, places: 2);
            $table->decimal('game_point_total_amount', total: 12, places: 2);
            $table->string('wallet_type')
                ->nullable()
                ->default(null);
            $table->string('status');
            $table->string('code')
                ->nullable()
                ->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stall_orders');
    }
};
