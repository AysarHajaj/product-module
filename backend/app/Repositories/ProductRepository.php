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
        return Product::create($input);
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

    public function findActiveOrFail($id)
    {
        return Product::whereNull('deactivated_at')->findOrFail($id);
    }

    public function deactivate($product)
    {
        $product->update(['deactivated_at' => now()]);
    }

    public function getFiltered($text)
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
        ])->where(
            'products.name',
            'LIKE',
            "%{$text}%"
        )->orWhereHas(
            'user',
            function ($q) use ($text) {
                $q->where(
                    'users.name',
                    'LIKE',
                    "%{$text}%"
                );
            }
        )->get();

        return $products;
    }
}
