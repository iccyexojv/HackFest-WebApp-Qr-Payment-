<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();

            $table->string(column: 'name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();

            $table->string('password');
            $table->string('contact_number')
                ->nullable();
            $table->string('address')
                ->nullable();
            // $table->string('college_name')
            //     ->nullable();

            $table->rememberToken();

            $table->timestamps();
        });

        Schema::create('visitor_password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('visitor_sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('visitor_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
        Schema::dropIfExists('visitor_password_reset_tokens');
        Schema::dropIfExists('visitor_sessions');
    }
};
