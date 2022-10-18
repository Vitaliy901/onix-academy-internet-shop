<?php

namespace App\Services;

use Illuminate\Http\Request;

class AnswerService
{
    public function answerFilter(Request $request): array
    {
        if ($request->has('questions_id')) {
            $validated = $request->safe()->merge([
                'answerable_id' => $request->get('questions_id'),
                'answerable_type' => 'question',
            ])->except('questions_id');
        };

        if ($request->has('reviews_id')) {
            $validated = $request->safe()->merge([
                'answerable_id' => $request->get('reviews_id'),
                'answerable_type' => 'review',
            ])->except('reviews_id');
        };

        return $validated;
    }
}
