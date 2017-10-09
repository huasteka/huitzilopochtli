<?php
namespace App\Http\Controllers;

use App\Delivery;
use App\Merchandise;
use App\Purchase;
use App\Schemas\DeliverySchema;
use App\Schemas\MerchandiseSchema;
use App\Schemas\PurchaseSchema;
use App\Services\PurchaseService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PurchaseController extends RestController
{

    private $purchaseService;

    public function index()
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Purchase::all()));
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->getPurchaseService()->getValidationRulesOnCreate($request));
        $purchase = $this->getPurchaseService()->store($request);
        return $this->withJsonApi($this->getEncoder()->encodeData($purchase), Response::HTTP_CREATED);
    }

    public function show($purchaseId)
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Purchase::find($purchaseId)));
    }

    public function update(Request $request, $purchaseId)
    {
        return $this->findPurchaseAndExecuteCallback($purchaseId, function (Purchase $purchase) use ($request) {
            $this->getPurchaseService()->update($request, $purchase);
            return $this->withStatus(Response::HTTP_NO_CONTENT);
        });
    }

    public function destroy($purchaseId)
    {
        return $this->findPurchaseAndExecuteCallback($purchaseId, function (Purchase $purchase) {
            $purchase->delete();
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

    private function getEncoder()
    {
        return $this->createEncoder([
            Purchase::class => PurchaseSchema::class,
            Merchandise::class => MerchandiseSchema::class,
            Delivery::class => DeliverySchema::class
        ]);
    }

    private function getPurchaseService()
    {
        if (is_null($this->purchaseService)) {
            $this->purchaseService = new PurchaseService();
        }
        return $this->purchaseService;
    }

}
