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

    public function create($input)
    {
        Product::create($input);
    }

    public function show($id)
    {
        $product = Product::select(
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
        ])->findOrFail($id);

        return $product;
    }

    public function update($product, $input)
    {
        $product->update($input);
    }

    public function findOrFail($id)
    {
        return Product::findOrFail($id);
    }

    public function delete($product)
    {
        $product->delete();
    }

    public function findDeactivatedOrFail($id)
    {
        return Product::whereNotNull('deactivated_at')->findOrFail($id);
    }

    public function activate($product)
    {
        $product->update(['deactivated_at' => null]);
    }
}
