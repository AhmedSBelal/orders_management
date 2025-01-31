<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
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
            'user_id'     => $user->id,
            'name'        => $this->faker->word(),
            'description' => $this->faker->text(),
            'price'       => $this->faker->numberBetween(1000, 10000),
            'type'        => $this->faker->randomElement(['كاب', 'شال', 'عباية']),
            'cost'        => $this->faker->numberBetween(999, 9999),
            'tailor_name' => $this->faker->name(),
        ];
    }
}
