<?php

namespace App\Services;

use App\Formatters\AuthFormatter;
use App\Formatters\ProductFormatter;
use App\Models\Product;
use App\Models\User;
use App\Notifications\NewProductAdded;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductService extends Service
{
    private $productFormatter;
    private $authFormatter;
    private $loggingService;

    public function __construct(
        ProductFormatter $productFormatter,
        AuthFormatter $authFormatter,
        LoggingService $loggingService
    ) {
        $this->productFormatter = $productFormatter;
        $this->authFormatter = $authFormatter;
        $this->loggingService = $loggingService;
    }

    public function getAll()
    {
        DB::beginTransaction();
        try {
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

            $formattedProducts = $this->productFormatter->formatProducts($products);
            $result = $this->productFormatter->successResponseData($formattedProducts);

            DB::commit();
            return $this->getResponse($result, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->getResponse(
                $this->productFormatter->errorResponseData($th->getMessage()),
                500
            );
        }
    }

    public function store($input)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $input = $this->productFormatter->prepareInputData($input, $user);
            $product = Product::create($input);
            $result = $this->productFormatter->successResponseData(true);

            $this->loggingService->logAddProduct($input, $user, $product);
            try {
                $user->notify(new NewProductAdded($product->id));
            } catch (\Throwable $th) {
            }

            DB::commit();
            return $this->getResponse($result, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->getResponse(
                $this->productFormatter->errorResponseData($th->getMessage()),
                500
            );
        }
    }

    public function show($id)
    {
        DB::beginTransaction();
        try {
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

            $formattedProduct = $this->productFormatter->formatProduct($product);
            $result = $this->productFormatter->successResponseData($formattedProduct);

            DB::commit();
            return $this->getResponse($result, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->getResponse(
                $this->productFormatter->errorResponseData($th->getMessage()),
                500
            );
        }
    }

    public function update($input, $id)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $product = Product::findOrFail($id);
            $this->loggingService->logUpdateProduct($input, $user, $product);
            $product->update($input);
            $result = $this->productFormatter->successResponseData(true);

            DB::commit();
            return $this->getResponse($result, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->getResponse(
                $this->productFormatter->errorResponseData($th->getMessage()),
                500
            );
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $product = Product::findOrFail($id);
            $product->delete();
            $result = $this->productFormatter->successResponseData(true);
            $this->loggingService->logDeleteProduct($user, $product);

            DB::commit();
            return $this->getResponse($result, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->getResponse(
                $this->productFormatter->errorResponseData($th->getMessage()),
                500
            );
        }
    }

    public function activate($id)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $product = Product::whereNotNull('deactivated_at')->findOrFail($id);
            $product->update(['deactivated_at' => null]);
            $result = $this->productFormatter->successResponseData(true);
            $this->loggingService->logActivateProduct($user, $product);

            DB::commit();
            return $this->getResponse($result, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->getResponse(
                $this->productFormatter->errorResponseData($th->getMessage()),
                500
            );
        }
    }

    public function deactivate($id)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $product = Product::whereNull('deactivated_at')->findOrFail($id);
            $product->update(['deactivated_at' => now()]);
            $result = $this->productFormatter->successResponseData(true);
            $this->loggingService->logDeactivateProduct($user, $product);

            DB::commit();
            return $this->getResponse($result, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->getResponse(
                $this->productFormatter->errorResponseData($th->getMessage()),
                500
            );
        }
    }

    public function getFiltered($text)
    {
        DB::beginTransaction();
        try {
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
            $formattedProducts = $this->productFormatter->formatProducts($products);
            $result = $this->productFormatter->successResponseData($formattedProducts);

            DB::commit();
            return $this->getResponse($result, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->getResponse(
                $this->productFormatter->errorResponseData($th->getMessage()),
                500
            );
        }
    }

    public function getProductUser($id)
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            $user = User::findOrFail($product->user_id);
            $user = $this->authFormatter->formatUser($user);
            $result = $this->productFormatter->successResponseData($user);

            DB::commit();
            return $this->getResponse($result, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->getResponse(
                $this->productFormatter->errorResponseData($th->getMessage()),
                500
            );
        }
    }
}
