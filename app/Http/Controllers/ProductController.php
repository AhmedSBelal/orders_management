<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Products\ProductCreateRequest;
use App\Http\Requests\Products\ProductUpdateRequest;
use App\Http\Requests\Products\ProductsFilterRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductsFilterRequest $request)
    {
        $title = "Products";
        $perPage = $request->get('per_page', 10);
        $productsQuery = Product::productsByFilter($request);
        $products = $productsQuery->paginate($perPage)->appends($request->query());

        return view('products.index', compact('products', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Create Product";
        return view('products.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductCreateRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $data['user_id'] = Auth::user()->id;

            $product = Product::create($data);

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $photoPath = $photo->store('products', 'public');
                    $product->images()->create(['photo_path' => $photoPath]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'تم إضافة المنتج بنجاح.');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ: ' . $exception->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('images');
        $title = 'Product Details';
        return view('products.show', compact('product', 'title'));
    }

    /**
     * Get product details for AJAX request
     */
    public function getDetails(Product $product)
    {
        try {
            $product->load('images', 'user');
            
            $profitMarginRetail = $product->price - $product->cost;
            $profitMarginWholesale = $product->wholesale_price ? ($product->wholesale_price - $product->cost) : 0;
            $discountPercentage = 0;
            
            if ($product->wholesale_price && $product->price > 0) {
                $discountPercentage = (($product->price - $product->wholesale_price) / $product->price) * 100;
            }
            
            $html = view('products.partials.details-modal-content', compact(
                'product', 
                'profitMarginRetail', 
                'profitMarginWholesale',
                'discountPercentage'
            ))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحميل البيانات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load('images');
        $title = 'Edit Product';
        return view('products.edit', compact('product', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        try {
            DB::beginTransaction();

            $product->update($request->validated());

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $photoPath = $photo->store('products', 'public');
                    $product->images()->create(['photo_path' => $photoPath]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'تم تحديث المنتج بنجاح.');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->photo_path);
            }

            $product->delete();
            return redirect()->back()->with('success', 'تم حذف المنتج وصوره بنجاح.');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Remove a single image from a product.
     */
    public function deleteImage(Product $product, ProductImage $image)
    {
        try {
            if ($image->product_id !== $product->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'هذه الصورة لا تنتمي لهذا المنتج'
                ], 403);
            }
            
            Storage::disk('public')->delete($image->photo_path);
            $image->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'تم حذف الصورة بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الصورة: ' . $e->getMessage()
            ], 500);
        }
    }
}