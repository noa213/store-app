<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'), // או bcrypt('password')
            'role' => 'user',
            'favs_ar' => [],
            'remember_token' => Str::random(10),
        ];
    }
}
