<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthRepository
{
    public function create($input)
    {
        $user = User::create($input);

        return $user;
    }

    public function createToken($user)
    {
        $token = $user->createToken('product-module')->accessToken;

        return $token;
    }

    public function attempt($input)
    {
        if (Auth::attempt($input)) {
            $user = Auth::user();

            return $user;
        }

        return null;
    }

    public function revokeAuthUserToken()
    {
        Auth::user()->token()->revoke();
    }

    public function authUser()
    {
        return Auth::user();
    }

    public function findOrFail($id)
    {
        return User::findOrFail($id);
    }
}
