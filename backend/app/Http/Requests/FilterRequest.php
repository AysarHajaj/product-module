<?php

namespace App\Http\Requests;

class FilterRequest extends Request
{
    public function messages()
    {
        return [
            'q' => 'You need to enter your a key word to search for',
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
            "q" => "required|string",
        ];
    }
}
