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
    // public function register(RegisterRequest $request): JsonResponse
    // {
    //     $userData = $request->validated();

    //     $userData['email_verified_at'] = now();

    //     $user = $this->userService->createUser($userData);

    //     // Issue token after registration
    //     $response = Http::post(env('APP_URL') . '/oauth/token', [
    //         'grant_type' => 'password',
    //         'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
    //         'client_secret' => env('PASSPORT_PASSWORD_SECRET'),
    //         'username' => $userData['email'],
    //         'password' => $userData['password'],
    //         'scope' => '',
    //     ]);

    //     $user['token'] = $response->json();

    //     return response()->json([
    //         'success' => true,
    //         'statusCode' => 201,
    //         'message' => 'User has been registered successfully.',
    //         'data' => $user,
    //     ], 201);
    // }
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
    // public function login(LoginRequest $request): JsonResponse
    // {
    //     if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    //         $user = Auth::user();

    //         $response = Http::post(env('APP_URL') . '/oauth/token', [
    //             'grant_type' => 'password',
    //             'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
    //             'client_secret' => env('PASSPORT_PASSWORD_SECRET'),
    //             'username' => $request->email,
    //             'password' => $request->password,
    //             'scope' => '',
    //         ]);

    //         $user['token'] = $response->json();

    //         return response()->json([
    //             'success' => true,
    //             'statusCode' => 200,
    //             'message' => 'User has been logged successfully.',
    //             'data' => $user,
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'success' => true,
    //             'statusCode' => 401,
    //             'message' => 'Unauthorized.',
    //             'errors' => 'Unauthorized',
    //         ], 401);
    //     }
    // }
    public function login(LoginRequest $request): JsonResponse
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            // Create an access token for the user
            $token = $user->createToken('YourAppName')->accessToken;

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