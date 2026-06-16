<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Catalog Service — Translation model.
 *
 * Owns the `translations` table in `coffeehub_catalog` (Requirement 2.1, 6.2).
 * Stores localised name, description, and category for each MenuItem per Language.
 * Trans-unit IDs in XLIFF exports follow the pattern `db.{menu_item_id}.{field}`
 * where field ∈ {name, description, category} (Design: XLIFF contract).
 */
class Translation extends Model
{
    use HasFactory;

    /**
     * Bind this model to the coffeehub_catalog connection (Requirement 6.2).
     */
    protected $connection = 'coffeehub_catalog';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * The menu item this translation belongs to.
     */
    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    /**
     * The language this translation is written in.
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
