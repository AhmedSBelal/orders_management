<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderStatus extends Model
{

    const IN_PROCESSING = 'تحت التجهيز';

    protected $fillable = ['name'];

    /**
     * Get the orders for the status.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'status_id');
    }

    // Helper method to get status by name
    public static function getByName(string $name)
    {
        return static::where('name', $name)->first();
    }

}
