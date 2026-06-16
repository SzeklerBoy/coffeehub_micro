<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Catalog Service — translations migration.
 *
 * This migration targets ONLY the `coffeehub_catalog` database (Requirement 6.2, 6.6).
 * FK constraints here reference menu_items and languages in the same `coffeehub_catalog`
 * database only — no cross-service FK constraints (Requirement 6.5).
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
        Schema::connection('coffeehub_catalog')->create('translations', function (Blueprint $table) {
            $table->id();
            // FK to menu_items and languages in coffeehub_catalog — intra-service, allowed (Requirement 6.5).
            $table->foreignId('menu_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('language_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('category');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('coffeehub_catalog')->dropIfExists('translations');
    }
};
