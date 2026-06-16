<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Order Service — OrderItem model.
 *
 * Owns the `order_items` table in `coffeehub_order` (Requirement 5.1, 6.4).
 *
 * menu_item_id is a plain integer cross-service reference — no Eloquent relationship
 * to a MenuItem model (which lives in coffeehub_catalog). Integrity is enforced at
 * order-creation time via Catalog_Service Internal_API (Requirement 5.2, 7.2, 11.4).
 *
 * ETAinMinutes is denormalised from the Catalog_Service response at creation time so
 * that totalPrepTime can be computed locally without additional cross-service calls
 * (Requirement 5.12).
 */
class OrderItem extends Model
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

    /**
     * The order this line item belongs to.
     * Intra-service relationship — both models are in coffeehub_order (safe to keep).
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
