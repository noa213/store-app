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
    public function index(Request $request)
    {
        $data = $this->categoryService->getCategoriesList($request->query());
        return response()->json(['data' => $data]);
    }

    /**
     * Display the specified resource.
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
    public function store(StoreCategoryRequest $request)
    {
        try {
            $category = $this->categoryService->createCategory($request->validated());

            return response()->json(['msg' => 'Category saved successfully in the system.'], Response::HTTP_OK);

        } catch (\Exception $e) {
            \Log::error('Error from createCategory function: ' . $e->getMessage());

            return response()->json(['msg' => 'Internal Server Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }    }


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
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
