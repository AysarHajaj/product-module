<?php

namespace App\Constants;

use App\Constants\Sources;

class Actions
{
    const ADD = "add";
    const UPDATE = "update";
    const DELETE = "delete";
    const ACTIVATE = "activate";
    const DEACTIVATE = "deactivate";
    const ALL = [
        self::ADD => [
            "id" => self::ADD,
            "name" => "Add",
        ],
        self::UPDATE => [
            "id" => self::UPDATE,
            "name" => "Update",
        ],
        self::DELETE => [
            "id" => self::DELETE,
            "name" => "Delete",
        ],
        self::ACTIVATE => [
            "id" => self::ACTIVATE,
            "name" => "Activate",
        ],
        self::DEACTIVATE => [
            "id" => self::DEACTIVATE,
            "name" => "Deactivate",
        ],
    ];
}
