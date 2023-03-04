<?php

namespace App\Services;

use App\Formatters\AuthFormatter;
use App\Formatters\ProductFormatter;
use App\Notifications\NewProductAdded;
use App\Repositories\AuthRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductService extends Service
{
    private $productRepository;
    private $productFormatter;
    private $authRepository;
    private $authFormatter;
    private $loggingService;

    public function __construct(
        ProductRepository $productRepository,
        ProductFormatter $productFormatter,
        AuthRepository $authRepository,
        AuthFormatter $authFormatter,
        LoggingService $loggingService
    ) {
        $this->productRepository = $productRepository;
        $this->productFormatter = $productFormatter;
        $this->authRepository = $authRepository;
        $this->authFormatter = $authFormatter;
        $this->loggingService = $loggingService;
    }

    public function getAll()
    {
        DB::beginTransaction();
        try {
            $products = $this->productRepository->all();
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
            $user = $this->authRepository->authUser();
            $input = $this->productFormatter->prepareInputData($input, $user);
            $product = $this->productRepository->create($input);
            $result = $this->productFormatter->successResponseData(true);

            $this->loggingService->logAddProduct($input, $user, $product);
            $user->notify(new NewProductAdded($product->id));

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
            $product = $this->productRepository->show($id);
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
            $user = $this->authRepository->authUser();
            $product = $this->productRepository->findOrFail($id);
            $this->loggingService->logUpdateProduct($input, $user, $product);
            $this->productRepository->update($product, $input);
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
            $user = $this->authRepository->authUser();
            $product = $this->productRepository->findOrFail($id);
            $this->productRepository->delete($product);
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
            $user = $this->authRepository->authUser();
            $product = $this->productRepository->findDeactivatedOrFail($id);
            $this->productRepository->activate($product);
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
            $user = $this->authRepository->authUser();
            $product = $this->productRepository->findActiveOrFail($id);
            $this->productRepository->deactivate($product);
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
            $products = $this->productRepository->getFiltered($text);
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
            $product = $this->productRepository->findOrFail($id);
            $user = $this->authRepository->findOrFail($product->user_id);
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
