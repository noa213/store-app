<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function createUser(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'user',
            'favs_ar' => $data['favs_ar'] ?? []
        ]);

        return $user;
    }

    public function updateUser(User $user, array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);
        return $user;
    }

    public function getUserById($id)
    {
        return User::find($id);
    }

    public function deleteUser(User $user)
    {
        $user->delete();
    }

    public function getAllUsers()
    {
        return User::all();
    }

    public function getUserRole(User $user)
    {
        return $user->role();
    }

    public function changeRole(User $user)
    {
        if( $user->role == 'admin')
        {
            $user->role = 'user';
        }
        if( $user->role == 'user')
        {
            $user->role = 'admin';
        }
    }      
}
