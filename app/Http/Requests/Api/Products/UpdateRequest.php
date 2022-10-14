<?php

namespace App\Http\Requests\Api\Products;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', Product::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'  => ['bail', 'sometimes', 'string', 'max:255'],
            'description'  => ['bail', 'sometimes', 'string'],
            'in_stock' => ['bail', 'sometimes', 'integer', 'between:0,200'],
            'price' => ['bail', 'sometimes', 'integer', 'min:0'],
            'images.*'  => [
                'bail', 'sometimes', 'file', 'max:5120', 'mimes:jpeg,gif,png',
                /* 'dimensions:min_width=1440,min_height=1080', */
            ],
            'category_id' => ['bail', 'sometimes', 'integer', 'min:1'],
        ];
    }
}
