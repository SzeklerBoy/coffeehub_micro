<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Facility Service — desks migration.
 *
 * This migration targets ONLY the `coffeehub_facility` database (Requirement 4.1, 6.3, 6.6).
 * The explicit connection() binding prevents this migration from running against
 * any other service's database (Requirement 6.7).
 *
 * Note: The monolith's FK from `orders.desk_id → desks.id` is NOT present here.
 * That reference lives in the Order_Service DB and is enforced at the application
 * layer via Internal_API calls (Requirement 5.2, 6.5).
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
        Schema::connection('coffeehub_facility')->create('desks', function (Blueprint $table) {
            $table->id();
            $table->integer('deskID')->unique()->nullable();
            $table->boolean('is_occupied')->default(false);
            $table->timestamp('joined_at')->nullable();
            $table->integer('nrOfSeats')->default(0);
            $table->string('description')->nullable();
            $table->double('x');
            $table->double('y');
            $table->string('code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('coffeehub_facility')->dropIfExists('desks');
    }
};
