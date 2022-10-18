<?php

namespace App\Http\Requests\Api\Answers;

use App\Models\Answer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'per_page' => ['bail', 'sometimes', 'integer', 'between:2,10'],
            'questions_id' => [
                'bail', 'exclude_with:reviews_id',
                Rule::requiredIf($this->user()->cannot('index', Answer::class)),
                'integer', 'min:1'
            ],
            'reviews_id' => [
                'bail', 'exclude_with:questions_id',
                Rule::requiredIf($this->user()->cannot('index', Answer::class)),
                'integer', 'min:1'
            ],
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->questions_id && $this->reviews_id) {
            $this->query->remove('questions_id');
            $this->query->remove('reviews_id');
        }
    }

    public function messages()
    {
        return [
            'questions_id.required' => 'The :attribute field or the reviews_id is required.',
            'reviews_id.required' => 'The :attribute field or the questions_id is required.',
        ];
    }
}
