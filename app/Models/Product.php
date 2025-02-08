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
        'cost',
        'tailor_name',
        'type'
    ];

    // relations
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function orders(): BelongsToMany {
        return $this->belongsToMany( Order::class, 'order_product', 'product_id');
    }

    static function productsByFilter($request) {

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

        return $products->get();

    }

}
