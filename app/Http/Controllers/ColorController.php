<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Colors\ColorCreateRequest;
use App\Http\Requests\Colors\ColorFilterRequest;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ColorFilterRequest $request)
    {
        $title = 'colores';
        $colorsQuery = Color::colorsByFilter($request);
        $colors = $colorsQuery->paginate(10)->appends($request->query());
        return view('colors.index', compact('colors', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Add New colore';
        return view('colors.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ColorCreateRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::user()->id;

            // معالجة رفع الصورة
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('colors', 'public');
                $data['photo'] = $photoPath;
            }

            Color::create($data);
            return redirect()->back()->with('success', 'تم إضافة اللون بنجاح.');
        } catch (\Exception $exception) {
            Log::error('Error creating color: ' . $exception->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة اللون. الرجاء المحاولة مرة أخرى.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Color $color)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Color $color)
    {
        $title = 'Edit Color';
        return view('colors.edit', compact('color', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ColorCreateRequest $request, Color $color)
    {
        try {
            $data = $request->validated();

            // التحقق من وجود صورة جديدة
            if ($request->hasFile('photo')) {
                // حذف الصورة القديمة إن وجدت
                if ($color->photo) {
                    Storage::disk('public')->delete($color->photo);
                }
                // رفع الصورة الجديدة
                $photoPath = $request->file('photo')->store('colors', 'public');
                $data['photo'] = $photoPath;
            }

            // التحقق إذا كان المستخدم يريد حذف الصورة بدون استبدالها
            if ($request->has('delete_photo')) {
                if ($color->photo) {
                    Storage::disk('public')->delete($color->photo);
                }
                $data['photo'] = null; // حذف مسار الصورة من قاعدة البيانات
            }

            $color->update($data);
            return redirect()->back()->with('success', 'تم تحديث اللون بنجاح.');
        } catch (\Exception $exception) {
            Log::error('Error updating color: ' . $exception->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث اللون. الرجاء المحاولة مرة أخرى.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
     public function destroy(Color $color)
    {
        try {
            // حذف الصورة المرتبطة باللون قبل حذف اللون نفسه
            if ($color->photo) {
                Storage::disk('public')->delete($color->photo);
            }

            if ($color->delete()) {
                return redirect()->back()->with('success', 'Color deleted successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to delete color.');
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
