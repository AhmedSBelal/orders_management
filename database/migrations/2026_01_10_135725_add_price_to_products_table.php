<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_product', function (Blueprint $table) {
            // إضافة الحقل كـ nullable أولاً
            $table->decimal('price', 10, 2)
                  ->nullable()
                  ->after('product_id')
                  ->comment('السعر المستخدم وقت الشراء (قطاعي/جمله)');
        });

        // تحديث البيانات الحالية
        $this->populateExistingPrices();
        
        // بعد ملء البيانات، نجعل الحقل مطلوباً
        Schema::table('order_product', function (Blueprint $table) {
            $table->decimal('price', 10, 2)
                  ->nullable(false)
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_product', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }

    /**
     * تعبئة الأسعار للمنتجات الموجودة
     */
    private function populateExistingPrices(): void
    {
        // أولاً: نحتاج للتحقق إذا كان جدول order_product يحتوي على بيانات
        $hasData = DB::table('order_product')->exists();
        
        if (!$hasData) {
            return; // لا توجد بيانات، لا داعي للمعالجة
        }

        // الطريقة الآمنة: تحديث كل سطر على حدة
        // استعلام لجلب جميع صفوف order_product مع معلومات الطلب والمنتج
        $orderProducts = DB::table('order_product')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->select(
                'order_product.id as pivot_id',
                'orders.is_wholesale',
                'products.price as retail_price',
                'products.wholesale_price',
                DB::raw('COALESCE(products.wholesale_price, products.price) as final_wholesale_price')
            )
            ->get();

        foreach ($orderProducts as $item) {
            // تحديد السعر المناسب بناءً على نوع الطلب
            $price = $item->is_wholesale 
                ? $item->final_wholesale_price 
                : $item->retail_price;

            DB::table('order_product')
                ->where('id', $item->pivot_id)
                ->update(['price' => $price]);
        }

    }

};
