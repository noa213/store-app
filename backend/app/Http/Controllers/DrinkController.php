<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Drink;
use App\Http\Requests\StoreDrinkRequest;
use App\Http\Requests\UpdateDrinkRequest;
use App\Services\DrinkService;

class DrinkController extends Controller
{
    protected $drinkService;

    public function __construct(DrinkService $drinkService)
    {
        $this->drinkService = $drinkService;
    }

    /**
     * @OA\Post(
     *     path="/api/drinks",
     *     summary="Create a new drink",
     *     tags={"Drinks"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "ml", "price", "user_id"},
     *             @OA\Property(property="name", type="string", example="Coca-Cola"),
     *             @OA\Property(property="ml", type="number", format="float", example=500),
     *             @OA\Property(property="price", type="number", format="float", example=6.5),
     *             @OA\Property(property="user_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Drink created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="msg", type="string", example="Drink saved successful in the system.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function store(StoreDrinkRequest $request)
    {
        $validatedData = $request->validated();

        return $this->drinkService->create($validatedData);
    }

    public function index(): JsonResponse
    {
        try {
            $drinks = $this->drinkService->fetchAll();
            return response()->json([
                'data' => $drinks
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('Error fetching drinks: ' . $e->getMessage());
            return response()->json([
                'msg' => 'Internal Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/drinks/user/{userId}",
     *     summary="Get drinks by user ID",
     *     tags={"Drinks"},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         description="ID of the user",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Drinks for the given user",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=5),
     *                 @OA\Property(property="name", type="string", example="Pepsi Max"),
     *                 @OA\Property(property="ml", type="number", format="float", example=500),
     *                 @OA\Property(property="price", type="number", format="float", example=7.00),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-01T15:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-02T15:00:00Z")
     *             ))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No drinks found for this user"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function getByUserId($userId): JsonResponse
    {
        try {
            $drinks = $this->drinkService->getByUserId($userId);

            if (!$drinks) {
                return response()->json([
                    'msg' => 'No drinks found for this user'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'data' => $drinks
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('Error fetching drinks by user ID: ' . $e->getMessage());
            return response()->json([
                'msg' => 'Internal Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/drinks/{id}",
     *     summary="Get a drink by ID",
     *     tags={"Drinks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the drink",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Drink data",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=10),
     *                 @OA\Property(property="name", type="string", example="Fanta"),
     *                 @OA\Property(property="ml", type="number", format="float", example=250),
     *                 @OA\Property(property="price", type="number", format="float", example=6.50),
     *                 @OA\Property(property="user_id", type="integer", example=3),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-04T10:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-04T11:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Drink not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        try {
            $drink = $this->drinkService->fetchById($id);

            if (!$drink) {
                return response()->json([
                    'msg' => 'Drink not found'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'data' => $drink
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('Error fetching drink by ID: ' . $e->getMessage());
            return response()->json([
                'msg' => 'Internal Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Put(
     *     path="/api/drinks/{id}",
     *     summary="Update an existing drink",
     *     tags={"Drinks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the drink to update",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "ml", "price"},
     *             @OA\Property(property="name", type="string", example="Coca Cola Zero"),
     *             @OA\Property(property="ml", type="number", format="float", example=330),
     *             @OA\Property(property="price", type="number", format="float", example=8.50)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Drink updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="msg", type="string", example="Drink updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Drink not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function update(UpdateDrinkRequest $request, $id): JsonResponse
    {
        try {
            $drink = $this->drinkService->update($id, $request->validated());

            if (!$drink) {
                return response()->json([
                    'msg' => 'Drink not found'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'msg' => 'Drink updated successfully'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('Error updating drink: ' . $e->getMessage());
            return response()->json([
                'msg' => 'Internal Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/drinks/{id}",
     *     summary="Delete a drink by ID",
     *     tags={"Drinks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the drink to delete",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Drink deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="msg", type="string", example="Drink deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Drink not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Drink not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        try {
            $deleted = $this->drinkService->delete($id);

            if (!$deleted) {
                return response()->json([
                    'error' => 'Drink not found'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'msg' => 'Drink deleted successfully'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('Error deleting drink: ' . $e->getMessage());
            return response()->json([
                'msg' => 'Internal Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
