<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;

class ImageService
{
    protected array $images = [];

    public function createImage(Request $request, Product $product)
    {
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {

                if ($image->isValid()) {
                    $this->images[] = ['filename' => asset('storage/' . $image->store('images'))];
                }
            }

            $product->images()->createMany($this->images);

            $product->loadMissing('images')->refresh();
        }
    }
}
