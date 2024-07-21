<?php

namespace App\Http\Controllers\Api\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Get all categories
     */
    public function index(): JsonResponse
    {
        $categories = Category::all();
        return response()->json(CategoryResource::collection($categories), 200);
    }

    /**
     * Store a new category
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->only(['name', 'description']));
        return response()->json(new CategoryResource($category), 201);
    }

    /**
     * Get a single category
     */
    public function show($id): JsonResponse
    {
        $category = Category::findOrFail($id);
        return response()->json(new CategoryResource($category), 200);
    }

    /**
     * Update a category
     */
    public function update(CategoryRequest $request, $id): JsonResponse
    {
        $category = Category::findOrFail($id);
        $category->update($request->only(['name', 'description']));
        return response()->json(new CategoryResource($category), 200);
    }

    /**
     *  Delete a category
     */

    public function destroy($id): JsonResponse
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
