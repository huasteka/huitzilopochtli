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
     * @api {get} /products Fetch a list of products
     * @apiVersion 1.0.0
     * @apiGroup Product
     * @apiName GetProducts
     * @apiHeader {String} Authorization User generated JWT token
     * @apiUse RequestPagination
     * @apiSuccess {Object[]} data
     * @apiUse ResponseProductJson
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
     * @apiUse RequestProductJson
     * @apiUse ResponseProductJson
     * @apiUse ResponseErrorJson
     */
    public function store(Request $request)
    {
        $this->validateRequest($request, $this->getProductService()->validateOnCreate($request));
        $product = $this->getProductService()->create($request);
        return $this->withJsonApi($this->getEncoder()->encodeData($product), Response::HTTP_CREATED);
    }

    /**
     * @api {get} /products/:productId Fetch a single product
     * @apiVersion 1.0.0
     * @apiGroup Product
     * @apiName GetProduct
     * @apiHeader {String} Authorization User generated JWT token
     * @apiParam {Number} productId
     * @apiSuccess {Object} data
     * @apiUse ResponseProductJson
     */
    public function show($productId)
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Product::find($productId)));
    }

    /**
     * @api {get} /products/:productCode/details Fetch a single product by code
     * @apiVersion 1.0.0
     * @apiGroup Product
     * @apiName GetProductByCode
     * @apiHeader {String} Authorization User generated JWT token
     * @apiParam {String} productCode
     * @apiSuccess {Object} data
     * @apiUse ResponseProductJson
     */
    public function findByCode($productCode)
    {
        $product = Product::where(Product::CODE, $productCode)->first();
        return $this->withJsonApi($this->getEncoder()->encodeData($product));
    }

    /**
     * @api {put} /products/:productId Update an existent product
     * @apiVersion 1.0.0
     * @apiGroup Product
     * @apiName UpdateProduct
     * @apiHeader {String} Authorization User generated JWT token
     * @apiParam {Number} productId
     * @apiUse RequestProductJson
     * @apiUse ResponseErrorJson
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
     * @api {delete} /products/:productId Delete an existent product
     * @apiVersion 1.0.0
     * @apiGroup Product
     * @apiName DeleteProduct
     * @apiHeader {String} Authorization User generated JWT token
     * @apiParam {Number} productId
     * @apiUse ResponseErrorJson
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

    /**
     * @apiDefine RequestProductJson
     * @apiBody {String} name
     * @apiBody {String} code
     * @apiBody {String} [description]
     */
    private function getEncoder()
    {
        return $this->createEncoder([Product::class => ProductSchema::class]);
    }

    private function getProductService()
    {
        return $this->productService;
    }

}
