<?php

namespace Database\Factories;

use App\Enums\OrderStatuses;
use App\Enums\PaymentStatus;
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
            'city'         => $this->faker->city,
            'post_office'  => $this->faker->postcode,
            'deposited'    => $this->faker->numberBetween(999, 9999),
            'total_price'  => $this->faker->numberBetween(1000, 10000),
            'status'       => $this->faker->randomElement(array_column(OrderStatuses::cases(), 'value')),
            'come_from'    => $this->faker->randomElement(['فيس', 'واتس بيزنز', 'واتس عادي']),
            'payment_status' => $this->faker->randomElement(array_column(PaymentStatus::cases(), 'value')),
        ];
    }

    public function withProduct() {
        return $this->afterCreating(function (Order $order) {
            $products = Product::factory()->count(3)->create();
            foreach ($products as $product) {
                $order->products()->attach($product->id, [
                    'color_id' => Color::inRandomOrder()->first()->id,
                    'size'     =>  $this->faker->randomFloat(1, 20, 30),
                    'quantity' => rand(1, 10),
                ]);
            }
        });
    }

}
