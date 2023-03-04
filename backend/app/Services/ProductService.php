<?php

namespace App\Services;

use App\Formatters\ProductFormatter;
use App\Repositories\AuthRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;

class ProductService extends Service
{
    private $productRepository;
    private $productFormatter;
    private $authRepository;

    public function __construct(
        ProductRepository $productRepository,
        ProductFormatter $productFormatter,
        AuthRepository $authRepository
    ) {
        $this->productRepository = $productRepository;
        $this->productFormatter = $productFormatter;
        $this->authRepository = $authRepository;
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
            $this->productRepository->create($input);
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
}
