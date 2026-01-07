<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderImage extends Model
{
    protected $fillable = [
        'order_id',
        'photo_path',
    ];

    /**
     * Get the order that owns the image.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
