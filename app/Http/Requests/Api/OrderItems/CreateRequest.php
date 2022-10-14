<?php

namespace App\Http\Requests\Api\OrderItems;

use Illuminate\Foundation\Http\FormRequest;

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
            'product_id' => ['bail', 'required', 'integer', 'min:1'],
            'quantity' => ['bail', 'required', 'integer', 'min:1']
        ];
    }
}
