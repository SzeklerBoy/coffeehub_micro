<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MenuItem extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, table: 'order_items', relatedPivotKey: 'order_id')->withPivot(['quantity', 'paid']);
    }

    public function translation(): HasMany
    {
        return $this->hasMany(Translation::class);
    }

    public function translationWithLocale(): HasOne
    {
        $langID = Language::where('code', session('locale'))->value('id');

        return $this->hasOne(Translation::class)->where('language_id', $langID);

    }
}
