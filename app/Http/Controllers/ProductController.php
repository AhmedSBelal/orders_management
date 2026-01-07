<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Products\ProductCreateRequest;
use App\Http\Requests\Products\ProductsFilterRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductsFilterRequest $request)
    {
        $title = "Products";
        // Get the number of items per page from the request, defaulting to 10
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
            // نستخدم معاملة قاعدة البيانات لضمان أن كل شيء يتم أو لا شيء
            DB::beginTransaction();

            $data = $request->validated();
            $data['user_id'] = Auth::user()->id;

            // إنشاء المنتج أولاً
            $product = Product::create($data);

            // التحقق من وجود صور ورفعها
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $photoPath = $photo->store('products', 'public');
                    // إنشاء سجل جديد لكل صورة في جدول product_images
                    $product->images()->create(['photo_path' => $photoPath]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'تم إضافة المنتج وصوره بنجاح.');
        } catch (\Exception $exception) {
            DB::rollBack(); // التراجع عن كل التغييرات في حالة حدوث خطأ
            return redirect()->back()->with('error', $exception->getMessage())->withInput();
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
        // تحميل الصور مع المنتج
        $product->load('images');
        $title = 'Edit Product';
        return view('products.edit', compact('product', 'title'));
    }


    /**
     * Update the specified resource in storage.
     */
     public function update(ProductCreateRequest $request, Product $product)
    {
        try {
            DB::beginTransaction();

            $product->update($request->validated());

            // إضافة الصور الجديدة
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
            // حذف المنتج سيقوم تلقائياً بحذف الصور المرتبطة به
            // بفضل onDelete('cascade') في الـ migration
            // ولكن يجب حذف الملفات الفعلية من التخزين
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
        // التأكد من أن الصورة تخص هذا المنتج
        if ($image->product_id !== $product->id) {
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
}
