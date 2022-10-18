<?php

namespace App\Http\Requests\Api\Answers;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'questions_id' => ['bail', 'exclude_with:reviews_id', 'required_with:text', 'integer', 'min:1'],
            'reviews_id' => ['bail', 'exclude_with:questions_id', 'required_with:text', 'integer', 'min:1'],
            'text' => ['bail', 'required', 'string', 'between:8,700'],
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->questions_id && $this->reviews_id) {
            $this->request->remove('questions_id');
            $this->request->remove('reviews_id');
        }
    }
}
