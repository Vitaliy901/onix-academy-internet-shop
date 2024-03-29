<?php

namespace App\Http\Requests\Api\Categories;

use App\Models\Category;
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
        return $this->user()->can('update', Category::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['bail', 'sometimes', 'string', 'max:255'],
            'description' => ['bail', 'sometimes', 'string'],
        ];
    }
}
