<?php

namespace App\Services\Deliverable;

use App\Contact;
use App\Deliverable;
use App\Delivery;
use App\DeliveryAddress;
use App\Services\AbstractRepository;
use Illuminate\Http\Request;

/**
 * @method DeliverableRequestReader getRequestReader
 */
abstract class DeliverableRepository extends AbstractRepository
{

    use DeliverableRequestChecker;

    public function __construct(DeliverableRequestReader $requestReader)
    {
        parent::__construct($requestReader);
    }

    protected function createDelivery(Request $request, Deliverable $deliverable)
    {
        if ($this->hasDelivery($request)) {
            $delivery = new Delivery($this->readDelivery($request));
            $deliveryAddress = null;
            if ($this->hasDeliveryAddressId($request)) {
                $deliveryAddress = DeliveryAddress::find($request->input($this->getDeliveryProperty(static::$requestAttributeDeliveryAddressId)));
            } else if ($this->hasDeliveryAddress($request)) {
                $deliveryAddress = new DeliveryAddress($this->readDeliveryAddress($request));
                if ($deliveryAddress->save()) {
                    $deliveryAddress->createContactByAttributes($this->readContact($request));
                }
            }
            if (!is_null($deliveryAddress)) {
                $delivery->setAttribute(static::$requestAttributeDeliveryAddressId, $deliveryAddress->getKey());
                $deliverable->createDelivery($delivery);
            }
        }
    }

    protected function readDelivery(Request $request)
    {
        return $this->getRequestReader()->readAttributes($request, Delivery::class);
    }

    protected function readDeliveryAddress(Request $request)
    {
        return $this->getRequestReader()->readAttributes($request, DeliveryAddress::class);
    }

    protected function readContact(Request $request)
    {
        return $this->getRequestReader()->readAttributes($request, Contact::class);
    }

}
