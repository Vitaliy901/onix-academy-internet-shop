<?php

namespace App\Http\Requests\Api\Votes;

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
            'status'  => ['bail', 'required', 'in:up,down'],
        ];
    }
}
