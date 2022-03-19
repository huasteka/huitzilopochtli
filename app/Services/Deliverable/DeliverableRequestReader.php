<?php

namespace App\Services\Deliverable;

use App\Contact;
use App\Deliverable;
use App\Delivery;
use App\DeliveryAddress;
use App\Services\AbstractRequestReader;
use Illuminate\Http\Request;

abstract class DeliverableRequestReader extends AbstractRequestReader
{

    use DeliverablePropertyBuilder;

    public function readAttributes(Request $request, $type)
    {
        switch ($type) {
            case Delivery::class:
                return $this->readDeliveryAttributes($request);
            case DeliveryAddress::class:
                return $this->readDeliveryAddressAttributes($request);
            case Contact::class:
                return $this->readContactAttributes($request);
            default:
                return [];
        }
    }

    protected function readDeliverableAttributes(Request $request)
    {
        return [
            Deliverable::CODE => $request->get(Deliverable::CODE),
            Deliverable::DESCRIPTION => $request->get(Deliverable::DESCRIPTION),
            Deliverable::GROSS_VALUE => $request->get(Deliverable::GROSS_VALUE, 0.00),
            Deliverable::NET_VALUE => $request->get(Deliverable::NET_VALUE, 0.00),
            Deliverable::DISCOUNT => $request->get(Deliverable::DISCOUNT, 0.00),
        ];
    }

    private function readDeliveryAttributes(Request $request)
    {
        return [
            Delivery::SENT_AT => $request->input($this->getDeliveryProperty(Delivery::SENT_AT)),
            Delivery::DELIVERY_TIME => $request->input($this->getDeliveryProperty(Delivery::DELIVERY_TIME)),
            Delivery::ARRIVED_AT => $request->input($this->getDeliveryProperty(Delivery::ARRIVED_AT)),
        ];
    }

    private function readDeliveryAddressAttributes(Request $request)
    {
        return [
            DeliveryAddress::IS_DEFAULT => $request->input($this->getDeliveryAddressProperty(DeliveryAddress::IS_DEFAULT))
        ];
    }

    private function readContactAttributes(Request $request)
    {
        $contactList = $request->input($this->getDeliveryAddressProperty(self::$requestAttributeContacts));
        return array_pop($contactList);
    }

}
