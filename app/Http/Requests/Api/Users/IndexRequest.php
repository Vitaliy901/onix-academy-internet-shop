<?php

namespace App\Http\Requests\Api\Users;

use App\Models\User;
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
        return $this->user()->can('index', User::class);
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
            'keywords' => ['bail', 'sometimes', 'string', 'min:1'],
            'getTrashed' => ['bail', 'sometimes', 'in:trashed'],
            'per_page' => ['bail', 'sometimes', 'integer', 'between:2,10'],
        ];
    }
}
