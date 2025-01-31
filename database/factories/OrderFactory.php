<?php

namespace Database\Factories;

use App\Models\Color;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first();
        return [
            'user_id'      => $user->id,
            'location'     => $this->faker->address,
            'client_name'  => $this->faker->name,
            'client_phone' => $this->faker->phoneNumber,
            'deposited'    => $this->faker->numberBetween(999, 9999),
            'total_price'  => $this->faker->numberBetween(1000, 10000),
            'status'       => $this->faker->randomElement(['pending', 'delivered', 'returned', 'cancelled']),
        ];
    }

    public function withProduct() {
        return $this->afterCreating(function (Order $order) {
            $products = Product::factory()->count(3)->create();
            foreach ($products as $product) {
                $order->products()->attach($product->id, [
                    'color_id' => Color::inRandomOrder()->first()->id,
                    'quantity' => rand(1, 10),
                ]);
            }
        });
    }

}
