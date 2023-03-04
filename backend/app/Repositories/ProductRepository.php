<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function all()
    {
        $products = Product::select(
            'products.id',
            'products.name',
            'products.price',
            'products.type',
            'products.user_id',
            'products.deactivated_at',
            'products.created_at',
        )->with([
            'user' => function ($q) {
                $q->select(
                    'users.id',
                    'users.name'
                );
            }
        ])->get();

        return $products;
    }
}
