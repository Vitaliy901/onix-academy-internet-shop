<?php

namespace App\Http\Requests\Api\Reviews;

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
            'text'  => ['bail', 'sometimes', 'string', 'between:10,800'],
            'benefits' => ['bail', 'sometimes', 'nullable', 'string', 'max:255'],
            'disadvantages' => ['bail', 'sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}
