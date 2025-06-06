<?php

namespace Database\Seeders;

use App\Models\Drink;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {   
        $this->call(RoleSeeder::class);
        User::factory(10)->create();
        Drink::factory()->count(10)->create();

        $this->call(UserSeeder::class);
    
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
    }
}    
