<?php

namespace App\Http\Requests;

class LoginRequest extends Request
{
    public function messages()
    {
        return [
            'email.required' => 'You need to enter your email',
            'email.email' => 'The email should be email structured',
            'password.required' => 'You need to enter a password',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            "email" => "required|email",
            "password" => "required",
        ];
    }
}
