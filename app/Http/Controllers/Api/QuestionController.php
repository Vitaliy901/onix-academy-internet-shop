<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Questions\CreateRequest;
use App\Http\Requests\Api\Questions\IndexRequest;
use App\Http\Requests\Api\Questions\UpdateRequest;
use App\Http\Resources\QuestionResource;
use App\Http\Traits\HttpResponse;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    use HttpResponse;
    /**
     * Display a listing of the resource.
     * 
     * @param  \App\Http\Requests\Api\Questions\IndexRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request)
    {
        $questions = Question::date($request->sort_by)
            ->product($request->product_id)
            ->paginate($request->per_page ?? 5);

        return QuestionResource::collection($questions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Api\Questions\CreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $question = $request->user()->questions()->create($request->validated());

        return new QuestionResource($question);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        return new QuestionResource($question);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Api\Questions\UpdateRequest  $request
     * @param  \App\Models\Question $question
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Question $question)
    {
        $this->authorize('update', $question);

        $question->update($request->validated());

        return new QuestionResource($question);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $this->authorize('delete', $question);

        DB::transaction(function () use ($question) {
            Answer::destroy($question->answers);

            $question->delete();
        });

        return $this->success(null, 200, 'Review deleted successfully!');
    }
}
