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
        Schema::table('colors', function (Blueprint $table) {
            // إضافة عمود الصورة
            // نجعله nullable لأن الألوان الموجودة حالياً ليس لها صور
            $table->string('photo')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('colors', function (Blueprint $table) {
            // حذف العمود في حالة عمل rollback
            $table->dropColumn('photo');
        });
    }
};
