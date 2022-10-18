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
            'imageIds' => ['bail', 'sometimes', 'array'],
            'imageIds.*' => ['bail', 'required_with:imageIds', 'integer', 'min:1'],
            'category_id' => ['bail', 'sometimes', 'integer', 'min:1'],
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->imageIds === null) {
            $this->request->remove('imageIds');
        }
    }
}
