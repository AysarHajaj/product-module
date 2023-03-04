<?php

namespace App\Formatters;

use App\Constants\ProductTypes;

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

        return $product;
    }
}
