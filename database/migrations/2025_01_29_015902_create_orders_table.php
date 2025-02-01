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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('location');
            $table->string('client_name');
            $table->string('client_phone')->nullable();
            $table->float('deposited');
            $table->float('total_price');
            $table->enum('status', ['قيد التنفيذ', 'وصل', 'مرتجع', 'تم الالغاء'])->default('قيد التنفيذ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
