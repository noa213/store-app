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
