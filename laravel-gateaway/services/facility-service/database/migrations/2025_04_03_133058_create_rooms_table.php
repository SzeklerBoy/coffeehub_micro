<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Facility Service — rooms migration.
 *
 * This migration targets ONLY the `coffeehub_facility` database (Requirement 4.1, 6.3, 6.6).
 * The explicit connection() binding prevents this migration from running against
 * any other service's database (Requirement 6.7).
 */
return new class extends Migration
{
    /**
     * The database connection that should be used by the migration.
     * Ensures this migration exclusively targets coffeehub_facility (Requirement 6.6).
     */
    protected $connection = 'coffeehub_facility';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('coffeehub_facility')->create('rooms', function (Blueprint $table) {
            $table->id();
            $table->integer('width');
            $table->integer('length');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('coffeehub_facility')->dropIfExists('rooms');
    }
};
