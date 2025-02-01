<?php

namespace App\Http\Controllers;

use App\Http\Requests\Orders\CreateOrderRequest;
use App\Http\Requests\Orders\OrdersFilterRequest;
use App\Models\Color;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(OrdersFilterRequest $request)
    {
        $title = 'Dashboard - Orders';
        $orders = Order::ordersByFilter($request);
        return view('Dashboard.index', compact('orders', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Create - Orders';
        $products = Product::all();
        $colors = Color::all();
        return view('Dashboard.create-order', compact('title', 'products', 'colors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateOrderRequest $request)
    {
        $data = $request->validated();
//        dd($data);
        $productsId = $data['products'];
        $colorsId = $data['colors'];
        $quantitiesId = $data['quantities'];

//        dd($colorsId);

        $productQuantity = [];
        $sz = count($data['colors']);
        for ($i = 0; $i < $sz; $i++) {
            $productQuantity[$productsId[$i]]['quantity'] = !isset($productQuantity[$productsId[$i]]['quantity']) ? 0 : $productQuantity[$productsId[$i]]['quantity'];
            $productQuantity[$productsId[$i]] = [
                'quantity' => $productQuantity[$productsId[$i]]['quantity'] + $quantitiesId[$i],
                ];
        }

        $products = Product::whereIn('id', $productsId)->get();

        $total_price = 0;

        foreach ($products as $product) {
            $total_price += $product->price * $productQuantity[$product->id]['quantity'];
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'location' => $data['location'],
            'client_name' => $data['client_name'],
            'client_phone' => $data['client_phone'],
            'deposited' => $data['deposited'],
            'total_price' => $total_price,
            'status' => $data['status']
        ]);

        for ($i = 0; $i < $sz; $i++) {
            $order->products()->attach($productsId[$i], [
                "color_id"   => $colorsId[$i],
                "quantity"   => $quantitiesId[$i]
            ]);
        }

        return redirect()->route('orders.create')->with('success', 'Order created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Order $orders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $orders)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $orders)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $orders)
    {
        //
    }
}
