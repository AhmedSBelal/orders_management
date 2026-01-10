<?php

namespace App\Models;

use App\Http\Requests\Orders\OrdersFilterRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{

    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'wholesale_price',
        'cost',
        'tailor_name',
        'type'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'cost' => 'decimal:2',
    ];

    // relations
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function orders(): BelongsToMany {
        return $this->belongsToMany( Order::class, 'order_product', 'product_id')
        ->withPivot('color_id', 'quantity', 'size', 'is_done', 'price');
    }

    /**
     * Get the images for the product.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    static function productsByFilter($request) 
    {
        $products = self::query();

        if (!empty($request->name)) {
            $products->where('name', 'like', '%' . $request->name . '%');
        }

        if (!empty($request->description)) {
            $products->where('description', 'like', '%' . $request->description . '%');
        }

        if (!empty($request->price)) {
            $products->where('price', 'like', '%' . $request->price . '%');
        }

        // فلترة حسب سعر الجمله
        if (!empty($request->wholesale_price)) {
            $products->where('wholesale_price', 'like', '%' . $request->wholesale_price . '%');
        }

        if (!empty($request->cost)) {
            $products->where('cost', 'like', '%' . $request->cost . '%');
        }

        if (!empty($request->tailor_name)) {
            $products->where('tailor_name', 'like', '%' . $request->tailor_name . '%');
        }

        if (!empty($request->type)) {
            $products->where('type', 'like', '%' . $request->type . '%');
        }

        if(!empty($request->created_at)){
            $products->whereDate('created_at', $request->created_at);
        }

        if(!empty($request->updated_at)){
            $products->whereDate('created_at', $request->updated_at);
        }

        return $products;
    }

    // دالة للحصول على السعر بناءً على نوع الطلب
    public function getPriceByOrderType($isWholesale = false)
    {
        if ($isWholesale) {
            return $this->wholesale_price ?? $this->price;
        }
        return $this->price;
    }

    // دالة للحصول على نسبة الخصم الجمله
    public function getWholesaleDiscountPercentageAttribute()
    {
        if (!$this->wholesale_price || $this->price == 0) {
            return 0;
        }
        return round((($this->price - $this->wholesale_price) / $this->price) * 100, 2);
    }
    // دالة للحصول على الفرق بين السعرين
    public function getPriceDifferenceAttribute()
    {
        return $this->price - ($this->wholesale_price ?? $this->price);
    }

}
