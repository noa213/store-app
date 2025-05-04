<?php

namespace Database\Factories;

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
        return [
            'name' => $name = fake()->words(2, true),
            'info' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 10, 1000), // מחיר בין 10 ל-1000
            'category_url' => fake()->slug(),
            'img_url' => fake()->imageUrl(640, 480, 'products', true),
            'user_id' => 1,
        ];
    }
}
