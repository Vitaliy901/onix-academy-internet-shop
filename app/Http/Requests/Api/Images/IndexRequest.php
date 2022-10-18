<?php

namespace App\Http\Requests\Api\Images;

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
            'product_id' => ['bail', 'required', 'integer', 'min:1'],
            'per_page' => ['bail', 'sometimes', 'integer', 'between:2,10'],
        ];
    }
}
