<?php

namespace App\Services;

use App\Formatters\AuthFormatter;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\DB;

class AuthService extends Service
{
    private $authRepository;
    private $authFormatter;

    public function __construct(
        AuthRepository $authRepository,
        AuthFormatter $authFormatter
    ) {
        $this->authRepository = $authRepository;
        $this->authFormatter = $authFormatter;
    }

    public function register($input)
    {
        DB::beginTransaction();
        try {
            $input['password'] = bcrypt($input['password']);
            $user = $this->authRepository->create($input);
            $token = $this->authRepository->createToken($user);
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
            $user = $this->authRepository->attempt($input);
            if ($user) {
                $token = $this->authRepository->createToken($user);
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
            $this->authRepository->revokeAuthUserToken();
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
