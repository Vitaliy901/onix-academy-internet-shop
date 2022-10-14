<?php

namespace App\Http\Requests\Api\OrderItems;

use Illuminate\Foundation\Http\FormRequest;

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
            'sort_by' => ['bail', 'sometimes', 'in:price'],
            'per_page' => ['bail', 'sometimes', 'integer', 'between:2,10'],
        ];
    }
}
