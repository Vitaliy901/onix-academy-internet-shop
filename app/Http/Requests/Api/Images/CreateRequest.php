<?php

namespace App\Http\Requests\Api\Images;

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
            'images' => ['bail', 'required', 'array'],
            'images.*'  => [
                'bail', 'required', 'file', 'max:5120', 'mimes:jpeg,gif,png',
                /* 'dimensions:min_width=1440,min_height=1080', */
            ],
        ];
    }
}
