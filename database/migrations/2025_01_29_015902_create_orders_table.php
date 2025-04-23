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
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('location');
            $table->string('client_name');
            $table->string('client_phone');
            $table->string('city');
            $table->string('post_office')->nullable();
            $table->float('deposited')->default(0);
            $table->float('total_price');
            $table->string('status')->default(\App\Enums\OrderStatuses::InProcessing);
            $table->string('come_from')->nullable();
            $table->string('payment_status')->default(\App\Enums\PaymentStatus::WaitPaid);
            $table->text('notes')->nullable();
            $table->float('total_price_after_discount')->default(0);
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
