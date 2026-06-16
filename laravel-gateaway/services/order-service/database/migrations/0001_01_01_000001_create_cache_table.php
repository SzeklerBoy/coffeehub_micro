<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Order Service — cache and cache_locks migration.
 *
 * Provisions the cache infrastructure tables in `coffeehub_order` so that the
 * Order_Service can use database-backed caching independently (Requirement 11.5).
 *
 * This migration targets ONLY `coffeehub_order` (Requirement 6.4, 6.6).
 */
return new class extends Migration
{
    /**
     * The database connection that should be used by the migration.
     * Ensures this migration exclusively targets coffeehub_order (Requirement 6.6).
     */
    protected $connection = 'coffeehub_order';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('coffeehub_order')->create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::connection('coffeehub_order')->create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('coffeehub_order')->dropIfExists('cache_locks');
        Schema::connection('coffeehub_order')->dropIfExists('cache');
    }
};
