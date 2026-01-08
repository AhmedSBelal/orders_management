<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'قماش', 'type' => 'raw_material'],
            ['name' => 'خيوط وإكسسوارات', 'type' => 'raw_material'],
            ['name' => 'تصنيع / خياطة', 'type' => 'labor'],
            ['name' => 'مرتبات إدارية', 'type' => 'labor'],
            ['name' => 'إعلانات', 'type' => 'marketing'],
            ['name' => 'تصوير منتجات', 'type' => 'marketing'],
            ['name' => 'شحن', 'type' => 'logistics'],
            ['name' => 'إنترنت', 'type' => 'overhead'],
            ['name' => 'كهرباء', 'type' => 'overhead'],
            ['name' => 'إيجار', 'type' => 'overhead'],
        ];

        foreach ($categories as $category) {
            ExpenseCategory::firstOrCreate($category);
        }
    }
}
