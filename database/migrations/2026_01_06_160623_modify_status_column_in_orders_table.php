<?php

// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// return new class extends Migration
// {
//     /**
//      * Run the migrations.
//      */
//     public function up(): void
//     {
//         Schema::table('orders', function (Blueprint $table) {
//             // 1. إضافة عمود جديد مؤقت لتخزين الـ ID
//             $table->unsignedBigInteger('new_status_id')->nullable()->after('status');
//         });

//         // 2. ملء العمود الجديد بالـ ID المناسب بناءً على اسم الحالة القديمة
//         DB::table('orders')->update([
//             'new_status_id' => DB::raw('(SELECT id FROM order_statuses WHERE order_statuses.name = orders.status LIMIT 1)')
//         ]);

//         // 3. حذف العمود القديم وتغيير اسم العمود الجديد
//         Schema::table('orders', function (Blueprint $table) {
//             $table->dropColumn('status');
//             $table->renameColumn('new_status_id', 'status_id');
//         });

//         // 4. إضافة قيد المفتاح الأجنبي
//         Schema::table('orders', function (Blueprint $table) {
//             $table->foreign('status_id')->references('id')->on('order_statuses')->restrictOnDelete(); // منع الحذف إذا كان مستخدماً
//         });
//     }

//     /**
//      * Reverse the migrations.
//      */
//     public function down(): void
//     {
//         Schema::table('orders', function (Blueprint $table) {
//             $table->dropForeign(['status_id']);
//         });

//         // إعادة العمود القديم
//         Schema::table('orders', function (Blueprint $table) {
//             $table->string('status')->nullable()->after('status_id');
//         });

//         // ملء العمود القديم مرة أخرى
//         DB::table('orders')->update([
//             'status' => DB::raw('(SELECT name FROM order_statuses WHERE order_statuses.id = orders.status_id LIMIT 1)')
//         ]);

//         // حذف العمود الجديد
//         Schema::table('orders', function (Blueprint $table) {
//             $table->dropColumn('status_id');
//         });
//     }
// };


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1️⃣ إضافة status_id الجديد
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('status_id')->nullable()->after('payment_status');
        });

        // 2️⃣ جلب كل الحالات الموجودة في orders
        $statuses = DB::table('orders')
            ->whereNotNull('status')
            ->distinct()
            ->pluck('status');

        // 3️⃣ إضافة أي status ناقص
        foreach ($statuses as $status) {
            $cleanStatus = trim(preg_replace('/\s+/', ' ', $status));

            DB::table('order_statuses')->updateOrInsert(
                ['name' => $cleanStatus],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        // 4️⃣ ربط orders بـ status_id
        DB::statement("
            UPDATE orders
            SET status_id = (
                SELECT id 
                FROM order_statuses 
                WHERE order_statuses.name = orders.status
                LIMIT 1
            )
        ");

        // 5️⃣ تأكيد إن مفيش NULL
        $nullCount = DB::table('orders')->whereNull('status_id')->count();
        if ($nullCount > 0) {
            throw new \RuntimeException(
                "Migration aborted: {$nullCount} orders without status_id"
            );
        }

        // 6️⃣ إضافة FK
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('status_id')
                  ->references('id')
                  ->on('order_statuses')
                  ->restrictOnDelete();
        });

        // 7️⃣ حذف العمود القديم
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->string('status')->nullable();
        });

        DB::statement("
            UPDATE orders
            SET status = (
                SELECT name
                FROM order_statuses
                WHERE order_statuses.id = orders.status_id
                LIMIT 1
            )
        ");

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status_id');
        });
    }
};
