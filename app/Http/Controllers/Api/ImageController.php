<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Images\CreateRequest;
use App\Http\Requests\Api\Images\IndexRequest;
use App\Http\Resources\ImageResource;
use App\Http\Traits\HttpResponse;
use App\Models\Image;
use App\Services\ImageService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    use HttpResponse;

    public function __construct()
    {
        $this->authorizeResource(Image::class, 'image');
    }
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\Api\Images\IndexRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request)
    {
        $images = Image::where('product_id', $request->product_id)
            ->paginate($request->per_page ?? 5);

        return ImageResource::collection($images);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Api\Images\CreateRequest $request
     * @param  \App\Http\Resources\ImageResource $imageService
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request, ImageService $imageService)
    {
        $imageIds = $imageService->createImage($request);

        return $imageIds;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Image $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        return new ImageResource($image);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Image $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image $image)
    {
        $slice = Str::of($image->filename)->afterLast('/');

        if ($image->delete()) {
            Storage::delete('images/' . $slice);
        }

        return $this->success(null, 200, 'Image deleted successful!');
    }
}
