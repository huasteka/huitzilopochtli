<?php
namespace App\Http\Controllers;

use App\Product;
use App\Schemas\ProductSchema;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{

    public function index()
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Product::all()));
    }

    public function store(Request $request)
    {
        $this->validateRequest($request, ['code' => 'required|unique:products']);
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
            return $this->withStatus(Response::HTTP_BAD_REQUEST);
        }
        $this->validateRequest($request, ['code' => 'required']);
        $product->fill($this->parseRequest($request));
        $product->save();
        return $this->withStatus(Response::HTTP_NO_CONTENT);
    }

    public function destroy($productId)
    {
        $product = Product::find($productId);
        if (is_null($product)) {
            return $this->withStatus(Response::HTTP_BAD_REQUEST);
        }
        $product->delete();
        return $this->withStatus(Response::HTTP_NO_CONTENT);
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

    private function getEncoder()
    {
        return $this->createEncoder([Product::class => ProductSchema::class]);
    }

}
