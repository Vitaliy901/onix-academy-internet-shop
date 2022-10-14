<?php

namespace App\Http\Requests\Api\Orders;

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
            'address' => ['bail', 'sometimes', 'string', 'max:255'],
            'status' => ['bail', 'sometimes', 'string', 'in:open,confirmed,canceled'],
        ];
    }
}
