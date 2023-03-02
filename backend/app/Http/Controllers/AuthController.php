<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
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
