<?php

namespace Database\Seeders;

use App\Enums\OrderStatuses;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحصول على جميع قيم الـ Enum
        $statuses = OrderStatuses::cases();

        foreach ($statuses as $status) {
            // إنشاء سجل جديد لكل حالة
            \App\Models\OrderStatus::firstOrCreate(['name' => $status->value]);
        }
    }
}
