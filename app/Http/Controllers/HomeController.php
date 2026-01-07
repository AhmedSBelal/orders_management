<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderStatus;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    // public function inProcessing() {
    //     $title = "Products In Processing";
    //     $orders = Order::where('status', OrderStatuses::InProcessing)->get();
    //     $colors = Color::all();
    //     $colorWithId = [];
    //     foreach ($colors as $color) {
    //         $colorWithId[$color->id] = $color->name;
    //     }
    //     // product name with color with quantity
    //     $data = [];
    //     foreach ($orders as $order) {
    //         $products = $order->products()->get();
    //         foreach ($products as $product) {

    //             if (!isset($data[$product->name][$colorWithId[$product->pivot->color_id]][(string)$product->pivot->size])) {
    //                 $data[$product->name][$colorWithId[$product->pivot->color_id]][(string)$product->pivot->size] = 0;
    //             }
    //             $data[$product->name][$colorWithId[$product->pivot->color_id]][(string)$product->pivot->size]
    //                 = $data[$product->name][$colorWithId[$product->pivot->color_id]][(string)$product->pivot->size] + $product->pivot->quantity;
    //         }
    //     }
    //     return view('orders.in-processing', compact('title', 'data'));
    // }

    public function welcome()
    {
        $title = "Welcome";
        return view('welcome', compact('title'));
    }

    public function inProcessing()
    {
        $title = "Products In Processing";

        // Use model constant
        $inProcessingStatus = OrderStatus::getByName(OrderStatus::IN_PROCESSING);

        if (!$inProcessingStatus) {
            return view('orders.in-processing', [
                'title' => $title,
                'data' => []
            ])->with('error', 'In Processing status not configured.');
        }

        $orders = Order::where('status_id', $inProcessingStatus->id)
            ->with('products')
            ->get();

        $colorWithId = Color::pluck('name', 'id')->toArray();

        $data = [];
        foreach ($orders as $order) {
            foreach ($order->products as $product) {
                $colorName = $colorWithId[$product->pivot->color_id] ?? 'Unknown';
                $size = (string) ($product->pivot->size ?? 'N/A');

                if (!isset($data[$product->name][$colorName][$size])) {
                    $data[$product->name][$colorName][$size] = 0;
                }

                $data[$product->name][$colorName][$size] += $product->pivot->quantity;
            }
        }

        return view('orders.in-processing', compact('title', 'data'));
    }

}
