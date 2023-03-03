<?php

namespace App\Constants;

use App\Constants\Sources;

class ProductTypes
{
    const ITEM = "item";
    const SERVICE = "service";
    const ALL = [
        self::ITEM => [
            "id" => self::ITEM,
            "name" => "Item",
        ],
        self::SERVICE => [
            "id" => self::SERVICE,
            "name" => "service",
        ],
    ];
}
