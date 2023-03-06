<?php

namespace App\Services;

use App\Formatters\AuthFormatter;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthService extends Service
{
    private $authFormatter;

    public function __construct(
        AuthFormatter $authFormatter
    ) {
        $this->authFormatter = $authFormatter;
    }

    public function register($input)
    {
        DB::beginTransaction();
        try {
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            $token = $user->createToken('product-module')->accessToken;
            $user = $this->authFormatter->formatUser($user);

            $result = $this->authFormatter->prepareSuccessResponse();
            $result = $this->authFormatter->addDataToResponse(
                $result,
                'user',
                $user
            );
            $result = $this->authFormatter->addDataToResponse(
                $result,
                'token',
                $token
            );

            DB::commit();
            return $this->getResponse($result, 200);
        } catch (\Throwable $th) {

            DB::rollBack();
            return $this->getResponse(
                $this->authFormatter->errorResponseData($th->getMessage()),
                500
            );
        }
    }

    public function login($input)
    {
        DB::beginTransaction();
        try {
            $user = null;
            if (Auth::attempt($input)) {
                $user = Auth::user();
            }
            if ($user) {
                $token = $user->createToken('product-module')->accessToken;
                $user = $this->authFormatter->formatUser($user);

                $result = $this->authFormatter->prepareSuccessResponse();
                $result = $this->authFormatter->addDataToResponse(
                    $result,
                    'user',
                    $user
                );
                $result = $this->authFormatter->addDataToResponse(
                    $result,
                    'token',
                    $token
                );

                DB::commit();
                return $this->getResponse($result, 200);
            }

            DB::rollBack();
            return $this->getResponse(
                $this->authFormatter->errorResponseData(
                    'Incorrect email or password'
                ),
                403
            );
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->getResponse(
                $this->authFormatter->errorResponseData($th->getMessage()),
                500
            );
        }
    }

    public function logout()
    {
        DB::beginTransaction();
        try {
            Auth::user()->token()->revoke();
            $result = $this->authFormatter->successResponseData(true);

            DB::commit();
            return $this->getResponse($result, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->getResponse(
                $this->authFormatter->errorResponseData($th->getMessage()),
                500
            );
        }
    }

    public function unauthenticated()
    {
        $result = $this->authFormatter->errorResponseData('unauthenticated');

        return $this->getResponse($result, 403);
    }
}
