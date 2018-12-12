<?php

namespace App\Http\Controllers;

use App\Product;
use App\Schemas\ProductSchema;
use App\Services\Product\ProductService;
use App\Util\Pagination;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class ProductController extends RestController
{

    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @api {get} /products Fetch product list
     * @apiVersion 1.0.0
     * @apiGroup Product
     * @apiName GetProducts
     * @apiHeader {String} Authorization User generated JWT token
     */
    public function index(Request $request)
    {
        $pageSize = Pagination::getInstance($request)->getPageSize();
        return $this->withJsonApi($this->getEncoder()->encodeData(Product::paginate($pageSize)));
    }

    /**
     * @api {post} /products Create product
     * @apiVersion 1.0.0
     * @apiGroup Product
     * @apiName CreateProduct
     * @apiHeader {String} Authorization Generated JWT token
     */
    public function store(Request $request)
    {
        $this->validateRequest($request, $this->getProductService()->validateOnCreate($request));
        $product = $this->getProductService()->create($request);
        return $this->withJsonApi($this->getEncoder()->encodeData($product), Response::HTTP_CREATED);
    }

    /**
     * @api {get} /products/:productId Fetch product
     * @apiVersion 1.0.0
     * @apiGroup Product
     * @apiName GetProduct
     * @apiHeader {String} Authorization User generated JWT token
     */
    public function show($productId)
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Product::find($productId)));
    }

    /**
     * @api {get} /products/product/:productCode Fetch product by code
     * @apiVersion 1.0.0
     * @apiGroup Product
     * @apiName GetProductByCode
     * @apiHeader {String} Authorization User generated JWT token
     */
    public function findByCode($productCode)
    {
        $product = Product::where(Product::CODE, $productCode)->first();
        return $this->withJsonApi($this->getEncoder()->encodeData($product));
    }

    /**
     * @api {put} /products/:productId Update product
     * @apiVersion 1.0.0
     * @apiGroup Product
     * @apiName UpdateProduct
     * @apiHeader {String} Authorization User generated JWT token
     */
    public function update(Request $request, $productId)
    {
        return $this->findProductAndExecuteCallback($productId, function (Product $product) use ($request) {
            $productCode = Product::CODE;
            $this->validateRequest($request, $this->getProductService()->validateOnUpdate($request), [
                $productCode => "required|unique:products,{$productCode},{$product->id}"
            ]);
            $this->getProductService()->update($request, $product);
            return $this->withStatus(Response::HTTP_NO_CONTENT);
        });
    }

    /**
     * @api {delete} /products/:productId Delete product
     * @apiVersion 1.0.0
     * @apiGroup Product
     * @apiName DeleteProduct
     * @apiHeader {String} Authorization User generated JWT token
     */
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
