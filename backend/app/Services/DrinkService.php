<?php

namespace App\Services;

use App\Models\Drink;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DrinkService
{
    public function create(array $data)
    {
        // $user = Auth::user();

        // if (!$user) {
        //     return response()->json([
        //         'msg' => 'User not authenticated',
        //     ], Response::HTTP_UNAUTHORIZED);
        // }

        $drink = Drink::create([
            'name' => $data['name'],
            'ml' => $data['ml'],
            'price' => $data['price'],
            'user_id' => /*$user->id*/ 2, 
        ]);

        return response()->json([
            'msg' => 'Drink saved successfully in the system.',
            'drink' => $drink,
        ], Response::HTTP_CREATED);
    }

    public function fetchAll()
    {
        $drinks = Drink::all();

        $enhancedDrinks = $drinks->map(function ($drink) {
            $user = User::find($drink->user_id);

            return [
                'id' => $drink->id,
                'name' => $drink->name,
                'ml' => $drink->ml,
                'price' => $drink->price,
                'created_at' => $drink->created_at ? $drink->created_at->format('d/m/Y H:i') : null,
                'updated_at' => $drink->updated_at ? $drink->updated_at->format('d/m/Y H:i') : null,
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at ? $user->created_at->format('d/m/Y H:i') : null,
                    'updated_at' => $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : null,
                ] : null,
            ];
        });

        return $enhancedDrinks;
    }

    public function fetchById($id)
    {
        $drink = Drink::find($id);

        if (!$drink) {
            return null;
        }

        $user = User::find($drink->user_id);

        return [
            'id' => $drink->id,
            'name' => $drink->name,
            'ml' => $drink->ml,
            'price' => $drink->price,
            'created_at' => $drink->created_at ? $drink->created_at->format('d/m/Y H:i') : null,
            'updated_at' => $drink->updated_at ? $drink->updated_at->format('d/m/Y H:i') : null,
            'user' => $user ? [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at ? $user->created_at->format('d/m/Y H:i') : null,
                'updated_at' => $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : null,
            ] : null,
        ];
    }

    public function getByUserId($userId)
    {
        $drinks = Drink::where('user_id', $userId)->get();

        if ($drinks->isEmpty()) {
            return null;
        }

        return $drinks->map(function ($drink) {
            return [
                'id' => $drink->id,
                'name' => $drink->name,
                'ml' => $drink->ml,
                'price' => $drink->price,
                'user_id' => $drink->user_id,
                'created_at' => $drink->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $drink->updated_at->format('Y-m-d H:i:s'),
            ];
        });
    }
    public function update($id, array $data)
    {
        $drink = Drink::find($id);

        if (!$drink) {
            return null;
        }

        $drink->update($data);
        return $drink;
    }

    public function delete($id): bool
    {
        $drink = Drink::find($id);

        if (!$drink) {
            return false;
        }

        $drink->delete();

        return true;
    }
}
