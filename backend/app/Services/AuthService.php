<?php

namespace App\Services;

use App\Formatters\AuthFormatter;
use App\Repositories\AuthRepository;

class AuthService extends Service
{
    private $authRepository;
    private $authFormatter;

    public function __construct(AuthRepository $authRepository, AuthFormatter $authFormatter)
    {
        $this->authRepository = $authRepository;
        $this->authFormatter = $authFormatter;
    }

    public function register($input)
    {
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

            return $this->getResponse($result, 200);
        } catch (\Throwable $th) {
            return $this->getResponse(
                $this->authFormatter->errorResponseData($th->getMessage()),
                500
            );
        }
    }
}
