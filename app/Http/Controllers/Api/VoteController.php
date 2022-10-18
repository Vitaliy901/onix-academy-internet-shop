<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Votes\CreateRequest;
use App\Http\Requests\Api\Votes\UpdateRequest;
use App\Http\Resources\VoteResource;
use App\Http\Traits\HttpResponse;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoteController extends Controller
{
    use HttpResponse;
    /**
     * Get the number of votes on a question.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'question_id' => ['bail', 'required', 'min:1'],
        ]);

        $votes = Vote::where($validated)->first();

        return new VoteResource($votes);
    }

    /**
     * Create a vote for the question.
     *
     * @param \App\Http\Requests\Api\Votes\CreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $vote = DB::transaction(function () use ($request) {
            return $request->user()->votes()->create($request->validated());
        });

        return new VoteResource($vote);
    }

    /**
     * Update the vote of the question.
     *
     * @param  App\Http\Requests\Api\Votes\UpdateRequest $request
     * @param  App\Models\Vote $vote
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Vote $vote)
    {
        $this->authorize('update', $vote);

        DB::transaction(function () use ($vote, $request) {
            $vote->update($request->validated());
        });

        return new VoteResource($vote->refresh());
    }

    /**
     * Remove the vote.
     *
     * @param  App\Models\Vote $vote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vote $vote)
    {
        $this->authorize('delete', $vote);

        DB::transaction(function () use ($vote) {
            $vote->delete();
        });

        return $this->success(null, 200, 'Vote deleted successfully!');
    }
}
