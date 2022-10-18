<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Answers\CreateRequest;
use App\Http\Requests\Api\Answers\IndexRequest;
use App\Http\Requests\Api\Answers\UpdateRequest;
use App\Http\Resources\AnswerResource;
use App\Http\Traits\HttpResponse;
use App\Models\Answer;
use App\Services\AnswerService;

class AnswerController extends Controller
{
    use HttpResponse;

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\Api\Answers\IndexRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request)
    {
        $answers = Answer::morphFilter($request)->paginate($request->per_page ?? 5);

        return AnswerResource::collection($answers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Api\Answers\CreateRequest $request
     * @param  \App\Http\Resources\AnswerResource $answerService
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request, AnswerService $answerService)
    {
        $validated = $answerService->answerFilter($request);

        return $request->user()->answers()->create($validated);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Answer $answer
     * @return \Illuminate\Http\Response
     */
    public function show(Answer $answer)
    {
        return new AnswerResource($answer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Api\Answers\UpdateRequest $request
     * @param  \App\Models\Answer $answer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Answer $answer)
    {
        $this->authorize('update', $answer);

        $answer->update($request->validated());

        return new AnswerResource($answer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Answer $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Answer $answer)
    {
        $this->authorize('delete', $answer);

        $answer->delete();

        return $this->success(null, 200, 'Answer deleted successfully!');
    }
}
