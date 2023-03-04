<?php

namespace App\Services;

use App\Formatters\ProductFormatter;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;

class ProductService extends Service
{
    private $productRepository;
    private $productFormatter;

    public function __construct(
        ProductRepository $productRepository,
        ProductFormatter $productFormatter
    ) {
        $this->productRepository = $productRepository;
        $this->productFormatter = $productFormatter;
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
}
