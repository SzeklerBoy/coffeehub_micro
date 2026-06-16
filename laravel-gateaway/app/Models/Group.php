<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Group extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function desks(): BelongsToMany
    {
        return $this->belongsToMany(Desk::class, relatedPivotKey: 'desk_id');
    }

    public function latestOrder(): HasOne
    {
        return $this->hasOne(Order::class)->latestOfMany();
    }

    public function activeOrders(): HasMany
    {
        return $this->hasMany(Order::class)->whereIn('status', ['ordered', 'pending']);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
