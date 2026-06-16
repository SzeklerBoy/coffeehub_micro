<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Catalog Service — languages migration.
 *
 * This migration targets ONLY the `coffeehub_catalog` database (Requirement 6.2, 6.6).
 * The explicit connection() binding prevents this migration from running against
 * any other service's database (Requirement 6.7).
 */
return new class extends Migration
{
    /**
     * The database connection that should be used by the migration.
     * Ensures this migration exclusively targets coffeehub_catalog (Requirement 6.6).
     */
    protected $connection = 'coffeehub_catalog';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('coffeehub_catalog')->create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('coffeehub_catalog')->dropIfExists('languages');
    }
};
