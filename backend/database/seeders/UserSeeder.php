<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'superadmin User',
            'email' => 'superadmin@gmail.com',
            'role' => 'superadmin',
        ]);

        if ($admin) {
            $admin->assignRole('superadmin');
        }

        User::factory()->count(10)->create();
    }
}

