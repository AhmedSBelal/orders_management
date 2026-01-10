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
        Schema::table('products', function (Blueprint $table) {
            // إضافة سعر الجمله
            $table->decimal('wholesale_price', 10, 2)
                  ->nullable()
                  ->after('price')
                  ->comment('سعر الجمله');
        });

        // تأخير هذا الجزء حتى نتمكن من نسخ البيانات أولاً
        Schema::table('products', function (Blueprint $table) {
            // 1. نسخ سعر price الحالي إلى wholesale_price للمنتجات الموجودة
            DB::statement('UPDATE products SET wholesale_price = price WHERE wholesale_price IS NULL');
            
            // 2. جعل الحقل مطلوباً بعد نسخ البيانات
            $table->decimal('wholesale_price', 10, 2)
                  ->nullable(false)
                  ->change()
                  ->comment('سعر الجمله - مطلوب');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('wholesale_price');
        });
    }
};
