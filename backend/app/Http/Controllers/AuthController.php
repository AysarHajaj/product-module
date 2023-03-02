<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegistrationRequest $request)
    {
    }

    public function login(LoginRequest $request)
    {
    }

    public function logout()
    {
    }

    public function unauthenticated()
    {
    }
}
