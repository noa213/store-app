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
     *     @OA\Response(response=200, description="List of all users"),
     *     @OA\Response(response=403, description="Forbidden")
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
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="User found"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=403, description="Forbidden")
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
     *     @OA\Response(response=201, description="User created"),
     *     @OA\Response(response=403, description="Forbidden")
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
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=403, description="Forbidden")
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
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=403, description="Forbidden")
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
     *     path="/api/user-info",
     *     summary="Get authenticated user's info",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="User info retrieved successfully"),
     *     @OA\Response(response=400, description="User has been deleted"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function fetchUserInfo(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'User has been deleted'], 400);
        }
        return response()->json(['data' => $user], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/decode-token",
     *     summary="Decode JWT token",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"token"},
     *             @OA\Property(property="token", type="string", example="your.jwt.token")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Token decoded successfully"),
     *     @OA\Response(response=400, description="Invalid token"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
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

    /**
     * @OA\Patch(
     *     path="/api/users/{id}/role",
     *     summary="Change user role",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"role"},
     *             @OA\Property(property="role", type="string", enum={"user", "admin", "superadmin"})
     *         )
     *     ),
     *     @OA\Response(response=200, description="Role updated successfully"),
     *     @OA\Response(response=400, description="Invalid role or user trying to change own role"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="User not found")
     * )
     */
    public function changeRole(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($request->user()->id == $id) {
            return response()->json(['message' => "You can't change your own role"], 400);
        }

        $validated = $request->validate([
            'role' => 'required|string|in:admin,user,superadmin',
        ]);

        $user->role = $validated['role'];
        $user->save();

        return response()->json(['message' => 'Role updated successfully'], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/users/admins",
     *     summary="Get all admin users",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="List of admins"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function getRoleAdmin()
    {
        $admins = User::where('role', 'admin')->get();
        return response()->json(['data' => $admins], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/users/regulars",
     *     summary="Get all users with user role",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="List of users"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function getRoleUser()
    {
        $users = User::where('role', 'user')->get();
        return response()->json(['data' => $users], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/auth-user-role",
     *     summary="Get role of authenticated user",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Authenticated user role"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function getRoleAuthUser()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'No authenticated user'], 401);
        }
        return response()->json(['data' => $user->role], 200);
    }
}
