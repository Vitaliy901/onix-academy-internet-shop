<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\HttpResponse;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImageController extends Controller
{
    use HttpResponse;
    /**
     * Removing one product image.
     *
     * @param \App\Models\Product  $product
     * @param integer $id
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Product $product, $id)
    {
        foreach ($product->images as $image) {
            if ($image->id == $id) {
                $slice = Str::of($image->filename)->afterLast('/');
                Storage::delete('images/' . $slice);
                $image->delete();

                return $this->success(null, 200, 'Image deleted successful!');
            }
        }

        return $this->error(null, 404, 'Image not found!');
    }
}
