<?php

namespace App\Http\Requests\Api\Answers;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'text' => ['bail', 'required', 'string', 'between:8,700'],
        ];
    }
}
