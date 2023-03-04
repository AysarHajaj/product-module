<?php

namespace App\Constants;

use App\Constants\Sources;

class Statuses
{
    const ACTIVE = "active";
    const INACTIVE = "inactive";
    const ALL = [
        self::ACTIVE => [
            "id" => self::ACTIVE,
            "name" => "Active",
        ],
        self::INACTIVE => [
            "id" => self::INACTIVE,
            "name" => "Inactive",
        ],
    ];
}
