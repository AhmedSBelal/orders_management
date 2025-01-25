<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'client_id',
        'deposited',
        'total_price'
    ];

    // relations

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function client(): BelongsTo {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function products(): BelongsToMany {
        return $this->belongsToMany(Product::class, 'order_product', 'order_id', 'product_id');
    }

}
