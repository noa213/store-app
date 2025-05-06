<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{

    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     */

    /**
     * @swagger
     * /categories:
     *   get:
     *     summary: Retrieve a list of categories
     *     tags: [Category]
     *     parameters:
     *       - in: query
     *         name: page
     *         schema:
     *           type: integer
     *         description: The page number for pagination (optional)
     *       - in: query
     *         name: per_page
     *         schema:
     *           type: integer
     *         description: The number of items per page (optional)
     *     responses:
     *       200:
     *         description: Successfully retrieved the list of categories
     *         content:
     *           application/json:
     *             schema:
     *               type: object
     *               properties:
     *                 data:
     *                   type: array
     *                   items:
     *                     type: object
     *                     properties:
     *                       id:
     *                         type: integer
     *                         example: 1
     *                       name:
     *                         type: string
     *                         example: "Category 1"
     *                       url_name:
     *                         type: string
     *                         example: "category-1"
     *                       info:
     *                         type: string
     *                         example: "This is a description of category 1"
     *                       img_url:
     *                         type: string
     *                         example: "https://via.placeholder.com/640x480.png/0000cc?text=category+1"
     *       500:
     *         description: Internal server error while retrieving categories
     *         content:
     *           application/json:
     *             schema:
     *               type: object
     *               properties:
     *                 msg:
     *                   type: string
     *                   description: Error message
     *                   example: "Internal Server Error"
     */
    public function index(Request $request)
    {
        $data = $this->categoryService->getCategoriesList($request->query());
        return response()->json(['data' => $data]);
    }

    /**
     * Display the specified resource.
     */

    /**
     * @swagger
     * /categories:
     *   post:
     *     summary: Create a new category
     *     tags: [Category]
     *     security:
     *       - bearerAuth: []  # If you're using JWT or Bearer Token for authentication, leave this
     *     requestBody:
     *       required: true
     *       content:
     *         application/json:
     *           schema:
     *             type: object
     *             properties:
     *               name:
     *                 type: string
     *                 example: "New Category"
     *               url_name:
     *                 type: string
     *                 example: "new-category"
     *               info:
     *                 type: string
     *                 example: "This is a description of the new category"
     *               img_url:
     *                 type: string
     *                 example: "https://via.placeholder.com/640x480.png/0000cc?text=new+category+image"
     *     responses:
     *       200:
     *         description: Successfully created the category
     *         content:
     *           application/json:
     *             schema:
     *               type: object
     *               properties:
     *                 msg:
     *                   type: string
     *                   description: Success message
     *                   example: "Category saved successfully in the system."
     *       400:
     *         description: Invalid category data provided
     *         content:
     *           application/json:
     *             schema:
     *               type: object
     *               properties:
     *                 msg:
     *                   type: string
     *                   description: Error message
     *                   example: "Invalid data provided for category creation"
     *       500:
     *         description: Internal server error while creating the category
     *         content:
     *           application/json:
     *             schema:
     *               type: object
     *               properties:
     *                 msg:
     *                   type: string
     *                   description: Error message
     *                   example: "Internal Server Error"
     */
    public function show($categoryId)
    {
        try {
            $category = $this->categoryService->getCategoryById($categoryId);
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
     * Store a newly created resource in storage.
     */

    /**
     * @swagger
     * /categories:
     *   post:
     *     summary: Create a new category
     *     tags: [Category]
     *     security:
     *       - bearerAuth: []  # If you're using JWT or Bearer Token for authentication, leave this
     *     requestBody:
     *       required: true
     *       content:
     *         application/json:
     *           schema:
     *             type: object
     *             properties:
     *               name:
     *                 type: string
     *                 example: "New Category"
     *               url_name:
     *                 type: string
     *                 example: "new-category"
     *               info:
     *                 type: string
     *                 example: "This is a description of the new category"
     *               img_url:
     *                 type: string
     *                 example: "https://via.placeholder.com/640x480.png/0000cc?text=new+category+image"
     *     responses:
     *       201:
     *         description: Successfully created the category
     *         content:
     *           application/json:
     *             schema:
     *               type: object
     *               properties:
     *                 msg:
     *                   type: string
     *                   description: Success message
     *                   example: Category saved successfully in the system.
     *       400:
     *         description: Invalid category data provided
     *         content:
     *           application/json:
     *             schema:
     *               type: object
     *               properties:
     *                 msg:
     *                   type: string
     *                   description: Error message
     *                   example: Invalid data provided for category creation
     *       500:
     *         description: Internal server error while creating the category
     *         content:
     *           application/json:
     *             schema:
     *               type: object
     *               properties:
     *                 msg:
     *                   type: string
     *                   description: Error message
     *                   example: Internal Server Error
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $category = $this->categoryService->createCategory($request->validated());

            return response()->json(['msg' => 'Category saved successfully in the system.'], Response::HTTP_CREATED);

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
     * Update the specified resource in storage.
     */

    /**
     * @swagger
     * /categories/{id}:
     *   put:
     *     summary: Update a category by ID
     *     tags: [Category]
     *     security:
     *       - bearerAuth: []  # If you're using JWT or Bearer Token for authentication, leave this
     *     parameters:
     *       - in: path
     *         name: id
     *         schema:
     *           type: string
     *         required: true
     *         description: The ID of the category to update
     *       - in: body
     *         name: category
     *         description: The category details to update
     *         required: true
     *         schema:
     *           type: object
     *           properties:
     *             name:
     *               type: string
     *               example: "Updated Category Name"
     *             url_name:
     *               type: string
     *               example: "updated-category-url"
     *             info:
     *               type: string
     *               example: "Updated description of the category"
     *             img_url:
     *               type: string
     *               example: "https://via.placeholder.com/640x480.png/0000cc?text=updated+category+image"
     *     responses:
     *       200:
     *         description: Successfully updated the category
     *         content:
     *           application/json:
     *             schema:
     *               type: object
     *               properties:
     *                 msg:
     *                   type: string
     *                   description: Success message
     *                   example: Category updated successfully in the system
     *       400:
     *         description: Category ID not provided
     *         content:
     *           application/json:
     *             schema:
     *               type: object
     *               properties:
     *                 msg:
     *                   type: string
     *                   description: Error message
     *                   example: ID of category must be received
     *       404:
     *         description: Category not found
     *         content:
     *           application/json:
     *             schema:
     *               type: object
     *               properties:
     *                 msg:
     *                   type: string
     *                   description: Error message
     *                   example: Category not found
     *       500:
     *         description: Internal server error while updating the category
     *         content:
     *           application/json:
     *             schema:
     *               type: object
     *               properties:
     *                 msg:
     *                   type: string
     *                   description: Error message
     *                   example: Internal Server Error
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
     * Remove the specified resource from storage.
     */

    /**
     * @swagger
     * /categories/{id}:
     *   delete:
     *     summary: Delete a category by ID
     *     tags: [Category]
     *     security:
     *       - bearerAuth: []  # If you're using JWT or Bearer Token for authentication, leave this
     *     parameters:
     *       - in: path
     *         name: id
     *         schema:
     *           type: string
     *         required: true
     *         description: The ID of the category to delete
     *     responses:
     *       200:
     *         description: Successfully deleted the category
     *         content:
     *           application/json:
     *             schema:
     *               type: object
     *               properties:
     *                 msg:
     *                   type: string
     *                   description: Success message
     *                   example: Category deleted successfully in the system
     *       400:
     *         description: Category does not exist in the system
     *         content:
     *           application/json:
     *             schema:
     *               type: object
     *               properties:
     *                 msg:
     *                   type: string
     *                   description: Error message
     *                   example: Category does not exist in the system
     *       500:
     *         description: Internal server error while deleting the category
     *         content:
     *           application/json:
     *             schema:
     *               type: object
     *               properties:
     *                 msg:
     *                   type: string
     *                   description: Error message
     *                   example: Internal Server Error
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
