<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Auth Service — users, password_reset_tokens, and sessions migrations.
 *
 * This migration targets ONLY the `coffeehub_auth` database (Requirement 6.1, 6.6).
 * Any attempt to run it against a different connection will use the explicit
 * connection() binding set below.
 */
return new class extends Migration
{
    /**
     * The database connection that should be used by the migration.
     * Ensures this migration exclusively targets coffeehub_auth (Requirement 6.6).
     */
    protected $connection = 'coffeehub_auth';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('coffeehub_auth')->create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone')->nullable();
            $table->string('role')->nullable();
            $table->boolean('is_active');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            // Unique index on email at the database level (Requirement 1.7, 6.6).
            $table->unique('email', 'users_email_unique');
        });

        Schema::connection('coffeehub_auth')->create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::connection('coffeehub_auth')->create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
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
        Schema::connection('coffeehub_auth')->dropIfExists('sessions');
        Schema::connection('coffeehub_auth')->dropIfExists('password_reset_tokens');
        Schema::connection('coffeehub_auth')->dropIfExists('users');
    }
};
