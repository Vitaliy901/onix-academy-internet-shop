<?php

namespace App\Http\Requests\Api\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['bail', 'sometimes', 'string', 'alpha', 'max:255'],
            'email' => [
                'bail', 'sometimes', 'email:rfc,dns', 'max:255',
                Rule::unique('users')->ignore(
                    $this->routeIs('me.update') ? $this->user() : $this->route('user')
                )
            ],
            'password' => ['bail', 'sometimes', 'confirmed', Password::min(6)->mixedCase()->numbers()->uncompromised()],
            'address' => ['bail', 'sometimes', 'string', 'max:255'],
            'phone' => ['bail', 'sometimes', 'string', 'regex:#^\(\d{3}\)\s?\d{3}\-\d{2}\-\d{2}$#', 'max:15'],
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->password === null) {
            $this->request->remove('password');
        }
    }
}
