<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Facility Service — Group model.
 *
 * Owns the `groups` table in `coffeehub_facility` (Requirement 4.1, 6.3).
 *
 * Cross-service relationships removed from the monolith:
 *   - latestOrder()  — Order data lives in coffeehub_order; accessed via
 *                      Order_Service Internal_API (Requirement 6.3, 6.5).
 *   - activeOrders() — Same as above.
 *   - orders()       — Same as above.
 *
 * The desks() relationship is INTRA-service (both groups and desks are in
 * coffeehub_facility) and is therefore retained.
 */
class Group extends Model
{
    use HasFactory;

    /**
     * Bind this model to the coffeehub_facility connection (Requirement 6.3).
     */
    protected $connection = 'coffeehub_facility';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * Desks that belong to this group (intra-service pivot, safe to keep).
     */
    public function desks(): BelongsToMany
    {
        return $this->belongsToMany(Desk::class, relatedPivotKey: 'desk_id');
    }
}
