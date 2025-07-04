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
        Schema::create('stall_stall_owner', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stall_id');
            $table->foreignId('stall_owner_id');
            $table->boolean('can_manage_stall_profile')
                ->default(false);
            $table->boolean('can_manage_stall_owner')
                ->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stall_stall_owner');
    }
};
