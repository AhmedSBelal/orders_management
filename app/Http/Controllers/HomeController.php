<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatuses;
use App\Models\Color;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function inProcessing() {
        $title = "Products In Processing";
        $orders = Order::where('status', OrderStatuses::InProcessing)->get();
        $colors = Color::all();
        $colorWithId = [];
        foreach ($colors as $color) {
            $colorWithId[$color->id] = $color->name;
        }
        // product name with color with quantity
        $data = [];
        foreach ($orders as $order) {
            $products = $order->products()->get();
            foreach ($products as $product) {

                if (!isset($data[$product->name][$colorWithId[$product->pivot->color_id]][(string)$product->pivot->size])) {
                    $data[$product->name][$colorWithId[$product->pivot->color_id]][(string)$product->pivot->size] = 0;
                }
                $data[$product->name][$colorWithId[$product->pivot->color_id]][(string)$product->pivot->size]
                    = $data[$product->name][$colorWithId[$product->pivot->color_id]][(string)$product->pivot->size] + $product->pivot->quantity;
            }
        }
        return view('Dashboard.in-processing', compact('title', 'data'));
    }

}
