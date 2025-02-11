<?php

namespace App\Http\Controllers;

use App\Http\Requests\Orders\OrderCreateRequest;
use App\Http\Requests\Orders\OrdersFilterRequest;
use App\Http\Requests\Orders\OrderUpdateRequest;
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
        try {
            $title = 'Dashboard - Orders';
            $orders = Order::ordersByFilter($request);
            return view('Dashboard.index', compact('orders', 'title'));
        } catch (\Exception $exception) {
            return 'something went wrong';
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Create - Order';
        $products = Product::all();
        $colors = Color::all();
        return view('Dashboard.create-order', compact('title', 'products', 'colors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderCreateRequest $request)
    {
        $data = $request->validated();
        $productsId = $data['products'];
        $colorsId = $data['colors'];
        $quantities = $data['quantities'];
        $sizes = $data['sizes'];

        $productQuantity = [];
        $sz = count($data['colors']);
        for ($i = 0; $i < $sz; $i++) {
            $productQuantity[$productsId[$i]]['quantity'] = !isset($productQuantity[$productsId[$i]]['quantity']) ? 0 : $productQuantity[$productsId[$i]]['quantity'];
            $productQuantity[$productsId[$i]] = [
                'quantity' => $productQuantity[$productsId[$i]]['quantity'] + $quantities[$i],
                ];
        }

        $products = Product::whereIn('id', $productsId)->get();

        $total_price = 0;

        foreach ($products as $product) {
            $total_price += $product->price * $productQuantity[$product->id]['quantity'];
        }

        $order = Order::create([
            'user_id'      => auth()->id(),
            'location'     => $data['location'],
            'client_name'  => $data['client_name'],
            'client_phone' => $data['client_phone'],
            'city'         => $data['city'],
            'post_office'  => $data['post_office'],
            'deposited'    => $data['deposited'],
            'total_price'  => $total_price,
            'status'       => $data['status'],
            'come_from'    => $data['come_from'],
        ]);

        for ($i = 0; $i < $sz; $i++) {
            $order->products()->attach($productsId[$i], [
                "color_id"   => $colorsId[$i],
                "size"       => $sizes[$i],
                "quantity"   => $quantities[$i]
            ]);
        }

        return redirect()->route('orders.create')->with('success', 'Order created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
//        $title = 'Show - Order';
//        $colors = Color::all();
//        $products = Product::all();
//        $products = $order->products()->get();
//        foreach ($products as $product) {
//            $product['color_id'] = $product->pivot->color_id;
//            $product['size'] = $product->pivot->size;
//            $product['quantity'] = $product->pivot->quantity;
//        }
//        $order['products'] = $products;
//        return view('Dashboard.show-order', compact('title', 'order', 'colors', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $title = 'Edit - Order';
        $colors = Color::all();
        $productsData = Product::all();
        $products = $order->products()->get();
        foreach ($products as $product) {
            $product['color_id'] = $product->pivot->color_id;
            $product['size'] = $product->pivot->size;
            $product['quantity'] = $product->pivot->quantity;
        }
        $order['products'] = $products;
        return view('Dashboard.edit-order', compact('title', 'order', 'colors', 'productsData'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(OrderUpdateRequest $request, Order $order)
    {
        $data = $request->validated();
        $productsId = $data['products'];
        $colorsId = $data['colors'];
        $quantities = $data['quantities'];
        $sizes = $data['sizes'];

        $productQuantity = [];
        $sz = count($data['colors']);
        for ($i = 0; $i < $sz; $i++) {
            $productQuantity[$productsId[$i]]['quantity'] = !isset($productQuantity[$productsId[$i]]['quantity']) ? 0 : $productQuantity[$productsId[$i]]['quantity'];
            $productQuantity[$productsId[$i]] = [
                'quantity' => $productQuantity[$productsId[$i]]['quantity'] + $quantities[$i],
            ];
        }

        $products = Product::whereIn('id', $productsId)->get();

        $total_price = 0;

        foreach ($products as $product) {
            $total_price += $product->price * $productQuantity[$product->id]['quantity'];
        }

        $order->update([
            'location'     => $data['location'],
            'client_name'  => $data['client_name'],
            'client_phone' => $data['client_phone'],
            'city'         => $data['city'],
            'post_office'  => $data['post_office'],
            'deposited'    => $data['deposited'],
            'total_price'  => $total_price,
            'status'       => $data['status'],
            'come_from'    => $data['come_from'],
            'payment_status' => $data['payment_status'],
        ]);

        $order->products()->detach();

        for ($i = 0; $i < $sz; $i++) {
            $order->products()->attach($productsId[$i], [
                "color_id"   => $colorsId[$i],
                "size"       => $sizes[$i],
                "quantity"   => $quantities[$i]
            ]);
        }

        return redirect()->back()->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        try {
            if ($order->delete()) {
                return redirect()->back()->with('success', 'Order deleted successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to delete order.');
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

}
