<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Orders\OrderCreateRequest;
use App\Http\Requests\Orders\OrderUpdateRequest;
use App\Http\Requests\Orders\OrdersFilterRequest;
use App\Http\Requests\Orders\BulkUpdateStatusRequest;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(OrdersFilterRequest $request)
    {
        try {
            $title = 'Dashboard - Orders';

            // This now receives a QueryBuilder instance from the model
            $ordersQuery = Order::ordersByFilter($request);

            // Get the number of items per page from the request, defaulting to 10
            $perPage = $request->get('per_page', 10);

            // Execute the query with pagination and append all query parameters to the pagination links
            $orders = $ordersQuery->paginate($perPage)->appends($request->query());

            return view('Dashboard.index', compact('orders', 'title'));
        } catch (\Exception $exception) {
            Log::error('Error in OrderController@index: ' . $exception->getMessage());
            return redirect()->back()->with('error', 'An error occurred while fetching orders. Please try again later.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $title = 'Create - Order';
            $products = Product::all();
            $colors = Color::all();
            return view('Dashboard.create-order', compact('title', 'products', 'colors'));
        } catch (\Exception $exception) {
            Log::error('Error in OrderController@create: ' . $exception->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the create form. Please try again later.');
        }
    }

    /**
     * Store a newly created resource in storage.OrderCreateRequest
     */
    public function store(OrderCreateRequest $request)
    {
        // dd($request->all());
        try {
            $data = $request->validated();

            if (!$this->validateArrays($data)) {
                return redirect()->back()->with('error', 'Invalid product data. Please check your input.');
            }

            $productsId = $data['products'];
            $colorsId = $data['colors'];
            $quantities = $data['quantities'];
            $sizes = $data['sizes'];
            $isDone = $data['is_done'] ?? [];

            $productQuantity = $this->calculateProductQuantities($productsId, $quantities);
            $total_price = $this->calculateTotalPrice($productsId, $productQuantity);

            $order = Order::create([
                'user_id' => auth()->id(),
                'location' => $data['location'],
                'client_name' => $data['client_name'],
                'client_phone' => $data['client_phone'],
                'city' => $data['city'],
                // 'post_office'  => $data['post_office'],
                'deposited' => $data['deposited'] ?? 0,
                'total_price' => $total_price,
                'status' => $data['status'],
                'come_from' => $data['come_from'],
                'total_price_after_discount' => $data['total_price_after_discount'] ?? 0,
                'notes' => $data['notes'],
            ]);

            $this->attachProductsToOrder($order, $productsId, $colorsId, $sizes, $quantities, $isDone);

            return redirect()->route('orders.create')->with('success', 'Order created successfully.');
        } catch (\Exception $exception) {
            Log::error('Error in OrderController@store: ' . $exception->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating the order. Please try again later.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        try {
            $title = 'Edit - Order';
            $colors = Color::all();
            $productsData = Product::all();
            $products = $order->products()->get();

            $products = $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'color_id' => $product->pivot->color_id,
                    'size' => $product->pivot->size,
                    'quantity' => $product->pivot->quantity,
                    'is_done' => $product->pivot->is_done
                ];
            });

            return view('Dashboard.edit-order', compact('title', 'order', 'colors', 'productsData', 'products'));
        } catch (\Exception $exception) {
            Log::error('Error in OrderController@edit: ' . $exception->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the edit form. Please try again later.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderUpdateRequest $request, Order $order)
    {
        // dd($request->all());
        try {
            $data = $request->validated();

            if (!$this->validateArrays($data)) {
                return redirect()->back()->with('error', 'Invalid product data. Please check your input.');
            }

            $productsId = $data['products'];
            $colorsId = $data['colors'];
            $quantities = $data['quantities'];
            $sizes = $data['sizes'];
            $isDone = $data['is_done'] ?? [];

            $productQuantity = $this->calculateProductQuantities($productsId, $quantities);
            $total_price = $this->calculateTotalPrice($productsId, $productQuantity);

            $order->update([
                'location' => $data['location'],
                'client_name' => $data['client_name'],
                'client_phone' => $data['client_phone'],
                'city' => $data['city'],
                // 'post_office'  => $data['post_office'],
                'deposited' => $data['deposited'] ?? 0,
                'total_price' => $total_price,
                'status' => $data['status'],
                'come_from' => $data['come_from'],
                // 'payment_status' => $data['payment_status'],
                'total_price_after_discount' => $data['total_price_after_discount'] ?? 0,
                'notes' => $data['notes'] ?? null,
            ]);

            $order->products()->detach();
            $this->attachProductsToOrder($order, $productsId, $colorsId, $sizes, $quantities, $isDone);
            // dd($request->all());
            return redirect()->back()->with('success', 'Order updated successfully.');
        } catch (\Exception $exception) {
            Log::error('Error in OrderController@update: ' . $exception->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the order. Please try again later.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        try {
            if ($order->delete()) {
                return redirect()->back()->with('success', 'Order deleted successfully.');
            }
            return redirect()->back()->with('error', 'Failed to delete order.');
        } catch (\Exception $exception) {
            Log::error('Error in OrderController@destroy: ' . $exception->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the order. Please try again later.');
        }
    }

    /**
     * Validate that all arrays have the same length
     */
    private function validateArrays($data): bool
    {
        $arrays = ['products', 'colors', 'quantities', 'sizes'];
        $length = count($data['products']);

        foreach ($arrays as $array) {
            if (!isset($data[$array]) || count($data[$array]) !== $length) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate product quantities
     */
    private function calculateProductQuantities($productsId, $quantities): array
    {
        $productQuantity = [];
        $sz = count($productsId);

        for ($i = 0; $i < $sz; $i++) {
            $productQuantity[$productsId[$i]]['quantity'] =
                ($productQuantity[$productsId[$i]]['quantity'] ?? 0) + $quantities[$i];
        }

        return $productQuantity;
    }

    /**
     * Calculate total price
     */
    private function calculateTotalPrice($productsId, $productQuantity): float
    {
        $products = Product::whereIn('id', $productsId)->get();
        $total_price = 0;

        foreach ($products as $product) {
            $total_price += $product->price * $productQuantity[$product->id]['quantity'];
        }

        return $total_price;
    }

    /**
     * Attach products to order
     */
    private function attachProductsToOrder($order, $productsId, $colorsId, $sizes, $quantities, $isDone): void
    {
        $sz = count($productsId);
        for ($i = 0; $i < $sz; $i++) {
            $order->products()->attach($productsId[$i], [
                "color_id" => $colorsId[$i],
                "size" => $sizes[$i],
                "quantity" => $quantities[$i],
                "is_done" => !empty($isDone[$i]) ? 1 : 0,
            ]);
        }
    }

    /**
     * Update the status of multiple orders.
     */
    public function bulkUpdateStatus(BulkUpdateStatusRequest $request)
    {
        try {
            // Validation is now handled automatically by BulkUpdateStatusRequest

            $orderIds = $request->input('order_ids');
            $newStatus = $request->input('status');

            Order::whereIn('id', $orderIds)->update(['status' => $newStatus]);

            return redirect()->back()->with('success', 'Order status updated successfully.');
        } catch (\Exception $exception) {
            Log::error('Error in OrderController@bulkUpdateStatus: ' . $exception->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating order status. Please try again later.');
        }
    }

}
