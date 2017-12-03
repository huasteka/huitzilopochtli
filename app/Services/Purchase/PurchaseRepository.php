<?php

namespace App\Services\Purchase;

use App\MerchandisePurchase;
use App\Purchase;
use App\Services\Deliverable\DeliverableRepository;
use Illuminate\Http\Request;

/**
 * @method PurchaseRequestReader getRequestReader
 */
class PurchaseRepository extends DeliverableRepository
{

    public function __construct(PurchaseRequestReader $requestReader)
    {
        parent::__construct($requestReader);
    }

    public function create(Request $request)
    {
        $purchase = new Purchase($this->getRequestReader()->readAttributes($request, Purchase::class));
        if ($purchase->save()) {
            $this->createMerchandises($request, $purchase);
            $this->createDelivery($request, $purchase);
        }
        return $purchase;
    }

    /**
     * TODO Purchased merchandise and delivery information can be updated
     *
     * @param Request $request
     * @param Purchase $purchase
     */
    public function update(Request $request, Purchase $purchase)
    {

    }

    private function createMerchandises(Request $request, Purchase $purchase)
    {
        if ($this->hasMerchandises($request)) {
            foreach ($request->get(static::$requestAttributeMerchandises) as $merchandise) {
                $purchase->createMerchandise(
                    $merchandise[self::$requestAttributeId],
                    $merchandise[MerchandisePurchase::QUANTITY],
                    $this->getByKey($merchandise, MerchandisePurchase::PURCHASE_PRICE, 0.00),
                    $this->getByKey($merchandise, MerchandisePurchase::SUPPLIER_ID, null)
                );
            }
        }
    }

}
