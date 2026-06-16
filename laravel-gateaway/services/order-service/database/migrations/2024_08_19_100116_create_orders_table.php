<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Order Service — orders migration.
 *
 * This migration targets ONLY the `coffeehub_order` database (Requirement 5.1, 6.4, 6.6).
 * The explicit connection() binding prevents this migration from running against
 * any other service's database (Requirement 6.7).
 *
 * Cross-service FK constraints removed from the monolith:
 *   - desk_id:    was foreignId()->constrained() → now plain unsignedBigInteger with index.
 *                 Desk records live in coffeehub_facility; validated via Facility_Service
 *                 Internal_API at order creation time (Requirement 5.2, 7.1).
 *   - group_id:   same treatment as desk_id (Requirement 5.2, 7.1).
 *   - waiter_id:  was a soft FK to users in coffeehub_auth → now plain unsignedBigInteger
 *                 with index. Validated via Auth_Service Internal_API (Requirement 5.2, 7.3).
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
        Schema::connection('coffeehub_order')->create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();

            // Cross-service references — plain indexed integers, no DB-level FK constraints
            // (Requirement 5.2, 6.4, 6.5). Integrity enforced via Internal_API calls.
            $table->unsignedBigInteger('desk_id')->nullable()->index();
            $table->unsignedBigInteger('group_id')->nullable()->index();
            $table->unsignedBigInteger('waiter_id')->nullable()->index();

            $table->timestamp('ordered_at');
            $table->timestamp('completed_at')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['ordered', 'pending', 'served', 'completed', 'cancelled'])
                  ->default('ordered');
            $table->double('totalPrepTime')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('coffeehub_order')->dropIfExists('orders');
    }
};
