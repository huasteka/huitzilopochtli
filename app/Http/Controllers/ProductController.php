<?php
namespace App\Http\Controllers;

use App\Product;
use App\Schemas\ProductSchema;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class ProductController extends StandardController
{

    public function index()
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Product::all()));
    }

    public function store(Request $request)
    {
        $this->validateRequest($request, Product::validationRulesOnCreate());
        $product = Product::create($this->parseRequest($request));
        return $this->withJsonApi($this->getEncoder()->encodeData($product), Response::HTTP_CREATED);
    }

    public function show($productId)
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Product::find($productId)));
    }

    public function update(Request $request, $productId)
    {
        $product = Product::find($productId);
        if (is_null($product)) {
            return $this->withStatus(Response::HTTP_NOT_FOUND);
        }
        $this->validateRequest($request, Product::validationRulesOnUpdate());
        $product->fill($this->parseRequest($request));
        $product->save();
        return $this->withStatus(Response::HTTP_NO_CONTENT);
    }

    public function destroy($productId)
    {
        $product = Product::find($productId);
        if (is_null($product)) {
            return $this->withStatus(Response::HTTP_NOT_FOUND);
        }
        $product->delete();
        return $this->withStatus(Response::HTTP_NO_CONTENT);
    }

    protected function parseRequest(Request $request)
    {
        return Product::readAttributes($request);
    }

    private function getEncoder()
    {
        return $this->createEncoder([Product::class => ProductSchema::class]);
    }

}
