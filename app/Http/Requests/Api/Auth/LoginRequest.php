<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => ['bail', 'required', 'string', 'email'],
            'password' => ['bail', 'required', 'min:6'],
            'device_name' => ['bail', 'required', 'string', 'max:255'],
        ];
    }
}
