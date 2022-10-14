<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Products\CreateRequest;
use App\Http\Requests\Api\Products\IndexRequest;
use App\Http\Requests\Api\Products\UpdateRequest;
use App\Http\Resources\ProductResource;
use App\Http\Traits\HttpResponse;
use App\Models\Product;
use App\Services\ImageService;

class ProductController extends Controller
{
    use HttpResponse;
    /**
     * Display a listing of the resource.
     * @param \App\Http\Requests\Api\Products\IndexRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request)
    {
        $products = Product::categories($request->category_ids)
            ->rating($request->sort_by)
            ->inStock($request->in_stock)
            ->sortByPrice($request->sort_by_price)
            ->paginate($request->per_page ?? 5);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Api\Products\CreateRequest  $request
     * @param  \App\Services\ImageService $imageService
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request, ImageService $imageService)
    {
        $product = Product::create($request->safe()->except('images'));

        $imageService->createImage($request, $product);

        return $this->success(new ProductResource($product), 201, 'Product created successful!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product $request
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Api\Products\UpdateRequest  $request
     * @param  \App\Models\Product $request
     * @param  \App\Services\ImageService $imageService
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Product $product, ImageService $imageService)
    {
        $product->update($request->safe()->except('images'));

        $imageService->createImage($request, $product);

        return $this->success(new ProductResource($product), 200, 'Product updated successful!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', Product::class);

        $product->delete();

        return $this->success(null, 200, 'Product deleted successfully!');
    }
}
