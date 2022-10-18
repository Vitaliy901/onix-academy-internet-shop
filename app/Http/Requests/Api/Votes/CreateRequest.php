<?php

namespace App\Http\Requests\Api\Votes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'status'  => ['bail', 'required', 'in:up,down'],
            'question_id' => [
                'bail', 'required',
                Rule::unique('votes')->where(function ($query) {
                    return $query->where('user_id', $this->user()->id);
                }),
                'integer', 'min:1'
            ],
        ];
    }
}
