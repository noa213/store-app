<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'superadmin User',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'superadmin',
        ]);

        if ($admin) {
            $admin->assignRole('superadmin');
        }

        User::factory()->count(10)->create();
    }
}

