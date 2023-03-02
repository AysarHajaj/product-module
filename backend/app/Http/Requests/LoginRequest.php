<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

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

    protected function failedValidation(Validator $validator)
    {
        $response = new JsonResponse([
            'error' => $validator->errors()->first(),
        ], 400);

        throw new ValidationException($validator, $response);
    }
}
