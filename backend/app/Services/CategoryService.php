<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function getCategoriesList($params)
    {
        $perPage = $params['perPage'] ?? 20;
        $page = $params['page'] ?? 1;
        $sort = $params['sort'] ?? 'id';
        $reverse = ($params['reverse'] ?? null) === 'yes' ? 'asc' : 'desc';

        return Category::orderBy($sort, $reverse)
            ->paginate($perPage, ['*'], 'page', $page);
    }
    public function getCategoryById($id)
    {
        return Category::find($id);
    }
    public function createCategory(array $data)
    {
        return Category::create($data);
    }
}
