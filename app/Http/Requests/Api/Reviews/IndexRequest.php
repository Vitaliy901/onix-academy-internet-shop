<?php

namespace App\Http\Requests\Api\Reviews;

use App\Models\Review;
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
            'sort_bought' => ['bail', 'sometimes', 'in:bought'],
            'sort_by' => ['bail', 'sometimes', 'in:date'],
            'product_id' => [
                'bail',
                Rule::when($this->user()->can('index', Review::class), 'sometimes', 'required'),
                'integer', 'min:1'
            ],
            'per_page' => ['bail', 'sometimes', 'integer', 'between:2,10'],
        ];
    }
}
