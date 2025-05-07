<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Schema(
 *     schema="Category",
 *     type="object",
 *     title="Category",
 *     required={"id", "name", "url_name", "info", "img_url"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Category 1"),
 *     @OA\Property(property="url_name", type="string", example="category-1"),
 *     @OA\Property(property="info", type="string", example="This is a description of category 1"),
 *     @OA\Property(property="img_url", type="string", example="https://via.placeholder.com/640x480.png/0000cc?text=category+1")
 * )
 */
class CategoryController extends Controller
{

    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Get all categories",
     *     tags={"Category"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page",
     *         required=false,
     *         @OA\Schema(type="integer")
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
     *                     @OA\Property(property="name", type="string", example="Category 1"),
     *                     @OA\Property(property="url_name", type="string", example="category-1"),
     *                     @OA\Property(property="info", type="string", example="Description..."),
     *                     @OA\Property(property="img_url", type="string", example="https://example.com/image.jpg")
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
        $data = $this->categoryService->getCategoriesList($request->query());
        return response()->json(['data' => $data]);
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     summary="Get category by ID",
     *     tags={"Category"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the category to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/Category"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="msg", type="string", example="Category not found")
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
            $category = $this->categoryService->getCategoryById($id);
            if (!$category) {
                return response()->json(['msg' => 'Category not found'], response::HTTP_NOT_FOUND);
            }

            return response()->json(['data' => $category], Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('Error from fetchCategoryById function: ' . $e->getMessage());
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
     * @OA\Post(
     *     path="/api/categories",
     *     summary="Create a new category",
     *     tags={"Category"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "url_name", "info", "img_url"},
     *             @OA\Property(property="name", type="string", example="new category"),
     *             @OA\Property(property="url_name", type="string", example="new-category"),
     *             @OA\Property(property="info", type="string", example="This is a new test category"),
     *             @OA\Property(property="img_url", type="string", format="uri", example="https://via.placeholder.com/150")  
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category saved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="msg", type="string", example="Category saved successfully in the system.")
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
    public function store(StoreCategoryRequest $request)
    {
        try {
            $category = $this->categoryService->createCategory($request->validated());

            return response()->json([
                'msg' => 'Category saved successfully in the system.',
                'id' => $category->id,  
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            \Log::error('Error from createCategory function: ' . $e->getMessage());

            return response()->json(['msg' => 'Internal Server Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     summary="Update an existing category",
     *     tags={"Category"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the category to update",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "url_name", "info", "img_url"},
     *             @OA\Property(property="name", type="string", example="new category"),
     *             @OA\Property(property="url_name", type="string", example="new-category"),
     *             @OA\Property(property="info", type="string", example="This is a new test category"),
     *             @OA\Property(property="img_url", type="string", format="uri", example="https://via.placeholder.com/150")  
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="msg", type="string", example="Category updated successfully in the system")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Missing category ID",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="msg", type="string", example="id of category must be received")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="msg", type="string", example="Category not found")
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
    public function update(UpdateCategoryRequest $request, $id)
    {
        try {
            if (!$id) {
                return response()->json(['msg' => 'id of category must be received'], Response::HTTP_BAD_REQUEST);
            }

            $category = $this->categoryService->updateCategory($id, $request->validated());

            if (!$category) {
                return response()->json(['msg' => 'Category not found'], Response::HTTP_NOT_FOUND);
            }

            return response()->json(['msg' => 'Category updated successfully in the system'], Response::HTTP_OK);

        } catch (\Exception $e) {
            \Log::error("Error from editCategory function: " . $e->getMessage());

            return response()->json(['msg' => 'Internal Server Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     summary="Delete a category by its ID",
     *     tags={"Category"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the category to delete",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="msg", type="string", example="Category deleted successfully in the system")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Category does not exist",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="msg", type="string", example="Category does not exist in the system")
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
            $deleted = $this->categoryService->deleteCategoryById($id);

            if (!$deleted) {
                return response()->json([
                    'msg' => 'Category does not exist in the system'
                ], Response::HTTP_BAD_REQUEST);
            }

            return response()->json([
                'msg' => 'Category deleted successfully in the system'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            \Log::error("Error from deleteCategory function: " . $e->getMessage());

            return response()->json([
                'msg' => 'Internal Server Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
