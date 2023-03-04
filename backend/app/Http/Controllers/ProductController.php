<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = $this->productService->getAll();

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $input = $request->only(['name', 'price', 'type']);
        $response = $this->productService->store($input);

        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $response = $this->productService->show($id);

        return $response;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $input = $request->only(['name', 'price', 'type']);
        $response = $this->productService->update($input, $id);

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $response = $this->productService->delete($id);

        return $response;
    }

    /**
     * Activate the specified resource in storage.
     */
    public function activate(string $id)
    {
        $response = $this->productService->activate($id);

        return $response;
    }

    /**
     * Deactivate the specified resource in storage.
     */
    public function deactivate(string $id)
    {
        //
    }
}
