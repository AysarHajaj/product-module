<?php

namespace App\Formatters;

class Formatter
{
    public function prepareSuccessResponse()
    {
        return [
            'result' => []
        ];
    }

    public function prepareErrorResponse()
    {
        return [
            'error' => []
        ];
    }

    public function successResponseData($data)
    {
        return [
            'result' => $data
        ];
    }

    public function errorResponseData($data)
    {
        return [
            'error' => $data
        ];
    }

    public function addDataToResponse($response, $key, $data)
    {
        if (isset($response['result'])) {
            $response['result'][$key] = $data;
        }

        if (isset($response['error'])) {
            $response['error'][$key] = $data;
        }

        return $response;
    }
}
