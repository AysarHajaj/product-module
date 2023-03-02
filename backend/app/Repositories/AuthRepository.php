<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthRepository
{
    public function create($input)
    {
        try {
            $user = User::create($input);

            return $user;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function createToken($user)
    {
        try {
            $token = $user->createToken('product-module')->accessToken;

            return $token;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function attempt($input)
    {
        try {
            if (Auth::attempt($input)) {
                $user = Auth::user();

                return $user;
            }

            return null;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function revokeAuthUserToken()
    {
        try {
            Auth::user()->token()->revoke();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
