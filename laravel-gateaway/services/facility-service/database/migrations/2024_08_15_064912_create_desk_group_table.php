<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Facility Service — desk_group pivot migration.
 *
 * This migration targets ONLY the `coffeehub_facility` database (Requirement 4.1, 6.3, 6.6).
 * The explicit connection() binding prevents this migration from running against
 * any other service's database (Requirement 6.7).
 *
 * The FK constraints here are INTRA-service (desk_id → desks.id and group_id → groups.id
 * are both within coffeehub_facility) and are therefore kept (Requirement 6.5).
 * Only FKs that would cross into another service's database (e.g. orders.desk_id → desks.id)
 * are omitted — those live in the Order_Service DB instead.
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
        Schema::connection('coffeehub_facility')->create('desk_group', function (Blueprint $table) {
            $table->id();
            // Both FKs reference tables within coffeehub_facility — safe to keep (Requirement 6.5).
            $table->foreignId('group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('desk_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('coffeehub_facility')->dropIfExists('desk_group');
    }
};
