<?php

namespace App\Http\Controllers;

use App\Delivery;
use App\Merchandise;
use App\Product;
use App\Sale;
use App\Schemas\DeliverySchema;
use App\Schemas\MerchandiseSchema;
use App\Schemas\ProductSchema;
use App\Schemas\SaleSchema;
use App\Services\Sale\SaleService;
use App\Util\Pagination;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SaleController extends RestController
{

    private $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    /**
     * @api {get} /sales Fetch sale list
     * @apiVersion 1.0.0
     * @apiGroup Sale
     * @apiName GetSales
     * @apiHeader {String} Authorization User generated JWT token
     */
    public function index(Request $request)
    {
        $pageSize = Pagination::getInstance($request)->getPageSize();
        return $this->withJsonApi($this->getEncoder()->encodeData(Sale::paginate($pageSize)));
    }

    /**
     * @api {post} /sales Create sale
     * @apiVersion 1.0.0
     * @apiGroup Sale
     * @apiName CreateSale
     * @apiHeader {String} Authorization Generated JWT token
     */
    public function store(Request $request)
    {
        $this->validateRequest($request, $this->getSaleService()->validateOnCreate($request));
        $sale = $this->getSaleService()->create($request);
        return $this->withJsonApi($this->getEncoder()->encodeData($sale), Response::HTTP_CREATED);
    }

    /**
     * @api {get} /sales/:saleId Fetch sale
     * @apiVersion 1.0.0
     * @apiGroup Sale
     * @apiName GetSale
     * @apiHeader {String} Authorization User generated JWT token
     */
    public function show($saleId)
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Sale::find($saleId)));
    }

    /**
     * @api {put} /sales/:saleId Update sale
     * @apiVersion 1.0.0
     * @apiGroup Sale
     * @apiName UpdateSale
     * @apiHeader {String} Authorization User generated JWT token
     */
    public function update(Request $request, $saleId)
    {
        return $this->findSaleAndExecuteCallback($saleId, function (Sale $sale) use ($request) {
            $this->validateRequest($request, $this->getSaleService()->validateOnUpdate($request));
            $this->getSaleService()->update($request, $sale);
            return $this->withStatus(Response::HTTP_NO_CONTENT);
        });
    }

    /**
     * @api {delete} /sales/:saleId Delete sale
     * @apiVersion 1.0.0
     * @apiGroup Sale
     * @apiName DeleteSale
     * @apiHeader {String} Authorization User generated JWT token
     */
    public function destroy($saleId)
    {
        return $this->findSaleAndExecuteCallback($saleId, function (Sale $sale) {
            $sale->delete();
            return $this->withStatus(Response::HTTP_NO_CONTENT);
        });
    }

    private function findSaleAndExecuteCallback($saleId, callable $callback)
    {
        $sale = Sale::find($saleId);
        if (is_null($sale)) {
            return $this->withStatus(Response::HTTP_NOT_FOUND);
        }
        return $callback($sale);
    }

    private function getEncoder()
    {
        return $this->createEncoder([
            Sale::class => SaleSchema::class,
            Merchandise::class => MerchandiseSchema::class,
            Product::class => ProductSchema::class,
            Delivery::class => DeliverySchema::class,
        ]);
    }

    private function getSaleService()
    {
        return $this->saleService;
    }

}
