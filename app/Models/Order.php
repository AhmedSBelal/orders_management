<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\Orders\OrdersFilterRequest;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{

    use HasFactory;

    protected $fillable = [
        'user_id',
        'is_wholesale',
        'location',
        'client_name',
        'client_phone',
        'city',
        'post_office',
        'deposited',
        'total_price',
        'status_id',
        'come_from',
        'payment_status',
        'notes',
        'total_price_after_discount',
    ];

    // relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_product', 'order_id')
            ->withPivot('color_id', 'quantity', 'size', 'is_done', 'price'); // Include pivot columns;
    }

    public function status(): BelongsTo // <-- إضافة العلاقة الجديدة
    {
        return $this->belongsTo(OrderStatus::class);
    }

    /**
     * Get the images for the order.
     */
    public function images(): HasMany // <-- العلاقة الجديدة
    {
        return $this->hasMany(OrderImage::class);
    }


    // filter

    static function ordersByFilter(OrdersFilterRequest $request)
    {
        // Start with a query builder instance
        $orders = self::query();

        if (!empty($request->location)) {
            $orders->where('location', 'like', '%' . $request->location . '%');
        }

        if (!empty($request->client_name)) {
            $orders->where('client_name', 'like', '%' . $request->client_name . '%');
        }

        if (!empty($request->client_phone)) {
            $orders->where('client_phone', 'like', '%' . $request->client_phone . '%');
        }

        if (!empty($request->status)) {
            // البحث عن الحالة بالاسم أولاً
            $status = \App\Models\OrderStatus::where('name', $request->status)->first();
            if ($status) {
                $orders->where('status_id', $status->id);
            }
        }

        if (!empty($request->total_price)) {
            $orders->where('total_price', 'like', '%' . $request->total_price . '%');
        }

        if (!empty($request->deposited)) {
            $orders->where('deposited', 'like', '%' . $request->deposited . '%');
        }

        if (!empty($request->come_from)) {
            $orders->where('come_from', 'like', '%' . $request->come_from . '%');
        }

        if (!empty($request->payment_status)) {
            $orders->where('payment_status', $request->payment_status);
        }

        if (!empty($request->created_at)) {
            $orders->whereDate('created_at', $request->created_at);
        }

        if (!empty($request->updated_at)) {
            $orders->whereDate('updated_at', $request->updated_at);
        }

        if (isset($request->is_wholesale) && $request->is_wholesale !== '') {
            // يمكن تمرير القيمة كـ 0/1 أو true/false
            $orders->where('is_wholesale', (bool) $request->is_wholesale);
        }

        return $orders;
    }


    // Accessor للحصول على النوع كنص (اختياري)
    public function getOrderTypeAttribute(): string
    {
        return $this->is_wholesale ? 'جمله' : 'قطاعي';
    }
    
    // Accessor للحصول على النوع باللغة الإنجليزية (اختياري)
    public function getOrderTypeEnglishAttribute(): string
    {
        return $this->is_wholesale ? 'wholesale' : 'retail';
    }
    
    // دالة للتحقق من النوع (اختياري)
    public function isWholesale(): bool
    {
        return (bool) $this->is_wholesale;
    }
    
    public function isRetail(): bool
    {
        return !$this->is_wholesale;
    }
    
    // دالة لتغيير النوع (اختياري)
    public function setAsWholesale(): void
    {
        $this->is_wholesale = true;
    }
    
    public function setAsRetail(): void
    {
        $this->is_wholesale = false;
    }

    // دالة لحساب السعر الإجمالي بناءً على نوع الطلب
    public function calculateTotalPrice()
    {
        $total = 0;
        
        foreach ($this->products as $product) {
            $productPrice = $product->getPriceByOrderType($this->is_wholesale);
            $quantity = $product->pivot->quantity ?? 1;
            $total += $productPrice * $quantity;
        }
        
        $this->total_price = $total;
        return $total;
    }

    // عند إضافة منتج للطلب، استخدم السعر المناسب
    public function addProduct(Product $product, $quantity = 1, $attributes = [])
    {
        $price = $product->getPriceByOrderType($this->is_wholesale);
        
        $this->products()->attach($product->id, array_merge([
            'quantity' => $quantity,
            'price' => $price, // يمكنك إضافة حقل price للـ pivot إذا أردت
        ], $attributes));
        
        $this->calculateTotalPrice();
        $this->save();
    }

    // للحصول على السعر الإجمالي من pivot table مباشرة
    public function getTotalFromPivotAttribute()
    {
        return $this->products->sum(function ($product) {
            return $product->pivot->price * $product->pivot->quantity;
        });
    }

}
