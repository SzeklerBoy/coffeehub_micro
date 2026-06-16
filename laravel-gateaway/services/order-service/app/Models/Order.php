<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Order Service — Order model.
 *
 * Owns the `orders` table in `coffeehub_order` (Requirement 5.1, 6.4).
 *
 * Cross-service Eloquent relationships removed from the monolith:
 *   - desk()      — Desk data lives in coffeehub_facility; accessed via
 *                   Facility_Service Internal_API (Requirement 11.4, 7.1).
 *   - group()     — Group data lives in coffeehub_facility; accessed via
 *                   Facility_Service Internal_API (Requirement 11.4, 7.1).
 *   - menuItems() — MenuItem data lives in coffeehub_catalog; accessed via
 *                   Catalog_Service Internal_API (Requirement 11.4, 7.2).
 *   - waiter()    — User data lives in coffeehub_auth; accessed via
 *                   Auth_Service Internal_API (Requirement 11.4, 7.3).
 *
 * Cross-service reference columns (desk_id, group_id, waiter_id) are stored
 * as plain indexed integers without DB-level FK constraints (Requirement 5.2, 6.5).
 *
 * Intra-service relationships retained:
 *   - orderItems() — OrderItem lives in the same coffeehub_order database.
 */
class Order extends Model
{
    use HasFactory;

    /**
     * Bind this model to the coffeehub_order connection (Requirement 6.4).
     */
    protected $connection = 'coffeehub_order';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'ordered_at'    => 'datetime',
        'completed_at'  => 'datetime',
    ];

    /**
     * The line items that belong to this order.
     * Intra-service relationship — both models are in coffeehub_order (safe to keep).
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Recalculate and persist totalPrepTime.
     *
     * totalPrepTime = Σ (ETAinMinutes ?? 0) × quantity  for all order_items
     * (Requirement 5.12)
     *
     * Call this after every line-item add or remove.
     */
    public function recalculateTotalPrepTime(): void
    {
        $total = $this->orderItems()
            ->get()
            ->sum(fn (OrderItem $item) => ($item->ETAinMinutes ?? 0) * $item->quantity);

        $this->totalPrepTime = $total;
        $this->save();
    }
}
