<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;

class ProductService
{
    public function getProductsList($params)
    {
        $perPage = intval($queryParams['perPage'] ?? 10);
        $page = intval($queryParams['page'] ?? 1);
        $sort = $queryParams['sort'] ?? 'id';
        $reverse = ($queryParams['reverse'] ?? 'no') === 'yes' ? 'asc' : 'desc';

        return Product::orderBy($sort, $reverse)
            ->paginate($perPage, ['*'], 'page', $page);
    }
    public function getProductById($id)
    {
        return Product::find($id);
    }
    public function createProduct(array $data, int $userId): void
    {
        $data['user_id'] = $userId;
        Product::create($data);
    }
    public function updateCategory($id, array $data)
    {
        $category = Category::find($id);

        if (!$category) {
            return null;
        }

        $category->update($data);
        return $category;
    }
    public function deleteCategoryById($id)
    {
        return Category::destroy($id) > 0;
    }
}
