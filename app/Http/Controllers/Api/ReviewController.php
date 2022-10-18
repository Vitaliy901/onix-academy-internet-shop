<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Reviews\CreateRequest;
use App\Http\Requests\Api\Reviews\IndexRequest;
use App\Http\Requests\Api\Reviews\UpdateRequest;
use App\Http\Resources\ReviewResource;
use App\Http\Traits\HttpResponse;
use App\Models\Answer;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    use HttpResponse;
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\Api\Reviews\IndexRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request)
    {
        $reviews = Review::date($request->sort_by)
            ->whoBought($request->sort_bought)
            ->product($request->product_id)
            ->paginate($request->per_page ?? 5);

        return ReviewResource::collection($reviews);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Api\Reviews\CreateRequest $request
     * @param  \App\Services\ReviewService $reviewService
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request, ReviewService $reviewService)
    {
        $review = $reviewService->create($request->safe(), $request->user());

        if ($review) {
            return new ReviewResource($review);
        }

        return response()->noContent();
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Models\Review $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        return new ReviewResource($review);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Api\Reviews\UpdateRequest $request
     * @param  App\Models\Review $review
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Review $review)
    {
        $this->authorize('update', $review);

        $review->update($request->validated());

        return new ReviewResource($review);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Models\Review $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);

        DB::transaction(function () use ($review) {
            Answer::destroy($review->answers);

            $review->delete();
        });

        return $this->success(null, 200, 'Review deleted successfully!');
    }
}
