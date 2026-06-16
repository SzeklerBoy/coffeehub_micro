<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Order Service — order_items migration.
 *
 * This migration targets ONLY the `coffeehub_order` database (Requirement 5.1, 6.4, 6.6).
 *
 * Cross-service FK constraints removed from the monolith:
 *   - menu_item_id: was foreignId()->constrained() → now plain unsignedBigInteger with index.
 *                   MenuItem records live in coffeehub_catalog; validated via Catalog_Service
 *                   Internal_API at order creation time (Requirement 5.2, 7.2).
 *
 * Intra-service FK retained:
 *   - order_id: foreignId()->constrained()->cascadeOnDelete() is safe — both orders and
 *               order_items are owned by the same coffeehub_order database (Requirement 5.1).
 *
 * ETAinMinutes is stored here (denormalised from Catalog_Service at creation time)
 * for totalPrepTime calculation without requiring a cross-service call per item
 * (Requirement 5.12).
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
        Schema::connection('coffeehub_order')->create('order_items', function (Blueprint $table) {
            $table->id();

            // Intra-service FK — safe to constrain within coffeehub_order.
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();

            // Cross-service reference — plain indexed integer, no DB-level FK constraint
            // (Requirement 5.2, 6.5). Integrity enforced via Catalog_Service Internal_API call.
            $table->unsignedBigInteger('menu_item_id')->index();

            $table->integer('quantity')->default(1);
            $table->integer('paid')->default(0);

            // Denormalised from Catalog_Service at order-creation time for totalPrepTime
            // calculation (Requirement 5.12). NULL means the item has no prep time.
            $table->double('ETAinMinutes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('coffeehub_order')->dropIfExists('order_items');
    }
};
