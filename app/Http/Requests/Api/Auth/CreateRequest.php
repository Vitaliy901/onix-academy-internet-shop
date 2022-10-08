<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

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
            'name' => ['bail', 'required', 'string', 'alpha', 'max:255'],
            'email' => ['bail', 'required', 'string', 'email:rfc,dns', 'max:255', 'unique:users'],
            'password' => ['bail', 'required', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->uncompromised()],
            'address' => ['bail', 'required', 'string', 'max:255'],
            'phone' => ['bail', 'required', 'string', 'regex:#^\(\d{3}\)\s?\d{3}\-\d{2}\-\d{2}$#', 'max:15'],
            'device_name' => ['bail', 'required', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'phone.regex' => 'A :attribute must be in the following format: (xxx) xxx-xx-xx!',
        ];
    }
}
