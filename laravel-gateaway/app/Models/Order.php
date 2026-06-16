<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'ordered_at' => 'datetime',
    ];

    public function desk(): BelongsTo
    {
        return $this->belongsTo(Desk::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function menuItems(): BelongsToMany
    {
        return $this->belongsToMany(MenuItem::class, table: 'order_items', relatedPivotKey: 'menu_item_id')->withPivot(['quantity', 'paid']);
    }

    public function waiter(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
