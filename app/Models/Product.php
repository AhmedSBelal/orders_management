<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    // relations
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function orders(): BelongsToMany {
        return $this->belongsToMany( Order::class, 'order_product', 'product_id');
    }
}
