<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use OpenApi\Annotations as OA;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Get all users",
     *     tags={"Users"},
     *     @OA\Response(response=200, description="List of all users")
     * )
     */
    public function index()
    {
        return response()->json($this->userService->getAllUsers(), 200);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Get user by ID",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id", in="path", required=true, @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="User found"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function show($id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) return response()->json(['message' => 'User not found'], 404);
        return response()->json($user, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     summary="Create a new user",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="John"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="password", type="string", example="secret123"),
     *             @OA\Property(property="role", type="string", example="user"),
     *             @OA\Property(property="favs_ar", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(response=201, description="User created")
     * )
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated(); 
        $user = $this->userService->createUser($validated);
        return response()->json($user, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Update a user",
     *     tags={"Users"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="role", type="string"),
     *             @OA\Property(property="favs_ar", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(response=200, description="User updated"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) return response()->json(['message' => 'User not found'], 404);
        $validated = $request->validated();
        $updatedUser = $this->userService->updateUser($user, $validated);
        return response()->json($updatedUser, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Delete a user",
     *     tags={"Users"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="User deleted"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function destroy($id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) return response()->json(['message' => 'User not found'], 404);
        $this->userService->deleteUser($user);
        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/users/fetch/{id}",
     *     summary="Fetch user info",
     *     tags={"Users"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="User info returned"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function fetchUserInfo($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'User not found'], 404);
        return response()->json($user, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/users/decode-token",
     *     summary="Decode JWT token",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"token"},
     *             @OA\Property(property="token", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Decoded token"),
     *     @OA\Response(response=400, description="Invalid token")
     * )
     */
    public function decodeToken(Request $request)
    {
        $token = $request->input('token');
        if (!$token) return response()->json(['message' => 'Token is required'], 400);
        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            return response()->json($decoded, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid token', 'error' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/users/change-role/{id}",
     *     summary="Change user role",
     *     tags={"Users"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"role"},
     *             @OA\Property(property="role", type="string", example="admin")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Role updated"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function changeRole(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'User not found'], 404);
        $validated = $request->validate(['role' => 'required|string']);
        $user->role = $validated['role'];
        $user->save();
        return response()->json(['message' => 'Role updated successfully', 'user' => $user], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/users/role/admin",
     *     summary="Get all admins",
     *     tags={"Users"},
     *     @OA\Response(response=200, description="List of admins")
     * )
     */
    public function getRoleAdmin()
    {
        $users = User::where('role', 'admin')->get();
        return response()->json($users, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/users/role/user",
     *     summary="Get all users",
     *     tags={"Users"},
     *     @OA\Response(response=200, description="List of regular users")
     * )
     */
    public function getRoleUser()
    {
        $users = User::where('role', 'user')->get();
        return response()->json($users, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/users/role/me",
     *     summary="Get role of authenticated user",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="User role"),
     *     @OA\Response(response=401, description="Not authenticated")
     * )
     */
    public function getRoleAuthUser()
    {
        $user = Auth::user();
        if (!$user) return response()->json(['message' => 'No authenticated user'], 401);
        return response()->json(['role' => $user->role], 200);
    }
}
