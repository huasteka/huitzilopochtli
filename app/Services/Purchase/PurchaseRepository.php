<?php

namespace App\Services\Purchase;

use App\Contact;
use App\Delivery;
use App\DeliveryAddress;
use App\MerchandisePurchase;
use App\Purchase;
use App\Services\AbstractRepository;
use Illuminate\Http\Request;

/**
 * @method PurchaseRequestReader getRequestReader
 */
class PurchaseRepository extends AbstractRepository
{

    use PurchaseRequestChecker;

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
                    $merchandise[MerchandisePurchase::PURCHASE_PRICE]
                );
            }
        }
    }

    private function createDelivery(Request $request, Purchase $purchase)
    {
        if ($this->hasDelivery($request)) {
            $delivery = new Delivery($this->getRequestReader()->readAttributes($request, Delivery::class));
            $deliveryAddress = null;
            if ($this->hasDeliveryAddressId($request)) {
                $deliveryAddress = DeliveryAddress::find($request->input($this->getDeliveryProperty(static::$requestAttributeDeliveryAddressId)));
            } else if ($this->hasDeliveryAddress($request)) {
                $deliveryAddress = new DeliveryAddress($this->getRequestReader()->readAttributes($request, DeliveryAddress::class));
                if ($deliveryAddress->save()) {
                    $deliveryAddress->createContactByAttributes($this->getRequestReader()->readAttributes($request, Contact::class));
                }
            }
            if (!is_null($deliveryAddress)) {
                $delivery->setAttribute(static::$requestAttributeDeliveryAddressId, $deliveryAddress->getKey());
                $purchase->createDelivery($delivery);
            }
        }
    }

}
