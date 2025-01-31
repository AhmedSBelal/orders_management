<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{

    use HasFactory;

    protected $fillable = [
        'location',
        'client_name',
        'client_phone',
        'deposited',
        'total_price',
        'status',
    ];

    // relations
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function products(): BelongsToMany {
        return $this->belongsToMany( Product::class, 'order_product', 'order_id',);
    }

}
