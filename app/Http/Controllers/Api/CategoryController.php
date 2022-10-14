<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Categories\CreateRequest;
use App\Http\Requests\Api\Categories\UpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Traits\HttpResponse;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use HttpResponse;
    /**
     * Display a listing of the resource.
     * 
     * @param \App\Http\Resources\CategoryResource $categoryService
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, CategoryService $categoryService)
    {
        $categories = $categoryService->index($request);

        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Api\Categories\CreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $category = Category::create($request->validated());

        return $this->success(new CategoryResource($category), 201, 'Category created successful');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Api\Categories\UpdateRequest $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Category $category)
    {
        $category->update($request->validated());

        return $this->success(new CategoryResource($category), 200, 'Category updated successful');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', Category::class);

        $category->delete();

        return $this->success(null, 200, 'Category deleted successful!');
    }
}
