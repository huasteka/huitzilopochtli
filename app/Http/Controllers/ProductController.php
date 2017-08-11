<?php
namespace App\Http\Controllers;

use App\Product;
use App\Services\JsonResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{

    public function create(Request $request)
    {
        $this->validateRequest($request, ['code' => 'required|unique:products']);
        $product = Product::create($this->parseRequest($request));
        return $this->withJson(new JsonResponseFormatter($product), Response::HTTP_CREATED);
    }

    public function update(Request $request, $productId)
    {
        $product = Product::find($productId);
        if (is_null($product)) {
            return $this->withStatus(Response::HTTP_BAD_REQUEST);
        } else {
            $this->validateRequest($request, ['code' => 'required']);
            $product->fill($this->parseRequest($request));
            $product->save();
            return $this->withStatus(Response::HTTP_NO_CONTENT);
        }
    }

    private function validateRequest(Request $request, array $newRules = [])
    {
        $defaultRules = [
            'name' => 'required',
            'retail_price' => 'required',
            'purchase_price' => 'required',
        ];
        $this->validate($request, array_merge($defaultRules, $newRules));
    }

    private function parseRequest(Request $request)
    {
        return Product::readAttributes($request);
    }

    public function destroy($productId)
    {
        $product = Product::find($productId);
        if (!is_null($product)) {
            $product->delete();
        }
        return $this->withStatus(Response::HTTP_NO_CONTENT);
    }

    public function findOne($productId)
    {
        return $this->withJson(new JsonResponseFormatter(Product::find($productId)));
    }

    public function findAll()
    {
        return $this->withJson(new JsonResponseFormatter(Product::all()));
    }

}
