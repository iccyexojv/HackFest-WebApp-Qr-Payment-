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
        Schema::create('stall_order_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->longText('old_data')
                ->nullable()
                ->default(null);
            $table->longText('new_data')
                ->nullable()
                ->default(null);
            $table->string('caused_by_type')
                ->nullable();
            $table->unsignedBigInteger('caused_by_id')
                ->nullable();
            $table->morphs('logged_for');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stall_order_logs');
    }
};
