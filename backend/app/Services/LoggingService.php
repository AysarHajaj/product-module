<?php

namespace App\Services;

use App\Constants\Actions;
use App\Formatters\AuthFormatter;
use App\Formatters\LoggingFormatter;
use App\Formatters\ProductFormatter;
use App\Notifications\NewProductAdded;
use App\Repositories\AuthRepository;
use App\Repositories\LoggingRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoggingService extends Service
{
    private $loggingRepository;
    private $loggingFormatter;

    public function __construct(
        LoggingRepository $loggingRepository,
        LoggingFormatter $loggingFormatter,
    ) {
        $this->loggingRepository = $loggingRepository;
        $this->loggingFormatter = $loggingFormatter;
    }

    public function logAddProduct($input, $user, $product)
    {
        $loggingData = $this->loggingFormatter->prepareLoggingData(
            Actions::ADD,
            $product,
            $user
        );
        $logging = $this->loggingRepository->create($loggingData);

        $loggingFieldsData = $this->loggingFormatter->prepareAddLoggingFields(
            $logging,
            $input
        );
        $this->loggingRepository->insertLoggingFields($loggingFieldsData);
    }

    public function logUpdateProduct($input, $user, $product)
    {
        $loggingData = $this->loggingFormatter->prepareLoggingData(
            Actions::UPDATE,
            $product,
            $user
        );
        $logging = $this->loggingRepository->create($loggingData);

        $loggingFieldsData = $this->loggingFormatter->prepareUpdateLoggingFields(
            $logging,
            $input,
            $product
        );
        $this->loggingRepository->insertLoggingFields($loggingFieldsData);
    }

    public function logDeleteProduct($user, $product)
    {
        $loggingData = $this->loggingFormatter->prepareLoggingData(
            Actions::DELETE,
            $product,
            $user
        );
        $this->loggingRepository->create($loggingData);
    }

    public function logActivateProduct($user, $product)
    {
        $loggingData = $this->loggingFormatter->prepareLoggingData(
            Actions::ACTIVATE,
            $product,
            $user
        );
        $this->loggingRepository->create($loggingData);
    }

    public function logDeactivateProduct($user, $product)
    {
        $loggingData = $this->loggingFormatter->prepareLoggingData(
            Actions::DEACTIVATE,
            $product,
            $user
        );
        $this->loggingRepository->create($loggingData);
    }
}
