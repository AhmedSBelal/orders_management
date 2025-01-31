<?php

namespace Database\Seeders;

use App\Models\Color;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::factory()
//            ->has(Color::factory()->count(5))
//            ->has(Product::factory()->count(5))
//            ->has(Order::factory()->count(5))
            ->count(5)
            ->create();

    }
}
