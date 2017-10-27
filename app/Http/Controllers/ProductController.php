<?php

namespace App\Http\Controllers;

use App\Product;
use App\Schemas\ProductSchema;
use App\Services\Product\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class ProductController extends RestController
{

    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Product::all()));
    }

    public function store(Request $request)
    {
        $this->validateRequest($request, $this->getProductService()->validateOnCreate($request));
        $product = $this->getProductService()->create($request);
        return $this->withJsonApi($this->getEncoder()->encodeData($product), Response::HTTP_CREATED);
    }

    public function show($productId)
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Product::find($productId)));
    }

    public function update(Request $request, $productId)
    {
        return $this->findProductAndExecuteCallback($productId, function (Product $product) use ($request) {
            $this->validateRequest($request, $this->getProductService()->validateOnUpdate($request));
            $this->getProductService()->update($request, $product);
            return $this->withStatus(Response::HTTP_NO_CONTENT);
        });
    }

    public function destroy($productId)
    {
        return $this->findProductAndExecuteCallback($productId, function (Product $product) {
            $product->delete();
            return $this->withStatus(Response::HTTP_NO_CONTENT);
        });
    }

    private function findProductAndExecuteCallback($productId, callable $callback)
    {
        $product = Product::find($productId);
        if (is_null($product)) {
            return $this->withStatus(Response::HTTP_NOT_FOUND);
        }
        return $callback($product);
    }

    private function getEncoder()
    {
        return $this->createEncoder([Product::class => ProductSchema::class]);
    }

    private function getProductService()
    {
        return $this->productService;
    }

}
