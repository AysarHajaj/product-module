<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthRepository
{
    public function create($input)
    {
        DB::beginTransaction();
        try {
            $user = User::create($input);

            DB::commit();
            return $user;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function createToken($user)
    {
        DB::beginTransaction();
        try {
            $token = $user->createToken('product-module')->accessToken;

            DB::commit();
            return $token;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function attempt($input)
    {
        DB::beginTransaction();
        try {
            if (Auth::attempt($input)) {
                $user = Auth::user();

                DB::commit();
                return $user;
            }

            return null;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
