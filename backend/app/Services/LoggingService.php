<?php

namespace App\Services;

use App\Constants\Actions;
use App\Formatters\LoggingFormatter;
use App\Models\Logging;
use App\Models\LoggingField;

class LoggingService extends Service
{
    private $loggingFormatter;

    public function __construct(
        LoggingFormatter $loggingFormatter,
    ) {
        $this->loggingFormatter = $loggingFormatter;
    }

    public function logAddProduct($input, $user, $product)
    {
        $loggingData = $this->loggingFormatter->prepareLoggingData(
            Actions::ADD,
            $product,
            $user
        );
        $logging = Logging::create($loggingData);

        $loggingFieldsData = $this->loggingFormatter->prepareAddLoggingFields(
            $logging,
            $input
        );
        LoggingField::insert($loggingFieldsData);
    }

    public function logUpdateProduct($input, $user, $product)
    {
        $loggingData = $this->loggingFormatter->prepareLoggingData(
            Actions::UPDATE,
            $product,
            $user
        );
        $logging = Logging::create($loggingData);

        $loggingFieldsData = $this->loggingFormatter->prepareUpdateLoggingFields(
            $logging,
            $input,
            $product
        );
        LoggingField::insert($loggingFieldsData);
    }

    public function logDeleteProduct($user, $product)
    {
        $loggingData = $this->loggingFormatter->prepareLoggingData(
            Actions::DELETE,
            $product,
            $user
        );
        Logging::create($loggingData);
    }

    public function logActivateProduct($user, $product)
    {
        $loggingData = $this->loggingFormatter->prepareLoggingData(
            Actions::ACTIVATE,
            $product,
            $user
        );
        Logging::create($loggingData);
    }

    public function logDeactivateProduct($user, $product)
    {
        $loggingData = $this->loggingFormatter->prepareLoggingData(
            Actions::DEACTIVATE,
            $product,
            $user
        );
        Logging::create($loggingData);
    }
}
