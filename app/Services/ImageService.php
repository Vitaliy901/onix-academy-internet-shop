<?php

namespace App\Services;

use App\Models\Image;
use Illuminate\Http\Request;

class ImageService
{
    protected array $ids = [];

    public function createImage(Request $request): array
    {
        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $image) {

                if ($image->isValid()) {
                    $this->ids[] = Image::insertGetId([
                        'filename' => asset('storage/' . $image->store('images')),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            return $this->ids;
        }
    }
}
