<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RefreshTokenRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * User registration
     */
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation", "role", "favs_ar"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123"),
     *             @OA\Property(
     *                 property="role",
     *                 type="string",
     *                 enum={"user", "admin", "superadmin"},
     *                 example="user"
     *             ),
     *             @OA\Property(property="favs_ar", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="statusCode", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="User has been registered successfully."),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="user", type="object", 
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="john@example.com"),
     *                     @OA\Property(property="role", type="string", example="admin"),
     *                     @OA\Property(property="favs_ar", type="array", @OA\Items(type="string")),
     *                     @OA\Property(property="created_at", type="string", example="2024-05-12T12:34:56Z"),
     *                     @OA\Property(property="updated_at", type="string", example="2024-05-12T12:34:56Z")
     *                 ),
     *                 @OA\Property(property="token", type="string", example="1|abcde12345faketoken")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 additionalProperties=@OA\Property(type="array", @OA\Items(type="string"))
     *             )
     *         )
     *     )
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $userData = $request->validated();
        $userData['email_verified_at'] = now();

        $user = $this->userService->createUser($userData);

        $token = $user->createToken(env('APP_URL'))->accessToken;

        return response()->json([
            'success' => true,
            'statusCode' => 201,
            'message' => 'User has been registered successfully.',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ], 201);
    }


    /**
     * Login user
     */ 
    /**
    *@OA\Post(
    *     path="/api/login",
    *     summary="login user",
    *     tags={"Auth"},
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             required={"name", "email"},
    *             @OA\Property(property="name", type="string", example="John Doe"),
    *             @OA\Property(property="email", type="string", format="email", example="john@example.com")
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="User logged successfully",
    *         @OA\JsonContent(
    *             type="object",
    *             @OA\Property(property="success", type="boolean", example=true),
    *             @OA\Property(property="statusCode", type="integer", example=200),
    *             @OA\Property(property="message", type="string", example="User has been registered successfully."),
    *             @OA\Property(
    *                 property="data",
    *                 type="object",
    *                 @OA\Property(property="user", type="object", 
    *                     @OA\Property(property="id", type="integer", example=1),
    *                     @OA\Property(property="name", type="string", example="John Doe"),
    *                     @OA\Property(property="email", type="string", example="john@example.com"),
    *                     @OA\Property(property="role", type="string", example="admin"),
    *                     @OA\Property(property="favs_ar", type="array", @OA\Items(type="string")),
    *                     @OA\Property(property="created_at", type="string", example="2024-05-12T12:34:56Z"),
    *                     @OA\Property(property="updated_at", type="string", example="2024-05-12T12:34:56Z")
    *                 ),
    *                 @OA\Property(property="token", type="string", example="1|abcde12345faketoken")
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="Internal Server Error",
    *         @OA\JsonContent(
    *             @OA\Property(property="message", type="string", example="The given data was invalid."),
    *             @OA\Property(
    *                 property="errors",
    *                 type="object",
    *                 additionalProperties=@OA\Property(type="array", @OA\Items(type="string"))
    *             )
    *         )
    *     )
    * )
    */
    public function login(LoginRequest $request): JsonResponse
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            $token = $user->createToken(env('APP_URL'))->accessToken;

            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'User has been logged in successfully.',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                ],
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }
    }

    /**
     * Login user
     *
     * @param  LoginRequest  $request
     */
    public function me(): JsonResponse
    {

        $user = auth()->user();

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Authenticated use info.',
            'data' => $user,
        ], 200);
    }

    /**
     * refresh token
     *
     * @return void
     */
    public function refreshToken(RefreshTokenRequest $request): JsonResponse
    {
        $response = Http::asForm()->post(env('APP_URL') . '/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->refresh_token,
            'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
            'client_secret' => env('PASSPORT_PASSWORD_SECRET'),
            'scope' => '',
        ]);

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Refreshed token.',
            'data' => $response->json(),
        ], 200);
    }

    /**
     * Logout
     */
    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Log out the authenticated user",
     *     tags={"Auth"},
     *     security={{"passport": {}}},
     *     @OA\Response(
     *         response=204,
     *         description="User logged out successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="statusCode", type="integer", example=204),
     *             @OA\Property(property="message", type="string", example="Logged out successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'statusCode' => 204,
            'message' => 'Logged out successfully.',
        ], 204);
    }
}