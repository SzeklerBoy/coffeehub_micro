<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Catalog Service — Language model.
 *
 * Owns the `languages` table in `coffeehub_catalog` (Requirement 2.1, 6.2).
 */
class Language extends Model
{
    use HasFactory;

    /**
     * Bind this model to the coffeehub_catalog connection (Requirement 6.2).
     */
    protected $connection = 'coffeehub_catalog';

    /**
     * All translations in this language.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(Translation::class);
    }
}
