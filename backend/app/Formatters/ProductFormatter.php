<?php

namespace App\Formatters;

use App\Constants\ProductTypes;
use App\Constants\Statuses;

class ProductFormatter extends Formatter
{
    public function formatProducts($products)
    {
        $result = [];
        foreach ($products as $product) {
            $result[] = $this->formatProduct($product);
        }

        return $result;
    }

    public function formatProduct($product)
    {
        if (isset($product->type)) {
            $product['type'] = ProductTypes::ALL[$product['type']]['name'];
        }
        if (isset($product->deactivated_at)) {
            $product['status'] = Statuses::INACTIVE;
        } else {
            $product['status'] = Statuses::ACTIVE;
        }
        if (isset($product->user->name)) {
            $product['user_name'] = $product->user->name;
        }

        unset(
            $product->user,
            $product->deactivated_at,
            $product->user_id,
        );


        return $product;
    }

    public function prepareInputData($input, $user)
    {
        $input['user_id'] = $user->id;

        return $input;
    }
}
