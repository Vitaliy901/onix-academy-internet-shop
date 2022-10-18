<?php

namespace App\Services;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductService
{
    public function addImages(Request $request, Product $product)
    {
        Image::whereIn('id', $request->imageIds)
            ->update(['product_id' => $product->id]);
    }
}
