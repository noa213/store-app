<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Drink>
 */
class DrinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'ml' => $this->faker->randomElement(['250ml', '330ml', '500ml', '1L']),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'price' => $this->faker->randomFloat(2, 1, 20), 
        ];
    }
}
