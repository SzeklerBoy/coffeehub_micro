<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Catalog Service — MenuItem model.
 *
 * Owns the `menu_items` table in `coffeehub_catalog` (Requirement 2.1, 6.2).
 * The cross-service `orders()` relationship present in the monolith has been removed;
 * order data is accessed via the Order_Service Internal_API instead (Requirement 11.4).
 */
class MenuItem extends Model
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
     * All translations for this menu item.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(Translation::class);
    }

    /**
     * Single translation for the currently active locale (stored in session).
     * Falls back gracefully when the locale is not found.
     */
    public function translationWithLocale(): HasOne
    {
        $langID = Language::where('code', session('locale'))->value('id');

        return $this->hasOne(Translation::class)->where('language_id', $langID);
    }
}
