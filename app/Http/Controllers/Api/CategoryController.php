<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request)
    {
        $query = Category::query();

        $this->applySearch($query, $request, ['name']);

        if ($request->filled('page')) {
            $categories = $query
                ->latest()
                ->paginate($request->integer('per_page', 10));

            return $this->successPagination(
                $categories,
                CategoryResource::class,
                'Categories retrieved successfully.'
            );
        }

        $categories = $query
            ->latest()
            ->get();

        return $this->success(
            CategoryResource::collection($categories),
            'Categories retrieved successfully.'
        );
    }

    /**
     * Store a newly created category.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return $this->success(
            new CategoryResource($category),
            'Category created successfully.',
            201
        );
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        return $this->success(
            new CategoryResource($category),
            'Category retrieved successfully.'
        );
    }

    /**
     * Update the specified category.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return $this->success(
            new CategoryResource($category),
            'Category updated successfully.'
        );
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return $this->success(
            null,
            'Category deleted successfully.'
        );
    }
}