<?php

namespace App\Http\Requests\Api\Users;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
            'startDate' => ['bail', 'sometimes', 'date'],
            'endDate' => ['bail', 'sometimes', 'date'],
            'sortBy' => ['bail', 'sometimes', 'in:top'],
            'getTrashed' => ['bail', 'sometimes', 'in:trashed'],
            'per_page' => ['bail', 'sometimes', 'integer', 'between:2,10'],
        ];
    }
}
