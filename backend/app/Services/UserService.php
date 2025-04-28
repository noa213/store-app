<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    // יצירת משתמש חדש
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

    // עדכון פרטי משתמש
    public function updateUser(User $user, array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);
        return $user;
    }

    // חיפוש משתמש לפי מזהה
    public function getUserById($id)
    {
        return User::find($id);
    }

    // מחיקת משתמש
    public function deleteUser(User $user)
    {
        $user->delete();
    }

    // קבלת כל המשתמשים
    public function getAllUsers()
    {
        return User::all();
    }
}
