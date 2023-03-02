<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

class Service
{
    public function getResponse($data, $status)
    {
        return new JsonResponse($data, $status);
    }
}
