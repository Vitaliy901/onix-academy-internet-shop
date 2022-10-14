<?php

namespace App\Http\Requests\Api\Orders;

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
            'startDate' => ['bail', 'sometimes', 'date'],
            'endDate' => ['bail', 'sometimes', 'date'],
            'getTrashed' => ['bail', 'sometimes', 'in:trashed'],
            'sort_by' => ['bail', 'sometimes', 'in:price'],
            'status' => ['bail', 'sometimes', 'in:open,confirmed'],
            'users_ids' => ['bail', 'sometimes', 'regex:#(\d,?)+#'],
            'per_page' => ['bail', 'sometimes', 'integer', 'between:2,10'],
        ];
    }
}
