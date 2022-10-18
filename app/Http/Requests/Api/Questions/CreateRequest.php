<?php

namespace App\Http\Requests\Api\Questions;

use App\Models\Question;
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
        return $this->user()->cannot('create', Question::class);
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
            'product_id' => ['bail', 'required', 'integer', 'min:1'],
        ];
    }
}
