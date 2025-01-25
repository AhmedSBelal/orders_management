<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'description',
        'price',
        'cost',
        'tailor',
        'color_id',
    ];

    // relations

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'product_id');
    }

    public function order(): HasMany
    {
        return $this->belongsToMany(Order::class, 'order_product_pivot', 'product_id', 'order_id');
    }

}
