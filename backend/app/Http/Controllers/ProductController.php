<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $products = $this->productService->getProductsList($request->query());
            return response()->json(['data' => $products], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error("Error in ProductController@index: " . $e->getMessage());
            return response()->json(['msg' => 'Internal Server Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json([
                    'msg' => 'User not authenticated'
                ], Response::HTTP_UNAUTHORIZED);
            }

            $this->productService->createProduct($request->validated(), $user->id);

            return response()->json([
                'msg' => 'Product created successfully'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            \Log::error("Error from createProduct: " . $e->getMessage());

            return response()->json([
                'msg' => 'Internal Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $product = $this->productService->getProductById($id);

            if (!$product) {
                return response()->json([
                    'msg' => 'Product not found'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'data' => $product
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error("Error from getProductById: " . $e->getMessage());

            return response()->json([
                'msg' => 'Internal Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function showByUserId($userId)
    {
        try {
            $products = ProductService::getProductsByUserId($userId);

            if ($products->isEmpty()) {
                return response()->json([
                    'msg' => 'No products found for this user.'
                ], 404);
            }

            return response()->json([
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            \Log::error("Error from getProductsByUserId: " . $e->getMessage());

            return response()->json([
                'msg' => 'Internal Server Error'
            ], 500);
        }
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $result = ProductService::updateProduct($id, $request->validated());

            if (!$result) {
                return response()->json(['msg' => 'Product not found'], 404);
            }

            return response()->json([
                'msg' => 'Product updated successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            \Log::error('Error from updateProduct function: ' . $e->getMessage());

            return response()->json([
                'msg' => 'Internal Server Error'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $deleted = ProductService::deleteProductById($id);

            if (!$deleted) {
                return response()->json(['msg' => 'Product not found'], Response::HTTP_NOT_FOUND);
            }

            return response()->json(['msg' => 'Product deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error("Error from deleteProduct function: " . $e->getMessage());

            return response()->json(['msg' => 'Internal Server Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
