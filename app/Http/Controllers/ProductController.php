<?php
namespace App\Http\Controllers;

use App\Product;
use App\Schemas\ProductSchema;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class ProductController extends RestController
{

    private $productService;

    public function index()
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Product::all()));
    }

    public function store(Request $request)
    {
        $this->validateRequest($request, $this->getService()->getValidationRulesOnCreate($request));
        $product = $this->getService()->store($request);
        return $this->withJsonApi($this->getEncoder()->encodeData($product), Response::HTTP_CREATED);
    }

    public function show($productId)
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Product::find($productId)));
    }

    public function update(Request $request, $productId)
    {
        return $this->findProductAndExecuteCallback($productId, function (Product $product) use ($request) {
            $this->getService()->update($request, $product);
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

    private function getService()
    {
        if (is_null($this->productService)) {
            $this->productService = new ProductService();
        }
        return $this->productService;
    }

}
