<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Facility Service — Room model.
 *
 * Owns the `rooms` table in `coffeehub_facility` (Requirement 4.1, 6.3).
 * No cross-service FK relationships — rooms are self-contained within
 * the Facility_Service database.
 */
class Room extends Model
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
}
