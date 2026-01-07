<?php

namespace App\Http\Controllers;

use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderStatusController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statuses = OrderStatus::withCount('orders')->get(); // جلب عدد الطلبات المرتبطة بكل حالة
        return view('orders.statuses.index', compact('statuses'));
    }

     /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $request->validate([
            'name' => 'required|string|max:255|unique:order_statuses,name',
        ], [
            'name.required' => 'حقل اسم الحالة مطلوب.',
            'name.unique' => 'هذه الحالة موجودة بالفعل.',
        ]);

        try {
            // إنشاء الحالة الجديدة
            OrderStatus::create(['name' => $request->name]);
            
            // إعادة التوجيه مع رسالة نجاح
            return redirect()->route('orders.statuses.index')->with('success', 'تم إضافة الحالة الجديدة بنجاح.');
        } catch (\Exception $e) {
            // تسجيل الخطأ وتوجيه المستخدم برسالة خطأ
            Log::error('Error creating status: ' . $e->getMessage());
            return redirect()->route('orders.statuses.index')->with('error', 'حدث خطأ أثناء إضافة الحالة. يرجى المحاولة مرة أخرى.');
        }
    }

    // ... update, show methods

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderStatus $status)
    {
        try {
            // التحقق من وجود طلبات مرتبطة بهذه الحالة
            if ($status->orders()->exists()) {
                return redirect()->back()->with('error', "لا يمكن حذف حالة '{$status->name}' لأنها مرتبطة بـ {$status->orders_count} طلب/طلبات.");
            }

            $status->delete();
            return redirect()->back()->with('success', 'تم حذف الحالة بنجاح.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء محاولة الحذف.');
        }
    }
}
