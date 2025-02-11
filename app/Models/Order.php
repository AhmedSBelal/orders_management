<?php

namespace App\Models;

use App\Http\Requests\Orders\OrdersFilterRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{

    use HasFactory;

    protected $fillable = [
        'user_id',
        'location',
        'client_name',
        'client_phone',
        'city',
        'post_office',
        'deposited',
        'total_price',
        'status',
        'come_from'
    ];

    // relations
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function products(): BelongsToMany {
        return $this->belongsToMany( Product::class, 'order_product', 'order_id')
            ->withPivot('color_id', 'quantity', 'size'); // Include pivot columns;
    }

    // filter

    static function ordersByFilter(OrdersFilterRequest $request) {

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
            $orders->where('status', $request->status);
        }

        if (!empty($request->total_price)) {
            $orders->where('total_price', 'like', '%' . $request->total_price . '%');
        }

        if (!empty($request->deposited)) {
            $orders->where('deposited', 'like', '%' . $request->deposited . '%');
        }

        if(!empty($request->come_from)){
            $orders->where('come_from', 'like','%' . $request->come_from . '%');
        }

        if (!empty($request->payment_status)) {
            $orders->where('payment_status', $request->payment_status);
        }

        if(!empty($request->created_at)){
            $orders->whereDate('created_at', $request->created_at);
        }

        if(!empty($request->updated_at)){
            $orders->whereDate('created_at', $request->updated_at);
        }

        return $orders->get();

    }

}
