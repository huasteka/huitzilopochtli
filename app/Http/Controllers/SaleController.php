<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Client;
use App\Delivery;
use App\Merchandise;
use App\MerchandiseSale;
use App\Product;
use App\Sale;
use App\Schemas\ContactSchema;
use App\Schemas\ClientSchema;
use App\Schemas\DeliverySchema;
use App\Schemas\MerchandiseSchema;
use App\Schemas\MerchandiseSaleSchema;
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
     * @apiUse RequestPagination
     * @apiSuccess {Object[]} data
     * @apiUse ResponseSaleJson
     */
    public function index(Request $request)
    {
        $pageSize = Pagination::getInstance($request)->getPageSize();
        $resultSet = Sale::orderBy('created_at', 'desc')->paginate($pageSize);
        return $this->withJsonApi($this->getEncoder()->encodeData($resultSet));
    }

    /**
     * @api {post} /sales Create sale
     * @apiVersion 1.0.0
     * @apiGroup Sale
     * @apiName CreateSale
     * @apiHeader {String} Authorization Generated JWT token
     * @apiUse RequestSaleJson
     * @apiUse ResponseSaleJson
     * @apiUse ResponseErrorJson
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
     *  @apiParam {Number} saleId
     * @apiSuccess {Object} data
     * @apiUse ResponseSaleJson
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
     * @apiParam {Number} saleId
     * @apiUse RequestSaleJson
     * @apiUse ResponseErrorJson
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
     * @apiParam {Number} saleId
     * @apiUse ResponseErrorJson
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

    /**
     * @apiDefine RequestSaleJson
     * @apiBody {String} code
     * @apiBody {Number} [gross_value]
     * @apiBody {Number} [discount]
     * @apiBody {Number} [net_value]
     * @apiBody {String} [description]
     * @apiBody {Object[]} merchandises
     * @apiBody {Number} merchandises.id
     * @apiBody {Number} merchandises.client_id
     * @apiBody {Number} merchandises.retail_price
     * @apiBody {Number} merchandises.quantity
     */
    private function getEncoder()
    {
        $entityMap = [
            Sale::class => SaleSchema::class,
            Delivery::class => DeliverySchema::class,
            MerchandiseSale::class => MerchandiseSaleSchema::class,
            Client::class => ClientSchema::class,
            Contact::class => ContactSchema::class,
            Merchandise::class => MerchandiseSchema::class,
            Product::class => ProductSchema::class,
        ];

        $includedPaths = [
            Sale::RELATIONSHIP_DELIVERY,
            Sale::RELATIONSHIP_MERCHANDISES_SOLD,
            implode('.', [
                Sale::RELATIONSHIP_MERCHANDISES_SOLD,
                MerchandiseSale::RELATIONSHIP_CLIENT,
            ]),
            implode('.', [
                Sale::RELATIONSHIP_MERCHANDISES_SOLD,
                MerchandiseSale::RELATIONSHIP_MERCHANDISE,
            ]),
            implode('.', [
                Sale::RELATIONSHIP_MERCHANDISES_SOLD,
                MerchandiseSale::RELATIONSHIP_MERCHANDISE,
                Merchandise::RELATIONSHIP_PRODUCT,
            ]),
        ];

        return $this->createEncoder($entityMap, $includedPaths);
    }

    private function getSaleService()
    {
        return $this->saleService;
    }

}
