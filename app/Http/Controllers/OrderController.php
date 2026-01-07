<?php

// namespace App\Http\Controllers;

// use App\Models\Color;
// use App\Models\Order;
// use App\Models\Product;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Log;
// use App\Http\Requests\Orders\OrderCreateRequest;
// use App\Http\Requests\Orders\OrderUpdateRequest;
// use App\Http\Requests\Orders\OrdersFilterRequest;
// use App\Http\Requests\Orders\BulkUpdateStatusRequest;

// class OrderController extends Controller
// {
//     /**
//      * Display a listing of the resource.
//      */
//     public function index(OrdersFilterRequest $request)
//     {
//         try {
//             $title = 'Dashboard - Orders';

//             // This now receives a QueryBuilder instance from the model
//             $ordersQuery = Order::ordersByFilter($request);

//             // Get the number of items per page from the request, defaulting to 10
//             $perPage = $request->get('per_page', 10);

//             // Execute the query with pagination and append all query parameters to the pagination links
//             $orders = $ordersQuery->paginate($perPage)->appends($request->query());

//             return view('orders.index', compact('orders', 'title'));
//         } catch (\Exception $exception) {
//             Log::error('Error in OrderController@index: ' . $exception->getMessage());
//             return redirect()->back()->with('error', 'An error occurred while fetching orders. Please try again later.');
//         }
//     }

//     /**
//      * Show the form for creating a new resource.
//      */
//     public function create()
//     {
//         try {
//             $title = 'Create - Order';
//             $products = Product::all();
//             $colors = Color::all();
//             return view('orders.create-order', compact('title', 'products', 'colors'));
//         } catch (\Exception $exception) {
//             Log::error('Error in OrderController@create: ' . $exception->getMessage());
//             return redirect()->back()->with('error', 'An error occurred while loading the create form. Please try again later.');
//         }
//     }

//     /**
//      * Store a newly created resource in storage.OrderCreateRequest
//      */
//     public function store(OrderCreateRequest $request)
//     {
//         try {
//             $data = $request->validated();

//             if (!$this->validateArrays($data)) {
//                 return redirect()->back()->with('error', 'Invalid product data. Please check your input.');
//             }

//             $productsId = $data['products'];
//             $colorsId = $data['colors'];
//             $quantities = $data['quantities'];
//             $sizes = $data['sizes'];
//             $isDone = $data['is_done'] ?? [];

//             $productQuantity = $this->calculateProductQuantities($productsId, $quantities);
//             $total_price = $this->calculateTotalPrice($productsId, $productQuantity);

//             // هذا الجزء الآن صحيح لأن $data['status_id'] سيأتي من الفورم المعدل
//             $order = Order::create([
//                 'user_id' => auth()->id(),
//                 'location' => $data['location'],
//                 'client_name' => $data['client_name'],
//                 'client_phone' => $data['client_phone'],
//                 'city' => $data['city'],
//                 'deposited' => $data['deposited'] ?? 0,
//                 'total_price' => $total_price,
//                 'status_id' => $data['status_id'], // <-- هذا صحيح الآن
//                 'come_from' => $data['come_from'],
//                 'total_price_after_discount' => $data['total_price_after_discount'] ?? 0,
//                 'notes' => $data['notes'],
//             ]);

//             $this->attachProductsToOrder($order, $productsId, $colorsId, $sizes, $quantities, $isDone);

//             return redirect()->route('orders.create')->with('success', 'Order created successfully.');
//         } catch (\Exception $exception) {
//             Log::error('Error in OrderController@store: ' . $exception->getMessage());
//             return redirect()->back()->with('error', 'An error occurred while creating the order. Please try again later.');
//         }
//     }

//     /**
//      * Show the form for editing the specified resource.
//      */
//     public function edit(Order $order)
//     {
//         try {
//             $title = 'Edit - Order';
//             $colors = Color::all();
//             $productsData = Product::all();
//             $products = $order->products()->get();

//             $products = $products->map(function ($product) {
//                 return [
//                     'id' => $product->id,
//                     'name' => $product->name,
//                     'price' => $product->price,
//                     'color_id' => $product->pivot->color_id,
//                     'size' => $product->pivot->size,
//                     'quantity' => $product->pivot->quantity,
//                     'is_done' => $product->pivot->is_done
//                 ];
//             });

//             return view('orders.edit-order', compact('title', 'order', 'colors', 'productsData', 'products'));
//         } catch (\Exception $exception) {
//             Log::error('Error in OrderController@edit: ' . $exception->getMessage());
//             return redirect()->back()->with('error', 'An error occurred while loading the edit form. Please try again later.');
//         }
//     }

//     /**
//      * Update the specified resource in storage.
//      */
//     public function update(OrderUpdateRequest $request, Order $order)
//     {
//         try {
//             $data = $request->validated();

//             if (!$this->validateArrays($data)) {
//                 return redirect()->back()->with('error', 'Invalid product data. Please check your input.');
//             }

//             $productsId = $data['products'];
//             $colorsId = $data['colors'];
//             $quantities = $data['quantities'];
//             $sizes = $data['sizes'];
//             $isDone = $data['is_done'] ?? [];

//             $productQuantity = $this->calculateProductQuantities($productsId, $quantities);
//             $total_price = $this->calculateTotalPrice($productsId, $productQuantity);

//             $order->update([
//                 'location' => $data['location'],
//                 'client_name' => $data['client_name'],
//                 'client_phone' => $data['client_phone'],
//                 'city' => $data['city'],
//                 'deposited' => $data['deposited'] ?? 0,
//                 'total_price' => $total_price,
//                 'status_id' => $data['status_id'],
//                 'come_from' => $data['come_from'],
//                 'total_price_after_discount' => $data['total_price_after_discount'] ?? 0,
//                 'notes' => $data['notes'] ?? null,
//             ]);

//             $order->products()->detach();
//             $this->attachProductsToOrder($order, $productsId, $colorsId, $sizes, $quantities, $isDone);

//             return redirect()->back()->with('success', 'Order updated successfully.');
//         } catch (\Exception $exception) {
//             Log::error('Error in OrderController@update: ' . $exception->getMessage());
//             return redirect()->back()->with('error', 'An error occurred while updating the order. Please try again later.');
//         }
//     }

//     /**
//      * Remove the specified resource from storage.
//      */
//     public function destroy(Order $order)
//     {
//         try {
//             if ($order->delete()) {
//                 return redirect()->back()->with('success', 'Order deleted successfully.');
//             }
//             return redirect()->back()->with('error', 'Failed to delete order.');
//         } catch (\Exception $exception) {
//             Log::error('Error in OrderController@destroy: ' . $exception->getMessage());
//             return redirect()->back()->with('error', 'An error occurred while deleting the order. Please try again later.');
//         }
//     }

//     /**
//      * Validate that all arrays have the same length
//      */
//     private function validateArrays($data): bool
//     {
//         $arrays = ['products', 'colors', 'quantities', 'sizes'];
//         $length = count($data['products']);

//         foreach ($arrays as $array) {
//             if (!isset($data[$array]) || count($data[$array]) !== $length) {
//                 return false;
//             }
//         }

//         return true;
//     }

//     /**
//      * Calculate product quantities
//      */
//     private function calculateProductQuantities($productsId, $quantities): array
//     {
//         $productQuantity = [];
//         $sz = count($productsId);

//         for ($i = 0; $i < $sz; $i++) {
//             $productQuantity[$productsId[$i]]['quantity'] =
//                 ($productQuantity[$productsId[$i]]['quantity'] ?? 0) + $quantities[$i];
//         }

//         return $productQuantity;
//     }

//     /**
//      * Calculate total price
//      */
//     private function calculateTotalPrice($productsId, $productQuantity): float
//     {
//         $products = Product::whereIn('id', $productsId)->get();
//         $total_price = 0;

//         foreach ($products as $product) {
//             $total_price += $product->price * $productQuantity[$product->id]['quantity'];
//         }

//         return $total_price;
//     }

//     /**
//      * Attach products to order
//      */
//     private function attachProductsToOrder($order, $productsId, $colorsId, $sizes, $quantities, $isDone): void
//     {
//         $sz = count($productsId);
//         for ($i = 0; $i < $sz; $i++) {
//             $order->products()->attach($productsId[$i], [
//                 "color_id" => $colorsId[$i],
//                 "size" => $sizes[$i],
//                 "quantity" => $quantities[$i],
//                 "is_done" => !empty($isDone[$i]) ? 1 : 0,
//             ]);
//         }
//     }

//     /**
//      * Update the status of multiple orders.
//      */
//     public function bulkUpdateStatus(BulkUpdateStatusRequest $request)
//     {
//         try {
//             $orderIds = $request->input('order_ids');
//             $newStatusId = $request->input('status'); // <-- افترض أن الفورم سيرسل 'status_id'
//             Order::whereIn('id', $orderIds)->update(['status_id' => $newStatusId]);

//             return redirect()->back()->with('success', 'Order status updated successfully.');
//         } catch (\Exception $exception) {
//             Log::error('Error in OrderController@bulkUpdateStatus: ' . $exception->getMessage());
//             return redirect()->back()->with('error', 'An error occurred while updating order status. Please try again later.');
//         }
//     }

//     public function search(Request $request)
//     {
//         try {
//             $clientName = $request->input('client_name', '');
//             $clientPhone = $request->input('client_phone', '');
//             $location = $request->input('location', '');

//             // بناء الاستعلام
//             $query = Order::with(['products']); // نحمل المنتجات فقط

//             if (!empty($clientPhone)) {
//                 $query->where('client_phone', 'LIKE', '%' . $clientPhone . '%');
//             }
//             if (!empty($clientName)) {
//                 $query->where('client_name', 'LIKE', '%' . $clientName . '%');
//             }
//             if (!empty($location)) {
//                 $query->where('location', 'LIKE', '%' . $location . '%');
//             }

//             $orders = $query->orderBy('created_at', 'DESC')->limit(10)->get();

//             // نجمع كل الـ color_ids الموجودة في النتائج لجلب بيانات الألوان دفعة واحدة
//             $colorIds = $orders->pluck('products')->flatten()->pluck('pivot.color_id')->filter()->unique();
//             $colors = Color::whereIn('id', $colorIds)->get()->keyBy('id');

//             // تنسيق النتائج
//             $results = $orders->map(function ($order) use ($clientName, $clientPhone, $location, $colors) {
//                 $score = 0;

//                 // حساب درجة التشابه (كما في الكود السابق)
//                 if (!empty($clientPhone)) {
//                     if ($order->client_phone === $clientPhone)
//                         $score += 100;
//                     elseif (strpos($order->client_phone, $clientPhone) !== false)
//                         $score += 80;
//                 }
//                 if (!empty($clientName)) {
//                     if (strtolower($order->client_name) === strtolower($clientName))
//                         $score += 50;
//                     elseif (strpos(strtolower($order->client_name), strtolower($clientName)) !== false)
//                         $score += 30;
//                 }
//                 if (!empty($location) && strpos(strtolower($order->location), strtolower($location)) !== false) {
//                     $score += 20;
//                 }

//                 return [
//                     'id' => $order->id,
//                     'client_name' => $order->client_name,
//                     'client_phone' => $order->client_phone,
//                     'location' => $order->location,
//                     'city' => $order->city,
//                     'status' => $order->status->name ?? 'N/A',
//                     'deposited' => $order->deposited,
//                     'come_from' => $order->come_from,
//                     'total_price_after_discount' => $order->total_price_after_discount,
//                     'notes' => $order->notes,
//                     'created_at' => $order->created_at->format('Y-m-d'),
//                     'similarity_score' => $score,
//                     'products' => $order->products->map(function ($product) use ($colors) {
//                         // نحصل على اسم اللون من المجموعة التي جلبناها مسبقاً
//                         $colorName = 'N/A';
//                         if ($product->pivot->color_id && isset($colors[$product->pivot->color_id])) {
//                             $colorName = $colors[$product->pivot->color_id]->name;
//                         }

//                         return [
//                             'product_id' => $product->id,
//                             'product_name' => $product->name,
//                             'color_id' => $product->pivot->color_id,
//                             'color_name' => $colorName,
//                             'quantity' => $product->pivot->quantity,
//                             'size' => $product->pivot->size,
//                             'is_done' => $product->pivot->is_done,
//                         ];
//                     }),
//                     'edit_url' => route('orders.edit', $order->id)
//                 ];
//             })->filter(function ($order) {
//                 return $order['similarity_score'] > 0;
//             })->sortByDesc('similarity_score')->values();

//             return response()->json([
//                 'success' => true,
//                 'data' => $results
//             ]);

//         } catch (\Exception $e) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Search failed: ' . $e->getMessage()
//             ], 500);
//         }
//     }

// }


namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Order;
use App\Models\OrderImage;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\DB; 
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
            $ordersQuery = Order::ordersByFilter($request)->with(['images', 'status']); // <-- تحميل الصور والحالة

            // Get the number of items per page from the request, defaulting to 10
            $perPage = $request->get('per_page', 10);

            // Execute the query with pagination and append all query parameters to the pagination links
            $orders = $ordersQuery->paginate($perPage)->appends($request->query());

            return view('orders.index', compact('orders', 'title'));
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
            return view('orders.create-order', compact('title', 'products', 'colors'));
        } catch (\Exception $exception) {
            Log::error('Error in OrderController@create: ' . $exception->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the create form. Please try again later.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderCreateRequest $request)
    {
        try {
            DB::beginTransaction();
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
                'deposited' => $data['deposited'] ?? 0,
                'total_price' => $total_price,
                'status_id' => $data['status_id'],
                'come_from' => $data['come_from'],
                'total_price_after_discount' => $data['total_price_after_discount'] ?? 0,
                'notes' => $data['notes'],
            ]);

            // التحقق من وجود صور ورفعها
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $photoPath = $photo->store('orders', 'public');
                    // إنشاء سجل جديد لكل صورة في جدول order_images
                    $order->images()->create(['photo_path' => $photoPath]);
                }
            }

            $this->attachProductsToOrder($order, $productsId, $colorsId, $sizes, $quantities, $isDone);

            DB::commit();
            return redirect()->route('orders.create')->with('success', 'Order created successfully.');
        } catch (\Exception $exception) {
            DB::rollBack();
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
            
            // تحميل الصور مع الطلب
            $order->load('images');
            
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

            return view('orders.edit-order', compact('title', 'order', 'colors', 'productsData', 'products'));
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
        try {
            DB::beginTransaction();
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
                'deposited' => $data['deposited'] ?? 0,
                'total_price' => $total_price,
                'status_id' => $data['status_id'],
                'come_from' => $data['come_from'],
                'total_price_after_discount' => $data['total_price_after_discount'] ?? 0,
                'notes' => $data['notes'] ?? null,
            ]);

            // حذف الصور المحددة للحذف
            if ($request->has('delete_images')) {
                $imagesToDelete = $request->input('delete_images');
                foreach ($imagesToDelete as $imageId) {
                    $image = $order->images()->find($imageId);
                    if ($image) {
                        // حذف الملف من التخزين
                        Storage::disk('public')->delete($image->photo_path);
                        // حذف السجل من قاعدة البيانات
                        $image->delete();
                    }
                }
            }

            // إضافة الصور الجديدة
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $photoPath = $photo->store('orders', 'public');
                    $order->images()->create(['photo_path' => $photoPath]);
                }
            }

            $order->products()->detach();
            $this->attachProductsToOrder($order, $productsId, $colorsId, $sizes, $quantities, $isDone);

            DB::commit();
            return redirect()->back()->with('success', 'Order updated successfully.');
        } catch (\Exception $exception) {
            DB::rollBack();
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
            // حذف الصور المرتبطة بالطلب قبل حذف الطلب نفسه
            foreach ($order->images as $image) {
                Storage::disk('public')->delete($image->photo_path);
            }

            $order->delete();
            return redirect()->back()->with('success', 'Order deleted successfully.');
        } catch (\Exception $exception) {
            Log::error('Error in OrderController@destroy: ' . $exception->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the order. Please try again later.');
        }
    }

    /**
     * Remove a single image from an order.
     */
    public function deleteImage(Order $order, OrderImage $image)
    {
        // التأكد من أن الصورة تخص هذا الطلب
        if ($image->order_id !== $order->id) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // حذف الملف من التخزين
            Storage::disk('public')->delete($image->photo_path);
            // حذف السجل من قاعدة البيانات
            $image->delete();

            return response()->json(['success' => true, 'message' => 'تم حذف الصورة بنجاح.']);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'message' => 'فشل حذف الصورة.'], 500);
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
            $orderIds = $request->input('order_ids');
            $newStatusId = $request->input('status');
            Order::whereIn('id', $orderIds)->update(['status_id' => $newStatusId]);

            return redirect()->back()->with('success', 'Order status updated successfully.');
        } catch (\Exception $exception) {
            Log::error('Error in OrderController@bulkUpdateStatus: ' . $exception->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating order status. Please try again later.');
        }
    }

    public function search(Request $request)
    {
        try {
            $clientName = $request->input('client_name', '');
            $clientPhone = $request->input('client_phone', '');
            $location = $request->input('location', '');

            // بناء الاستعلام
            $query = Order::with(['products', 'images']); // <-- تحميل الصور أيضًا

            if (!empty($clientPhone)) {
                $query->where('client_phone', 'LIKE', '%' . $clientPhone . '%');
            }
            if (!empty($clientName)) {
                $query->where('client_name', 'LIKE', '%' . $clientName . '%');
            }
            if (!empty($location)) {
                $query->where('location', 'LIKE', '%' . $location . '%');
            }

            $orders = $query->orderBy('created_at', 'DESC')->limit(10)->get();

            // نجمع كل الـ color_ids الموجودة في النتائج لجلب بيانات الألوان دفعة واحدة
            $colorIds = $orders->pluck('products')->flatten()->pluck('pivot.color_id')->filter()->unique();
            $colors = Color::whereIn('id', $colorIds)->get()->keyBy('id');

            // تنسيق النتائج
            $results = $orders->map(function ($order) use ($clientName, $clientPhone, $location, $colors) {
                $score = 0;

                // حساب درجة التشابه
                if (!empty($clientPhone)) {
                    if ($order->client_phone === $clientPhone)
                        $score += 100;
                    elseif (strpos($order->client_phone, $clientPhone) !== false)
                        $score += 80;
                }
                if (!empty($clientName)) {
                    if (strtolower($order->client_name) === strtolower($clientName))
                        $score += 50;
                    elseif (strpos(strtolower($order->client_name), strtolower($clientName)) !== false)
                        $score += 30;
                }
                if (!empty($location) && strpos(strtolower($order->location), strtolower($location)) !== false) {
                    $score += 20;
                }

                return [
                    'id' => $order->id,
                    'client_name' => $order->client_name,
                    'client_phone' => $order->client_phone,
                    'location' => $order->location,
                    'city' => $order->city,
                    'status' => $order->status->name ?? 'N/A',
                    'deposited' => $order->deposited,
                    'come_from' => $order->come_from,
                    'total_price_after_discount' => $order->total_price_after_discount,
                    'notes' => $order->notes,
                    'created_at' => $order->created_at->format('Y-m-d'),
                    'similarity_score' => $score,
                    'products' => $order->products->map(function ($product) use ($colors) {
                        $colorName = 'N/A';
                        if ($product->pivot->color_id && isset($colors[$product->pivot->color_id])) {
                            $colorName = $colors[$product->pivot->color_id]->name;
                        }

                        return [
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'color_id' => $product->pivot->color_id,
                            'color_name' => $colorName,
                            'quantity' => $product->pivot->quantity,
                            'size' => $product->pivot->size,
                            'is_done' => $product->pivot->is_done,
                        ];
                    }),
                    'edit_url' => route('orders.edit', $order->id)
                ];
            })->filter(function ($order) {
                return $order['similarity_score'] > 0;
            })->sortByDesc('similarity_score')->values();

            return response()->json([
                'success' => true,
                'data' => $results
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed: ' . $e->getMessage()
            ], 500);
        }
    }
}