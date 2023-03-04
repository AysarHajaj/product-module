<?php

namespace App\Repositories;

use App\Models\Logging;
use App\Models\LoggingField;

class LoggingRepository
{
    public function create($data)
    {
        return Logging::create($data);
    }

    public function insertLoggingFields($data)
    {
        LoggingField::insert($data);
    }
}
