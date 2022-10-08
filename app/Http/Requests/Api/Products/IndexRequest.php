<?php

namespace App\Http\Requests\Api\Products;

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
            'sort_by' => ['bail', 'sometimes', 'in:rating'],
            'in_stock' => ['bail', 'sometimes', 'in:stock'],
            'sort_by_price' => ['bail', 'sometimes', 'in:cheap,expensive'],
            'category_ids' => ['bail', 'sometimes', 'regex:#(\d,?)+#'],
            'per_page' => ['bail', 'sometimes', 'integer', 'between:2,10'],
        ];
    }
}
