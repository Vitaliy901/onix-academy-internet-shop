<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\HttpResponse;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImageController extends Controller
{
    use HttpResponse;
    /**
     * Removing one product image.
     *
     * @param \App\Models\Product $product
     * @param \App\Models\Product $image
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Product $product, Image $image)
    {
        if ($product->images->contains('id', $image->id)) {

            $slice = Str::of($image->filename)->afterLast('/');
            Storage::delete('images/' . $slice);
            $image->delete();

            return $this->success(null, 200, 'Image deleted successful!');
        }

        return response()->noContent();
    }
}
