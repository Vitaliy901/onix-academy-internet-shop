<?php

namespace App\Http\Requests\Api\Products;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'  => ['bail', 'required', 'string', 'max:255'],
            'description'  => ['bail', 'required', 'string'],
            'in_stock' => ['bail', 'sometimes', 'integer', 'between:0,200'],
            'price' => ['bail', 'required', 'integer', 'min:0'],
            'images' => ['bail', 'required', 'array'],
            'images.*'  => [
                'bail', 'required', 'file', 'max:5120', 'mimes:jpeg,gif,png',
                /* 'dimensions:min_width=1440,min_height=1080', */
            ],
            'category_id' => ['bail', 'required', 'integer', 'min:1'],
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->in_stock === null) {
            $this->request->remove('in_stock');
        }
    }
}
