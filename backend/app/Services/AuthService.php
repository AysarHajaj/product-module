<?php

namespace App\Services;

use App\Repositories\AuthRepository;

class AuthService
{
    private $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }
}
