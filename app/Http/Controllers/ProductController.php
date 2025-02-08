<?php

namespace App\Http\Controllers;

use App\Http\Requests\Products\ProductCreateRequest;
use App\Http\Requests\Products\ProductsFilterRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductsFilterRequest $request)
    {
        $title = "Products";
        $products = Product::productsByFilter($request);
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
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            Product::create($data);
            return redirect()->back()->with('success', 'Product created successfully');
        } catch (\Exception $exception) {
            return redirect()->back()->with('success', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $title = 'Edit Product';
        return view('products.edit', compact('title', 'product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductCreateRequest $request, Product $product)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            $product->update($data);
            return redirect()->back()->with('success', 'Product updated successfully');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->back()->with('success', 'Product deleted successfully');
    }
}
