<?php

namespace App\Http\Requests;

class StoreProductRequest extends Request
{
    public function messages()
    {
        return [
            'name' => 'You need to enter valid name',
            'price' => 'You need to enter valid price',
            'type' => 'You need to enter valid type',
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
            'name' => "required|string",
            'price' => "required|numeric",
            'type' => "required|string",
        ];
    }
}
