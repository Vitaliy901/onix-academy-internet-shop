<?php

namespace App\Http\Requests\Api\Orders;

use App\Models\Order;
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
        return $this->user()->cannot('create', Order::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'comment' => ['bail', 'sometimes', 'string', 'between:10, 500'],
            'address' => ['bail', 'sometimes', 'string', 'max:255'],
        ];
    }


    protected function prepareForValidation()
    {
        if ($this->address === null) {
            $this->request->remove('address');
        }
    }
}
