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
}
