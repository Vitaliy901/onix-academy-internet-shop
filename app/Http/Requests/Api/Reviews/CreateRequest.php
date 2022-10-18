<?php

namespace App\Http\Requests\Api\Reviews;

use App\Models\Review;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->cannot('create', Review::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'text'  => ['bail', 'required', 'string', 'between:10,800'],
            'benefits' => ['bail', 'sometimes', 'nullable', 'string', 'max:255'],
            'disadvantages' => ['bail', 'sometimes', 'nullable', 'string', 'max:255'],
            'rating' => ['bail', 'required', 'integer', 'between:1,5'],
            'product_id' => [
                'bail', 'required',
                Rule::unique('reviews')->where(function ($query) {
                    return $query->where('user_id', $this->user()->id);
                }),
                'integer', 'min:1'
            ],
        ];
    }
}
