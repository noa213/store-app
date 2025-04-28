<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        Log::info('הגעתי לפונקציית index של המשתמשים');
        return response()->json($this->userService->getAllUsers(), 200);
    }

    public function show($id)
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user, 200);
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated(); 

        $user = $this->userService->createUser($validated);

        return response()->json($user, 201);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validated = $request->validated();

        $updatedUser = $this->userService->updateUser($user, $validated);

        return response()->json($updatedUser, 200);
    }

    public function destroy($id)
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $this->userService->deleteUser($user);

        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    public function fetchUserInfo($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user, 200);
    }

    public function decodeToken(Request $request)
    {
        $token = $request->input('token');

        if (!$token) {
            return response()->json(['message' => 'Token is required'], 400);
        }

        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

            return response()->json($decoded, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid token', 'error' => $e->getMessage()], 400);
        }
    }

    public function changeRole(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validated = $request->validate([
            'role' => 'required|string',
        ]);

        $user->role = $validated['role'];
        $user->save();

        return response()->json(['message' => 'Role updated successfully', 'user' => $user], 200);
    }

    public function getRoleAdmin()
    {
        $users = User::where('role', 'admin')->get();
        return response()->json($users, 200);
    }

    public function getRoleUser()
    {
        $users = User::where('role', 'user')->get();
        return response()->json($users, 200);
    }

    public function getRoleAuthUser()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'No authenticated user'], 401);
        }

        return response()->json(['role' => $user->role], 200);
    }
}
