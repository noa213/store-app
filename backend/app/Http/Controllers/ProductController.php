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
     * @OA\Get(
     *     path="/api/products",
     *     summary="Get all products",
     *     tags={"Product"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Product 1"),
     *                     @OA\Property(property="price", type="number", format="float", example=19.99),
     *                     @OA\Property(property="description", type="string", example="A great product"),
     *                     @OA\Property(property="user_id", type="integer", example=5),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-01T12:34:56Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-12-01T12:34:56Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="msg", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $products = $this->productService->getProductsList($request->query());
            return response()->json([
                'products' => $products->items(),
                'current_page' => $products->currentPage(),
                'total_pages' => $products->lastPage()
            ], Response::HTTP_OK);
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
    /**
     * @OA\Post(
     *     path="/api/products",
     *     summary="Create a new product",
     *     tags={"Product"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "info", "price", "category_url", "img_url"},
     *             @OA\Property(property="name", type="string", maxLength=255, example="Product Name"),
     *             @OA\Property(property="info", type="string", example="Detailed description of the product."),
     *             @OA\Property(property="price", type="number", format="float", minimum=0, example=99.99),
     *             @OA\Property(property="category_url", type="string", maxLength=255, example="electronics"),
     *             @OA\Property(property="img_url", type="string", format="uri", example="https://example.com/image.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="msg", type="string", example="Product created successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="User not authenticated",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="msg", type="string", example="User not authenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     type="array",
     *                     @OA\Items(type="string", example="The name field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="msg", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
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
            // $this->productService->createProduct($request->validated(), 3);

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
    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     summary="Get product by ID",
     *     tags={"Product"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the product to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 example={
     *                     "id": 1,
     *                     "name": "Sample Product",
     *                     "info": "This is a sample product",
     *                     "price": 49.99,
     *                     "category_url": "electronics",
     *                     "img_url": "https://example.com/image.jpg"
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="msg", type="string", example="Product not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="msg", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
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

            return response()->json(
                $product
                ,
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            \Log::error("Error from getProductById: " . $e->getMessage());

            return response()->json([
                'msg' => 'Internal Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/products/by-user/{userId}",
     *     summary="Get products by user ID",
     *     tags={"Product"},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         description="The ID of the user whose products to retrieve",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Products found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Sample Product"),
     *                     @OA\Property(property="info", type="string", example="This is a sample product"),
     *                     @OA\Property(property="price", type="number", format="float", example=49.99),
     *                     @OA\Property(property="category_url", type="string", example="electronics"),
     *                     @OA\Property(property="img_url", type="string", format="uri", example="https://example.com/image.jpg")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No products found for this user",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="msg", type="string", example="No products found for this user.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="msg", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
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
    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     summary="Update an existing product",
     *     tags={"Product"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to update",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "info", "price", "category_url", "img_url"},
     *             @OA\Property(property="name", type="string", example="Updated Product Name"),
     *             @OA\Property(property="info", type="string", example="Updated description of the product"),
     *             @OA\Property(property="price", type="number", format="float", example=99.99),
     *             @OA\Property(property="category_url", type="string", example="electronics"),
     *             @OA\Property(property="img_url", type="string", format="uri", example="https://example.com/image.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="msg", type="string", example="Product updated successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 example={
     *                     "id": 1,
     *                     "name": "Updated Product Name",
     *                     "info": "Updated description of the product",
     *                     "price": 99.99,
     *                     "category_url": "electronics",
     *                     "img_url": "https://example.com/image.jpg"
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="msg", type="string", example="Product not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     type="array",
     *                     @OA\Items(type="string", example="The name field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="msg", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
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
    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     summary="Delete a product by its ID",
     *     tags={"Product"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to delete",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="msg", type="string", example="Product deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="msg", type="string", example="Product not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="msg", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
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
