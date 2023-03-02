<?php

namespace App\Http\Requests;

use App\Formatters\Formatter;
use App\Services\Service;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class Request extends FormRequest
{
    private $formatter;
    private $service;

    public function __construct(Formatter $formatter, Service $service)
    {
        $this->formatter = $formatter;
        $this->service = $service;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->service->getResponse(
            $this->formatter->errorResponseData($validator->errors()->first()),
            400
        );

        throw new ValidationException($validator, $response);
    }
}
