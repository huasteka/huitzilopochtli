<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Delivery;
use App\Merchandise;
use App\MerchandisePurchase;
use App\Product;
use App\Purchase;
use App\Supplier;
use App\Schemas\ContactSchema;
use App\Schemas\DeliverySchema;
use App\Schemas\MerchandiseSchema;
use App\Schemas\MerchandisePurchaseSchema;
use App\Schemas\ProductSchema;
use App\Schemas\PurchaseSchema;
use App\Schemas\SupplierSchema;
use App\Services\Purchase\PurchaseService;
use App\Util\Pagination;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PurchaseController extends RestController
{

    private $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    /**
     * @api {get} /purchases Fetch a list of purchases
     * @apiVersion 1.0.0
     * @apiGroup Purchase
     * @apiName GetPurchases
     * @apiHeader {String} Authorization User generated JWT token
     * @apiUse RequestPagination
     * @apiSuccess {Object[]} data
     * @apiUse ResponsePurchaseJson
     */
    public function index(Request $request)
    {
        $pageSize = Pagination::getInstance($request)->getPageSize();
        return $this->withJsonApi($this->getEncoder()->encodeData(Purchase::paginate($pageSize)));
    }

    /**
     * @api {post} /purchases Create purchase
     * @apiVersion 1.0.0
     * @apiGroup Purchase
     * @apiName CreatePurchase
     * @apiHeader {String} Authorization Generated JWT token
     * @apiUse RequestPurchaseJson
     * @apiUse ResponsePurchaseJson
     * @apiUse ResponseErrorJson
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->getPurchaseService()->validateOnCreate($request));
        $purchase = $this->getPurchaseService()->create($request);
        return $this->withJsonApi($this->getEncoder()->encodeData($purchase), Response::HTTP_CREATED);
    }

    /**
     * @api {get} /purchases/:purchaseId Fetch a single purchase
     * @apiVersion 1.0.0
     * @apiGroup Purchase
     * @apiName GetPurchase
     * @apiHeader {String} Authorization User generated JWT token
     * @apiParam {Number} purchaseId
     * @apiSuccess {Object} data
     * @apiUse ResponsePurchaseJson
     */
    public function show($purchaseId)
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Purchase::find($purchaseId)));
    }

    /**
     * @api {put} /purchases/:purchaseId Update an existent purchase
     * @apiVersion 1.0.0
     * @apiGroup Purchase
     * @apiName UpdatePurchase
     * @apiHeader {String} Authorization User generated JWT token
     * @apiParam {Number} purchaseId
     * @apiUse RequestPurchaseJson
     * @apiUse ResponseErrorJson
     */
    public function update(Request $request, $purchaseId)
    {
        return $this->findPurchaseAndExecuteCallback($purchaseId, function (Purchase $purchase) use ($request) {
            $this->validate($request, $this->getPurchaseService()->validateOnUpdate($request));
            $this->getPurchaseService()->update($request, $purchase);
            return $this->withStatus(Response::HTTP_NO_CONTENT);
        });
    }

    /**
     * @api {delete} /purchases/:purchaseId Delete an existent purchase
     * @apiVersion 1.0.0
     * @apiGroup Purchase
     * @apiName DeletePurchase
     * @apiHeader {String} Authorization User generated JWT token
     * @apiHeader {String} Authorization User generated JWT token
     * @apiParam {Number} purchaseId
     * @apiUse ResponseErrorJson
     */
    public function destroy($purchaseId)
    {
        return $this->findPurchaseAndExecuteCallback($purchaseId, function (Purchase $purchase) {
            $purchase->delete();
            return $this->withStatus(Response::HTTP_NO_CONTENT);
        });
    }

    private function findPurchaseAndExecuteCallback($purchaseId, callable $callback)
    {
        $purchase = Purchase::find($purchaseId);
        if (is_null($purchase)) {
            return $this->withStatus(Response::HTTP_NOT_FOUND);
        }
        return $callback($purchase);
    }

    /**
     * @apiDefine RequestPurchaseJson
     * @apiBody {String} code
     * @apiBody {Number} [gross_value]
     * @apiBody {Number} [discount]
     * @apiBody {Number} [net_value]
     * @apiBody {String} [description]
     * @apiBody {Object[]} merchandises
     * @apiBody {Number} merchandises.id
     * @apiBody {Number} merchandises.supplier_id
     * @apiBody {Number} merchandises.purchase_price
     * @apiBody {Number} merchandises.quantity
     */
    private function getEncoder()
    {
        $entityMap = [
            Purchase::class => PurchaseSchema::class,
            Delivery::class => DeliverySchema::class,
            MerchandisePurchase::class => MerchandisePurchaseSchema::class,
            Supplier::class => SupplierSchema::class,
            Contact::class => ContactSchema::class,
            Merchandise::class => MerchandiseSchema::class,
            Product::class => ProductSchema::class,
        ];

        $includedPaths = [
            Purchase::RELATIONSHIP_DELIVERY,
            Purchase::RELATIONSHIP_MERCHANDISES_PURCHASED,
            implode('.', [
                Purchase::RELATIONSHIP_MERCHANDISES_PURCHASED,
                MerchandisePurchase::RELATIONSHIP_SUPPLIER,
            ]),
            implode('.', [
                Purchase::RELATIONSHIP_MERCHANDISES_PURCHASED,
                MerchandisePurchase::RELATIONSHIP_MERCHANDISE,
            ]),
            implode('.', [
                Purchase::RELATIONSHIP_MERCHANDISES_PURCHASED,
                MerchandisePurchase::RELATIONSHIP_MERCHANDISE,
                Merchandise::RELATIONSHIP_PRODUCT,
            ]),
        ];

        return $this->createEncoder($entityMap, $includedPaths);
    }

    private function getPurchaseService()
    {
        return $this->purchaseService;
    }

}
